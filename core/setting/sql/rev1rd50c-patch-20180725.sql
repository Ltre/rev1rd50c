
-- 强烈建议：改为nosql，并针对几个常用的字段加索引



-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
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
  `payload` mediumtext COLLATE utf8_unicode_ci COMMENT 'HTTP Payload from Telegram callback',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录该日志的时间',
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



-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_user` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_bot` tinyint(4) NOT NULL DEFAULT '0',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User对象字段（包含其他自定义字段）';


-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_chat` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `all_members_are_administrators` tinyint(4) NOT NULL DEFAULT '0',
  `photo` bigint(20) NOT NULL DEFAULT '0' COMMENT 'chat.photo -> chat_photo.id(tg官方不返回id字段，属于数据库自增)',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `invite_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pinned_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'chat.pinned_message -> message.message_id',
  `pinned_message_chat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'chat.pinned_message chat -> message.chat.id',
  `sticker_set_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `can_set_sticker_set` tinyint(4) NOT NULL DEFAULT '0',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Chat对象字段（包含其他自定义字段）';


-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_chat_photo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '该字段非tg对象的属性；采用自增',
  `small_file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `big_file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Chat对象字段（包含其他自定义字段）';



-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_message_entity` (
  `id` bigint(20) NOT NULL COMMENT '非官方MessageEntity属性；采用自增',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `offset` int(11) NOT NULL DEFAULT '0',
  `length` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'message_entity.user -> user.id',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='MessageEntity对象字段（包含其他自定义字段）';


-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_audio` (
  `file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `performer` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_size` bigint(20) NOT NULL DEFAULT '0',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Audio对象字段（包含其他自定义字段）';



-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_document` (
  `file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'document.thumb -> photo_size.file_id',
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_size` bigint(20) NOT NULL DEFAULT '0',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Document对象字段（包含其他自定义字段）';



-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
-- linode 已同步
CREATE TABLE `csdr_tg_photo_size` (
  `file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `file_size` bigint(20) NOT NULL DEFAULT '0',
  `log_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '记录此日志的毫秒时间戳',
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='PhotoSize\r\nThis object represents one size of a photo or a file / sticker thumbnail.\r\n图片/贴纸的某种尺寸的文件';




当前Update进度树：
  Update
    - Message
      - User
      - Chat
        - ChatPhoto
      - MessageEntity
      - Audio
      - Document
      - Game[ ]
        - Animation[ ]
      - Sticker[ ]
        - MaskPosition[ ]
      - Video[ ]
      - Voice[ ]
      - VideoNote[ ]
      - Contact[ ]
      - Location[ ]
      - Venue[ ]
      - Invoice[ ]
      - SuccessfulPayment[ ]
        - OrderInfo[ ]
          - ShippingAddress[ ]
    - InlineQuery[ ]
    - ChosenInlineResult[ ]
    - CallbackQuery[ ]
    - ShippingQuery[ ]
    - PreCheckoutQuery[ ]



