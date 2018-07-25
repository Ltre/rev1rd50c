-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 已同步
-- linode 未同步
CREATE TABLE `csdr_tg_update` (
  `update_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.update_id',
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.message -> message.message_id',
  `message_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.message chat -> message.chat.id',
  `edited_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.edited_message -> message.message_id',
  `edited_message_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.edited_message chat -> message.chat.id',
  `channel_post` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.channel_post -> message.message_id',
  `channel_post_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.channel_post chat -> message.chat.id',
  `edited_channel_post` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.edited_channel_post -> message.message_id',
  `edited_channel_post_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.edited_channel_post chat -> message.chat.id',
  `inline_query` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.inline_query -> inline_query.id',
  `inline_query_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.inline_query.from -> user.id',
  `chosen_inline_result` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.chosen_inline_result -> chosen_inline_result.result_id',
  `chosen_inline_result_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.chosen_inline_result.from -> user.id',
  `callback_query` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.callback_query -> callback_query.id',
  `callback_query_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'updatecallback_query.from -> user.id',
  `shipping_query` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.shipping_query -> shipping_query.id',
  `shipping_query_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.shipping_query.from -> user.id',
  `pre_checkout_query` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.pre_checkout_query -> pre_checkout_query.id',
  `pre_checkout_query_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'update.pre_checkout_query.from -> user.id',
  `log_time` int(11) NOT NULL DEFAULT '0' COMMENT '记录该日志的时间',
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Telegram bot api 的Update对象数据，部分字段为Update对象所不具有。';



-- @since 2018-07-25
-- HP localhost 未同步
-- Office localhost 已同步
-- linode 已同步
CREATE TABLE `csdr_tg_message` (
  `message_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'user.id',
  `date` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'chat.id',
  `forward_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `forward_from_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `forward_from_message_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `forward_signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `forward_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reply_to_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reply_to_message_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `edit_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `media_group_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author_signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci,
  `entities` text COLLATE utf8_unicode_ci,
  `caption_entities` text COLLATE utf8_unicode_ci,
  `audio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `game` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sticker` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `voice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video_note` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `caption` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `venue` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `new_chat_members` text COLLATE utf8_unicode_ci,
  `left_chat_member` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `new_chat_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `new_chat_photo` text COLLATE utf8_unicode_ci,
  `delete_chat_photo` tinyint(4) NOT NULL DEFAULT '0',
  `group_chat_created` tinyint(4) NOT NULL DEFAULT '0',
  `supergroup_chat_created` tinyint(4) NOT NULL DEFAULT '0',
  `channel_chat_created` tinyint(4) NOT NULL DEFAULT '0',
  `migrate_to_chat_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `migrate_from_chat_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pinned_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `invoice` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `successful_payment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `connected_website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的时间(毫秒)',
  PRIMARY KEY (`message_id`,`chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Message对象字段（包含其他自定义字段）';
