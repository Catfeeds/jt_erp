/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : rssys

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-07-01 23:49:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `websites`
-- ----------------------------
DROP TABLE IF EXISTS `websites`;
CREATE TABLE `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spu` char(8) NOT NULL COMMENT 'SPU',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `sale_price` float(10,2) NOT NULL COMMENT '售价',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `sale_end_hours` int(1) DEFAULT 6 COMMENT '促销持续时间，产品页显示倒计时用',
  `info` longtext DEFAULT NULL COMMENT '产品详情',
  `images` text DEFAULT NULL COMMENT '产品首图 通常是多图，产品页首图幻灯片',
  `facebook` text DEFAULT NULL COMMENT 'FB跟踪代码',
  `google` text DEFAULT NULL COMMENT 'GA代码',
  `other` text DEFAULT NULL COMMENT '其它JS代码',
  `product_style_title` varchar(50) DEFAULT 'Color' COMMENT '属性名称',
  `product_style` text DEFAULT NULL,
  `related_id` varchar(255) DEFAULT '' COMMENT '推荐产品ID',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸',
  `sale_city` varchar(50) DEFAULT '' COMMENT '销售地区',
  `domain` varchar(255) DEFAULT '' COMMENT '域名',
  `host` varchar(50) DEFAULT '',
  `theme` varchar(50) DEFAULT '' COMMENT '模板',
  `ads_time` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '添加时间',
  `uid` int(11) DEFAULT NULL COMMENT '产品开发人员ID',
  `sale_info` varchar(255) DEFAULT '' COMMENT '促销信息',
  `additional` text DEFAULT NULL COMMENT '产品参数',
  `next_price` decimal(11,2) DEFAULT 0.00 COMMENT '下一件价格',
  `designer` int(11) NOT NULL DEFAULT 0 COMMENT '设计师',
  `is_ads` tinyint(1) unsigned DEFAULT 0 COMMENT '是否投放',
  `ads_user` int(11) unsigned DEFAULT 0 COMMENT '投放人员ID',
  `think` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '选品思路',
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `disable` varchar(255) DEFAULT '0' COMMENT '产品是否已下架  0未下架  1已下架',
  `is_group` tinyint(1) unsigned DEFAULT 0 COMMENT '是否组合产品',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`,`host`),
  KEY `designer` (`designer`),
  KEY `is_ads` (`is_ads`),
  KEY `ads_user` (`ads_user`),
  KEY `sale_city` (`sale_city`),
  KEY `title` (`title`),
  KEY `uid` (`uid`),
  KEY `spu` (`spu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='站点';

-- ----------------------------
-- Records of websites
-- ----------------------------
INSERT INTO `websites` VALUES ('1', 'A1234567', 'test', '123.00', '2222.00', '6', '<p>tesfsfsdf</p>', '[\"\\/upload\\/image\\/20180701\\/15304500565b38d088db8da7.49689291.jpg\",\"\\/upload\\/image\\/20180701\\/15304500605b38d08c8ee8d3.64671822.jpg\"]', '', '', '', '', '[{\"image\":\"\\/upload\\/image\\/20180701\\/15304503925b38d1d8b4bb29.51665976.jpg\",\"name\":\"test\",\"add_price\":0}]', '', 'S,M,L', 'TH', 'test', 'test', 'test', null, '2018-07-01 19:10:36', '1', '', '<p>sdfsdfs</p>', null, '2', '0', '0', null, '2018-07-01 21:06:36', '0', '0');
INSERT INTO `websites` VALUES ('2', 'A1234567', 'sfsdfsdf', '22.00', '2222.00', '6', '<p>sfdsfsdf</p>', '[\"\\/upload\\/image\\/20180701\\/15304512715b38d5470fcc66.08846845.jpg\"]', '', '', '', 'sfsdfsdfs', '[{\"image\":\"\\/upload\\/image\\/20180701\\/15304512795b38d54f86b0a9.29190058.jpg\",\"name\":\"test\",\"add_price\":0},{\"image\":\"\\/upload\\/image\\/20180701\\/15304512845b38d554391623.18713197.jpg\",\"name\":\"ddd\",\"add_price\":0}]', '', 'S,M,L', 'TH', 'sfsdf', 'test2', 'test', null, '2018-07-01 21:21:35', '1', 'sdfdsfs', '<p>sfdsfsdfsf</p>', '12.00', '2', '0', '0', null, '2018-07-01 23:00:31', '0', '1');

-- ----------------------------
-- Table structure for `websites_group`
-- ----------------------------
DROP TABLE IF EXISTS `websites_group`;
CREATE TABLE `websites_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL COMMENT '站点ID',
  `group_title` varchar(50) NOT NULL DEFAULT '' COMMENT '组合产品标题',
  `group_price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '组合产品价格',
  `website_ids` varchar(50) NOT NULL COMMENT '套餐产品ID',
  `group_sort` int(5) unsigned DEFAULT 0 COMMENT '组合排序',
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='组合销售产品表\r\n';

-- ----------------------------
-- Records of websites_group
-- ----------------------------
INSERT INTO `websites_group` VALUES ('7', '2', 'aaa', '1.00', '1,2', '0');
INSERT INTO `websites_group` VALUES ('8', '2', 'bbb', '2.00', '1,1', '0');
INSERT INTO `websites_group` VALUES ('9', '2', 'ccc', '3.00', '1,2,3', '0');
