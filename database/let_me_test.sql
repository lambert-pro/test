/*
 Navicat Premium Data Transfer

 Source Server         : docker-database-root
 Source Server Type    : MySQL
 Source Server Version : 100613 (10.6.13-MariaDB-1:10.6.13+maria~ubu2004)
 Source Host           : database:6033
 Source Schema         : my_test

 Target Server Type    : MySQL
 Target Server Version : 100613 (10.6.13-MariaDB-1:10.6.13+maria~ubu2004)
 File Encoding         : 65001

 Date: 24/06/2023 03:50:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for let_me_test
-- ----------------------------
DROP TABLE IF EXISTS `let_me_test`;
CREATE TABLE `let_me_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- ----------------------------
-- Records of let_me_test
-- ----------------------------
BEGIN;
INSERT INTO `let_me_test` (`id`, `name`) VALUES (1, 'lambert');
INSERT INTO `let_me_test` (`id`, `name`) VALUES (2, 'cau');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
