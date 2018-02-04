/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : rev1rd50c

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2018-02-05 01:16:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `csdr_article`
-- ----------------------------
DROP TABLE IF EXISTS `csdr_article`;
CREATE TABLE `csdr_article` (
  `article_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章ID（自增数据+时间戳）的sha1',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
  `images` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章图片链接数组序列化',
  `digest` text COLLATE utf8_unicode_ci NOT NULL COMMENT '简介',
  `contents` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章正文，支持HTML',
  `admin_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `editor` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '自定义的发布者（对外显示用）',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章';

-- ----------------------------
-- Records of csdr_article
-- ----------------------------
INSERT INTO `csdr_article` VALUES ('446', '456', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', '1', '11', '1231', '0', '123', '0', '0');
INSERT INTO `csdr_article` VALUES ('FDSA', 'FDSFDS', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', 'FDS22', 'FDS', '111111111111', '0', 'FDS', '0', '0');
INSERT INTO `csdr_article` VALUES ('fdsaf', 'vz  ds', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', '45', 'fds', 'sfdfda', '0', '5466', '0', '0');
INSERT INTO `csdr_article` VALUES ('fdsf89s', '测试的范德萨上的都是', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', 'DFSAF', '这是摘要', '反对萨芬的萨', '0', 'EDITOR', '0', '0');
INSERT INTO `csdr_article` VALUES ('mm', 'fds', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', 'r34', 'das', 'dsafd fdsa f', '0', '56', '0', '0');
INSERT INTO `csdr_article` VALUES ('sdfdfdsd', 'fd', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', '1', '1', '1', '1', '1', '0', '0');
INSERT INTO `csdr_article` VALUES ('sfsa', 'f', 'http://w2.dwstatic.com/s1_dwstatic/duowanvideo/20180205/00/4930649.jpg', 'fdfsa', '1', '15', '0', '5', '0', '0');
