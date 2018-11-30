/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : rssys

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-08-01 08:15:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ad_fee`
-- ----------------------------
DROP TABLE IF EXISTS `ad_fee`;
CREATE TABLE `ad_fee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL COMMENT '站点ID',
  `ad_total` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '广告总额',
  `ad_date` date NOT NULL COMMENT '广告费用日期',
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`,`ad_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ad_fee
-- ----------------------------
