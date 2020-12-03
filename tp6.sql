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

 Date: 03/12/2020 18:03:31
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
  `phone` char(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `pwd` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `pwd_salt` char(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码盐',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `header_pic` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号状态，1正常，2禁用，默认1',
  `rose_id` tinyint(4) NOT NULL COMMENT '账号角色id，对应角色表id',
  `extra_category_id` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '除角色含有的权限，额外的权限id',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注描叙',
  `created_at` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `created_id` int(10) NULL DEFAULT NULL COMMENT '创建此账号的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此账号的时间',
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此账号的管理员id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '后台管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_admin
-- ----------------------------

-- ----------------------------
-- Table structure for tp6_rose
-- ----------------------------
DROP TABLE IF EXISTS `tp6_rose`;
CREATE TABLE `tp6_rose`  (
  `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `rule` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色拥有的功能规则id，对应功能表中id集合字符串，多个功能以,拼接。超级管理员为*表示有全部功能',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注描叙',
  `created_at` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `created_id` int(10) NULL DEFAULT NULL COMMENT '创建此角色的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL COMMENT '最后一次修改时间',
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此角色的管理员Id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '后台角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_rose
-- ----------------------------
INSERT INTO `tp6_rose` VALUES (1, '超级管理员', 0, '*', NULL, 1606989490, NULL, NULL, NULL);

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
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '功能状态：1正常，2禁用，默认1',
  `created_at` int(10) NULL DEFAULT NULL,
  `created_id` int(10) NULL DEFAULT NULL COMMENT '添加此功能的管理员id',
  `updated_at` int(10) NULL DEFAULT NULL,
  `updated_id` int(10) NULL DEFAULT NULL COMMENT '最后一次修改此功能的管理id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台功能规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_rule
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
