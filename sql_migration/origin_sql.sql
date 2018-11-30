

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '订单管理中心', null, null, '1', null);
INSERT INTO `menu` VALUES ('2', '仓库管理中心', null, null, '2', null);
INSERT INTO `menu` VALUES ('3', '物流管理中心', null, null, '3', null);
INSERT INTO `menu` VALUES ('4', '产品中心', '1', '/products-base/index', '9', null);
INSERT INTO `menu` VALUES ('5', '站点管理', '1', '/websites/index', '1', null);
INSERT INTO `menu` VALUES ('6', '分类管理', '1', '/categories/index', '10', null);
INSERT INTO `menu` VALUES ('7', '订单管理', '1', '/orders/index', '2', null);
INSERT INTO `menu` VALUES ('8', '系统管理', null, null, '10', null);
INSERT INTO `menu` VALUES ('9', '统计中心', '1', '/orders/order-count', '20', null);
INSERT INTO `menu` VALUES ('10', '销售额统计', '1', '/orders/money-count', '30', null);
INSERT INTO `menu` VALUES ('11', '仓库管理', '2', '/warehouse/index', '50', null);
INSERT INTO `menu` VALUES ('14', '库位库存', '2', '/location-stock/index', '1', null);
INSERT INTO `menu` VALUES ('15', 'SKU上架', '2', '/location-stock/select-code', '30', null);
INSERT INTO `menu` VALUES ('16', '称重出库', '2', '/location-stock/order-weight', null, null);
INSERT INTO `menu` VALUES ('17', '待发货订单', '2', '/orders/index', '20', 0x7B2275726C5F706172616D65746572223A224F72646572735365617263685B7374617475735D3D37227D);
INSERT INTO `menu` VALUES ('18', '已打包订单', '2', '/orders/index', null, 0x7B2275726C5F706172616D65746572223A224F72646572735365617263685B7374617475735D3D38227D);
INSERT INTO `menu` VALUES ('19', '采购需求', '2', '/replenishment/index', '50', null);
INSERT INTO `menu` VALUES ('20', '订单审核', '1', '/orders-audit/index', '30', null);
INSERT INTO `menu` VALUES ('21', 'SKU对应', '2', '/sku-boxs/index', '50', null);
INSERT INTO `menu` VALUES ('22', '采购单', '2', '/purchases/index', '50', null);
INSERT INTO `menu` VALUES ('23', '收货单', '2', '/receipt/index', '60', null);
INSERT INTO `menu` VALUES ('24', '统计报表', null, null, '4', null);
INSERT INTO `menu` VALUES ('25', '订单统计报表', '24', '/order-status-change/index', '1', null);
INSERT INTO `menu` VALUES ('26', '订单推送监控', '24', '/get-shipping-no/index', '5', null);
INSERT INTO `menu` VALUES ('27', '库存日志', '2', '/stock-logs/index', '50', null);
INSERT INTO `menu` VALUES ('28', '调拨', '2', '/requisitions/index', '60', null);
INSERT INTO `menu` VALUES ('29', '下架库位库存', '2', '/location-stock/up-sku-stock', '80', null);
INSERT INTO `menu` VALUES ('30', '仓库盘存', '2', '/inventorys/index', '90', null);
INSERT INTO `menu` VALUES ('32', '预计到货报表', '24', '/purchases-expect/index', '100', null);
INSERT INTO `menu` VALUES ('33', '异常收货单', '2', '/receipt-abnormal/index', '60', null);
INSERT INTO `menu` VALUES ('34', '转寄仓订单', '1', '/forward/index', null, null);
INSERT INTO `menu` VALUES ('35', '超时订单', '1', '/orders-audit/index', null, 0x7B2275726C5F706172616D65746572223A224F72646572735365617263685B7374617475735D3D3132227D);
INSERT INTO `menu` VALUES ('36', '采购及SKU库存查询报表', '24', '/purchases-search/index', '1', null);
INSERT INTO `menu` VALUES ('37', '需求列表', null, null, '100', null);
INSERT INTO `menu` VALUES ('38', '任务进度', '37', '/demand/index', null, null);
INSERT INTO `menu` VALUES ('39', '需求池', '37', '/demand/demand-pool', null, null);
INSERT INTO `menu` VALUES ('40', '采购退货单', '2', '/back/index', '55', null);



SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------


SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/assignment/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/assignment/assign');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/assignment/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/assignment/revoke');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/assignment/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/default/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/default/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/menu/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/assign');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/remove');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/permission/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/assign');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/remove');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/role/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/assign');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/refresh');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/route/remove');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/rule/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/activate');
INSERT INTO `auth_item_child` VALUES ('物流组', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('销售三组', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('销售二组', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/admin/user/login');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/login');
INSERT INTO `auth_item_child` VALUES ('物流组', '/admin/user/logout');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/logout');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/request-password-reset');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/reset-password');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/signup');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/admin/user/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/add-sku');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/addback');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/purchase-sure-back');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/sure-back');
INSERT INTO `auth_item_child` VALUES ('采购组', '/back/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/categories/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/db-explain');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/download-mail');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/toolbar');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/default/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/user/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/user/reset-identity');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/debug/user/set-identity');
INSERT INTO `auth_item_child` VALUES ('查看任务列表', '/demand/*');
INSERT INTO `auth_item_child` VALUES ('查看任务列表', '/demand/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/forward/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/forward/*');
INSERT INTO `auth_item_child` VALUES ('销售三组', '/forward/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/forward/import-forward');
INSERT INTO `auth_item_child` VALUES ('物流组', '/forward/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/forward/index');
INSERT INTO `auth_item_child` VALUES ('销售三组', '/forward/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/forward/relieve-forward');
INSERT INTO `auth_item_child` VALUES ('物流组', '/get-shipping-no/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/get-shipping-no/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/get-shipping-no/push-order-again');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/action');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/diff');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/preview');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/gii/default/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/add-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/confirm');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/delete-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/import-inventorys');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/update-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/inventorys/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-log/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/add-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/ajax-add-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/ajax-order-weight');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/ajax-select-code');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/location-stock/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/order-weight');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/select-code');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/location-stock/view');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/*');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/download');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/download');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/download');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/download');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/download');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/get-attr-by-sku');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/get-attr-by-sku');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/get-attr-by-sku');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/get-attr-by-sku');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/get-attr-by-sku');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/get-attr-by-spu');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/get-attr-by-spu');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/get-attr-by-spu');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/get-attr-by-spu');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/get-attr-by-spu');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/get-sku-by-attr');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/get-sku-by-attr');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/get-sku-by-attr');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/get-sku-by-attr');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/get-sku-by-attr');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/import');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/import');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/import');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/import');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/index');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/save-order-item');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/save-order-item');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/save-order-item');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/save-order-item');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/save-order-item');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/update');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-audit/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders-audit/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders-audit/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders-audit/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders-audit/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders-item/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders-item/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders-item/index');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-item/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders-item/update');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-item/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders-item/view');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders-item/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders-item/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders-item/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('采购组', '/orders/change-status');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/create');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/delete');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/download');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/download');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/download');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('采购组', '/orders/import');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/import-payment-collection-bill');
INSERT INTO `auth_item_child` VALUES ('查询组', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/index');
INSERT INTO `auth_item_child` VALUES ('查询组', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/money-count');
INSERT INTO `auth_item_child` VALUES ('查询组', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/order-count');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/update');
INSERT INTO `auth_item_child` VALUES ('查询组', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/orders/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/delete');
INSERT INTO `auth_item_child` VALUES ('查询组', '/product-comment/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/product-comment/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/image-upload');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/image-upload');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/image-upload');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-base/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/update');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-base/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-base/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-base/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-base/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-base/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-suppliers/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/update');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-suppliers/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-suppliers/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-suppliers/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-suppliers/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-suppliers/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/image-upload');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/image-upload');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/image-upload');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-variant/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/update');
INSERT INTO `auth_item_child` VALUES ('查询组', '/products-variant/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/products-variant/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/products-variant/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/products-variant/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/products-variant/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases-expect/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases-expect/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/back');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/confirm-purchases');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/delete');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/purchases/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/handledone');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/handledone');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/handlemsg');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/handlemsg');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/upload');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/upload');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-abnormal/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt-abnormal/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/update-stock');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt-logs/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/confirm-purchases');
INSERT INTO `auth_item_child` VALUES ('采购组', '/receipt/confirm-purchases');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/print-sku-code');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/track');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/update-qty');
INSERT INTO `auth_item_child` VALUES ('物流组', '/receipt/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/replenishment/add-purchases');
INSERT INTO `auth_item_child` VALUES ('采购组', '/replenishment/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/replenishment/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/replenishment/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/replenishment/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/add-sku');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/confirm-requisitions');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/del-sku');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/done-requisitions');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/requisitions/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/shop/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/shop/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/shop/add-order');
INSERT INTO `auth_item_child` VALUES ('财务', '/shop/add-order');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/shop/add-order');
INSERT INTO `auth_item_child` VALUES ('财务', '/shop/api');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/shop/api');
INSERT INTO `auth_item_child` VALUES ('财务', '/shop/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/shop/index');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/*');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/*');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/*');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/site/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/*');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/about');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/about');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/about');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/about');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/about');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/about');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/about');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/about');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/about');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/about');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/about');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/captcha');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/contact');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/error');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/error');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/error');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/error');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/error');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/error');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/error');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/error');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/error');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/error');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/error');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/index');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/index');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/index');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/index');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/login');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/login');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/login');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/login');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/login');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/login');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/login');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/login');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/login');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/login');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/login');
INSERT INTO `auth_item_child` VALUES ('查询组', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('物流组', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('翻译组', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('翻译组-ID', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('翻译组-PH', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('翻译组-TH', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('翻译组-TW', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('设计师', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('财务', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/*');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/*');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/create');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/create');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/delete');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/delete');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/index');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/update');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/update');
INSERT INTO `auth_item_child` VALUES ('SKU对应', '/sku-boxs/view');
INSERT INTO `auth_item_child` VALUES ('采购组', '/sku-boxs/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-area/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-location-code/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stock-logs/view');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/*');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/create');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/delete');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/index');
INSERT INTO `auth_item_child` VALUES ('采购组', '/stocks/index');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/update');
INSERT INTO `auth_item_child` VALUES ('物流组', '/stocks/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/suppliers/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/suppliers/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/suppliers/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/delete');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/update');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites-sku/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites-sku/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites-sku/view');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/*');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/*');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/*');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/create');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/create');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/create');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/delete');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/delete');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/image-upload');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/image-upload');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/index');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/index');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/index');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/update');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/update');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/upload');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/upload');
INSERT INTO `auth_item_child` VALUES ('系统管理员', '/websites/view');
INSERT INTO `auth_item_child` VALUES ('财务', '/websites/view');
INSERT INTO `auth_item_child` VALUES ('销售一组', '/websites/view');
INSERT INTO `auth_item_child` VALUES ('财务', '查询组');
INSERT INTO `auth_item_child` VALUES ('财务', '物流组');
INSERT INTO `auth_item_child` VALUES ('财务', '采购组');
INSERT INTO `auth_item_child` VALUES ('销售三组', '销售一组');
INSERT INTO `auth_item_child` VALUES ('销售二组', '销售一组');
INSERT INTO `auth_item_child` VALUES ('销售四组', '销售一组');


SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('查看任务列表', '52', '1541143039');
INSERT INTO `auth_assignment` VALUES ('查询组', '34', '1533310418');
INSERT INTO `auth_assignment` VALUES ('物流组', '29', '1535971281');
INSERT INTO `auth_assignment` VALUES ('物流组', '31', '1534126545');
INSERT INTO `auth_assignment` VALUES ('物流组', '32', '1534126553');
INSERT INTO `auth_assignment` VALUES ('物流组', '37', '1536976807');
INSERT INTO `auth_assignment` VALUES ('物流组', '43', '1535340324');
INSERT INTO `auth_assignment` VALUES ('物流组', '44', '1535342307');
INSERT INTO `auth_assignment` VALUES ('物流组', '45', '1535349291');
INSERT INTO `auth_assignment` VALUES ('物流组', '50', '1535776099');
INSERT INTO `auth_assignment` VALUES ('物流组', '51', '1535776266');
INSERT INTO `auth_assignment` VALUES ('物流组', '53', '1535798022');
INSERT INTO `auth_assignment` VALUES ('物流组', '73', '1539328947');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '1', '1530197577');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '13', '1530796732');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '15', '1530797192');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '36', '1541146842');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '38', '1541157284');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '39', '1535337775');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '52', '1535785076');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '70', '1540193958');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '71', '1539151973');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '78', '1540345066');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '84', '1541146933');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '85', '1541158896');
INSERT INTO `auth_assignment` VALUES ('系统管理员', '86', '1541383914');
INSERT INTO `auth_assignment` VALUES ('翻译组', '10', '1530796903');
INSERT INTO `auth_assignment` VALUES ('翻译组', '11', '1530796911');
INSERT INTO `auth_assignment` VALUES ('翻译组', '16', '1530936569');
INSERT INTO `auth_assignment` VALUES ('翻译组', '26', '1532326172');
INSERT INTO `auth_assignment` VALUES ('翻译组', '28', '1532403666');
INSERT INTO `auth_assignment` VALUES ('翻译组', '30', '1532915180');
INSERT INTO `auth_assignment` VALUES ('翻译组', '46', '1535351166');
INSERT INTO `auth_assignment` VALUES ('翻译组', '59', '1536805091');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '25', '1536204051');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '27', '1536203861');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '42', '1536204097');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '64', '1537844687');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '65', '1537845806');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '67', '1538446831');
INSERT INTO `auth_assignment` VALUES ('翻译组-ID', '74', '1539582590');
INSERT INTO `auth_assignment` VALUES ('翻译组-TH', '58', '1536140195');
INSERT INTO `auth_assignment` VALUES ('设计师', '2', '1530441465');
INSERT INTO `auth_assignment` VALUES ('设计师', '7', '1530538984');
INSERT INTO `auth_assignment` VALUES ('设计师', '8', '1530688863');
INSERT INTO `auth_assignment` VALUES ('设计师', '9', '1530688895');
INSERT INTO `auth_assignment` VALUES ('采购组', '14', '1530797044');
INSERT INTO `auth_assignment` VALUES ('采购组', '29', '1532573518');
INSERT INTO `auth_assignment` VALUES ('采购组', '31', '1533107984');
INSERT INTO `auth_assignment` VALUES ('采购组', '32', '1533108082');
INSERT INTO `auth_assignment` VALUES ('采购组', '37', '1535795154');
INSERT INTO `auth_assignment` VALUES ('采购组', '43', '1535340327');
INSERT INTO `auth_assignment` VALUES ('采购组', '45', '1535349295');
INSERT INTO `auth_assignment` VALUES ('采购组', '50', '1536292178');
INSERT INTO `auth_assignment` VALUES ('采购组', '51', '1535776268');
INSERT INTO `auth_assignment` VALUES ('采购组', '53', '1535798020');
INSERT INTO `auth_assignment` VALUES ('采购组', '73', '1539328950');
INSERT INTO `auth_assignment` VALUES ('销售一组', '12', '1530690896');
INSERT INTO `auth_assignment` VALUES ('销售一组', '21', '1531376935');
INSERT INTO `auth_assignment` VALUES ('销售一组', '24', '1531536549');
INSERT INTO `auth_assignment` VALUES ('销售一组', '35', '1533521543');
INSERT INTO `auth_assignment` VALUES ('销售一组', '4', '1530539287');
INSERT INTO `auth_assignment` VALUES ('销售一组', '48', '1535614228');
INSERT INTO `auth_assignment` VALUES ('销售一组', '60', '1536904258');
INSERT INTO `auth_assignment` VALUES ('销售一组', '61', '1536911993');
INSERT INTO `auth_assignment` VALUES ('销售一组', '77', '1541161422');
INSERT INTO `auth_assignment` VALUES ('销售一组', '82', '1541161391');
INSERT INTO `auth_assignment` VALUES ('销售三组', '22', '1538962940');
INSERT INTO `auth_assignment` VALUES ('销售三组', '41', '1534732341');
INSERT INTO `auth_assignment` VALUES ('销售三组', '47', '1535425667');
INSERT INTO `auth_assignment` VALUES ('销售三组', '49', '1535680605');
INSERT INTO `auth_assignment` VALUES ('销售三组', '56', '1535969612');
INSERT INTO `auth_assignment` VALUES ('销售三组', '57', '1535970434');
INSERT INTO `auth_assignment` VALUES ('销售三组', '6', '1538139412');
INSERT INTO `auth_assignment` VALUES ('销售三组', '66', '1538011358');
INSERT INTO `auth_assignment` VALUES ('销售三组', '68', '1538622619');
INSERT INTO `auth_assignment` VALUES ('销售三组', '69', '1538962917');
INSERT INTO `auth_assignment` VALUES ('销售二组', '17', '1531126051');
INSERT INTO `auth_assignment` VALUES ('销售二组', '18', '1531126060');
INSERT INTO `auth_assignment` VALUES ('销售二组', '20', '1531185169');
INSERT INTO `auth_assignment` VALUES ('销售二组', '37', '1536976809');
INSERT INTO `auth_assignment` VALUES ('销售二组', '40', '1534477617');
INSERT INTO `auth_assignment` VALUES ('销售二组', '5', '1530539280');
INSERT INTO `auth_assignment` VALUES ('销售二组', '55', '1536635137');
INSERT INTO `auth_assignment` VALUES ('销售二组', '62', '1537854593');
INSERT INTO `auth_assignment` VALUES ('销售二组', '63', '1537322625');
INSERT INTO `auth_assignment` VALUES ('销售二组', '72', '1539153068');
INSERT INTO `auth_assignment` VALUES ('销售二组', '79', '1540435280');
INSERT INTO `auth_assignment` VALUES ('销售二组', '81', '1540437146');
INSERT INTO `auth_assignment` VALUES ('销售二组', '87', '1541559001');
INSERT INTO `auth_assignment` VALUES ('销售二组', '88', '1541988874');
INSERT INTO `auth_assignment` VALUES ('销售四组', '19', '1541150158');
INSERT INTO `auth_assignment` VALUES ('销售四组', '4', '1539844800');
INSERT INTO `auth_assignment` VALUES ('销售四组', '54', '1541161114');
INSERT INTO `auth_assignment` VALUES ('销售四组', '75', '1539825484');
INSERT INTO `auth_assignment` VALUES ('销售四组', '76', '1539825579');
INSERT INTO `auth_assignment` VALUES ('销售四组', '83', '1540797966');


-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/ad-fee/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/import', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/ad-fee/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/admin/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/change-status', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/login', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/back/*', '2', null, null, null, '1541405337', '1541405337');
INSERT INTO `auth_item` VALUES ('/back/add-sku', '2', null, null, null, '1541405344', '1541405344');
INSERT INTO `auth_item` VALUES ('/back/addback', '2', null, null, null, '1541405342', '1541405342');
INSERT INTO `auth_item` VALUES ('/back/create', '2', null, null, null, '1541405352', '1541405352');
INSERT INTO `auth_item` VALUES ('/back/index', '2', null, null, null, '1541405355', '1541405355');
INSERT INTO `auth_item` VALUES ('/back/purchase-sure-back', '2', null, null, null, '1541405339', '1541405339');
INSERT INTO `auth_item` VALUES ('/back/sure-back', '2', null, null, null, '1541405341', '1541405341');
INSERT INTO `auth_item` VALUES ('/back/view', '2', null, null, null, '1541405353', '1541405353');
INSERT INTO `auth_item` VALUES ('/categories/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/categories/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/categories/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/categories/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/categories/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/categories/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/country/*', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/country/get-area', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/country/get-city', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/country/get-province', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/debug/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/user/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/user/reset-identity', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/debug/user/set-identity', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/demand/*', '2', null, null, null, '1541138519', '1541138519');
INSERT INTO `auth_item` VALUES ('/demand/demand-pool', '2', null, null, null, '1541141861', '1541141861');
INSERT INTO `auth_item` VALUES ('/demand/index', '2', null, null, null, '1541138517', '1541138517');
INSERT INTO `auth_item` VALUES ('/domains/*', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/domains/create', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/domains/delete', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/domains/index', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/domains/update', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/domains/view', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/forward/*', '2', null, null, null, '1539746028', '1539746028');
INSERT INTO `auth_item` VALUES ('/forward/import-forward', '2', null, null, null, '1539746025', '1539746025');
INSERT INTO `auth_item` VALUES ('/forward/index', '2', null, null, null, '1539746023', '1539746023');
INSERT INTO `auth_item` VALUES ('/forward/relieve-forward', '2', null, null, null, '1539746026', '1539746026');
INSERT INTO `auth_item` VALUES ('/get-shipping-no/*', '2', null, null, null, '1536286533', '1536286533');
INSERT INTO `auth_item` VALUES ('/get-shipping-no/delete-order', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/get-shipping-no/index', '2', null, null, null, '1536286535', '1536286535');
INSERT INTO `auth_item` VALUES ('/get-shipping-no/push-order-again', '2', null, null, null, '1536286537', '1536286537');
INSERT INTO `auth_item` VALUES ('/gii/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/action', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/diff', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/preview', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gii/default/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/gridview/*', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/gridview/export/*', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/gridview/export/download', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/inventorys/*', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/add-stock', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/confirm', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/create', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/delete', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/delete-stock', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/import-inventory-all', '2', null, null, null, '1539073068', '1539073068');
INSERT INTO `auth_item` VALUES ('/inventorys/import-inventorys', '2', null, null, null, '1537671115', '1537671115');
INSERT INTO `auth_item` VALUES ('/inventorys/index', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/update', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/update-stock', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/inventorys/view', '2', null, null, null, '1537600640', '1537600640');
INSERT INTO `auth_item` VALUES ('/location-log/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-log/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-log/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-log/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-log/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-log/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/add-stock', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/ajax-add-stock', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/ajax-order-weight', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/ajax-select-code', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/ajax-select-order-sku', '2', null, null, null, '1536681104', '1536681104');
INSERT INTO `auth_item` VALUES ('/location-stock/ajax-up-sku-stock', '2', null, null, null, '1536681104', '1536681104');
INSERT INTO `auth_item` VALUES ('/location-stock/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/order-weight', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/select-code', '2', null, null, null, '1534037597', '1534037597');
INSERT INTO `auth_item` VALUES ('/location-stock/up-sku-stock', '2', null, null, null, '1536681104', '1536681104');
INSERT INTO `auth_item` VALUES ('/location-stock/up-sku-stock-info', '2', null, null, null, '1536681104', '1536681104');
INSERT INTO `auth_item` VALUES ('/location-stock/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/location-stock/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/order-status-change/*', '2', null, null, null, '1535788306', '1535788306');
INSERT INTO `auth_item` VALUES ('/order-status-change/index', '2', null, null, null, '1535788300', '1535788300');
INSERT INTO `auth_item` VALUES ('/order-status-change/view', '2', null, null, null, '1535788304', '1535788304');
INSERT INTO `auth_item` VALUES ('/orders-audit/*', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/change-status', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/download', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/get-attr-by-sku', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/get-attr-by-spu', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/get-sku-by-attr', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/import', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/index', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/money-count', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/order-count', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/save-order-item', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/update', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-audit/view', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders-item/*', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders-item/create', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders-item/delete', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders-item/index', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders-item/update', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders-item/view', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/*', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/change-status', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/orders/create', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/delete', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/download', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/orders/get-pdf', '2', null, null, null, '1536286556', '1536286556');
INSERT INTO `auth_item` VALUES ('/orders/import', '2', null, null, null, '1532921815', '1532921815');
INSERT INTO `auth_item` VALUES ('/orders/import-lc', '2', null, null, null, '1536680726', '1536680726');
INSERT INTO `auth_item` VALUES ('/orders/import-payment-collection-bill', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/orders/index', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/money-count', '2', null, null, null, '1532921815', '1532921815');
INSERT INTO `auth_item` VALUES ('/orders/order-count', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/orders/pick-printjhd', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/orders/push-order', '2', null, null, null, '1536286550', '1536286550');
INSERT INTO `auth_item` VALUES ('/orders/update', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/orders/view', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/*', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/create', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/delete', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/index', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/update', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/out-stock-logs/view', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/product-comment/*', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/product-comment/create', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/product-comment/delete', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/product-comment/index', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/product-comment/update', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/product-comment/view', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/products-base/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-base/add-suppliers', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/products-base/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-base/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-base/image-upload', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/products-base/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-base/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-base/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-suppliers/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/image-upload', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/products-variant/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/products-variant/update-color', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/products-variant/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/purchases-expect/*', '2', null, null, null, '1539072847', '1539072847');
INSERT INTO `auth_item` VALUES ('/purchases-expect/index', '2', null, null, null, '1539072944', '1539072944');
INSERT INTO `auth_item` VALUES ('/purchases-search/index', '2', null, null, null, '1540193980', '1540193980');
INSERT INTO `auth_item` VALUES ('/purchases/*', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/add-sku', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/purchases/back', '2', null, null, null, '1541495907', '1541495907');
INSERT INTO `auth_item` VALUES ('/purchases/confirm-purchases', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/create', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/create-new-order', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/purchases/create-new-order-by-spu', '2', null, null, null, '1539073068', '1539073068');
INSERT INTO `auth_item` VALUES ('/purchases/del-sku', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/purchases/delete', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/index', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/new-order', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/purchases/refound-order', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/purchases/select-sku', '2', null, null, null, '1539073069', '1539073069');
INSERT INTO `auth_item` VALUES ('/purchases/set-inware', '2', null, null, null, '1539073068', '1539073068');
INSERT INTO `auth_item` VALUES ('/purchases/update', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/purchases/view', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/*', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/create', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/delete', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/handledone', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/handlemsg', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/index', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/update', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/upload', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-abnormal/view', '2', null, null, null, '1539239298', '1539239298');
INSERT INTO `auth_item` VALUES ('/receipt-logs/*', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/create', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/delete', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/index', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/update', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/update-stock', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt-logs/view', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt/*', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/confirm-purchases', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/create', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/delete', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/index', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/print-sku-code', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt/print-sku-code-single', '2', null, null, null, '1539073069', '1539073069');
INSERT INTO `auth_item` VALUES ('/receipt/receipt-feedback', '2', null, null, null, '1539073069', '1539073069');
INSERT INTO `auth_item` VALUES ('/receipt/receipt-feedhandle', '2', null, null, null, '1539073069', '1539073069');
INSERT INTO `auth_item` VALUES ('/receipt/track', '2', null, null, null, '1537176334', '1537176334');
INSERT INTO `auth_item` VALUES ('/receipt/update', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/receipt/update-qty', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/receipt/view', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/replenishment/*', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/replenishment/add-purchases', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/replenishment/create', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/replenishment/delete', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/replenishment/index', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/replenishment/select-add', '2', null, null, null, '1539073069', '1539073069');
INSERT INTO `auth_item` VALUES ('/replenishment/update', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/replenishment/view', '2', null, null, null, '1534260490', '1534260490');
INSERT INTO `auth_item` VALUES ('/requisitions/*', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/add-sku', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/confirm-requisitions', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/create', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/del-sku', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/delete', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/done-requisitions', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/index', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/update', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/requisitions/view', '2', null, null, null, '1536570961', '1536570961');
INSERT INTO `auth_item` VALUES ('/shop/*', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/shop/add-order', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/shop/api', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/shop/index', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/site/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/about', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/captcha', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/contact', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/error', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/login', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/site/logout', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/sku-boxs/*', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/sku-boxs/create', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/sku-boxs/delete', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/sku-boxs/index', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/sku-boxs/update', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/sku-boxs/view', '2', null, null, null, '1535260814', '1535260814');
INSERT INTO `auth_item` VALUES ('/stock-location-area/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-area/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-area/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-area/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-area/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-area/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-location-code/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stock-logs/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/import-stocks', '2', null, null, null, '1534342449', '1534342449');
INSERT INTO `auth_item` VALUES ('/stocks/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/stocks/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/suppliers/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/suppliers/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/suppliers/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/suppliers/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/suppliers/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/suppliers/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/warehouse/*', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/warehouse/create', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/warehouse/delete', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/warehouse/index', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/warehouse/update', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/warehouse/view', '2', null, null, null, '1533606451', '1533606451');
INSERT INTO `auth_item` VALUES ('/websites-sku/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites-sku/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites-sku/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites-sku/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites-sku/sku', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/websites-sku/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites-sku/update-sku', '2', null, null, null, '1531364100', '1531364100');
INSERT INTO `auth_item` VALUES ('/websites-sku/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/*', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/copy', '2', null, null, null, '1532279371', '1532279371');
INSERT INTO `auth_item` VALUES ('/websites/create', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/delete', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/image-upload', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/websites/index', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/update', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('/websites/upload', '2', null, null, null, '1530502225', '1530502225');
INSERT INTO `auth_item` VALUES ('/websites/view', '2', null, null, null, '1530197535', '1530197535');
INSERT INTO `auth_item` VALUES ('SKU对应', '1', '只能进行SKU对应操作功能', null, null, '1536045875', '1536046010');
INSERT INTO `auth_item` VALUES ('查看任务列表', '1', null, null, null, '1541139022', '1541139022');
INSERT INTO `auth_item` VALUES ('查询组', '1', '只有查询权限', null, null, '1533310130', '1533310130');
INSERT INTO `auth_item` VALUES ('物流组', '1', null, null, null, '1533136409', '1533136409');
INSERT INTO `auth_item` VALUES ('系统管理员', '1', null, null, null, '1530197494', '1530197494');
INSERT INTO `auth_item` VALUES ('翻译组', '1', null, null, null, '1530796849', '1530796849');
INSERT INTO `auth_item` VALUES ('翻译组-ID', '1', '只能获取印尼地区的订单', null, null, '1536056767', '1536056767');
INSERT INTO `auth_item` VALUES ('翻译组-PH', '1', '只能查询到菲律宾的订单', null, null, '1536056998', '1536056998');
INSERT INTO `auth_item` VALUES ('翻译组-TH', '1', '只能获取泰国地区的单', null, null, '1536056884', '1536056884');
INSERT INTO `auth_item` VALUES ('翻译组-TW', '1', '只能获取台湾地区的订单信息', null, null, '1536056361', '1536056443');
INSERT INTO `auth_item` VALUES ('设计师', '1', null, null, null, '1530441385', '1530441385');
INSERT INTO `auth_item` VALUES ('财务', '1', null, null, null, '1530537757', '1530537757');
INSERT INTO `auth_item` VALUES ('采购组', '1', null, null, null, '1530796952', '1530796952');
INSERT INTO `auth_item` VALUES ('销售一组', '1', null, null, null, '1530537695', '1530537695');
INSERT INTO `auth_item` VALUES ('销售三组', '1', null, null, null, '1533260985', '1533260985');
INSERT INTO `auth_item` VALUES ('销售二组', '1', null, null, 0x693A313B, '1530537736', '1531928122');
INSERT INTO `auth_item` VALUES ('销售四组', '1', '销售四组', null, null, '1539825148', '1539825235');


SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for back
-- ----------------------------
DROP TABLE IF EXISTS `back`;
CREATE TABLE `back` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `back` varchar(50) NOT NULL DEFAULT '' COMMENT '退库单号',
  `order_number` varchar(50) NOT NULL DEFAULT '' COMMENT '采购单号',
  `consignee` varchar(50) NOT NULL DEFAULT '' COMMENT '退库收货人',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT '退库收货人电话，可以是手机也可以是座机',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '退库收货人地址',
  `express` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '快递单号',
  `expressPrice` decimal(10,2) DEFAULT '0.00' COMMENT '退货运费',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '退货类型，1仅退款，退货退款',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '退库单状态',
  `notes` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '采购成本金额',
  `amount_real` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '最终退库金额',
  `serial_number` varchar(50) NOT NULL DEFAULT '' COMMENT '退款流水号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='退库表';

INSERT INTO `back` VALUES ('57', 'T201811074180', '20181107-24', '三姐', '15089502590', '广东省汕尾市海丰县 梅陇镇溪西一巷头123奶茶店楼上3楼', '', '0.00', '2', '2', '清转寄仓库存，国内仓全退掉', '2018-11-07 18:16:49', '51', '27.00', '27.00', '');

-- ----------------------------
-- Table structure for back_items
-- ----------------------------
DROP TABLE IF EXISTS `back_items`;
CREATE TABLE `back_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `back_id` int(11) NOT NULL DEFAULT '0' COMMENT '退库单id',
  `sku` varchar(50) NOT NULL DEFAULT '' COMMENT '退库sku',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '退库数量',
  `notes` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='退库商品表';


DROP TABLE IF EXISTS `back_logs`;
CREATE TABLE `back_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `back_id` int(11) NOT NULL DEFAULT '0' COMMENT '退库id',
  `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '操作人操作变更的状态，例：由1变为2，那就是2',
  `records` varchar(255) NOT NULL COMMENT '操作记录',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退库日志表';

DROP TABLE IF EXISTS `forward`;
CREATE TABLE `forward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_code` char(5) DEFAULT NULL COMMENT '仓库代码，最多10个字符，通常为A,B,C,A1,B1',
  `id_order` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `lc_number` varchar(255) DEFAULT '' COMMENT '物流编号',
  `country` varchar(255) DEFAULT '' COMMENT '国家，使用二字码',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态（0未匹配，1已匹配，2已转运）',
  `new_id_order` bigint(20) unsigned DEFAULT '0' COMMENT '新订单号',
  `new_lc_number` varchar(255) DEFAULT '' COMMENT '新物流编号',
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `forward_time` datetime DEFAULT NULL COMMENT '匹配转寄时间',
  `uid` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_order` (`id_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='转寄仓';

DROP TABLE IF EXISTS `order_record`;
CREATE TABLE `order_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_users` bigint(20) unsigned NOT NULL,
  `id_order` bigint(20) unsigned NOT NULL,
  `id_order_status` bigint(20) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '操作类型(1新增,2编辑,3删除,4状态变更)',
  `user_name` varchar(64) DEFAULT NULL,
  `desc` text,
  `created_at` datetime DEFAULT NULL COMMENT '建立日期',
  PRIMARY KEY (`id`),
  KEY `id_order_status` (`id_order_status`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单操作记录';

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
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '下单时间',
  `pay` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'COD' COMMENT '支付方式 当然只有一种，COD',
  `comment` text COLLATE utf8_unicode_ci COMMENT '用户备注',
  `status` int(5) unsigned DEFAULT '1' COMMENT '状态 1待确认 2已经确认 3已采购 4已发货 5签收 6拒签',
  `qty` int(10) NOT NULL DEFAULT '1' COMMENT '购买数量',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `lc` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '货代',
  `is_print` tinyint(1) DEFAULT '2' COMMENT '是否已打印拣货单:1.已打印，2未打印',
  `lc_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '物流编号',
  `is_pdf` tinyint(4) DEFAULT '0' COMMENT '是否获取面单信息',
  `pdf` varchar(225) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '面单信息',
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '下单IP',
  `shipping_date` datetime DEFAULT NULL COMMENT '发货时间',
  `delivery_date` datetime DEFAULT NULL COMMENT '签收时间',
  `cost` decimal(10,2) DEFAULT '0.00' COMMENT '采购成本',
  `channel_type` char(1) COLLATE utf8_unicode_ci DEFAULT 'P' COMMENT '货物类型 P普货 M特货',
  `purchase_time` datetime DEFAULT NULL COMMENT '采购时间',
  `back_total` decimal(10,2) DEFAULT '0.00' COMMENT '回款金额',
  `cod_fee` decimal(10,2) DEFAULT '0.00' COMMENT 'COD手续费',
  `shipping_fee` decimal(10,2) DEFAULT '0.00' COMMENT '实际运费',
  `ads_fee` decimal(10,2) DEFAULT '0.00' COMMENT '广告费',
  `other_fee` decimal(10,2) DEFAULT '0.00' COMMENT '其它费用',
  `comment_u` text COLLATE utf8_unicode_ci COMMENT '操作人员备注',
  `back_date` datetime DEFAULT NULL COMMENT '回款时间',
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_lock` tinyint(1) unsigned DEFAULT '0' COMMENT '0 未锁定 1已锁单',
  `copy_admin` int(11) unsigned DEFAULT '0' COMMENT '生成新订单用户',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '产品开发人员',
  `money_status` tinyint(1) unsigned DEFAULT '0' COMMENT '0待结算，1已结算，2已退款',
  `weight` decimal(10,2) DEFAULT '0.00' COMMENT '重',
  `lc_number_forward` varchar(225) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '转运订单号',
  `id_order_forward` int(11) DEFAULT '0' COMMENT '订单号',
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


DROP TABLE IF EXISTS `orders_item`;
CREATE TABLE `orders_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `sku` varchar(25) DEFAULT NULL,
  `qty` int(3) unsigned NOT NULL COMMENT '购买数量',
  `price` decimal(10,2) NOT NULL COMMENT '单价',
  `color` varchar(255) DEFAULT '' COMMENT '颜色属性',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸属性',
  `image` varchar(255) DEFAULT '' COMMENT 'SKU对应图',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `products_base`;
CREATE TABLE `products_base` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categorie` int(11) NOT NULL COMMENT '分类 ',
  `product_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '产品类型  1普货 2特货',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别  1男 2女 0通用',
  `title` varchar(255) NOT NULL COMMENT '产品名称',
  `en_name` varchar(225) DEFAULT NULL,
  `spu` char(8) NOT NULL COMMENT '产品主编号，系统自动生成，长度8位',
  `image` text NOT NULL COMMENT '主图，保存图片URL',
  `uid` int(11) unsigned DEFAULT '0' COMMENT '产品开发人员ID',
  `open` tinyint(1) unsigned DEFAULT '0' COMMENT '可见性  0没设置   1组内可见  2 所有人可见      ',
  `declaration_hs` varchar(255) DEFAULT NULL COMMENT '海关申报编码',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `spu` (`spu`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品基本信息表';

-- ----------------------------
-- Records of products_base
-- ----------------------------
INSERT INTO `products_base` VALUES ('1', '2', '1', '0', 'test', '', 'Z00001PF', '/upload/image/20180702/15305036675b39a1f3234965.42590682.jpg', '1', '1', '', '2018-07-02 03:54:28');
INSERT INTO `products_base` VALUES ('2', '1', '1', '2', '包包', 'bag', 'A00002PF', '/upload/image/20180702/15305066185b39ad7aefee13.19842621.jpg', '1', '1', '', '2018-09-03 18:51:08');

DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_number` varchar(255) NOT NULL COMMENT '采购单号',
  `sku` char(13) NOT NULL COMMENT 'SKU',
  `qty` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `buy_link` text COMMENT '购买链接',
  `info` text COMMENT '说明',
  `delivery_qty` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实收数量',
  `refound_qty` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退货数量',
  `delivery_uid` int(11) NOT NULL DEFAULT '0' COMMENT '收货人',
  PRIMARY KEY (`id`),
  KEY `purchase_number` (`purchase_number`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `products_variant`;
CREATE TABLE `products_variant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spu` char(8) CHARACTER SET utf8mb4 NOT NULL COMMENT 'spu',
  `color` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '颜色',
  `size` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '尺寸',
  `sku` char(13) CHARACTER SET utf8mb4 NOT NULL COMMENT '产品变体编号SPU+5位，共13位',
  `image` text CHARACTER SET utf8mb4 COMMENT '图片',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `spu` (`spu`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品变体表\r\n一个产品可能会有多个变体，所有变体都保存在这里\r\n';

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL COMMENT '采购单号 格式：年月日-当开编号',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `amaount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `supplier` varchar(255) NOT NULL DEFAULT '通用供应商',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '采购状态 0草稿，1已确认，2已采购，3已收货',
  `uid` int(11) unsigned NOT NULL COMMENT '操作人',
  `platform` varchar(255) DEFAULT '1688' COMMENT '采购平台',
  `platform_order` varchar(255) DEFAULT '' COMMENT '平台订单号',
  `platform_track` varchar(255) DEFAULT '' COMMENT '物流单号',
  `track_name` varchar(50) DEFAULT '' COMMENT '快递公司',
  `delivery_time` date DEFAULT NULL COMMENT '预计到货时间',
  `shipping_amount` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `notes` varchar(225) DEFAULT '' COMMENT '备注',
  `is_back` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否有退货，1表示有退货',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `supplier` (`supplier`),
  KEY `uid` (`uid`),
  KEY `order_number` (`order_number`)
) ENGINE=InnoDB AUTO_INCREMENT=2785 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `receipt_abnormal`;
CREATE TABLE `receipt_abnormal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '异常收货单id',
  `track_number` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '物流单号',
  `contents` text NOT NULL COMMENT '反馈内容',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0待采购处理，1待库房处理，2处理完成',
  `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建者id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `receipt_abnormal_logs`;
CREATE TABLE `receipt_abnormal_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_abnormal_id` int(11) NOT NULL DEFAULT '0' COMMENT '异常收货单id',
  `dealContents` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '处理内容',
  `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '回复人id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '表示谁回复：1采购回复，2库房回复',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `receipt_feedback`;
CREATE TABLE `receipt_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收货反馈表id',
  `receipt_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上架单号',
  `order_number` varchar(50) NOT NULL DEFAULT '0' COMMENT '采购单号',
  `sku` char(13) NOT NULL DEFAULT '' COMMENT 'SKU',
  `contents` varchar(255) NOT NULL DEFAULT '' COMMENT '反馈内容',
  `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '收货反馈状态：1正常，2异常',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` time NOT NULL DEFAULT '00:00:00' COMMENT '更新时间',
  `track_number` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物流单号',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1为采购回复',
  PRIMARY KEY (`id`),
  KEY `order_number` (`order_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `receipt_logs`;
CREATE TABLE `receipt_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `track_number` varchar(255) NOT NULL COMMENT '快递单号',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `create_uid` int(11) unsigned NOT NULL COMMENT '创建人',
  `comment` text COMMENT '备注',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0收货中 1完成 2异常 3取消',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `track_number` (`track_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `receipt_logs_items`;
CREATE TABLE `receipt_logs_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) unsigned NOT NULL COMMENT '上架单号',
  `order_number` varchar(50) NOT NULL COMMENT '采购单号',
  `sku` char(13) NOT NULL,
  `buy_qty` int(5) NOT NULL COMMENT '应收数量',
  `get_qty` int(5) NOT NULL COMMENT '实收数量',
  `location_code` varchar(50) NOT NULL COMMENT '库位编号',
  `warning_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '异常状态 0正常 1待处理 2已确认',
  `update_date` datetime DEFAULT NULL,
  `update_uid` int(11) unsigned DEFAULT '0' COMMENT '异常处理人',
  PRIMARY KEY (`id`),
  KEY `receipt_id` (`receipt_id`),
  KEY `order_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `replenishment`;
CREATE TABLE `replenishment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) unsigned DEFAULT NULL COMMENT '订单ID',
  `sku_id` varchar(255) DEFAULT NULL COMMENT 'sku',
  `supplement_number` int(11) NOT NULL DEFAULT '0' COMMENT '补充数量',
  `status` varchar(255) NOT NULL DEFAULT '未采购' COMMENT '已采购 /未采购',
  `create_time` datetime DEFAULT NULL,
  `update_tag` tinyint(1) unsigned DEFAULT '0' COMMENT '更新订单标志',
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `requisitions`;
CREATE TABLE `requisitions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL COMMENT '单号',
  `order_type` varchar(50) NOT NULL COMMENT '调拨类型: 库内调拨、库间调拨、退货调拨',
  `out_stock` varchar(50) NOT NULL DEFAULT 'SZ001' COMMENT '调出仓',
  `in_stock` varchar(50) NOT NULL COMMENT '调入仓，退货类型的调入仓为：退货仓',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  `create_uid` int(11) unsigned NOT NULL COMMENT '操作人',
  `order_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0草稿，1已确认，2已完成',
  PRIMARY KEY (`id`),
  KEY `order_number` (`order_number`),
  KEY `order_type` (`order_type`),
  KEY `out_stock` (`out_stock`),
  KEY `in_stock` (`in_stock`),
  KEY `order_status` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='调拨单';

DROP TABLE IF EXISTS `requisitions_items`;
CREATE TABLE `requisitions_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `req_id` int(11) unsigned NOT NULL,
  `sku` char(13) NOT NULL,
  `qty` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `req_id` (`req_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='调拨单明细';

DROP TABLE IF EXISTS `shiping_area`;
CREATE TABLE `shiping_area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区',
  `post_code` varchar(50) DEFAULT NULL COMMENT '邮编',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  `country` char(2) NOT NULL COMMENT '国家',
  PRIMARY KEY (`id`),
  KEY `province` (`province`,`city`,`area`)
) ENGINE=InnoDB AUTO_INCREMENT=180139 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of shiping_area
-- ----------------------------
INSERT INTO `shiping_area` VALUES ('12195', 'กรุงเทพมหานครฯ(Bangkok)', 'เขตสัมพันธวงศ์(Samphanthawong)', null, '10100', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12196', 'กรุงเทพมหานครฯ(Bangkok)', 'ป้อมปราบศัตรูพ่าย(Pom Prap Sattru Phai)', null, '10100', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12197', 'กรุงเทพมหานครฯ(Bangkok)', 'วัฒนา(Wattana)', null, '10110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12198', 'กรุงเทพมหานครฯ(Bangkok)', 'บางคอแหลม(Bang Kholame)', null, '10120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12199', 'กรุงเทพมหานครฯ(Bangkok)', 'เขตยานนาวา(Yannawa)', null, '10120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12200', 'กรุงเทพมหานครฯ(Bangkok)', 'ราษฎร์บูรณะ(Rat Burana)', null, '10140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12201', 'กรุงเทพมหานครฯ(Bangkok)', 'ทุ่งครุ(Thung Khru)', null, '10140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12202', 'กรุงเทพมหานครฯ(Bangkok)', 'บางบอน(Bang Bon)', null, '10150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12203', 'กรุงเทพมหานครฯ(Bangkok)', 'บางขุนเทียน(Bang Khun Thian)', null, '10150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12204', 'กรุงเทพมหานครฯ(Bangkok)', 'บางแค(Bang Khae)', null, '10160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12205', 'กรุงเทพมหานครฯ(Bangkok)', 'พิษณุโลก(Phasi Charoen)', null, '10160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12206', 'กรุงเทพมหานครฯ(Bangkok)', 'หนองแขม(Nong Khaem)', null, '10160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12207', 'กรุงเทพมหานครฯ(Bangkok)', 'ทวีวัฒนา(Thawi Wattana)', null, '10170', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12208', 'กรุงเทพมหานครฯ(Bangkok)', 'ตลิ่งชัน(Taling Chan)', null, '10170', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12209', 'กรุงเทพมหานครฯ(Bangkok)', 'พระนคร(Phra Nakhon)', null, '10200', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12210', 'กรุงเทพมหานครฯ(Bangkok)', 'หลักสี่(Lak Si)', null, '10210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12211', 'กรุงเทพมหานครฯ(Bangkok)', 'สายไหม(Sai Mai)', null, '10220', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12212', 'กรุงเทพมหานครฯ(Bangkok)', 'บางเขน(Bang Khen)', null, '10220', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12213', 'กรุงเทพมหานครฯ(Bangkok)', 'ลาดพร้าว(Lat Phrao)', null, '10230', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12214', 'กรุงเทพมหานครฯ(Bangkok)', 'คันนายาเวา(Khanna Yao)', null, '10230', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12215', 'กรุงเทพมหานครฯ(Bangkok)', 'บางกะปิ(Bang Kapi)', null, '10240', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12216', 'กรุงเทพมหานครฯ(Bangkok)', 'บึงกุ่ม(Bung Kum)', null, '10240', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12217', 'กรุงเทพมหานครฯ(Bangkok)', 'สะพานสูง B39(Saphan Sung B39)', null, '10240', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12218', 'กรุงเทพมหานครฯ(Bangkok)', 'สรวงหลวง(Suang Luang)', null, '10250', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12219', 'กรุงเทพมหานครฯ(Bangkok)', 'พระสัตวแพทย์(Pra Vet)', null, '10250', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12220', 'กรุงเทพมหานครฯ(Bangkok)', 'บางนา(Bang Na)', null, '10260', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12221', 'กรุงเทพมหานครฯ(Bangkok)', 'พระโขนง(Phra Khanong)', null, '10260', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12222', 'กรุงเทพมหานครฯ(Bangkok)', 'ดุสิต(Dusit)', null, '10300', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12223', 'กรุงเทพมหานครฯ(Bangkok)', 'วังทองหรั่ง(Wangthong Lang)', null, '10310', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12224', 'กรุงเทพมหานครฯ(Bangkok)', 'ห้วยขวาง(Huai Khwang)', null, '10320', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12225', 'กรุงเทพมหานครฯ(Bangkok)', 'ปทุมวัน(Pathum Wan)', null, '10330', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12226', 'กรุงเทพมหานครฯ(Bangkok)', 'พญาไท(Phaya Thai)', null, '10400', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12227', 'กรุงเทพมหานครฯ(Bangkok)', 'ดินแดง(Ding Daeng)', null, '10400', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12228', 'กรุงเทพมหานครฯ(Bangkok)', 'บางรัก(Bang Rak)', null, '10500', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12229', 'กรุงเทพมหานครฯ(Bangkok)', 'มีนบุรี(Min Buri)', null, '10510', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12230', 'กรุงเทพมหานครฯ(Bangkok)', 'คลองสามวา(Khlong Samwa)', null, '10510', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12231', 'กรุงเทพมหานครฯ(Bangkok)', 'ลาดกระบัง(Lat Krabang)', null, '10520', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12232', 'กรุงเทพมหานครฯ(Bangkok)', 'หนองจอก(Nong Chok)', null, '10530', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12233', 'กรุงเทพมหานครฯ(Bangkok)', 'คลองซาน(Khlong San)', null, '10600', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12234', 'กรุงเทพมหานครฯ(Bangkok)', 'ธนบุรี(Thon Buri)', null, '10600', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12235', 'กรุงเทพมหานครฯ(Bangkok)', 'บางกอกใหญ่(Bangkok Yai)', null, '10600', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12236', 'กรุงเทพมหานครฯ(Bangkok)', 'บางพลี(Bang Plat)', null, '10700', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12237', 'กรุงเทพมหานครฯ(Bangkok)', 'บางกอกน้อย(Bangkok Noi)', null, '10700', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12238', 'กรุงเทพมหานครฯ(Bangkok)', 'บางซื่อ(Bang Su)', null, '10800', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12239', 'สมุทรปราการ(Samut Prakan)', 'พระประแดง(Phra Pradaeng)', null, '10130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12240', 'สมุทรปราการ(Samut Prakan)', 'เมืองสมุทรปราการ(Muang Samut Prakan)', null, '10270', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12241', 'สมุทรปราการ(Samut Prakan)', 'พระสมุทรเจดีย์(Phra Samut Chedi)', null, '10290', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12242', 'สมุทรปราการ(Samut Prakan)', 'บางเสาธง(Bang Sao Thong)', null, '10540', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12243', 'สมุทรปราการ(Samut Prakan)', 'บางพลี(Bang Phli)', null, '10540', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12244', 'สมุทรปราการ(Samut Prakan)', 'บางบ่อ(Bang Bo)', null, '10560', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12245', 'นนทบุรี(Nonthaburi)', 'เมืองนนทบุรี(Muang Nonthaburi)', null, '11000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12246', 'นนทบุรี(Nonthaburi)', 'บางบัวทอง(Bang Bua Thong)', null, '11110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12247', 'นนทบุรี(Nonthaburi)', 'ปากเกร็ด(Pak Kret)', null, '11120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12248', 'นนทบุรี(Nonthaburi)', 'บางกรวย(Bang Kruai)', null, '11130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12249', 'นนทบุรี(Nonthaburi)', 'บางใหญ่(Bang Yai)', null, '11140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12250', 'นนทบุรี(Nonthaburi)', 'สายไห่(Sai Noi)', null, '11150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12251', 'ปทุมธานี(Pathum Thani)', 'เมืองปทุมธานี(Muang Pathum Thani)', null, '12000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12252', 'ปทุมธานี(Pathum Thani)', 'ธัญบุรี(Thanyaburi)', null, '12110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12253', 'ปทุมธานี(Pathum Thani)', 'คลองหลวง(Khlong Luang)', null, '12120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12254', 'ปทุมธานี(Pathum Thani)', 'ลาดกระบัง(Lat Lum Kaeo)', null, '12140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12255', 'ปทุมธานี(Pathum Thani)', 'ลำลูกกา(Lam Luk Ka)', null, '12150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12256', 'ปทุมธานี(Pathum Thani)', 'สามโคก(Sam Khok)', null, '12160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12257', 'ปทุมธานี(Pathum Thani)', 'หนองหญ้า(Nong Sua)', null, '12170', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12258', 'อ่างทอง(Ang Thong)', 'อำเภอเมืองอ่างทอง (Muang ANG THONG)', null, '14000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12259', 'อ่างทอง(Ang Thong)', 'อำเภอวิเศษชัยชาญ (Wiset Chai Chan)', null, '14110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12260', 'อ่างทอง(Ang Thong)', 'อำเภอโพธิ์ทอง (Pho Thong)', null, '14120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12261', 'อ่างทอง(Ang Thong)', 'อำเภอป่าโมก (Pa Mok)', null, '14130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12262', 'อ่างทอง(Ang Thong)', 'อำเภอไชโย (Chaiyo)', null, '14140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12263', 'อ่างทอง(Ang Thong)', 'อำเภอแสวงหา (Sawaeng Ha)', null, '14150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12264', 'อ่างทอง(Ang Thong)', 'อำเภอสามโก้ (Samko)', null, '14160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12265', 'สระบุรี(Saraburi)', 'เมืองสระบุรี(Muang Saraburi)', null, '18000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12266', 'สระบุรี(Saraburi)', 'เฉลิมพระเกียรติ(Chaloem Phra Kiat)', null, '18000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12267', 'สระบุรี(Saraburi)', 'แก่งคอย(Kaeng Khoi)', null, '18110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12268', 'สระบุรี(Saraburi)', 'พระพุทธบาท(Phra Phutthabat)', null, '18120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12269', 'สระบุรี(Saraburi)', 'บ้านโม(Ban Mo)', null, '18130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12270', 'สระบุรี(Saraburi)', 'หนองแค(Nong Khae)', null, '18140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12271', 'สระบุรี(Saraburi)', 'วิหารแดง(Wihan Daeng)', null, '18150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12272', 'สระบุรี(Saraburi)', 'เซาไห่(Sao Hai)', null, '18160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12273', 'สระบุรี(Saraburi)', 'หนองแสง(Nong Saeng)', null, '18170', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12274', 'สระบุรี(Saraburi)', 'มวกเหล็ก(Muak Lek)', null, '18180', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12275', 'สระบุรี(Saraburi)', 'หนองดู(Nong Doan)', null, '18190', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12276', 'สระบุรี(Saraburi)', 'ดอนผุด(Don Phut)', null, '18210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12277', 'สระบุรี(Saraburi)', 'วังม่วง(Wang Muang)', null, '18220', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12278', 'ระยอง(Rayong)', 'เมืองระยอง(Muang Rayong)', null, '21000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12279', 'ระยอง(Rayong)', 'แกลง(Klaeng)', null, '21110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12280', 'ระยอง(Rayong)', 'เขาชะเมา(Khao Chamao)', null, '21110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12281', 'ระยอง(Rayong)', 'บ้านค่าย(Ban Khai)', null, '21120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12282', 'ระยอง(Rayong)', 'บ้านช้าง(Ban Chang)', null, '21130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12283', 'ระยอง(Rayong)', 'ปลวกแดง(Pluak Daeng)', null, '21140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12284', 'ระยอง(Rayong)', 'นิคมพัฒนา(Nikhom Pattana)', null, '21180', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12285', 'ระยอง(Rayong)', 'วังจันทร์(Wang Chan)', null, '21210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12286', 'จันทบุรี(Chanthaburi)', 'เมืองจันทบุรี(Muang Chanthaburi)', null, '22000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12287', 'จันทบุรี(Chanthaburi)', 'ขลุง(Khlung)', null, '22110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12288', 'จันทบุรี(Chanthaburi)', 'ท่าใหม่(Tha Mai)', null, '22120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12289', 'จันทบุรี(Chanthaburi)', 'แหลมสิงห์(Laem Sing)', null, '22130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12290', 'จันทบุรี(Chanthaburi)', 'พงษ์รอนรอน(Pong Nam Ron)', null, '22140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12291', 'จันทบุรี(Chanthaburi)', 'มะขาม(Makham)', null, '22150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12292', 'จันทบุรี(Chanthaburi)', 'แก่งหางแมว(Kaeng Hang Maew)', null, '22160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12293', 'จันทบุรี(Chanthaburi)', 'นายายอาม(Na Yai Am)', null, '22160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12294', 'จันทบุรี(Chanthaburi)', 'ซอยเลย(Soi Dao)', null, '22180', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12295', 'จันทบุรี(Chanthaburi)', 'เขาคิชฌกูฏ(Khao Kitchakut)', null, '22210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12296', 'ตราด(Trat)', 'เมืองตราด(Muang Trat)', null, '23000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12297', 'ตราด(Trat)', 'เกาะกุด(Ko Kud)', null, '23000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12298', 'ตราด(Trat)', 'คลองใหญ่(Khlong Yai)', null, '23110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12299', 'ตราด(Trat)', 'แหลมงอบ(Laem Ngob)', null, '23120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12300', 'ตราด(Trat)', 'เกาะช้าง(Ko Chang)', null, '23120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12301', 'ตราด(Trat)', 'เขาสามพราน(Khao Saming)', null, '23130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12302', 'ตราด(Trat)', 'บ่อไร่(Bo Rai)', null, '23140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12303', 'ฉะเชิงเทรา(Chachoengsao)', 'เมืองฉะเชิงเทรา(Muang Chachoengsao)', null, '24000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12304', 'ฉะเชิงเทรา(Chachoengsao)', 'คลองขอนแก่น(Khlong Kaen)', null, '24000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12305', 'ฉะเชิงเทรา(Chachoengsao)', 'บางคล้า(Bang Khla)', null, '24110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12306', 'ฉะเชิงเทรา(Chachoengsao)', 'สนามชัยเขต(Rachsan)', null, '24120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12307', 'ฉะเชิงเทรา(Chachoengsao)', 'พนมสารคาม(Phanom Sarakham)', null, '24120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12308', 'ฉะเชิงเทรา(Chachoengsao)', 'บางปะกง(Bang Pakong)', null, '24130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12309', 'ฉะเชิงเทรา(Chachoengsao)', 'บ้านโพธิ์(Ban Pho)', null, '24140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12310', 'ฉะเชิงเทรา(Chachoengsao)', 'บางน้ำผึ้ง(Bang Nam Prieo)', null, '24150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12311', 'ฉะเชิงเทรา(Chachoengsao)', 'ตะตะบี(Ta Takiab)', null, '24160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12312', 'ฉะเชิงเทรา(Chachoengsao)', 'สนามอาเขตชัยภูมิ(Sanam Chai Khet)', null, '24160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12313', 'ฉะเชิงเทรา(Chachoengsao)', 'แปงยาว(Paeng Yao)', null, '24190', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12314', 'นครนายก(Nakhon Nayok)', 'เมืองนครนายก(Muang Nakhon Nayok)', null, '26000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12315', 'นครนายก(Nakhon Nayok)', 'บ้านนา(Ban Na)', null, '26110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12316', 'นครนายก(Nakhon Nayok)', 'องครักษ์(Ongkarak)', null, '26120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12317', 'นครนายก(Nakhon Nayok)', 'ปากพลี(Pak Phli)', null, '26130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12318', 'สระแก้ว(Sa Kaeo)', 'เขาฉกรรจ์(Khao Chakhun)', null, '27000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12319', 'สระแก้ว(Sa Kaeo)', 'เมืองสระแก้ว(Muang Sa Kaeo)', null, '27000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12320', 'สระแก้ว(Sa Kaeo)', 'วังน้ำเย็น(Wang Nam Yen)', null, '27120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12321', 'สระแก้ว(Sa Kaeo)', 'โคกสูง(Khok Sung)', null, '27120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12322', 'สระแก้ว(Sa Kaeo)', 'อรัญประเทศ(Aranyaprathet)', null, '27120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12323', 'สระแก้ว(Sa Kaeo)', 'วัฒนานคร(Wattana Nakhon)', null, '27160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12324', 'สระแก้ว(Sa Kaeo)', 'ตาพระยา(Ta Phraya)', null, '27180', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12325', 'สระแก้ว(Sa Kaeo)', 'วังซอมบุน(Wang Sombun)', null, '27250', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12326', 'สระแก้ว(Sa Kaeo)', 'คลองหาดใหญ่(Khlong Hat)', null, '27260', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12327', 'นครราชสีมา(Nakhon Ratchasima)', 'เมืองนครราชสีมา(Muang Nakhon Ratchasima)', null, '30000', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12328', 'นครราชสีมา(Nakhon Ratchasima)', 'พิมาย(Phimai)', null, '30110', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12329', 'นครราชสีมา(Nakhon Ratchasima)', 'บัวใหญ่(Bua Yai)', null, '30120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12330', 'นครราชสีมา(Nakhon Ratchasima)', 'บัวลาย(Bua Lai)', null, '30120', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12331', 'นครราชสีมา(Nakhon Ratchasima)', 'ปากช่อง(Pak Chong)', null, '30130', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12332', 'นครราชสีมา(Nakhon Ratchasima)', 'สีคิ้ว(Sikhiu)', null, '30140', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12333', 'นครราชสีมา(Nakhon Ratchasima)', 'ปักธงชัย(Pak Thong Chai)', null, '30150', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12334', 'นครราชสีมา(Nakhon Ratchasima)', 'โนนสูง (Non Sung)', null, '30160', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12335', 'นครราชสีมา(Nakhon Ratchasima)', 'สูงเนิน(Sung Noen)', null, '30170', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12336', 'นครราชสีมา(Nakhon Ratchasima)', 'ประทาย(Prathai)', null, '30180', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12337', 'นครราชสีมา(Nakhon Ratchasima)', 'โชคชัย(Chok Chai)', null, '30190', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12338', 'นครราชสีมา(Nakhon Ratchasima)', 'แดนขุนทอง(Dan Khun Thot)', null, '30210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12339', 'นครราชสีมา(Nakhon Ratchasima)', 'เทพารักษ์(Theparak)', null, '30210', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12340', 'นครราชสีมา(Nakhon Ratchasima)', 'พระธรรมกลม(Phra Thomgkom)', null, '30220', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12341', 'นครราชสีมา(Nakhon Ratchasima)', 'โนนไทย(Non Thai)', null, '30220', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12342', 'นครราชสีมา(Nakhon Ratchasima)', 'เฉลิมพระเกียรติ(Chaloem Phra Kiat)', null, '30230', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12343', 'นครราชสีมา(Nakhon Ratchasima)', 'จักราช(Chakkarat)', null, '30230', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12344', 'นครราชสีมา(Nakhon Ratchasima)', 'ห้วยแถลง(Huai Thalaeng)', null, '30240', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12345', 'นครราชสีมา(Nakhon Ratchasima)', 'ขอนแก่น(Khon Buri)', null, '30250', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12346', 'นครราชสีมา(Nakhon Ratchasima)', 'โขง(Khong)', null, '30260', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12347', 'นครราชสีมา(Nakhon Ratchasima)', 'ชุมพวง(Chum Phuang)', null, '30270', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12348', 'นครราชสีมา(Nakhon Ratchasima)', 'เมืองยาง(Muang Yang)', null, '30270', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('12349', 'นครราชสีมา(Nakhon Ratchasima)', 'ลุ่มธรรมชัย(Lum Thamenchai)', null, '30270', '1', 'TH');
INSERT INTO `shiping_area` VALUES ('136851', 'Kedah', 'Baling', 'Teloi Kanan', '9310', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136852', 'Kedah', 'Kulim', 'Kampung Banggol', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136853', 'Kedah', 'Kulim', 'Kampung Bukit Sidam', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136854', 'Kedah', 'Kulim', 'Kampung Deka', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136855', 'Kedah', 'Kulim', 'Kampung Ekor Kucing', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136856', 'Kedah', 'Kulim', 'Kampung Gajah Merangkak', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136857', 'Kedah', 'Kulim', 'Kampung Guar Lobak', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136858', 'Kedah', 'Kulim', 'Kampung Keda (Mukim Naga Lilit)', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136859', 'Kedah', 'Kulim', 'Kampung Kemumbong', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('136860', 'Kedah', 'Kulim', 'Kampung Kong Kedah', '9400', '1', 'MY');
INSERT INTO `shiping_area` VALUES ('172347', 'SUMATERA UTARA', 'BINJAI', 'BINJAI KOTA', '20711', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172348', 'SUMATERA UTARA', 'BINJAI', 'BINJAI KOTA', '20714', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172349', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20747', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172350', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20745', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172351', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20746', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172352', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20748', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172353', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20741', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172354', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20749', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172355', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20744', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172356', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20742', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172357', 'SUMATERA UTARA', 'BINJAI', 'BINJAI UTARA', '20743', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172358', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20728', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172359', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20724', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172360', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20727', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172361', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20723', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172362', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20722', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172363', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20721', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172364', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20725', '1', 'ID');
INSERT INTO `shiping_area` VALUES ('172365', 'SUMATERA UTARA', 'BINJAI', 'BINJAI SELATAN', '20726', '1', 'ID');


DROP TABLE IF EXISTS `sku_boxs`;
CREATE TABLE `sku_boxs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_sku` char(13) NOT NULL COMMENT '主SKU',
  `s_sku` char(13) NOT NULL COMMENT '附SKU',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态1启用 0取消',
  `create_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  `uid` int(11) unsigned NOT NULL COMMENT '添加人',
  PRIMARY KEY (`id`),
  KEY `s_sku` (`s_sku`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '姓名',
  `leader` tinyint(2) DEFAULT '0' COMMENT '级别 0普通 1组长 2经理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', 'sjs', '0eQ9zimiFtEX9IY3NGwZIVh4K9qDEhNS', '$2y$13$HA9tfFAL2IJG2FHUhBB8teNkVs4hhHIGWsUzwVm0.yhzMdSjGqzyC', null, 'sjs@123.com', '10', '1530441452', '1530441452', '', '0');
INSERT INTO `user` VALUES ('4', 'hqy', '9qIcI-jpM4I_62SNIMg-YY3qTZUKYMld', '$2y$13$KDte2QGO2QlbLjoTxKZR0u1tW/gvpsHATQuySzX9y8qxhqPN57HKi', null, 'hqy@kingdomskymall.net', '10', '1530538670', '1538963106', '何秋云', '1');
INSERT INTO `user` VALUES ('5', 'cth', 'xP8dwZ0ub2QaCCTr0407gR8F7gTyaurR', '$2y$13$juX6kgHk15HAwqc9/68mX.jHfBpyvg4oBjfVm6gkoYAAA9EnaIQHy', null, 'cth@kingdomskymall.net', '0', '1530538770', '1538644873', '蔡腾辉', '1');
INSERT INTO `user` VALUES ('6', 'qfn', 'hb713h1k6_lRNay5eZxIOqRjKEDq6FE2', '$2y$13$w.csGD/qvDPVc59OBNL.T.ShNS44JFJ87063/G13ntPRJYN0FAlve', null, 'qfn@abc.com', '10', '1530538793', '1538638014', '邱鸿恩', '1');
INSERT INTO `user` VALUES ('8', 'xl', 'i9-UiapssRelhg9CMk4dw5QAW9_E3TzF', '$2y$13$DnSDQg36ZJTQHFRl1CcDq./ZZoMuxC1sLdQWI07KyP26Q48/XUlEi', null, 'xl@abc.com', '0', '1530688852', '1541655475', '熊林', '0');
INSERT INTO `user` VALUES ('9', 'ysj', 'dGw-BOeIfZ9wgXCqsX2qbNMP8ofG38-Z', '$2y$13$abl00YiEmjKBdrbZkCDceefP5leJIQzYa4vF/1ZVNsJsuNTEJkfvu', null, 'ysj@abc.com', '10', '1530688882', '1530688882', '杨世杰', '0');
INSERT INTO `user` VALUES ('10', 'lh', 'ifM3CTGJsfdzxgB5w7csaJ_uV_Ga_6AU', '$2y$13$C4aL8ujiv3lvUU43DUZsmOkjTL/QgptoxCRtJXeQmrm3L5YHhQcs2', null, 'lh@abc.com', '10', '1530688927', '1538963204', '李涵', '0');
INSERT INTO `user` VALUES ('12', 'cyz', 'g1M4BS97Bc59MQk8rbKhrtS5MYgMyCiG', '$2y$13$Ym3PIBrdV27ZMxF8dJF9F.9kRHT6tWuIVoPsfgQqAN3.DJJWW/J2S', null, 'cyz@123.com', '10', '1530690886', '1539567507', '陈月珠', '0');
INSERT INTO `user` VALUES ('13', 'root', 'Umx1R8ptxcyf6-hqyhdCqDIcMOkwPRga', '$2y$13$YACUy8YbrqVrGf8JJqSsuO8wa/sw2sMGcbpqp2yA4tX3LEcm08qRa', null, 'root@123.com', '10', '1530796723', '1530796723', '管理员', '2');
INSERT INTO `user` VALUES ('16', 'jwm', '-v89AhcWa4v7HEGGGm6PfzHYixuYe-t1', '$2y$13$IP1VDO/Tnw0ZSBkWW6NMAuPN8R2o3CTZWjRtlnDR1Vz6Ezg0eKCVK', null, 'jwm@abc.com', '0', '1530936562', '1535970207', '蒋雯敏', '0');
INSERT INTO `user` VALUES ('19', 'ctn', 'nlDGd5PSoqK14lFps96KFuB5BAt7oMPZ', '$2y$13$HHG0SqNrErsJ9W/XqyXfxefrf2ctDyXOKoteGlN7NvBCtRTpKJSVO', null, 'ctn@abc.com', '10', '1531126017', '1538963112', '陈婷妮', '1');
INSERT INTO `user` VALUES ('20', 'xuwei', 'k1bcVpjyUDJY3ZdP6CiFSisk53FmiOPs', '$2y$13$IzSYz9LFYFUmv1MtroICUOXqxDdj3cV3UluleS40aW/HTWNVuFVDm', null, 'xuwei@abc.com', '0', '1531185161', '1536228140', '许为', '0');
INSERT INTO `user` VALUES ('21', 'zhd', '6pzvOv7q1Xr8ThbabjYYJSD73Gwm9ag3', '$2y$13$87JzMhNM84y3JVztH8fzn.WLvlkyT/3OstGbgRRj3b45S8KjXwQKK', null, 'zhd@qq.com', '0', '1531376909', '1538722824', '朱怀德', '0');
INSERT INTO `user` VALUES ('22', 'wdr', 'RTsH6Gf-C-shYBmF6HlJb-_U60QF69Bf', '$2y$13$zrsjbJ3nNIqpAp9FdVA54eW7h5N/pvsFvM0lNWCbBS72VhitSgynS', null, 'wdr@qq.com', '10', '1531376949', '1538965337', '吴东仁', '0');
INSERT INTO `user` VALUES ('23', 'test', 'PkQ4QKozweGxyEydPrj-KsATnRQNwCCF', '$2y$13$3WMwf0v/Cn7czilnM4o0helCttYEdGUpc0UYc9qjkihPHEpR5o3Jm', null, 'test@qqq.com', '0', '1531531525', '1541410747', '测试用户', '0');
INSERT INTO `user` VALUES ('24', 'lisi', 'wkDOxTgrNeq2N5qGoUFt-ooa_LswovHJ', '$2y$13$Vl0wlhh4x5jJ8genwVTXjOWNOEwzx/RavXPfSbGiB/YC0N3NDE/Ma', null, 'testbuy@qq.com', '0', '1531536521', '1541410561', '李四', '0');
INSERT INTO `user` VALUES ('25', 'weimeiling', 'HjlNryUEOTTBPqwDQMyfAtLXTgQlfPVH', '$2y$13$mza0TWbqv4j7yWz/nfzl9eYP6aVDEtaCJoyIgDDgfF/eQwj5TbxjG', null, 'weimeiling@qq.com', '10', '1531885625', '1531885625', '魏美玲', '0');
INSERT INTO `user` VALUES ('27', 'pyw', 'T84QzLOvVi0TFco5frEkAKETDLBZorC6', '$2y$13$v0Hflt2DmuUNWhJO/Np//.PSuJtiWPtDwMy44cTTsaLKDAkVUJS46', null, 'pyw1@qq.com', '10', '1532326271', '1538963196', '潘雨薇', '0');
INSERT INTO `user` VALUES ('28', 'test2', 'EzJBo6DSLCnk-9u1ETA1KsUIRDmszCxU', '$2y$13$ofCkCdOjiJ9cdpZ/Grnqe.W8KtPFtAU1hNgmxI1P.AronKeRt3vgC', null, 'test@qq.com', '0', '1532336831', '1541410567', 'test', '0');
INSERT INTO `user` VALUES ('29', 'tyl', '7S99Oo1iF8Q7zrYR5sIYPuPMyksj7WUY', '$2y$13$YoG3B7vRhb7O2vgVwAP0JuF81vBydpnv2QjkC9XXCXrMb7aFUxYEm', null, 'tyl@qq.com', '10', '1532573482', '1532573482', '唐玉兰', '1');
INSERT INTO `user` VALUES ('30', 'llp', 'MKfkWHLsn0DEzLC9JYqUCR1atrt93G9-', '$2y$13$a6tTSQtE85Oc8IIyFqiCJef.Iy3/VxPQNqPVuZYAPisoYcG9k1FGq', null, 'llp@qq.com', '10', '1532915099', '1538963277', '廖良萍', '0');
INSERT INTO `user` VALUES ('32', 'huzhi', 'hwOd6XNZJg-vSoBGcEDRlLF_v30ZOkQP', '$2y$13$jSjAp65p1PpDGvmeS.w.cu1qvjRpq1FXR5l7y6RsgUtJclbmTnTw.', null, 'huzhi@qq.com', '10', '1533108038', '1533108038', '胡志', '0');
INSERT INTO `user` VALUES ('34', 'guest', 'qLAhMfTWjNd3FG2NlFWS4WDcg_ip7xlP', '$2y$13$5aJnnb1e/Ye6ydH5HGo0B.4UGhVYBSxIhCNqRVVSGldlrxPKBTaYS', null, 'guest@king.com', '10', '1533310248', '1533310248', '查看用户', '2');
INSERT INTO `user` VALUES ('35', 'lf', '5nOK8wqgokfQrSNVfwl1v6nj0MnFohaH', '$2y$13$VrUytaMZWjuw7J5CcXwi1Od7sropDEGS/oRPU.zP93r4n/QZ2b4/m', null, 'lf@qq.com', '0', '1533521485', '1535780758', 'lf', '0');
INSERT INTO `user` VALUES ('37', 'hyf', 'y-xkBDgRcosa7vVRCpgitE1WcmsD_uU6', '$2y$13$TVF4ZAg3mkjrt5CkHm0reeIkBhpbTmsX4XVC/J8YNb6WReBCf3D4a', null, 'hyf@qq.com', '10', '1534238008', '1537267028', '黄宇芬', '0');
INSERT INTO `user` VALUES ('38', 'yl', 'cgvsRghE5bTN6feHkUdX2vdKPLYGbVg-', '$2y$13$O6I76If55kmcWTQOBcN9leqndeZ27PJCf5JIqk7NRsRuIxBmJF2Mu', null, 'yl@qq.com', '10', '1534318353', '1534318353', '易良', '0');
INSERT INTO `user` VALUES ('39', 'wangmian', 'WnYugC5v2OZMjy4E7FMdszyAIa_o_9Qw', '$2y$13$WcBuOBtVm5Ubr8C1QLmqDuq4DISCvhrdsPMlfwWDHm02y.sLqrnn.', null, 'wangmian@qq.com', '10', '1534405590', '1537266752', '王勉', '0');
INSERT INTO `user` VALUES ('40', 'hxy', 'zhjzztdZ9TRRhX39KaUTXGII6Ng4KrjI', '$2y$13$PQ4azPdnunHsY/huHW3RGO4ELNG5mkRdmaWrA3iugF3u89Kd9IIla', null, 'hxy@qq.com', '0', '1534477572', '1536635246', '黄晓燕', '0');
INSERT INTO `user` VALUES ('41', 'xjw', 'DYkdlaWPaL3kq41yPqocKBTScX6FCOoR', '$2y$13$5kYm6NNxhZMM1i3zf8NcKexYDK/tFI2YsgQeFuPHa36JWQMQqD8Wy', null, 'xjw@qq.com', '0', '1534732319', '1537166163', '谢江蔚', '1');
INSERT INTO `user` VALUES ('42', 'ljy', 'meHawjgW0dI8Wl_zzudOXPl_JPfCPl4w', '$2y$13$GcW7ZtBoPJOcGnPLxzqgwuDSiX0a3HnZi0HVEMuJRxyb8dMeOg7Mu', null, 'ljy@qq.com', '0', '1534820885', '1538453079', '李佳芸', '0');
INSERT INTO `user` VALUES ('43', 'yizhen', 'M-HqQ3DaG_vYum7n8_busY_iHhvh5eRS', '$2y$13$47trIBU3OnOf8XB2f8ZzE.v5/qOOZTZ7zCoCHeKuQ6Jq8JhYeZsNK', null, 'jiayizhen@kingdomskymall.com', '10', '1535339901', '1535703639', '贾亦真', '0');
INSERT INTO `user` VALUES ('44', 'fengyan', 'Ua1vpfdqLnULbC7rrUH1Gex3R_Hpj6Yh', '$2y$13$MUJD1D6gf0XCi7jQQn6uV.ZRPmIMuedPJLzE46JgIqhOLQn45r41y', null, 'fengyan@kingdomskymall.com', '0', '1535342280', '1535771721', '冯燕', '0');
INSERT INTO `user` VALUES ('45', 'lxz', 'ZggcVMjF0kfgwpMcG7P5T_KnUSw5J-NP', '$2y$13$TPu2Ix64DNU0arcGQplgOOI87w6usfj/SDsKOB83TiQAjKcMJkQty', null, 'lixiaozhuo@kingdomskymall.com', '10', '1535349273', '1535349273', '李晓卓', '0');
INSERT INTO `user` VALUES ('46', 'xml', 'Ok24gPWucX3Dfn4Ly576UQOWCJZCY5WQ', '$2y$13$8N9/wvUAKWxxryJa8ozo7OEGhaTyS8LhkdiNuwOL/rHh8Kik5/jMa', null, 'xml@qq.com', '0', '1535351149', '1536149487', '夏美玲', '0');
INSERT INTO `user` VALUES ('47', 'LX', 'iO39p5nX-WNAjuxF34OQLizD9junS2xG', '$2y$13$a.TOpq.ACl.4i8z/eT/WLey3ttqsI/r0JnV.MDDxFCyvY209N01Wi', null, 'lx@qq.com', '10', '1535425644', '1538963582', '刘霞', '0');
INSERT INTO `user` VALUES ('48', 'GJC', 'RtcrDZUj3xersgKhhOc8kIVZBiOTBd7m', '$2y$13$duzhygM3Fq6Np34vNc7HOuQ8PHrWZz2qBmmmvBVYuiL7bWCKS4Az2', null, 'GJC@qq.com', '0', '1535614125', '1541405179', '管健丞', '0');
INSERT INTO `user` VALUES ('49', 'lmy', 'HuRKSbBvMh7ofs9iceOdxAGgF9P_Dq64', '$2y$13$X89NND7D2MCXVuQVghCAceC2qfxp9lgU/jbMqwskdx5V7XePu1Vy.', null, 'lmy@qq.com', '0', '1535680564', '1541736289', '李梦月', '0');
INSERT INTO `user` VALUES ('50', 'xushengneng', 'N0p6b5Gdfxu2Agowh_T7-DLpENHCEwhK', '$2y$13$msFlzBE5FemOmR2PUf4rReXYZ89yaMc0N3gxSBnHWdssJN8/e5U0a', null, 'xushengneng@kingdomskymall.com', '10', '1535776074', '1538119303', '许盛能', '0');
INSERT INTO `user` VALUES ('51', 'lindanling', 'bX1Bca2iK0oMIoQx3MJa53s2WKH8J8kr', '$2y$13$KdWj0htqZjTiRxmuD48EDuxBOdhtFMa39PC2IwWUxbPKkVLFKBSXi', null, 'lindanling@kingdomskymall.com', '10', '1535776226', '1535776226', '林丹玲', '0');
INSERT INTO `user` VALUES ('52', 'admin', '3Z8otGsQooJjmH6-SWbvVATgQq9TZx6S', '$2y$13$kzgI4g0TwHKVRYZnA4KMbOTePBChaXlgL64AsmZAoFgOuLmzG7E/u', null, 'admin@qq.com', '10', '1535785061', '1535785061', '管理员', '2');
INSERT INTO `user` VALUES ('53', 'lixiaofei', 'h57qRq8MgVahw8z2BzTdqcMGT4SvaTye', '$2y$13$F.rgx0uCqgcmQR33XWFrzuNELHx5Iv83cL7B8S8at0zfGcWMhmNum', null, 'lixiaofei@kingdomskymall.com', '10', '1535797949', '1535797949', '李小飞', '0');
INSERT INTO `user` VALUES ('54', 'huyingfang', 'xtrEaO4Letr7ZEdRKJcAlsMbMJouCVvk', '$2y$13$.BMDjRr0KM4k0k1gOo.ZPel4lxeynJXT9s58e5UjwCco1kzU4iARa', null, 'huyingfang@qq.com', '10', '1535967778', '1537267567', '胡莹芳', '0');
INSERT INTO `user` VALUES ('55', 'longkaijie', 'TrkqVcWVlCzw0-bQ_B0eagQbaM88vDxl', '$2y$13$nICSmn8jsQWJfK1JzljgveoG1VC5PxTVL1le.9mWIEmSSz2v3ucEq', null, 'longkaijie@qq.com', '0', '1535969367', '1536821763', '龙凯杰', '0');
INSERT INTO `user` VALUES ('56', 'yinjiangzhou', '8hNMrfGwniEqtmzfHROUE17_n0-n7pNA', '$2y$13$P/pMQ0M.lcass4Gtahd75uQ6Os8D3albrWFgq00lZW/FN.o/kSHD6', null, 'yinjiangzhou@qq.com', '0', '1535969563', '1535970172', '印江舟', '0');
INSERT INTO `user` VALUES ('57', 'YJZ', 'Jl8nyuYWXV_CLoDQMwv21U7E3AotEL-D', '$2y$13$aP4.z3ver/gue6nyehcYKOs3zju0JwfEOX7HKThd2Ru9x6fzK.7xa', null, 'YJZ@qq.com', '0', '1535970406', '1536747677', '印江舟1', '0');
INSERT INTO `user` VALUES ('58', 'lihan_TH', 'HKuOoncLioQTYEV9bHeUpYOc4s3tr_zL', '$2y$13$WuBuxViCSWrHHpm5lBp/DumJ09Z18PWlaeJg9mdTS7EPdEd.RusZS', null, 'th@aa.com', '10', '1536140176', '1536140176', '测试账号', '0');
INSERT INTO `user` VALUES ('59', 'wujialing', 'YIZ5MCTJDx0duIhNoJ_JIgivr8pnaSfY', '$2y$13$cyfhWzO39vXneF7uWtrNKOhxfMnV71efkeMpHQLcamFUa1mBQpJDm', null, 'wujialing@kingdomskymall.com', '10', '1536805069', '1538963274', '吴佳玲', '0');
INSERT INTO `user` VALUES ('60', 'wangmian_test', 'q2G0GHERO3Qs75bnn1ifAemsiH1f82UJ', '$2y$13$J2wWJ2nlgJdIdYzRRTqwF.nW/Imnw7AIm8YASZBwNOsP7GtOQFi9a', null, 'yudabai@aliyun.com', '0', '1536904230', '1536911841', '测试账号', '0');
INSERT INTO `user` VALUES ('61', 'wm_test', 'ldgxbXGge4brCMK6b_u1zOZxnutEXWRy', '$2y$13$ZvrqNu.0FwVUR3B2uNYzi.HXQwo7nI6ZFZXQEjO5JghY7F7xFLI7.', null, 'yudabais@aliyun.com', '0', '1536911896', '1541410660', '测试账号', '1');
INSERT INTO `user` VALUES ('62', 'LiQing', '7U61ZiCVn_MdCRmKrEwvJxdqkcqbBHsO', '$2y$13$xbAgU5Yw3.aMblLzGF19wufniSpAWUHZGWD5TmMlzTkVs45vnD1UG', null, 'LiQing@qq.com', '10', '1537235143', '1538637841', '李青', '0');
INSERT INTO `user` VALUES ('63', 'Wuwentao', '0_61jSMFFctaS5PQMPMvnscxa9EnUimE', '$2y$13$T9nVqqYjOQSeBR78AnEViemuqyIgHurY3x7LDPDe/hYypFp3nUqBe', null, 'Wuwentao@qq.com', '10', '1537322548', '1538637869', '吴文陶', '0');


DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_name` varchar(255) DEFAULT NULL COMMENT '仓库名称',
  `stock_code` char(5) DEFAULT NULL COMMENT '仓库代码，最多10个字符，通常为A,B,C,A1,B1',
  `stock_type` int(11) DEFAULT NULL COMMENT '仓库类型：1普通 2转运仓',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `uid` int(11) DEFAULT NULL COMMENT '操作人',
  `status` int(11) DEFAULT NULL COMMENT '状态 1可用，0禁用',
  PRIMARY KEY (`id`),
  KEY `stock_name` (`stock_name`,`stock_code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='仓库表';

-- ----------------------------
-- Records of warehouse
-- ----------------------------
INSERT INTO `warehouse` VALUES ('1', '深圳仓库', 'SZ001', '1', '2018-08-07 12:42:36', '15', '1');
INSERT INTO `warehouse` VALUES ('2', '泰国转寄仓', 'TH001', '2', '2018-10-17 03:16:31', '39', '1');
INSERT INTO `warehouse` VALUES ('3', '印尼转寄仓', 'ID001', '2', '2018-10-17 03:16:56', '39', '1');
INSERT INTO `warehouse` VALUES ('4', '马来转寄仓', 'MY001', '2', '2018-11-08 18:29:16', '39', '1');
INSERT INTO `warehouse` VALUES ('5', '台湾转寄仓', 'TW001', '2', '2018-11-08 18:34:04', '39', '1');
INSERT INTO `warehouse` VALUES ('6', '菲律宾转寄仓', 'PH001', '2', '2018-11-08 18:34:37', '39', '1');
INSERT INTO `warehouse` VALUES ('7', '香港转寄仓', 'HK001', '2', '2018-11-08 18:35:12', '39', '1');

DROP TABLE IF EXISTS `websites`;
CREATE TABLE `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spu` char(8) NOT NULL COMMENT 'SPU',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `sale_price` float(10,2) NOT NULL COMMENT '售价',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `sale_end_hours` int(1) DEFAULT '6' COMMENT '促销持续时间，产品页显示倒计时用',
  `info` longtext COMMENT '产品详情',
  `images` text COMMENT '产品首图 通常是多图，产品页首图幻灯片',
  `facebook` text COMMENT 'FB跟踪代码',
  `google` text COMMENT 'GA代码',
  `other` text COMMENT '其它JS代码',
  `product_style_title` varchar(50) DEFAULT 'Color' COMMENT '属性名称',
  `product_style` text,
  `related_id` varchar(255) DEFAULT '' COMMENT '推荐产品ID',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸',
  `sale_city` varchar(50) DEFAULT '' COMMENT '销售地区',
  `domain` varchar(255) DEFAULT '' COMMENT '域名',
  `host` varchar(50) DEFAULT '',
  `theme` varchar(50) DEFAULT '' COMMENT '模板',
  `ads_time` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `uid` int(11) DEFAULT NULL COMMENT '产品开发人员ID',
  `sale_info` varchar(255) DEFAULT '' COMMENT '促销信息',
  `additional` text COMMENT '产品参数',
  `next_price` decimal(11,2) DEFAULT '0.00' COMMENT '下一件价格',
  `designer` int(11) NOT NULL DEFAULT '0' COMMENT '设计师',
  `is_ads` tinyint(1) unsigned DEFAULT '0' COMMENT '是否投放',
  `ads_user` int(11) unsigned DEFAULT '0' COMMENT '投放人员ID',
  `think` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '选品思路',
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `disable` varchar(255) DEFAULT '0' COMMENT '产品是否已下架  0未下架  1已下架',
  `is_group` tinyint(1) unsigned DEFAULT '0' COMMENT '是否组合产品',
  `cloak` tinyint(1) unsigned DEFAULT '0' COMMENT 'Cloak',
  `cloak_url` varchar(255) DEFAULT NULL COMMENT '安全页',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`,`host`),
  KEY `designer` (`designer`),
  KEY `is_ads` (`is_ads`),
  KEY `ads_user` (`ads_user`),
  KEY `sale_city` (`sale_city`),
  KEY `title` (`title`),
  KEY `uid` (`uid`),
  KEY `spu` (`spu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站点';

DROP TABLE IF EXISTS `websites_group`;
CREATE TABLE `websites_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL COMMENT '站点ID',
  `group_title` varchar(50) NOT NULL DEFAULT '' COMMENT '组合产品标题',
  `group_price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '组合产品价格',
  `website_ids` varchar(50) NOT NULL COMMENT '套餐产品ID',
  `group_sort` int(5) unsigned DEFAULT '0' COMMENT '组合排序',
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='组合销售产品表\r\n';

DROP TABLE IF EXISTS `websites_group_ids`;
CREATE TABLE `websites_group_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL COMMENT '产品组合编号',
  `website_id` int(11) unsigned NOT NULL COMMENT '产品ID',
  `qty` int(2) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `websites_sku`;
CREATE TABLE `websites_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL,
  `sku` char(13) DEFAULT NULL COMMENT 'SKU',
  `color` varchar(255) DEFAULT '' COMMENT '颜色',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸',
  `sign` char(32) DEFAULT '颜色+尺寸的MD5值，用于销售点选定属性关联SKU用',
  `images` varchar(255) DEFAULT '' COMMENT '图片',
  `out_stock` tinyint(1) unsigned DEFAULT '1' COMMENT '库存状态，1有，0无，无库存的前台无法选中相关属性组合',
  PRIMARY KEY (`id`),
  KEY `sign` (`sign`) USING BTREE,
  KEY `website_id` (`website_id`) USING BTREE,
  KEY `sku` (`sku`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(2) unsigned NOT NULL DEFAULT '0' COMMENT '分类等级，产品分类共分三级，一级些值默认0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `cn_name` varchar(255) NOT NULL COMMENT '中文名称，通常ERP后台显示用',
  `en_name` varchar(255) NOT NULL COMMENT '英文名称，通常是用来做FEED用',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `level` (`level`,`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', '0', '0', '包', 'bag', '2018-07-02 11:24:58');
INSERT INTO `categories` VALUES ('3', '0', '0', '表', 'watch', null);
INSERT INTO `categories` VALUES ('4', '0', '0', '鞋', 'shoe', null);
INSERT INTO `categories` VALUES ('5', '0', '0', '服装', 'cloth', '2018-07-20 12:19:08');
INSERT INTO `categories` VALUES ('6', '0', '0', '家居', 'home', '2018-07-20 12:21:49');
INSERT INTO `categories` VALUES ('7', '0', '0', '户外', 'outdoors', '2018-07-20 12:22:18');
INSERT INTO `categories` VALUES ('8', '0', '0', '化妆品', 'Cosmetics', '2018-07-20 12:22:48');
INSERT INTO `categories` VALUES ('9', '0', '0', '工具', 'tool', '2018-07-20 12:26:49');
INSERT INTO `categories` VALUES ('10', '0', '0', '3C', '3C', '2018-07-20 12:27:06');
INSERT INTO `categories` VALUES ('12', '0', '0', '科技', 'tech', '2018-07-20 12:27:35');
INSERT INTO `categories` VALUES ('13', '0', '0', '玩具', 'toy', '2018-07-20 12:28:03');
INSERT INTO `categories` VALUES ('14', '0', '0', '首饰', 'Jewelry', '2018-07-20 12:28:30');
INSERT INTO `categories` VALUES ('15', '0', '0', '宠物', 'pet', '2018-07-20 12:29:04');
INSERT INTO `categories` VALUES ('16', '0', '0', '艺术', 'art', '2018-07-20 12:29:23');
INSERT INTO `categories` VALUES ('17', '0', '0', '烹饪', 'cook', '2018-07-20 12:29:42');
INSERT INTO `categories` VALUES ('18', '0', '0', '体育', 'sport', '2018-07-20 12:30:15');
INSERT INTO `categories` VALUES ('19', '0', '0', '健身', 'Fitness', '2018-07-20 12:30:36');
INSERT INTO `categories` VALUES ('21', '0', '0', '赠品', 'Gift', null);

DROP TABLE IF EXISTS `get_shipping_no`;
CREATE TABLE `get_shipping_no` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_order` int(11) NOT NULL COMMENT '订单ID',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '运单号获取次数',
  `return_content` varchar(500) NOT NULL DEFAULT '' COMMENT '物流API返回错误信息',
  `last_get_time` datetime DEFAULT NULL COMMENT '最后获取运单号时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_order_id` (`id_order`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE,
  KEY `idx_last_get_time` (`last_get_time`) USING BTREE,
  KEY `idx_return_content` (`return_content`(255))
) ENGINE=InnoDB AUTO_INCREMENT=1037 DEFAULT CHARSET=utf8 COMMENT='运单号获取失败缓存';

-- ----------------------------
-- Records of get_shipping_no
-- ----------------------------
INSERT INTO `get_shipping_no` VALUES ('1033', '600044492', '3', 'B032', '2018-11-12 18:01:32', '2018-11-09 10:46:58');
INSERT INTO `get_shipping_no` VALUES ('1035', '600045019', '1', 'B032', '2018-11-12 11:07:43', '2018-11-12 11:07:43');
INSERT INTO `get_shipping_no` VALUES ('1036', '600045537', '1', '英文品名为空', '2018-11-13 10:39:45', '2018-11-13 10:39:45');

DROP TABLE IF EXISTS `inventorys`;
CREATE TABLE `inventorys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stock` varchar(50) NOT NULL DEFAULT 'SZ001' COMMENT '仓库',
  `inventory_date` datetime NOT NULL COMMENT '盘存时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `create_uid` int(11) unsigned NOT NULL COMMENT '添加人',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0草稿，1已确认 2已更新库存',
  `comments` text COMMENT '说明',
  `is_all` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否全盘:1.部分盘;2:全盘',
  PRIMARY KEY (`id`),
  KEY `stock` (`stock`)
) ENGINE=InnoDB AUTO_INCREMENT=100015 DEFAULT CHARSET=utf8mb4 COMMENT='盘存单';

DROP TABLE IF EXISTS `inventorys_items`;
CREATE TABLE `inventorys_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) unsigned NOT NULL COMMENT '盘存单ID',
  `location_code` varchar(50) NOT NULL COMMENT '库位代码',
  `sku` varchar(50) NOT NULL,
  `inventory_qty` decimal(10,2) NOT NULL COMMENT '盘点数量',
  `stock_qty` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '库位库存数量',
  `difference_qty` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '差异',
  PRIMARY KEY (`id`),
  KEY `inventory_id` (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3548 DEFAULT CHARSET=utf8mb4 COMMENT='盘存明细';

DROP TABLE IF EXISTS `location_log`;
CREATE TABLE `location_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) DEFAULT NULL COMMENT '订单ID,上架的为空，发货的对应订单ID',
  `sku` char(13) NOT NULL COMMENT 'sku',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '数量，上架为正，下架为负',
  `stock_code` varchar(100) DEFAULT NULL COMMENT '仓库CODE',
  `location_code` varchar(50) DEFAULT NULL COMMENT '库位CODE',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作人',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 为上架 1 为订单下架 2为调拨单下架。 默认 0 ',
  PRIMARY KEY (`id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库位操作日志表';

-- ----------------------------
-- Records of location_log
-- ----------------------------
INSERT INTO `location_log` VALUES ('22', null, 'B00452MM01000', '70', 'SZ001', 'A001', '31', '2018-08-14 09:33:38', '0');
INSERT INTO `location_log` VALUES ('23', null, 'B00452MM01000', '70', 'SZ001', 'A001', '31', '2018-08-14 09:34:10', '0');
INSERT INTO `location_log` VALUES ('24', null, 'B00452MM01000', '70', 'SZ001', 'A001', '31', '2018-08-14 09:39:23', '0');

DROP TABLE IF EXISTS `location_stock`;
CREATE TABLE `location_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_code` varchar(100) NOT NULL COMMENT '库存CODE',
  `area_code` varchar(50) NOT NULL COMMENT '库区CODE',
  `location_code` varchar(50) NOT NULL COMMENT '库位CODE',
  `sku` char(13) NOT NULL COMMENT 'sku',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_date` timestamp NULL DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库位库存表\r\n';

-- ----------------------------
-- Records of location_stock
-- ----------------------------
INSERT INTO `location_stock` VALUES ('2511', 'SZ001', 'A', 'A002', 'A00048PM01000', '4', '2018-09-25 11:37:36', '2018-11-10 16:46:47');
INSERT INTO `location_stock` VALUES ('2512', 'SZ001', 'A', 'A002', 'A00048PM02000', '0', '2018-09-25 11:37:36', '2018-11-07 16:20:37');

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `order_status_change`;
CREATE TABLE `order_status_change` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` bigint(20) unsigned NOT NULL,
  `create_at` varchar(225) DEFAULT '' COMMENT '下单时间',
  `confirm_at` varchar(225) DEFAULT '' COMMENT '已确认时间',
  `purchasing_at` varchar(225) DEFAULT '' COMMENT '待采购时间',
  `purchase_at` varchar(225) DEFAULT '' COMMENT '采购时间',
  `sending_at` varchar(225) DEFAULT '' COMMENT '待发货时间',
  `send_at` varchar(225) DEFAULT '' COMMENT '发货时间',
  `receive_at` varchar(225) DEFAULT '' COMMENT '签收时间',
  `cancel_at` varchar(225) DEFAULT '' COMMENT '取消时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单状态变更记录';

-- ----------------------------
-- Records of order_status_change
-- ----------------------------
INSERT INTO `order_status_change` VALUES ('1', '600000221', '2018-07-08 00:32:00', '', '', '', '', '', '', '');

DROP TABLE IF EXISTS `product_comment`;
CREATE TABLE `product_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL COMMENT '产品ID',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(50) DEFAULT NULL COMMENT '电话',
  `body` varchar(255) DEFAULT NULL COMMENT '评论内容',
  `ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1068 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of product_comment
-- ----------------------------
INSERT INTO `product_comment` VALUES ('3', '851', 'Joe', '', 'Good', null, '1', '2018-08-09 20:57:18');


DROP TABLE IF EXISTS `products_suppliers`;
CREATE TABLE `products_suppliers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) unsigned NOT NULL COMMENT '供应商ID',
  `sku` char(13) NOT NULL COMMENT '产品变体编码',
  `url` text COMMENT '采购链接',
  `min_buy` int(5) unsigned DEFAULT '1' COMMENT '最小起订量',
  `price` decimal(7,2) DEFAULT NULL COMMENT '采购价',
  `deliver_time` int(3) DEFAULT NULL COMMENT '发货周期',
  PRIMARY KEY (`id`),
  KEY `sku` (`sku`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='SKU与供应商对应表\r\n一个SKU可以有多个供应商';

-- ----------------------------
-- Records of products_suppliers
-- ----------------------------
INSERT INTO `products_suppliers` VALUES ('1', '1', 'C00077PM01039', 'https://detail.1688.com/offer/557902052052.html?spm=a261y.7663282.0.0.1a2f2d2cBWVnc0&sk=consign', '1', '35.00', '1');


DROP TABLE IF EXISTS `purchase_for_orders`;
CREATE TABLE `purchase_for_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_number` varchar(255) NOT NULL COMMENT '采购单号',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of purchase_for_orders
-- ----------------------------
INSERT INTO `purchase_for_orders` VALUES ('3', '20180828-1', '600009463');


DROP TABLE IF EXISTS `stock_location_area`;
CREATE TABLE `stock_location_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_code` varchar(100) NOT NULL COMMENT '仓库编号',
  `area_code` varchar(50) NOT NULL COMMENT '库区编号',
  `area_name` varchar(50) DEFAULT NULL COMMENT '库区名称',
  `uid` int(11) NOT NULL COMMENT '操作人',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_code_2` (`stock_code`,`area_code`),
  KEY `stock_code` (`stock_code`),
  KEY `area_code` (`area_code`,`area_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='库区表\r\n';

-- ----------------------------
-- Records of stock_location_area
-- ----------------------------
INSERT INTO `stock_location_area` VALUES ('3', 'SZ001', 'A', 'A区', '15', '2018-08-07 12:43:35');
INSERT INTO `stock_location_area` VALUES ('4', 'SZ001', 'B', 'B区，放大货', '15', '2018-08-12 11:23:57');


DROP TABLE IF EXISTS `stock_location_code`;
CREATE TABLE `stock_location_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_code` varchar(100) NOT NULL COMMENT '仓库编号',
  `area_code` varchar(50) NOT NULL COMMENT '库区编号',
  `code` varchar(50) NOT NULL COMMENT '库位编号',
  `info` varchar(50) DEFAULT NULL COMMENT '库位说明',
  `uid` int(11) unsigned NOT NULL COMMENT '操作人',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_code` (`stock_code`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='库位表';

-- ----------------------------
-- Records of stock_location_code
-- ----------------------------
INSERT INTO `stock_location_code` VALUES ('2', 'SZ001', 'A', 'A001', '3c', '15', '2018-08-07 12:43:55');
INSERT INTO `stock_location_code` VALUES ('3', 'SZ001', 'A', 'A002', '服装专区', '15', '2018-08-12 11:24:25');
INSERT INTO `stock_location_code` VALUES ('4', 'SZ001', 'A', 'a003', '包', '15', '2018-09-03 17:44:10');
INSERT INTO `stock_location_code` VALUES ('5', 'SZ001', 'A', 'A004', '包', '15', '2018-09-03 17:45:17');
INSERT INTO `stock_location_code` VALUES ('9', 'SZ001', 'A', 'A005', ' 手表', '15', '2018-09-03 17:46:52');
INSERT INTO `stock_location_code` VALUES ('10', 'SZ001', 'A', 'A006', '鞋子', '15', '2018-09-03 17:47:31');


DROP TABLE IF EXISTS `stock_logs`;
CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` char(13) DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL COMMENT '订单号',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `cost` float NOT NULL DEFAULT '0' COMMENT '成本',
  `uid` int(11) unsigned DEFAULT NULL COMMENT '操作人',
  `log_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类别：1采购入库，2销售出库，3调拨入库，4调拨出库',
  `create_date` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=13968 DEFAULT CHARSET=utf8mb4 COMMENT='出入库记录';

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '供应商名称',
  `area` varchar(255) DEFAULT NULL COMMENT '地区，写省或市',
  `address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `contacts` varchar(255) DEFAULT NULL COMMENT '联系人',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `url` varchar(255) DEFAULT NULL COMMENT '店铺地址，企业网站、1688、淘宝网店',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '供应商状态，1可用、0禁用',
  `uid` int(11) unsigned NOT NULL COMMENT '产品开发人员ID，谁添加的就保存谁的UID',
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='供应商表\r\n';

DROP TABLE IF EXISTS `websites_copy`;
CREATE TABLE `websites_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spu` char(8) NOT NULL COMMENT 'SPU',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `sale_price` float(10,2) NOT NULL COMMENT '售价',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `sale_end_hours` int(1) DEFAULT '6' COMMENT '促销持续时间，产品页显示倒计时用',
  `info` longtext COMMENT '产品详情',
  `images` text COMMENT '产品首图 通常是多图，产品页首图幻灯片',
  `facebook` text COMMENT 'FB跟踪代码',
  `google` text COMMENT 'GA代码',
  `other` text COMMENT '其它JS代码',
  `product_style_title` varchar(50) DEFAULT 'Color' COMMENT '属性名称',
  `product_style` text,
  `related_id` varchar(255) DEFAULT '' COMMENT '推荐产品ID',
  `size` varchar(255) DEFAULT '' COMMENT '尺寸',
  `sale_city` varchar(50) DEFAULT '' COMMENT '销售地区',
  `domain` varchar(255) DEFAULT '' COMMENT '域名',
  `host` varchar(50) DEFAULT '',
  `theme` varchar(50) DEFAULT '' COMMENT '模板',
  `ads_time` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `uid` int(11) DEFAULT NULL COMMENT '产品开发人员ID',
  `sale_info` varchar(255) DEFAULT '' COMMENT '促销信息',
  `additional` text COMMENT '产品参数',
  `next_price` decimal(11,2) DEFAULT '0.00' COMMENT '下一件价格',
  `designer` int(11) NOT NULL DEFAULT '0' COMMENT '设计师',
  `is_ads` tinyint(1) unsigned DEFAULT '0' COMMENT '是否投放',
  `ads_user` int(11) unsigned DEFAULT '0' COMMENT '投放人员ID',
  `think` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '选品思路',
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `disable` varchar(255) DEFAULT '0' COMMENT '产品是否已下架  0未下架  1已下架',
  `is_group` tinyint(1) unsigned DEFAULT '0' COMMENT '是否组合产品',
  `cloak` tinyint(1) unsigned DEFAULT '0' COMMENT 'Cloak',
  `cloak_url` varchar(255) DEFAULT NULL COMMENT '安全页',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`,`host`),
  KEY `designer` (`designer`),
  KEY `is_ads` (`is_ads`),
  KEY `ads_user` (`ads_user`),
  KEY `sale_city` (`sale_city`),
  KEY `title` (`title`),
  KEY `uid` (`uid`),
  KEY `spu` (`spu`)
) ENGINE=InnoDB AUTO_INCREMENT=2725 DEFAULT CHARSET=utf8 COMMENT='站点';


