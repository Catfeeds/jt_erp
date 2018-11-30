/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : rssys

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-07-01 11:05:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `orders`
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL COMMENT '站点id',
  `product` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品名称',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人',
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '邮箱',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '国家，使用二字码',
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '省',
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '市',
  `area` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '区',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货地址',
  `post_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '邮编',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '下单时间',
  `pay` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'COD' COMMENT '支付方式 当然只有一种，COD',
  `comment` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户备注',
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '状态 1待确认 2已经确认 3已采购 4已发货 5签收 6拒签',
  `qty` int(10) NOT NULL DEFAULT 1 COMMENT '购买数量',
  `total` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '总价',
  `lc` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '货代',
  `lc_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '物流编号',
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '下单IP',
  `shipping_date` datetime DEFAULT NULL COMMENT '发货时间',
  `delivery_date` datetime DEFAULT NULL COMMENT '签收时间',
  `cost` decimal(10,2) DEFAULT 0.00 COMMENT '采购成本',
  `channel_type` char(1) COLLATE utf8_unicode_ci DEFAULT 'P' COMMENT '货物类型 P普货 M特货',
  `purchase_time` datetime DEFAULT NULL COMMENT '采购时间',
  `back_total` decimal(10,2) DEFAULT 0.00 COMMENT '回款金额',
  `cod_fee` decimal(10,2) DEFAULT 0.00 COMMENT 'COD手续费',
  `shipping_fee` decimal(10,2) DEFAULT 0.00 COMMENT '实际运费',
  `ads_fee` decimal(10,2) DEFAULT 0.00 COMMENT '广告费',
  `other_fee` decimal(10,2) DEFAULT 0.00 COMMENT '其它费用',
  `comment_u` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作人员备注',
  `back_date` datetime DEFAULT NULL COMMENT '回款时间',
  `update_time` timestamp NULL DEFAULT current_timestamp(),
  `is_lock` tinyint(1) unsigned DEFAULT 0 COMMENT '0 未锁定 1已锁单',
  `copy_admin` int(11) unsigned DEFAULT 0 COMMENT '生成新订单用户',
  `uid` int(10) unsigned DEFAULT 0 COMMENT '产品开发人员',
  `money_status` tinyint(1) unsigned DEFAULT 0 COMMENT '0待结算，1已结算，2已退款',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `website` (`website_id`),
  KEY `status` (`status`),
  KEY `country` (`country`),
  KEY `mobile` (`mobile`),
  KEY `email` (`email`),
  KEY `create_date` (`create_date`),
  KEY `lc` (`lc`),
  KEY `lc_number` (`lc_number`),
  KEY `ip` (`ip`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单';

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for `orders_item`
-- ----------------------------
DROP TABLE IF EXISTS `orders_item`;
CREATE TABLE `orders_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `sku` varchar(255) DEFAULT NULL,
  `qty` int(3) unsigned NOT NULL COMMENT '购买数量',
  `price` decimal(10,2) NOT NULL COMMENT '单价',
  `color` varchar(255) DEFAULT '' COMMENT '颜色属性',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸属性',
  `image` varchar(255) DEFAULT '' COMMENT 'SKU对应图',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of orders_item
-- ----------------------------
