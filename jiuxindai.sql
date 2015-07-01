/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : jiuxindai

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-14 10:20:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wh_asset`
-- ----------------------------
DROP TABLE IF EXISTS `wh_asset`;
CREATE TABLE `wh_asset` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_pass` varchar(255) NOT NULL,
  `money` float DEFAULT '0',
  `experience_money_max` int(11) DEFAULT '0',
  `experience_money_max_inc` int(11) DEFAULT '0',
  `bank_card` text,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `wh_member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_asset
-- ----------------------------
INSERT INTO `wh_asset` VALUES ('49', '$2y$13$nAcjS/.dkHtAA7MNomkhaO7YOauXjzdnEpyfCzHy31/IdR/AE8Y5m', '12333', '1321', '0', null, '1428549474', '1428549474');
INSERT INTO `wh_asset` VALUES ('50', '$2y$13$AcUUKF8Pt9hMlDYSixjTgOt8kzcuOA4jfDeWK9tXMhVNH7l5152Uy', '996189', '4000', '811', '{\"6227000140510381491\":\"O:29:\\\"modules\\\\asset\\\\models\\\\BankCard\\\":4:{s:38:\\\"\\u0000modules\\\\asset\\\\models\\\\BankCard\\u0000_cardId\\\";s:19:\\\"6227000140510381491\\\";s:40:\\\"\\u0000modules\\\\asset\\\\models\\\\BankCard\\u0000_bankName\\\";N;s:44:\\\"\\u0000modules\\\\asset\\\\models\\\\BankCard\\u0000_cardUserName\\\";N;s:41:\\\"\\u0000modules\\\\asset\\\\models\\\\BankCard\\u0000_cardPhone\\\";N;}\"}', '1428922757', '1428974074');

-- ----------------------------
-- Table structure for `wh_asset_money`
-- ----------------------------
DROP TABLE IF EXISTS `wh_asset_money`;
CREATE TABLE `wh_asset_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `step` double DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `action_uid` int(11) DEFAULT NULL,
  `llinfo` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_asset_money
-- ----------------------------
INSERT INTO `wh_asset_money` VALUES ('19', '49', '201', '20', '20', 'site/signup-verify', '49', null, '1428549474', '1428549474');
INSERT INTO `wh_asset_money` VALUES ('20', '49', '12', '20', '20', 'site/setjiuxin', '49', null, '1428549501', '1428549501');
INSERT INTO `wh_asset_money` VALUES ('21', '50', '201', '20', '20', 'site/signup-verify', '50', null, '1428922757', '1428922757');
INSERT INTO `wh_asset_money` VALUES ('22', '50', '100', '20', '20', 'site/idcard', '50', null, '1428922837', '1428922837');
INSERT INTO `wh_asset_money` VALUES ('23', '50', '101', '20', '20', 'site/email', '50', null, '1428922870', '1428922870');
INSERT INTO `wh_asset_money` VALUES ('24', '50', '1', '50', '10', 'site/recharge', '50', null, '1428922884', '1428922884');
INSERT INTO `wh_asset_money` VALUES ('25', '50', '123', '30', '10', 'pay/pay-with-balance', '50', null, '1428923079', '1428923079');
INSERT INTO `wh_asset_money` VALUES ('26', '50', '1233', '30', '10', 'pay/pay-with-balance', '50', null, '1428973908', '1428973908');
INSERT INTO `wh_asset_money` VALUES ('27', '50', '1000', '20', '30', 'pay/pay-with-balance', '50', null, '1428973908', '1428973908');
INSERT INTO `wh_asset_money` VALUES ('28', '50', '1222', '30', '10', 'pay/pay-with-balance', '50', null, '1428974006', '1428974006');
INSERT INTO `wh_asset_money` VALUES ('29', '50', '1000', '20', '30', 'pay/pay-with-balance', '50', null, '1428974006', '1428974006');
INSERT INTO `wh_asset_money` VALUES ('30', '50', '1233', '30', '10', 'pay/pay-with-balance', '50', null, '1428974074', '1428974074');
INSERT INTO `wh_asset_money` VALUES ('31', '50', '1000', '20', '30', 'pay/pay-with-balance', '50', null, '1428974074', '1428974074');

-- ----------------------------
-- Table structure for `wh_auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `wh_auth_assignment`;
CREATE TABLE `wh_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `wh_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `wh_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_auth_assignment
-- ----------------------------
INSERT INTO `wh_auth_assignment` VALUES ('admin', '3', '1428975790');

-- ----------------------------
-- Table structure for `wh_auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `wh_auth_item`;
CREATE TABLE `wh_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `wh_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `wh_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_auth_item
-- ----------------------------
INSERT INTO `wh_auth_item` VALUES ('admin', '1', '管理员', null, null, '1428975789', '1428975789');
INSERT INTO `wh_auth_item` VALUES ('asset/experience', '2', '体验金管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/experience/index', '2', '资金记录', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/experience/new-em', '2', '体验金操作', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/experience/setting', '2', '体验金设置', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/finance', '2', '资金动态', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/finance/index', '2', '用户资金', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/recharge', '2', '充值管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/recharge/export', '2', '充值导出', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/recharge/index', '2', '充值列表', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/withdraw', '2', '提现管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/withdraw/check', '2', '提现核对', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/withdraw/final-trial', '2', '提现终审', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/withdraw/first-trial', '2', '提现初审', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('asset/withdraw/index', '2', '提现记录', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category', '2', '栏目管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category/create', '2', '栏目添加', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category/delete', '2', '栏目删除', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category/index', '2', '栏目列表', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category/update', '2', '栏目更新', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/category/view', '2', '栏目详情', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post', '2', '文章管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/create', '2', '文章添加', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/delete', '2', '文章删除', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/index', '2', '文章列表', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/update', '2', '文章更新', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/upload', '2', '文章上传', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('cms/post/view', '2', '文章详情', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('invest/activity', '2', '活动管理', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/activity/create', '2', '活动添加', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/activity/delete', '2', '活动删除', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/activity/index', '2', '活动列表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/activity/update', '2', '活动修改', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/activity/view', '2', '活动查看', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list', '2', '投资列表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list/index', '2', '投资列表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list/month-index', '2', '返息月表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list/return-rate', '2', '返息', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list/return-rate-month', '2', '返息每月', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/list/view', '2', '投资查看', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product', '2', '标管理', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/check', '2', '标审核', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/create', '2', '标添加', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/delete', '2', '标删除', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/index', '2', '标列表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/uncheck', '2', '标解除审核', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/update', '2', '标修改', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('invest/product/view', '2', '标查看', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate', '2', '认证审核', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate/email', '2', '认证邮箱', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate/email-do', '2', '认证邮箱', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate/idcard', '2', '认证身份', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate/idcard-do', '2', '认证身份', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('member/authenticate/view', '2', '认证信息', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('member/members', '2', '会员管理', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/blacklist', '2', '锁定列表', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/friends', '2', '会员好友', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/index', '2', '会员列表', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/lock', '2', '会员锁定', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/unlock', '2', '会员解锁', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/members/view', '2', '会员详情', null, null, '1428975785', '1428975785');
INSERT INTO `wh_auth_item` VALUES ('member/verification', '2', '验证码管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('member/verification/email', '2', '邮箱验证码', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('member/verification/phone', '2', '手机验证码', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('user/management', '2', '用户管理', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/management/create', '2', '角色添加', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/management/delete', '2', '角色删除', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/management/index', '2', '用户列表', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/management/setting', '2', '角色设置', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/management/view', '2', '角色查看', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/rbac', '2', '权限管理', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/permissions', '2', '权限列表', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/role-create', '2', '角色添加', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/role-delete', '2', '角色删除', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/role-setting', '2', '角色设置', null, null, '1428975787', '1428975787');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/role-view', '2', '角色查看', null, null, '1428975786', '1428975786');
INSERT INTO `wh_auth_item` VALUES ('user/rbac/roles', '2', '角色列表', null, null, '1428975786', '1428975786');

-- ----------------------------
-- Table structure for `wh_auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `wh_auth_item_child`;
CREATE TABLE `wh_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `wh_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `wh_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wh_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `wh_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_auth_item_child
-- ----------------------------
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'asset/experience');
INSERT INTO `wh_auth_item_child` VALUES ('asset/experience', 'asset/experience/index');
INSERT INTO `wh_auth_item_child` VALUES ('asset/experience', 'asset/experience/new-em');
INSERT INTO `wh_auth_item_child` VALUES ('asset/experience', 'asset/experience/setting');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'asset/finance');
INSERT INTO `wh_auth_item_child` VALUES ('asset/finance', 'asset/finance/index');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'asset/recharge');
INSERT INTO `wh_auth_item_child` VALUES ('asset/recharge', 'asset/recharge/export');
INSERT INTO `wh_auth_item_child` VALUES ('asset/recharge', 'asset/recharge/index');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'asset/withdraw');
INSERT INTO `wh_auth_item_child` VALUES ('asset/withdraw', 'asset/withdraw/check');
INSERT INTO `wh_auth_item_child` VALUES ('asset/withdraw', 'asset/withdraw/final-trial');
INSERT INTO `wh_auth_item_child` VALUES ('asset/withdraw', 'asset/withdraw/first-trial');
INSERT INTO `wh_auth_item_child` VALUES ('asset/withdraw', 'asset/withdraw/index');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'cms/category');
INSERT INTO `wh_auth_item_child` VALUES ('cms/category', 'cms/category/create');
INSERT INTO `wh_auth_item_child` VALUES ('cms/category', 'cms/category/delete');
INSERT INTO `wh_auth_item_child` VALUES ('cms/category', 'cms/category/index');
INSERT INTO `wh_auth_item_child` VALUES ('cms/category', 'cms/category/update');
INSERT INTO `wh_auth_item_child` VALUES ('cms/category', 'cms/category/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'cms/post');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/create');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/delete');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/index');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/update');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/upload');
INSERT INTO `wh_auth_item_child` VALUES ('cms/post', 'cms/post/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'invest/activity');
INSERT INTO `wh_auth_item_child` VALUES ('invest/activity', 'invest/activity/create');
INSERT INTO `wh_auth_item_child` VALUES ('invest/activity', 'invest/activity/delete');
INSERT INTO `wh_auth_item_child` VALUES ('invest/activity', 'invest/activity/index');
INSERT INTO `wh_auth_item_child` VALUES ('invest/activity', 'invest/activity/update');
INSERT INTO `wh_auth_item_child` VALUES ('invest/activity', 'invest/activity/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'invest/list');
INSERT INTO `wh_auth_item_child` VALUES ('invest/list', 'invest/list/index');
INSERT INTO `wh_auth_item_child` VALUES ('invest/list', 'invest/list/month-index');
INSERT INTO `wh_auth_item_child` VALUES ('invest/list', 'invest/list/return-rate');
INSERT INTO `wh_auth_item_child` VALUES ('invest/list', 'invest/list/return-rate-month');
INSERT INTO `wh_auth_item_child` VALUES ('invest/list', 'invest/list/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'invest/product');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/check');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/create');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/delete');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/index');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/uncheck');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/update');
INSERT INTO `wh_auth_item_child` VALUES ('invest/product', 'invest/product/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'member/authenticate');
INSERT INTO `wh_auth_item_child` VALUES ('member/authenticate', 'member/authenticate/email');
INSERT INTO `wh_auth_item_child` VALUES ('member/authenticate', 'member/authenticate/email-do');
INSERT INTO `wh_auth_item_child` VALUES ('member/authenticate', 'member/authenticate/idcard');
INSERT INTO `wh_auth_item_child` VALUES ('member/authenticate', 'member/authenticate/idcard-do');
INSERT INTO `wh_auth_item_child` VALUES ('member/authenticate', 'member/authenticate/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'member/members');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/blacklist');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/friends');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/index');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/lock');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/unlock');
INSERT INTO `wh_auth_item_child` VALUES ('member/members', 'member/members/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'member/verification');
INSERT INTO `wh_auth_item_child` VALUES ('member/verification', 'member/verification/email');
INSERT INTO `wh_auth_item_child` VALUES ('member/verification', 'member/verification/phone');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'user/management');
INSERT INTO `wh_auth_item_child` VALUES ('user/management', 'user/management/create');
INSERT INTO `wh_auth_item_child` VALUES ('user/management', 'user/management/delete');
INSERT INTO `wh_auth_item_child` VALUES ('user/management', 'user/management/index');
INSERT INTO `wh_auth_item_child` VALUES ('user/management', 'user/management/setting');
INSERT INTO `wh_auth_item_child` VALUES ('user/management', 'user/management/view');
INSERT INTO `wh_auth_item_child` VALUES ('admin', 'user/rbac');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/permissions');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/role-create');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/role-delete');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/role-setting');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/role-view');
INSERT INTO `wh_auth_item_child` VALUES ('user/rbac', 'user/rbac/roles');

-- ----------------------------
-- Table structure for `wh_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `wh_auth_rule`;
CREATE TABLE `wh_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `wh_cms_category`
-- ----------------------------
DROP TABLE IF EXISTS `wh_cms_category`;
CREATE TABLE `wh_cms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_cms_category
-- ----------------------------
INSERT INTO `wh_cms_category` VALUES ('15', '单页文章', '0', '10', '1428459541', '1428459541');

-- ----------------------------
-- Table structure for `wh_cms_post`
-- ----------------------------
DROP TABLE IF EXISTS `wh_cms_post`;
CREATE TABLE `wh_cms_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_cms_post
-- ----------------------------
INSERT INTO `wh_cms_post` VALUES ('37', 'about', '关于玖信贷', '<p>&#34;&#29590;&#20449;&#36151;&#34;&#65288;www.jiuxindai.com&#65289;&#25104;&#31435;&#20110;2013&#24180;&#65292;&#24635;&#37096;&#35774;&#20110;&#21271;&#20140;&#65292;&#38582;&#23646;&#20110;&#20122;&#22307;&#22269;&#38469;&#25237;&#36164;&#26377;&#38480;&#20844;&#21496;&#19979;&#65292;&#21271;&#20140;&#29590;&#25215;&#36164;&#20135;&#31649;&#29702;&#26377;&#38480;&#20844;&#21496;P2P&#36164;&#20135;&#31649;&#29702;&#26495;&#22359;&#65292;&#23646;&#20110;&#21019;&#26032;&#23433;&#20840;&#22411;P2P&#32593;&#32476;&#24179;&#21488;&#12290;&#29590;&#20449;&#36151;&#22242;&#38431;&#25104;&#21592;&#22343;&#20026;&#34892;&#19994;&#20869;&#26377;&#19977;&#24180;&#21450;&#20197;&#19978;&#24037;&#20316;&#32463;&#39564;&#65292;&#26377;&#36131;&#20219;&#24863;&#12289;&#26377;&#25265;&#36127;&#12289;&#26377;&#29702;&#24819;&#30340;&#24180;&#36731;&#31934;&#33521;&#12290;&#21271;&#20140;&#20122;<span>&#22307;&#22269;&#38469;&#25237;&#36164;&#26377;&#38480;&#20844;&#21496;&#65292;&#26159;&#22269;&#20869;&#26497;&#23569;&#25968;&#27880;&#20876;&#36164;&#26412;&#37329;10000&#19975;&#20803;&#20197;&#19978;&#65292;&#19988;&#32463;&#33829;&#39033;&#30446;&#20026;&#34;&#36164;&#20135;&#31649;&#29702;&#12289;&#39033;&#30446;&#25237;&#36164;&#12289;&#25237;&#36164;&#31649;&#29702;&#12289;&#32463;&#27982;&#20449;&#24687;&#21672;&#35810;&#34;&#30340;&#20844;&#21496;&#20043;&#19968;&#12290;</span></p>', '15', '10', '1428459629', '1428459629');
INSERT INTO `wh_cms_post` VALUES ('38', 'baozhang', '保障', '<div class=\"page pt15\">&#10;            <div class=\"page-content\">&#10;                <h1 class=\"page-title\"><span>&#20445;&#38556;&#19968;</span></h1>&#10;&#10;                <p class=\"content-title\">100%&#19981;&#33391;&#20538;&#26435;&#22238;&#36141;</p>&#10;&#10;                <p class=\"ti-2\">&#10;                    &#29590;&#20449;&#36151;&#24635;&#37096;&#35774;&#20110;&#21271;&#20140;&#65292;&#25216;&#26415;&#30740;&#21457;&#20013;&#24515;&#21150;&#20844;&#20250;&#25152;&#20026;&#20122;&#22307;&#22269;&#38469;&#25237;&#36164;&#38598;&#22242;&#33258;&#36141;&#20135;&#19994;&#65292;&#24635;&#38754;&#31215;2700&#22810;&#24179;&#65307;&#36816;&#33829;&#32508;&#21512;&#20013;&#24515;&#20301;&#20110;&#22269;&#36152;CBD&#21830;&#22280;&#26680;&#24515;&#21306;&#22495;&#65292;&#19987;&#20139;&#21150;&#20844;&#38754;&#31215;3000&#22810;&#24179;&#12290;&#29590;&#20449;&#36151;&#38582;&#23646;&#20110;&#20122;&#22307;&#22269;&#38469;&#25237;&#36164;&#26377;&#38480;&#20844;&#21496;P2P&#36164;&#20135;&#31649;&#29702;&#26495;&#22359;&#65292;&#20122;&#22307;&#22269;&#38469;&#25237;&#36164;&#26377;&#38480;&#20844;&#21496;&#26159;&#19968;&#23478;&#37329;&#34701;&#12289;&#23454;&#19994;&#19982;&#20844;&#20849;&#26381;&#21153;&#23454;&#19994;&#24182;&#20030;&#30340;&#22823;&#22411;&#32508;&#21512;&#24615;&#20225;&#19994;&#38598;&#22242;&#65292;&#26159;&#22269;&#20869;&#26497;&#23569;&#25968;&#27880;&#20876;&#36164;&#26412;&#37329;10000&#19975;&#20803;&#20197;&#19978;&#65292;&#19988;&#32463;&#33829;&#39033;&#30446;&#20026;&#34;&#36164;&#20135;&#31649;&#29702;&#12289;&#39033;&#30446;&#25237;&#36164;&#12289;&#25237;&#36164;&#31649;&#29702;&#12289;&#32463;&#27982;&#20449;&#24687;&#21672;&#35810;&#34;&#30340;&#20844;&#21496;&#20043;&#19968;&#12290;&#29590;&#20449;&#36151;&#32972;&#38752;&#23454;&#19994;&#38598;&#22242;&#65292;&#24378;&#22823;&#30340;&#36164;&#37329;&#20316;&#20026;&#21518;&#30462;&#65292;&#25317;&#26377;&#36275;&#22815;&#30340;&#23454;&#21147;&#30830;&#20445;&#19968;&#26086;&#20986;&#29616;&#19981;&#33391;&#20538;&#26435;&#65292;&#21487;&#20197;100%&#36827;&#34892;&#22238;&#36141;&#12290;</p>&#10;            </div>&#10;        </div>&#10;        <div class=\"page pt15\">&#10;            <div class=\"page-content\">&#10;                <h1 class=\"page-title\"><span>&#20445;&#38556;&#20108;</span></h1>&#10;&#10;                <p class=\"content-title\">&#22269;&#20869;&#23569;&#26377;&#30340;&#21452;&#37325;&#39118;&#38505;&#20445;&#38556;&#37329;&#26426;&#21046;</p>&#10;&#10;                <p class=\"ti-2\">&#10;                    &#19968;&#20010;&#39033;&#30446;&#19978;&#32447;&#20043;&#21069;&#65292;&#29590;&#20449;&#36151;&#20250;&#20005;&#26684;&#35201;&#27714;&#21512;&#20316;&#30340;&#31532;&#19977;&#26041;&#37329;&#34701;&#24615;&#25285;&#20445;&#26426;&#26500;&#22312;&#25552;&#20379;&#34701;&#36164;&#39033;&#30446;&#21069;&#65292;&#23545;&#27599;&#26399;&#34701;&#36164;&#30340;&#39033;&#30446;&#25552;&#20986;&#39033;&#30446;&#37329;&#39069;&#30340;2%-4%&#20316;&#20026;&#39118;&#38505;&#20445;&#38556;&#37329;&#12290;&#22914;&#25353;&#26102;&#20607;&#20184;&#65292;&#21017;&#39033;&#30446;&#32467;&#26463;&#21518;&#36864;&#22238;&#35813;&#31508;&#20445;&#35777;&#37329;&#65307;&#22914;&#20986;&#29616;&#36829;&#32422;&#65292;&#21017;&#25187;&#38500;&#35813;&#31508;&#20445;&#35777;&#37329;&#65292;&#28165;&#20607;&#32467;&#26463;&#21518;&#19981;&#20104;&#36820;&#36824;&#12290;&#21516;&#26102;&#65292;&#29590;&#20449;&#36151;&#33258;&#36523;&#23558;&#20250;&#20986;&#36164;&#65292;&#25552;&#20379;&#19982;&#25285;&#20445;&#20844;&#21496;&#30456;&#21516;&#39069;&#24230;&#30340;&#36164;&#37329;&#65292;&#20316;&#20026;&#21452;&#37325;&#39118;&#38505;&#20445;&#38556;&#37329;&#23384;&#22312;&#65292;&#22312;&#39118;&#38505;&#20107;&#39033;&#20986;&#29616;&#19988;&#25285;&#20445;&#20844;&#21496;&#21450;&#20445;&#35777;&#37329;&#19981;&#33021;&#21450;&#26102;&#20195;&#20607;&#30340;&#24773;&#20917;&#19979;&#36805;&#36895;&#21551;&#29992;&#65292;&#30830;&#20445;&#25237;&#36164;&#20154;&#36164;&#37329;&#21450;&#25910;&#30410;&#21487;&#21363;&#26102;&#25910;&#22238;&#65292;&#36827;&#19968;&#27493;&#38145;&#20303;&#25237;&#36164;&#25910;&#30410;&#23433;&#20840;&#12290;</p>&#10;            </div>&#10;        </div>', '15', '10', '1428459933', '1428459933');

-- ----------------------------
-- Table structure for `wh_config`
-- ----------------------------
DROP TABLE IF EXISTS `wh_config`;
CREATE TABLE `wh_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(1000) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_config
-- ----------------------------
INSERT INTO `wh_config` VALUES ('1', 'setIncEM.verify_success_email', '101', null, null, null);
INSERT INTO `wh_config` VALUES ('2', 'setIncEM.verify_success_phone', '201', null, null, null);
INSERT INTO `wh_config` VALUES ('3', 'setIncEM.firstMoney', '30', null, null, null);
INSERT INTO `wh_config` VALUES ('4', 'setIncEM.firstIdcard', '100', null, null, null);
INSERT INTO `wh_config` VALUES ('5', 'setIncEM.jiuxin', '12', null, null, null);
INSERT INTO `wh_config` VALUES ('6', 'setIncEM.wechat', '100', null, null, null);
INSERT INTO `wh_config` VALUES ('7', 'setIncEM.invitationParent', '15', null, null, null);
INSERT INTO `wh_config` VALUES ('8', 'setIncEM.invitationParentConut9', '14', null, null, null);
INSERT INTO `wh_config` VALUES ('9', 'setIncEM.invitationConut9', '15', null, null, null);

-- ----------------------------
-- Table structure for `wh_debug`
-- ----------------------------
DROP TABLE IF EXISTS `wh_debug`;
CREATE TABLE `wh_debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `json` text,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_debug
-- ----------------------------
INSERT INTO `wh_debug` VALUES ('1', 'sdf', '1427099037', '1427099037');
INSERT INTO `wh_debug` VALUES ('2', '\"asdf\"', '1427099087', '1427099087');
INSERT INTO `wh_debug` VALUES ('3', '3', '1428923079', '1428923079');

-- ----------------------------
-- Table structure for `wh_invest`
-- ----------------------------
DROP TABLE IF EXISTS `wh_invest`;
CREATE TABLE `wh_invest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `introduce` text NOT NULL,
  `amount` int(11) NOT NULL,
  `buy_time_start` int(11) NOT NULL,
  `buy_time_end` int(11) NOT NULL,
  `each_max` int(11) NOT NULL,
  `each_min` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `invest_date` int(11) NOT NULL,
  `invest_status` smallint(6) DEFAULT '10',
  `type` smallint(6) NOT NULL,
  `imgs` text,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_invest
-- ----------------------------
INSERT INTO `wh_invest` VALUES ('1', '投资产品1', '投资产品1投资产品1投资产品1', '10000', '1428922757', '1429922757', '2000', '100', '10', '12', '10', '20', null, '1426144310', '1426144310');
INSERT INTO `wh_invest` VALUES ('2', '投资产品2', '投资产品2投资产品2投资产品2', '10000', '1428922757', '1429922757', '0', '100', '10', '12', '10', '20', null, '1426146971', '1426146971');
INSERT INTO `wh_invest` VALUES ('3', '投资产品3', '投资产品2投资产品2投资产品2', '10000', '1428922757', '1429922757', '2000', '100', '10', '12', '10', '10', null, '1426146993', '1428896254');

-- ----------------------------
-- Table structure for `wh_invest_list`
-- ----------------------------
DROP TABLE IF EXISTS `wh_invest_list`;
CREATE TABLE `wh_invest_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invest_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `investment_sum` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `interest` float DEFAULT '0',
  `interest_status` smallint(6) DEFAULT '10',
  `interest_time` varchar(11) DEFAULT NULL,
  `pay_time` int(11) DEFAULT NULL,
  `llinfo` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invest_id` (`invest_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `invest_id` FOREIGN KEY (`invest_id`) REFERENCES `wh_invest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `member_id` FOREIGN KEY (`member_id`) REFERENCES `wh_member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_invest_list
-- ----------------------------
INSERT INTO `wh_invest_list` VALUES ('6', '2', '50', '1222', '20', '122.2', '110', '1460596406', '1428974006', null, '1428974002', '1428974006');
INSERT INTO `wh_invest_list` VALUES ('7', '1', '50', '1233', '20', '123.3', '110', '1460596474', '1428974074', null, '1428974070', '1428974074');

-- ----------------------------
-- Table structure for `wh_invest_month`
-- ----------------------------
DROP TABLE IF EXISTS `wh_invest_month`;
CREATE TABLE `wh_invest_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invest_list_id` int(11) DEFAULT NULL,
  `m_step` float DEFAULT NULL,
  `m_status` int(11) DEFAULT NULL,
  `m_time` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invest_list_id` (`invest_list_id`),
  CONSTRAINT `invest_list_id` FOREIGN KEY (`invest_list_id`) REFERENCES `wh_invest_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_invest_month
-- ----------------------------
INSERT INTO `wh_invest_month` VALUES ('1', '6', '10.1833', '10', '1428974006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('2', '6', '10.1833', '10', '1431566006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('3', '6', '10.1833', '10', '1434158006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('4', '6', '10.1833', '10', '1436750006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('5', '6', '10.1833', '10', '1439342006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('6', '6', '10.1833', '10', '1441934006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('7', '6', '10.1833', '10', '1444526006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('8', '6', '10.1833', '10', '1447118006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('9', '6', '10.1833', '10', '1449710006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('10', '6', '10.1833', '10', '1452302006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('11', '6', '10.1833', '10', '1454894006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('12', '6', '10.1833', '10', '1457486006', '1428974006', '1428974006');
INSERT INTO `wh_invest_month` VALUES ('13', '7', '10.28', '10', '1428974074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('14', '7', '10.28', '10', '1431566074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('15', '7', '10.28', '10', '1434158074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('16', '7', '10.28', '10', '1436750074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('17', '7', '10.28', '10', '1439342074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('18', '7', '10.28', '10', '1441934074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('19', '7', '10.28', '10', '1444526074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('20', '7', '10.28', '10', '1447118074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('21', '7', '10.28', '10', '1449710074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('22', '7', '10.28', '10', '1452302074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('23', '7', '10.28', '10', '1454894074', '1428974074', '1428974074');
INSERT INTO `wh_invest_month` VALUES ('24', '7', '10.28', '10', '1457486074', '1428974074', '1428974074');

-- ----------------------------
-- Table structure for `wh_invest_post`
-- ----------------------------
DROP TABLE IF EXISTS `wh_invest_post`;
CREATE TABLE `wh_invest_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_invest_post
-- ----------------------------
INSERT INTO `wh_invest_post` VALUES ('37', 'active', '活动1活动1活动1活动1活动1', '<p>&#27963;&#21160;1&#27963;&#21160;1&#27963;&#21160;1&#27963;&#21160;1</p>', '10', '1428549675', '1428549675');

-- ----------------------------
-- Table structure for `wh_member`
-- ----------------------------
DROP TABLE IF EXISTS `wh_member`;
CREATE TABLE `wh_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idcard` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idcard_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idcard_status` smallint(6) DEFAULT '0',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_status` smallint(6) DEFAULT '0',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_status` smallint(6) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `invitation` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_member
-- ----------------------------
INSERT INTO `wh_member` VALUES ('49', '15003227199', 'kWs1G5zvG-xAkHZpP0d1w7xaNQKxQAJc', '$2y$13$nAcjS/.dkHtAA7MNomkhaO7YOauXjzdnEpyfCzHy31/IdR/AE8Y5m', null, null, null, '0', '15003227156', '10', '', '0', null, '03227156', '10', '1428549474', '1428549474');
INSERT INTO `wh_member` VALUES ('50', '15003227156', 'HdJ7m0fQnUG_qtJctI0YhJ1i-Pp5Ptlx', '$2y$13$AcUUKF8Pt9hMlDYSixjTgOt8kzcuOA4jfDeWK9tXMhVNH7l5152Uy', null, '130902199103153213', '周中原', '10', '15003227156', '10', '435690026@qq.com', '10', null, '03227156', '10', '1428922757', '1428922870');

-- ----------------------------
-- Table structure for `wh_member_other`
-- ----------------------------
DROP TABLE IF EXISTS `wh_member_other`;
CREATE TABLE `wh_member_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `row` varchar(255) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_member_other
-- ----------------------------
INSERT INTO `wh_member_other` VALUES ('11', '49', 'jiuxin', 'shiwolang99=|=67893', '1428549501', '1428549501');

-- ----------------------------
-- Table structure for `wh_migration`
-- ----------------------------
DROP TABLE IF EXISTS `wh_migration`;
CREATE TABLE `wh_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_migration
-- ----------------------------
INSERT INTO `wh_migration` VALUES ('m000000_000000_base', '1424997788');
INSERT INTO `wh_migration` VALUES ('m130524_201442_init', '1424997790');
INSERT INTO `wh_migration` VALUES ('m140506_102106_rbac_init', '1425084003');
INSERT INTO `wh_migration` VALUES ('m150227_003428_cms_post', '1424997790');
INSERT INTO `wh_migration` VALUES ('m150227_003809_cms_category', '1424997790');
INSERT INTO `wh_migration` VALUES ('m150302_075716_cms_menu', '1425287258');

-- ----------------------------
-- Table structure for `wh_user`
-- ----------------------------
DROP TABLE IF EXISTS `wh_user`;
CREATE TABLE `wh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wh_user
-- ----------------------------
INSERT INTO `wh_user` VALUES ('1', 'shiwolang', 'IFdiFlJnlXzgQwM8YdfNBA7rY9qrTyp6', '$2y$13$2Hf7860aWebORofdZc7JVuA9RgCnFdVCG6GRGXL3tGDkWamYVUS/i', null, 'shiwolang@live.com', '10', '1425111193', '1425111193');
INSERT INTO `wh_user` VALUES ('2', 'admindd', 'A1mIzqM-L-pOB20zhE-GhaaZM6jTCItc', '$2y$13$Czl92EybaH2souQKnpIDUumuVRf4nvFVHCd92GgxAn10AwsUUcAC2', null, 'admin@admin.com', '10', '1425612317', '1425612317');
INSERT INTO `wh_user` VALUES ('3', 'nihao1', 'FLJi9K10q2KZOPIMxo8X3Li5gtywpphA', '$2y$13$GxcS5MkNQzzkY/JTUd48pe/M2515da1D0YG5p4z/KxyphTKzgREMK', null, 'nihao@admin.com', '10', '1425612535', '1428456590');
INSERT INTO `wh_user` VALUES ('4', 'nihaoa', '8Iy4Ai3Ucgbx4dhg0fIJQp22Ea-0G4u_', '$2y$13$2.DlFOXaRtAkcniYdVhqU.OpjEBSD8w1F8qOr79UliDB1xFDMepgC', null, 'nihaoa@admin.com', '10', '1425612648', '1425612648');
INSERT INTO `wh_user` VALUES ('5', '123', '7eFEf3NolmFx13H4lB4XyRtmP3efpF1_', '$2y$13$.fMhs7eQLJxOl/vyjAyyWO.PxvgGrTghoIIyyouDASd1xKZGu18.q', null, '123', '10', '1428403960', '1428403960');

-- ----------------------------
-- Table structure for `wh_verification_code`
-- ----------------------------
DROP TABLE IF EXISTS `wh_verification_code`;
CREATE TABLE `wh_verification_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wh_verification_code
-- ----------------------------
INSERT INTO `wh_verification_code` VALUES ('179', '15003227156', '9133', '10', '10', '1428549459', '1428549474');
INSERT INTO `wh_verification_code` VALUES ('180', '15003227156', '9966', '10', '10', '1428922739', '1428922757');
INSERT INTO `wh_verification_code` VALUES ('181', '435690026@qq.com', '9357', '20', '10', '1428922846', '1428922870');
