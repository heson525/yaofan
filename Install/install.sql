DROP TABLE IF EXISTS `yyhy_config`;
CREATE TABLE `yyhy_config` (
 `k` varchar(255) NOT NULL,
 `v` text,
 PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `yyhy_config` (`k`, `v`) VALUES
('gg', '大哥哥大姐们啊！你们都是有钱的人呐～谁有那多余的零钱？给我这流浪的人啊...'),
('sitename', '烟雨要饭系统'),
('title', '24H全自动收款系统'),
('qq', '4049859'),
('panel', '一只要饭的小烟雨...'),
('copy', '烟雨寒云'),
('api', 'pay.com'),
('pid', '1000'),
('key', '123456'),
('username', 'admin'),
('password', '$2y$10$8ESLdGejJoWyI/wmXx6rj.3Oo07rQaDMYGpR.bPsDNHYTjzI7JgIS'),
('auth', '81a68b6a56391de87aa3f8d2df61641f'),
('keywords', '烟雨寒云,要饭网,24H自动要饭系统,烟雨要饭'),
('description', '烟雨寒云旗下24H全自动要饭系统'),
('music_sw', 'on');
DROP TABLE IF EXISTS `yyhy_order`;
CREATE TABLE `yyhy_order` (
 `trade_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 `msg` varchar(255) NOT NULL,
 `qq` varchar(255) NOT NULL,
 `money` decimal(10,2) NOT NULL,
 `type` varchar(255) NOT NULL,
 `ip` varchar(255) NOT NULL,
 `city` varchar(255) DEFAULT NULL,
 `nick` varchar(255) DEFAULT NULL,
 `addtime` varchar(255) NOT NULL,
 `endtime` varchar(255) DEFAULT NULL,
 `status` int(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;