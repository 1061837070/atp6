/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : tp6

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 28/12/2020 18:53:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tp6_admin
-- ----------------------------
DROP TABLE IF EXISTS `tp6_admin`;
CREATE TABLE `tp6_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '管理员昵称，可用于登录',
  `true_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '管理员真实姓名',
  `phone` char(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '手机号',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `pwd_salt` char(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码盐',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `header_pic` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号状态，1正常，2禁用，默认1',
  `rose_id` tinyint(4) NOT NULL COMMENT '账号角色id，对应角色表id',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注描叙',
  `created_at` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `created_id` int(10) NULL DEFAULT NULL COMMENT '创建此账号的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此账号的时间',
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此账号的管理员id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '后台管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_admin
-- ----------------------------
INSERT INTO `tp6_admin` VALUES (1, 'admin', 'admin', '', 'df78f25a8179efcbeb30570f74b0972a', '6c6a99', NULL, NULL, 1, 1, NULL, 1607398893, 1, NULL, NULL);
INSERT INTO `tp6_admin` VALUES (2, 'cqz', NULL, '13295993332', 'e6516d56134b58c84b071c4211103764', '8ecb55', '', '/storage/admin_header_pic/20201224/a236e23b357e6881deea90a8fdf8fe86.jpg', 1, 2, '', 1608800541, 1, 1609151385, 2);
INSERT INTO `tp6_admin` VALUES (3, 'cs3', NULL, '13295993333', 'b4960296310c64e9bb0ac8f6624e45d3', '7ebf4a', '', '/storage/admin_header_pic/20201224/4ced3c580e7e26747dc8c50fa196041c.jpg', 1, 3, '', 1608801240, 1, NULL, NULL);
INSERT INTO `tp6_admin` VALUES (4, 'cs4', NULL, '13295993334', '52605174c9799a8623aa0abb6da44573', '726679', '', '/storage/admin_header_pic/20201224/2df49f3a34eb825b1ed00583a31ab282.jpg', 1, 5, '', 1608801262, 1, 1609149648, 2);
INSERT INTO `tp6_admin` VALUES (5, 'cs5', NULL, '13295993335', 'c982910a14db21a0643e5524efffbf9f', '8a23f5', '', '/storage/admin_header_pic/20201224/7837d865cef8d4fee98411ab516ff2cd.jpg', 1, 2, '', 1608801290, 1, 1609152002, 2);

-- ----------------------------
-- Table structure for tp6_rose
-- ----------------------------
DROP TABLE IF EXISTS `tp6_rose`;
CREATE TABLE `tp6_rose`  (
  `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `rule` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '角色拥有的功能规则id，对应功能表中id集合字符串，多个功能以,拼接。超级管理员为*表示有全部功能',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注描叙',
  `created_at` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `created_id` int(10) NULL DEFAULT NULL COMMENT '创建此角色的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL COMMENT '最后一次修改时间',
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此角色的管理员Id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '后台角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_rose
-- ----------------------------
INSERT INTO `tp6_rose` VALUES (1, '超级管理员', 0, '*', NULL, 1607394001, 1, NULL, NULL);
INSERT INTO `tp6_rose` VALUES (2, '管理员', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26', '', 1608710346, 1, 1609144566, 1);
INSERT INTO `tp6_rose` VALUES (3, '技术员', 2, '1,2,5,6,7,8,9,22,24,25,26', '技术员管理技术功能', 1608710641, 1, 1608866635, 2);
INSERT INTO `tp6_rose` VALUES (5, '客服', 3, '1,3,10,11,12,13', '', 1608803431, 1, 1608865983, 2);

-- ----------------------------
-- Table structure for tp6_rule
-- ----------------------------
DROP TABLE IF EXISTS `tp6_rule`;
CREATE TABLE `tp6_rule`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '功能分类的id',
  `pid` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id，为0为顶级分类',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '功能标题',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限URL',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '同一父级下的排序',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1：菜单，2：按钮',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '功能状态：1正常，2禁用，默认1',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '备注',
  `created_at` int(10) NOT NULL,
  `created_id` int(10) NOT NULL COMMENT '添加此功能的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL,
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此功能的管理id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台功能规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_rule
-- ----------------------------
INSERT INTO `tp6_rule` VALUES (1, 0, '系统设置', '/', 1, 1, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (2, 1, '功能菜单', '/back/rule/index', 1, 1, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (3, 1, '角色管理', '/back/rose/index', 2, 1, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (4, 1, '管理员管理', '/back/admin/index', 3, 1, 1, NULL, 1607396066, 1, 1608704187, 1);
INSERT INTO `tp6_rule` VALUES (5, 2, '添加功能', '/back/rule/add', 1, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (6, 2, '编辑功能', '/back/rule/edit', 2, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (7, 2, '禁用功能', '/back/rule/stop', 3, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (8, 2, '启用功能', '/back/rule/start', 4, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (9, 2, '删除功能', '/back/rule/del', 5, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (10, 3, '添加角色', '/back/rose/add', 1, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (11, 3, '编辑角色', '/back/rose/edit', 2, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (12, 3, '分配权限', '/back/rose/setRule', 3, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (13, 3, '删除角色', '/back/rose/del', 4, 2, 1, NULL, 1607396066, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (14, 4, '添加管理员', '/back/admin/add', 1, 2, 1, NULL, 1607396066, 1, 1608704192, 1);
INSERT INTO `tp6_rule` VALUES (15, 4, '编辑管理员', '/back/admin/edit', 2, 2, 1, NULL, 1607396066, 1, 1608704197, 1);
INSERT INTO `tp6_rule` VALUES (16, 4, '分配角色', '/back/admin/setRose', 3, 2, 1, NULL, 1607396066, 1, 1608704202, 1);
INSERT INTO `tp6_rule` VALUES (17, 4, '重置密码', '/back/admin/resetPwd', 4, 2, 1, NULL, 1607396066, 1, 1608704207, 1);
INSERT INTO `tp6_rule` VALUES (18, 4, '禁用', '/back/admin/stop', 5, 2, 1, NULL, 1607396066, 1, 1608704211, 1);
INSERT INTO `tp6_rule` VALUES (19, 4, '启用', '/back/admin/start', 6, 2, 1, NULL, 1607396066, 1, 1608704181, 1);
INSERT INTO `tp6_rule` VALUES (20, 4, '删除管理员', '/back/admin/del', 7, 2, 1, NULL, 1607396066, 1, 1608704176, 1);
INSERT INTO `tp6_rule` VALUES (21, 0, '网站设置', '/back/webset/index', 2, 1, 1, '', 1608545538, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (22, 0, '网站设置2', '/back/webset/inde', 3, 1, 1, '', 1608545631, 1, NULL, NULL);
INSERT INTO `tp6_rule` VALUES (24, 0, '订单管理', '/', 5, 1, 2, '', 1608615898, 1, 1608866027, 2);
INSERT INTO `tp6_rule` VALUES (25, 24, '订单列表', '/back/order/index', 1, 1, 2, '', 1608616029, 1, 1608866027, 2);
INSERT INTO `tp6_rule` VALUES (26, 25, '订单详情', '/back/order/detail', 1, 2, 2, '', 1608805258, 2, 1608866027, 2);

SET FOREIGN_KEY_CHECKS = 1;
