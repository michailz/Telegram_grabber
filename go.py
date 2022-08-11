import configparser
import hashlib
import os
import sys
from pathlib import Path

import psycopg2
from telethon.errors.rpcerrorlist import ChannelPrivateError
from telethon.errors.rpcerrorlist import MsgIdInvalidError
from telethon.sync import TelegramClient
from telethon.tl.functions.channels import GetFullChannelRequest
from telethon.tl.types import PeerUser
from telethon.utils import get_display_name

config = configparser.ConfigParser()
config_path = Path(__file__).parent.resolve()
config.read(os.path.join(config_path, "config.ini"))
api_id = config['Telegram']['api_id']
api_hash = config['Telegram']['api_hash']
username = config['Telegram']['username']
host = config['Postgres']['host']
dbname = config['Postgres']['dbname']
user = config['Postgres']['user']
password = config['Postgres']['password']
client = TelegramClient(username, int(api_id), api_hash)
client.start()

try:
    conn = psycopg2.connect(f'host={host} dbname={dbname} user={user} password={password}')
    cur = conn.cursor()
except Exception:
    print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
    print('Can\'t connect to DB!!!')
    raise


async def main(channel_id, media_directory, min_id):
    i = 0
    try:
        async for message in client.iter_messages(channel_id, min_id=min_id, reverse=True):
            if message is not None:
                i += 1
                try:
                    action = text = sender_name = sender_id = sender_type = reply_id = temp = forward_id = None
                    forward_name = filename = file_extension = filehash_sha1 = create_date = edit_date = None
                    message_id = int(message.id)
                    sender_name = get_display_name(message.sender)
                    if 'PeerChannel(channel_id=' in str(message.peer_id):
                        channel_id = int(str(message.peer_id)[23:-1])
                    if message.action is not None:
                        action = str(message.action)
                    temp = str(message.from_id)
                    if 'PeerUser(user_id=' in temp:
                        sender_id = int(temp[17:-1])
                        sender_type = 1
                    if 'PeerChannel(channel_id=' in temp:
                        sender_id = int(temp[23:-1])
                        sender_type = 2
                    if 'PeerChat(chat_id=' in temp:
                        sender_id = int(temp[17:-1])
                        sender_type = 3
                    create_date = message.date
                    edit_date = message.edit_date
                    if message.reply_to_msg_id is not None:
                        reply_id = message.reply_to_msg_id
                    if message.forward is not None:
                        try:
                            if message.fwd_from.from_id is not None:
                                entity = await client.get_entity(message.fwd_from.from_id)
                                forward_id = entity.id
                                forward_name = entity.username
                        except ChannelPrivateError:
                            print(message.fwd_from.from_id, 'can\'t get info about the private channel')
                            if 'PeerChannel(channel_id=' in str(message.fwd_from.from_id):
                                forward_id = int(str(message.fwd_from.from_id)[23:-1])
                        except ValueError:
                            print(message.fwd_from.from_id, 'the channel is deleted')

                    text = message.raw_text
                    """
                    <start>Comment this part of code if you don't need to save media
                    """
                    if message.media:
                        path = await client.download_media(message.media, file=media_directory)
                        print(path)
                        if path is not None:
                            filename, file_extension = os.path.splitext(path)
                            file_extension = file_extension.lower()
                            if ' extra=' in file_extension:
                                file_extension = file_extension.split(' ')[0]
                            filebytes = Path(path).read_bytes()
                            filehash_sha1 = hashlib.sha1(filebytes)
                            filehash_sha1 = filehash_sha1.hexdigest()
                            try:
                                os.makedirs(os.path.join(media_directory, 'extension_' + file_extension.strip(".")))
                            except FileExistsError:
                                pass
                            os.replace(path, os.path.join(media_directory, 'extension_' + file_extension.strip("."),
                                                          filehash_sha1 + file_extension))
                            path = os.path.join(media_directory, 'extension_' + file_extension.strip("."),
                                                filehash_sha1 + file_extension)
                            filename = filename.split(os.sep)[-1]
                    """
                    <end> of media part
                    """
                    if text == '':
                        text = None
                    cur.execute("""INSERT INTO messages (
                        message_id, channel_id, action, text, sender_name, sender_id, sender_type, reply_id,
                        forward_id, forward_name, filename, file_extension, filehash_sha1, create_date, edit_date)
                    VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                    ON CONFLICT DO NOTHING""",
                                (message_id, channel_id, action, text, sender_name, sender_id, sender_type, reply_id,
                                 forward_id, forward_name, filename, file_extension, filehash_sha1, create_date, edit_date))
                    if i >= 50:
                        i = 0
                        conn.commit()

                except MsgIdInvalidError:
                    conn.commit()
                    print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
                    print(message)
                    raise
            conn.commit()
    except ChannelPrivateError:
        print('Channel is private')
        return 1

    except ValueError:
        print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
        return 1

    except Exception:
        print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
        raise

"""
0. Create directory media if not exists
"""
media_directory = os.path.join(config_path, 'media')
try:
    os.makedirs(media_directory)
except FileExistsError:
    pass

"""
1. Read channel names from orders.
2. Get all associated with channel names channels (main channel and chat, etc.) and put them to the channels table.
"""
try:
    cur.execute("SELECT distinct channel FROM orders")
    orders = cur.fetchall()
except Exception:
    print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
    print("Can't get channel list from orders")
    raise

for order in orders:
    channel = order[0]
    try:
        with client:
            result = client(GetFullChannelRequest(channel=channel))
            for c in result.chats:
                cur.execute("INSERT INTO channels (channel_id, title, date, username, order_channel) \
                VALUES(%s, %s, %s, %s, %s) \
                ON CONFLICT DO NOTHING", (c.id, c.title, c.date, c.username, channel))
        conn.commit()
    except Exception:
        print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
        raise
"""
3. Read all channel_ids and get the biggest message_id from channels/messages tables
"""
try:
    cur.execute("SELECT ch.channel_id, COALESCE(max(m.message_id), 0) cnt, ch.title FROM channels ch \
    LEFT JOIN messages m ON ch.channel_id = m.channel_id \
    GROUP BY ch.channel_id, ch.title")
    answers = cur.fetchall()
    channel_id = None
    for answer in answers:
        channel_id, min_id, title = answer[0], answer[1], answer[2]
        with client:
            print(f'Starting to process channel #{channel_id} {title} from message_id #{min_id}')
            """
4. Get all messages from channel
            """
            client.loop.run_until_complete(main(channel_id, media_directory, min_id))
except ChannelPrivateError:
    # noinspection PyUnboundLocalVariable
    print('The channel', channel_id, 'is private or blocked or you are banned from it')

except Exception:
    print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
    print("Can't get channel list from orders")
    raise

"""
5. Add users from messages to users table
"""
client.start()
print('Get user information')
while True:
    try:
        cur.execute("""SELECT DISTINCT sender_id FROM messages WHERE sender_id IS NOT NULL
        AND sender_type = 1
        AND sender_id NOT IN (SELECT user_id FROM users) LIMIT 100""")
        answers = cur.fetchall()
        if len(answers) == 0:
            sys.exit("Job finally is done!!!")
        for answer in answers:
            try:
                user = client.get_entity(PeerUser(answer[0]))
                user_id, deleted, verified, first_name, last_name, username, phone = \
                    user.id, user.deleted, user.verified, user.first_name, user.last_name, user.username, user.phone
                cur.execute("""INSERT INTO users (
                    user_id, deleted, verified, first_name, last_name, username, phone)
                VALUES(%s, %s, %s, %s, %s, %s, %s)
                ON CONFLICT DO NOTHING""", (user_id, deleted, verified, first_name, last_name, username, phone))
                # print(user_id, deleted, verified, first_name, last_name, username, phone)
            except ValueError:
                cur.execute("""INSERT INTO users (user_id, unable_get_info)
                VALUES(%s, %s) ON CONFLICT DO NOTHING""", (answer[0], 't'))
        conn.commit()

    except Exception:
        print("Oops!", sys.exc_info()[0], sys.exc_info()[1], "occurred.")
        raise
