-- @since 2018-07-25
-- HP localhost 已同步
-- Office localhost 未同步
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Telegram bot api 的Update对象数据，部分字段为Update对象所不具有。';
