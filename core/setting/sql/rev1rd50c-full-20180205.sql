CREATE DATABASE IF NOT EXISTS `rev1rd50c` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `rev1rd50c`;


DROP TABLE IF EXISTS `dm_rooter`;
CREATE TABLE IF NOT EXISTS `csdr_article` (
  `article_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章ID（自增数据+时间戳）的sha1',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
  `images` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章图片链接数组序列化',
  `contents` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章正文，支持HTML',
  `rooter_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `editor` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '自定义的发布者（对外显示用）',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章';


DROP TABLE IF EXISTS `dm_rooter`;
CREATE TABLE IF NOT EXISTS `dm_rooter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '通行证',
  `first_ip` varchar(16) NOT NULL DEFAULT '',
  `last_ip` varchar(255) NOT NULL DEFAULT '',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员';


DROP TABLE IF EXISTS `dm_mixed`;
CREATE TABLE `dm_mixed` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '代号,用于标识配置项',
  `content` longtext NOT NULL COMMENT '配置值(多个值存储时需序列化)',
  `note` varchar(64) NOT NULL DEFAULT '' COMMENT '注释',
  `create_ip` varchar(15) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_ip` varchar(15) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(32) NOT NULL DEFAULT '' COMMENT '创建人',
  `update_user` varchar(32) NOT NULL DEFAULT '' COMMENT '修改人',
  `valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='杂项配置';

