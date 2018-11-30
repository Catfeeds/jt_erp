CREATE TABLE `domains` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) DEFAULT '' COMMENT '域名',
  `status` tinyint(1) unsigned DEFAULT 1 COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;