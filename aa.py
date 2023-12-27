import configparser
import hashlib
import os
import sys
from pathlib import Path

import psycopg2
from telethon.errors.rpcerrorlist import ChannelPrivateError
from telethon.errors.rpcerrorlist import MsgIdInvalidError
from telethon.errors.rpcerrorlist import FileReferenceExpiredError
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

channels = {d.entity.username: d.entity
            for d in client.get_dialogs()
            if d.is_channel}

# choose the one that I want list users from
print(*channels, sep='\n')
channel = channels['sturmjager1']
for u in client.get_participants(channel):
    print(u.id, u.first_name, u.last_name, u.username)

