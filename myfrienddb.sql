/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50534
 Source Host           : localhost
 Source Database       : myfrienddb

 Target Server Type    : MySQL
 Target Server Version : 50534
 File Encoding         : utf-8

 Date: 12/27/2014 13:50:38 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `commentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `messageID` int(11) NOT NULL,
  `parentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `userName` varchar(11) NOT NULL DEFAULT '',
  `content` varchar(255) DEFAULT '',
  `datetime` datetime NOT NULL,
  `anonymity` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `comment`
-- ----------------------------
BEGIN;
INSERT INTO `comment` VALUES ('3', '9', '9', '10', '吴涛', '我是吴涛，第一次评论好开心', '2014-12-15 11:53:29', '0'), ('4', '11', '11', '9', '李卓立', '我是谁', '2014-12-15 01:24:24', '0'), ('5', '12', '12', '9', '李卓立', '第', '2014-12-15 01:27:15', '0'), ('6', '9', '9', '12', '小张', '我是匿名的', '2014-12-15 01:40:22', '0'), ('7', '12', '12', '11', '小明', '我发表了一个匿名', '2014-12-15 01:41:57', '1'), ('8', '12', '7', '11', '小明', '我对这个匿名用户评论了', '2014-12-15 01:43:39', '1'), ('9', '12', '8', '11', '小明', '这次我不匿名了', '2014-12-15 01:44:21', '0'), ('10', '12', '5', '11', '小明', '我是匿名的～～', '2014-12-15 01:51:42', '1'), ('11', '12', '5', '11', '小明', '我不匿名', '2014-12-15 01:51:53', '0');
COMMIT;

-- ----------------------------
--  Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `messageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) unsigned NOT NULL,
  `userName` varchar(11) NOT NULL DEFAULT '',
  `content` varchar(255) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `anonymity` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `message`
-- ----------------------------
BEGIN;
INSERT INTO `message` VALUES ('9', '9', '李卓立', '我是李卓立，这是我的第一条状态，是公开的，希望大家来评论', '2014-12-15 11:52:40', '0'), ('10', '10', '吴涛', '大家猜猜我是谁？', '2014-12-15 11:54:05', '1'), ('11', '11', '小明', '我发了一条状态！', '2014-12-15 12:59:28', '0'), ('12', '12', '小张', '我发表了状态～～', '2014-12-15 01:00:52', '0'), ('13', '11', '小明', '我发表了匿名', '2014-12-15 01:42:32', '1'), ('14', '11', '小明', '我发表了匿名', '2014-12-15 01:42:50', '1'), ('15', '11', '小明', 'jviXOJof', '2014-12-15 01:43:02', '0'), ('16', '11', '小明', '匿名发布', '2014-12-15 01:52:12', '1'), ('17', '9', '李卓立', '老子匿名发布了', '2014-12-15 21:29:03', '1'), ('18', '9', '李卓立', '李卓立屏蔽了小张', '2014-12-27 12:43:10', '0'), ('19', '9', '李卓立', '', '2014-12-27 01:27:20', '0');
COMMIT;

-- ----------------------------
--  Table structure for `relation`
-- ----------------------------
DROP TABLE IF EXISTS `relation`;
CREATE TABLE `relation` (
  `relationID` int(11) NOT NULL AUTO_INCREMENT,
  `userID1` int(11) unsigned NOT NULL,
  `userID2` int(11) unsigned NOT NULL,
  `state` enum('friend','send') NOT NULL,
  `forbidMessage` enum('1to2','2to1','all','none') NOT NULL DEFAULT 'none',
  `forbidInfo` enum('1to2','2to1','all','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`relationID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `relation`
-- ----------------------------
BEGIN;
INSERT INTO `relation` VALUES ('4', '10', '9', 'friend', 'none', 'none'), ('5', '11', '12', 'friend', 'none', 'none'), ('6', '12', '9', 'friend', 'all', 'none'), ('7', '11', '9', 'friend', 'none', 'none'), ('8', '11', '13', 'friend', 'none', 'none'), ('15', '9', '13', 'send', 'none', 'none');
COMMIT;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userPassword` char(40) NOT NULL DEFAULT '',
  `userName` varchar(11) NOT NULL DEFAULT '',
  `userSex` enum('male','female') DEFAULT NULL,
  `userPhone` char(11) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user`
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('9', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '李卓立', 'male', '15651971731'), ('10', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '吴涛', 'male', '12345678901'), ('11', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '小明', 'male', '12345678901'), ('12', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '小张', 'male', '12345678901'), ('13', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '小花', 'male', '12345678901');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
