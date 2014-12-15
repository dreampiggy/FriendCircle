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

 Date: 12/15/2014 21:47:39 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `relation`
-- ----------------------------
DROP TABLE IF EXISTS `relation`;
CREATE TABLE `relation` (
  `relationID` int(11) NOT NULL AUTO_INCREMENT,
  `userID1` int(11) unsigned NOT NULL,
  `userID2` int(11) unsigned NOT NULL,
  `state` enum('friend','send') NOT NULL,
  `forbidMessage` enum('1to2','2to1','none') NOT NULL DEFAULT 'none',
  `forbidInfo` enum('1to2','2to1','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`relationID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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

SET FOREIGN_KEY_CHECKS = 1;
