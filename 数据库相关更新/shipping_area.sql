CREATE TABLE `shiping_area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区',
  `post_code` varchar(50) DEFAULT NULL COMMENT '邮编',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '状态 1启用 0禁用',
  PRIMARY KEY (`id`),
  KEY `province` (`province`,`city`,`area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;