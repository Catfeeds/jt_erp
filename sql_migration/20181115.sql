alter table orders add `order_no` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '新的订单编号，跟订单id保持一致' after id;