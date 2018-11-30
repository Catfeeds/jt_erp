
DROP TABLE IF EXISTS `shipping_order_settlement`;

CREATE TABLE `shipping_order_settlement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_order` int(10) NOT NULL COMMENT '订单ID',
  `lc_number` varchar(255) NOT NULL COMMENT '运单号',
  `back_order_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '回款总金额',
  `back_order` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '回款金额',
  `status` tinyint(1) DEFAULT 1 COMMENT '结款状态(0未结款,1部分结款,2已结款)',
  `currency` varchar(225) DEFAULT '' COMMENT '货币',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '结款日期',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物流订单结算表';



DROP TABLE IF EXISTS `shipping_logistics_state`;

CREATE TABLE `shipping_logistics_state` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_order` int(10) NOT NULL COMMENT '订单ID',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '国家，使用二字码',
  `lc_number` varchar(255) NOT NULL COMMENT '运单号',
  `lc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '货代',
  `state` int(11) DEFAULT '1' COMMENT '物流状态(1在途,2已签收,3拒签,4丢件)',
  `type` int(11) DEFAULT '1' COMMENT '订单类型(1有效,2无效,3转寄仓)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物流订单状态表';


DROP TABLE IF EXISTS `shipping_settlement`;

CREATE TABLE `shipping_settlement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `settlement_number` varchar(255) NOT NULL COMMENT '采购单号 格式：年月日-当开编号',
  `lc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '货代',
  `back_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '回款金额',
  `other_fee` decimal(10,2) DEFAULT '0.00' COMMENT '其它费用',
  `currency` varchar(225) DEFAULT '' COMMENT '货币',
  `status` tinyint(2) unsigned NOT NULL DEFAULT 1 COMMENT '状态 1草稿，2已确认，3作废',
  `uid` int(11) unsigned NOT NULL COMMENT '操作人',
  `date_time` varchar(50) DEFAULT NULL	COMMENT '回款日期',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '导入日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物流回款表';



DROP TABLE IF EXISTS `shipping_settlement_item`;

CREATE TABLE `shipping_settlement_item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_shipping_settlement` int(10) NOT NULL COMMENT '回款ID',
  `id_order` int(10) NOT NULL COMMENT '订单ID',
  `lc_number` varchar(255) NOT NULL COMMENT '运单号',
  `back_order_total` decimal(10,2) DEFAULT '0.00' COMMENT '回款金额',
  `cod_fee` decimal(10,2) DEFAULT '0.00' COMMENT 'COD手续费',
  `shipping_fee` decimal(10,2) DEFAULT '0.00' COMMENT '实际运费',
  `other_fee` decimal(10,2) DEFAULT '0.00' COMMENT '其它费用',
  `currency` varchar(225) DEFAULT '' COMMENT '货币',
  `uid` int(11) unsigned NOT NULL COMMENT '操作人',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '结款日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单回款明细表';

ALTER TABLE location_log ADD location_stock_id INT(11) NOT NULL DEFAULT 0 COMMENT '库存库位id',ADD original_qty INT(11) NOT NULL DEFAULT 0 COMMENT '原来库存数量';




