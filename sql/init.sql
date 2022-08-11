
CREATE SEQUENCE channels_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."channels" (
    "id" integer DEFAULT nextval('channels_id_seq') NOT NULL,
    "channel_id" bigint NOT NULL,
    "title" character varying NOT NULL,
    "date" timestamptz NOT NULL,
    "username" character varying,
    "insert_date" timestamptz DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "order_channel" character varying NOT NULL,
    CONSTRAINT "channels_channel_id" UNIQUE ("channel_id"),
    CONSTRAINT "channels_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


CREATE SEQUENCE messages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."messages" (
    "id" bigint DEFAULT nextval('messages_id_seq') NOT NULL,
    "message_id" bigint NOT NULL,
    "channel_id" bigint,
    "action" character varying,
    "text" character varying,
    "sender_name" character varying,
    "sender_id" bigint,
    "sender_type" smallint,
    "reply_id" bigint,
    "forward_id" bigint,
    "forward_name" character varying,
    "filename" character varying,
    "file_extension" character varying,
    "filehash_sha1" character varying,
    "create_date" timestamptz NOT NULL,
    "edit_date" timestamptz,
    "store_date" timestamptz DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT "messages_message_id_channel_id" UNIQUE ("message_id", "channel_id"),
    CONSTRAINT "messages_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

ALTER TABLE "public"."messages" add column "ts" tsvector
GENERATED ALWAYS AS (to_tsvector('russian', text)) STORED;

COMMENT ON COLUMN "public"."messages"."sender_type" IS '1: user, 2: channel, 3: chat';

COMMENT ON COLUMN "public"."messages"."reply_id" IS 'message_id';


CREATE SEQUENCE orders_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."orders" (
    "id" integer DEFAULT nextval('orders_id_seq') NOT NULL,
    "channel" character varying NOT NULL,
    CONSTRAINT "orders_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."users" (
    "id" bigint DEFAULT nextval('users_id_seq') NOT NULL,
    "user_id" bigint NOT NULL,
    "first_name" character varying,
    "last_name" character varying,
    "username" character varying,
    "phone" character varying,
    "store_date" timestamptz DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "deleted" boolean,
    "verified" boolean,
    "unable_get_info" boolean DEFAULT false NOT NULL,
    CONSTRAINT "users_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "users_user_id" UNIQUE ("user_id")
) WITH (oids = false);
