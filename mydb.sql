-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2020-01-10 10:05:40
-- 服务器版本： 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`uid`, `username`, `password`) VALUES
(1, 'admin', '1b99dd27ee85bc94957f3f809b3bf39f');

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `aid` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `ArticleTitle` varchar(240) NOT NULL COMMENT '文章标题',
  `category_id` int(11) NOT NULL COMMENT '所属栏目',
  `ArticleDescription` varchar(240) NOT NULL COMMENT '文章描述',
  `ArticleImage` varchar(120) DEFAULT NULL COMMENT '文章图片链接',
  `ArticleAuthor` varchar(20) NOT NULL COMMENT '文章作者',
  `ArticleSource` varchar(220) NOT NULL COMMENT '文章来源',
  `ArticleContent` longtext NOT NULL COMMENT '文章内容',
  `ArticleState` tinyint(4) NOT NULL COMMENT '文章状态{1：显示，0：隐藏}',
  `SEOTitle` varchar(120) NOT NULL COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) NOT NULL COMMENT 'SEO关键词',
  `SEODescription` varchar(180) NOT NULL COMMENT 'SEO描述',
  `ArticlePositionIndex` varchar(3) NOT NULL DEFAULT 'off' COMMENT '推荐栏位：首页 ',
  `ArticlePositionRecommend` varchar(3) NOT NULL DEFAULT 'off' COMMENT '推荐栏位：推荐',
  `ArticlePositionTop` varchar(3) NOT NULL DEFAULT 'off' COMMENT '推荐栏位：置顶',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '文章排序',
  `AddTime` int(11) NOT NULL COMMENT '文章添加时间',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `article`
--

INSERT INTO `article` (`aid`, `ArticleTitle`, `category_id`, `ArticleDescription`, `ArticleImage`, `ArticleAuthor`, `ArticleSource`, `ArticleContent`, `ArticleState`, `SEOTitle`, `SEOKeyword`, `SEODescription`, `ArticlePositionIndex`, `ArticlePositionRecommend`, `ArticlePositionTop`, `sort`, `AddTime`) VALUES
(22, '明天12月10号', 1, '内容', '', '', '', '', 1, '', '', '', '', '', '', 0, 1575861656);

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(25) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `model_id` int(11) NOT NULL COMMENT '模型id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `cname`, `sort`, `pid`, `model_id`) VALUES
(12, '文章管理', 0, 0, 17),
(13, '手机', 0, 0, 21),
(14, '每日事项', 0, 0, 23);

-- --------------------------------------------------------

--
-- 表的结构 `column_module`
--

DROP TABLE IF EXISTS `column_module`;
CREATE TABLE IF NOT EXISTS `column_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目模型id',
  `ModuleName` varchar(30) NOT NULL COMMENT '模型名称',
  `ModuleTableName` varchar(20) NOT NULL COMMENT '模型表名',
  `sort` smallint(11) NOT NULL DEFAULT '0',
  `ModuleRemark` varchar(260) NOT NULL COMMENT '模型备注',
  `ModuleState` tinyint(4) NOT NULL DEFAULT '1' COMMENT '模型状态 0隐藏 1显示',
  `icon` varchar(100) NOT NULL COMMENT '模型图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `column_module`
--

INSERT INTO `column_module` (`id`, `ModuleName`, `ModuleTableName`, `sort`, `ModuleRemark`, `ModuleState`, `icon`) VALUES
(21, '产品模块', 'diy_product', 1, '产品数据表', 1, '\\uploads\\files\\20191227\\49c4697c1777c0b4af521358961895e5.png'),
(17, '文章模型', 'diy_article', 0, '文章数据表', 1, '\\uploads\\files\\20191227\\8bbe2ce083eba20e12a0606d345e5d87.png'),
(22, '图片模块', 'diy_picture', 2, '图片模块数据表', 1, '\\uploads\\files\\20191227\\bbef1248300b1184c6f01bb947cfd64b.png'),
(23, '待办事项', 'todo_list', 3, '待办事项表', 1, ''),
(24, '待办事项分类', 'todo_list_category', 4, '待办事项分类表', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `config_name` varchar(64) DEFAULT NULL COMMENT '配置名称',
  `config_value` longtext COMMENT '配置值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `config`
--

INSERT INTO `config` (`id`, `config_name`, `config_value`) VALUES
(1, 'website_config', '{\"WebsiteName\":\"\\u8fd9\\u91cc\\u8bf7\\u586b\\u5199\\u60a8\\u7684\\u7f51\\u7ad9\\u540d\\u79f0\",\"WebsiteKeyword\":\"\\u667a\\u80fd\\u706f\\uff0cLED\\u706f\",\"WebsiteDesc\":\"\\u8fd9\\u662f\\u4e00\\u6bb5\\u63cf\\u8ff0\\u5185\\u5bb9\",\"WebsiteCopy\":\"\\u7248\\u6743\\u4fe1\\u606f\",\"WebsiteICP\":\"ICP\\u5907\\u6848\",\"WebsiteAddress\":\"\\u5730\\u5740\",\"WebsiteEmail\":\"1819540291@qq.com\",\"WebsitePhone\":\"15768429013\",\"WebsiteContent\":\"\\u7b2c\\u4e00\\u4e2a\\u94fe\\u63a5\\uff0c<a target=\\\"_self\\\" href=\\\"http:\\/\\/www.baidu.com\\\">\\u7b2c\\u4e00\\u4e2a\\u6211<\\/a>\",\"WebsiteLogo\":\"\\\\uploads\\\\files\\\\20191107\\\\17efa2bda4faa1e5e7534b529643600e.jpg\",\"WebsiteFavicon\":\"\\\\uploads\\\\files\\\\20191107\\\\14875187736dec5a68411ef418572c4b.jpg\",\"newFavicon\":\"1\"}'),
(2, 'upload_config', '{\"UploadImagesMax\":\"1633\",\"UploadImageSuffix\":\"jpg,png,jpeg,bmp,gif,webp\",\"UploadVideoMax\":\"0\",\"UploadVideoSuffix\":\"\",\"UploadAudioMax\":\"0\",\"UploadAudioSuffix\":\"\",\"UploadOtherMax\":\"0\",\"UploadOtherSuffix\":\"\"}'),
(3, 'thumbnail_config', '{\"ThumbnailType\":\"0\",\"ThumbnailProductWidth\":\"400\",\"ThumbnailProductHeight\":\"400\",\"ThumbnailArticleWidth\":\"300\",\"ThumbnailArticleHeight\":\"300\",\"ThumbnailImagesWidth\":\"400\",\"ThumbnailImagesHeight\":\"400\"}'),
(4, 'watermark_config', '{\"WatermarkType\":\"0\",\"WatermarkPosition\":\"2\",\"WatermarkContent\":\"\\u6c34\\u5370\\u6587\\u5b57\",\"WatermarkFontFamily\":\"\",\"WatermarkFontSize\":\"\",\"WatermarkAngle\":\"\",\"watermarkColor\":\"\",\"WatermarkTransparency\":\"50\",\"WatermarkImage\":\"\\\\uploads\\\\watermark\\\\20191108\\\\8c20e535a286b6f239a54a068326a9e0.jpg\"}'),
(5, 'system_config', '{\"Editor\":\"UEditor\",\"WebsiteCopy\":\"\\u7248\\u6743\\u4fe1\\u606f\"}'),
(6, 'email_config', '{\"SenderEmailAccount\":\"1819540291@qq.com\",\"SenderEmailPassword\":\"rdyjcluwpiuacgjb\",\"SMTPServerAddress\":\"smtp.qq.com\",\"ServerPort\":\"25\"}');

-- --------------------------------------------------------

--
-- 表的结构 `diy_article`
--

DROP TABLE IF EXISTS `diy_article`;
CREATE TABLE IF NOT EXISTS `diy_article` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SEOTitle` varchar(120) DEFAULT '' COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) DEFAULT '' COMMENT 'SEO关键词',
  `SEODescription` varchar(180) DEFAULT '' COMMENT 'SEO描述',
  `cid` int(11) DEFAULT NULL,
  `sumary` varchar(160) DEFAULT NULL,
  `PicturePath` varchar(80) DEFAULT NULL,
  `isshow` tinyint(4) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `author` varchar(20) DEFAULT NULL,
  `source` varchar(120) DEFAULT NULL,
  `content` text COMMENT '文章内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `diy_article`
--

INSERT INTO `diy_article` (`id`, `SEOTitle`, `SEOKeyword`, `SEODescription`, `cid`, `sumary`, `PicturePath`, `isshow`, `title`, `author`, `source`, `content`) VALUES
(4, '', '', '', 12, '434', '\\uploads\\article\\20191230\\b28082972ef7a959f8c38f4a969eac90.jpg', 1, '标题4564', '我是作者', '来源', '<p>这是很多的呢绒</p><p>这是一张图片明天</p><p><br/></p><p>的flag</p><p><img src=\"/ueditor/php/upload/image/20191230/1577711105460224.jpg\" title=\"1577711105460224.jpg\" alt=\"New-Picture-1.jpg\"/></p>');

-- --------------------------------------------------------

--
-- 表的结构 `diy_picture`
--

DROP TABLE IF EXISTS `diy_picture`;
CREATE TABLE IF NOT EXISTS `diy_picture` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SEOTitle` varchar(120) DEFAULT '' COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) DEFAULT '' COMMENT 'SEO关键词',
  `SEODescription` varchar(180) DEFAULT '' COMMENT 'SEO描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `diy_product`
--

DROP TABLE IF EXISTS `diy_product`;
CREATE TABLE IF NOT EXISTS `diy_product` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SEOTitle` varchar(120) DEFAULT '' COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) DEFAULT '' COMMENT 'SEO关键词',
  `SEODescription` varchar(180) DEFAULT '' COMMENT 'SEO描述',
  `name` varchar(100) DEFAULT NULL COMMENT '产品名称',
  `slogan` varchar(120) DEFAULT NULL COMMENT '产品标语',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `excel`
--

DROP TABLE IF EXISTS `excel`;
CREATE TABLE IF NOT EXISTS `excel` (
  `b` varchar(3) NOT NULL,
  `c` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `excel`
--

INSERT INTO `excel` (`b`, `c`) VALUES
('1', '2'),
('4', '5'),
('7', '8'),
('1', '2'),
('4', '5'),
('7', '8');

-- --------------------------------------------------------

--
-- 表的结构 `field_management`
--

DROP TABLE IF EXISTS `field_management`;
CREATE TABLE IF NOT EXISTS `field_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段id',
  `FieldTitle` varchar(20) NOT NULL COMMENT '用于表单上的标题',
  `FieldName` varchar(60) NOT NULL COMMENT '字段名称',
  `FieldType` varchar(15) NOT NULL COMMENT '字段类型',
  `FieldLength` varchar(4) DEFAULT NULL COMMENT '字段长度',
  `DefaultValue` varchar(15) NOT NULL COMMENT '字段默认值',
  `FieldComment` varchar(100) DEFAULT NULL COMMENT '字段注释内容',
  `FormType` char(2) NOT NULL COMMENT '字段类型 1输入框 2文本域 3编辑器 4下拉框 5复选框 6单选框 7开关 8密码框 9日期选择框 10文件上传 11图片上传',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `FormConfig` varchar(300) DEFAULT NULL COMMENT '表单项配置',
  `model_id` int(11) DEFAULT NULL COMMENT '模型id',
  `ShowInTableList` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示在数据列表',
  `required` tinyint(4) DEFAULT '0' COMMENT '是否必选 0可选 1必选',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `field_management`
--

INSERT INTO `field_management` (`id`, `FieldTitle`, `FieldName`, `FieldType`, `FieldLength`, `DefaultValue`, `FieldComment`, `FormType`, `sort`, `FormConfig`, `model_id`, `ShowInTableList`, `required`) VALUES
(15, '文章分类', 'cid', 'INT', '', '', '', '4', 1, '{\"tip\":\"\",\"title\":\"文章分类\",\"class\":\"\",\"id\":\"category\",\"api\":\"http://www.cnweb-local.com/cms/Article.html?aid=22\",\"select\":[{\"value\":\"\",\"text\":\"\"}]}', 17, 0, 1),
(16, '文章简介', 'sumary', 'VARCHAR', '160', '', '', '2', 3, '{\"tip\":\"\",\"title\":\"文章简介\",\"class\":\"\",\"id\":\"\"}', 17, 1, 0),
(17, '封面图片', 'PicturePath', 'VARCHAR', '80', '', '', '11', 4, '{\"tip\":\"\",\"title\":\"封面图片\",\"class\":\"\",\"id\":\"UploadArticleImage\",\"api\":\"https://www.jianshu.com/p/8ffdf9e400ea\"}', 17, 0, 0),
(18, '前台显示', 'isshow', 'TINYINT', '', '', '', '6', 8, '{\"tip\":\"你是\",\"title\":\"前台显示\",\"class\":\"wodeclass\",\"id\":\"woideid\",\"radio\":[{\"checked\":true,\"text\":\"是\",\"value\":\"1\"},{\"checked\":false,\"text\":\"否\",\"value\":\"0\"}]}', 17, 1, 0),
(20, '文章标题', 'title', 'VARCHAR', '100', '', '', '1', 0, '{\"tip\":\"\",\"title\":\"文章标题\",\"class\":\"\",\"id\":\"\"}', 17, 1, 0),
(21, '文章作者', 'author', 'VARCHAR', '20', '', '', '1', 5, '{\"tip\":\"\",\"title\":\"文章作者\",\"class\":\"\",\"id\":\"\"}', 17, 0, 0),
(22, '文章来源', 'source', 'VARCHAR', '120', '', '', '1', 6, '{\"tip\":\"\",\"title\":\"文章来源\",\"class\":\"\",\"id\":\"\"}', 17, 0, 0),
(26, '文章内容', 'content', 'TEXT', '', '', '文章内容', '3', 7, '{\"tip\":\"\",\"title\":\"文章内容\",\"class\":\"\",\"id\":\"\"}', 17, 1, 0),
(24, '标题/名称', 'name', 'VARCHAR', '100', '', '产品名称', '1', 1, '{\"tip\":\"\",\"title\":\"标题/名称\",\"class\":\"\",\"id\":\"\"}', 21, 1, 0),
(25, '产品标语', 'slogan', 'VARCHAR', '120', '', '产品标语', '1', 2, '{\"tip\":\"\",\"title\":\"产品标语\",\"class\":\"\",\"id\":\"\"}', 21, 1, 0),
(27, '标题', 'title', 'VARCHAR', '100', '', '待办事项标题', '1', 1, '{\"tip\":\"\",\"title\":\"标题\",\"class\":\"\",\"id\":\"\"}', 23, 1, 1),
(28, '重要性', 'importance', 'VARCHAR', '2', '', '1：重要紧急 2：重要不紧急 3：不重要紧急 4：不重要不紧急', '4', 2, '{\"tip\":\"\",\"title\":\"重要性\",\"class\":\"\",\"id\":\"\",\"api\":\"https://github.com/airbnb/javascript#objects\",\"select\":[{\"value\":\"1\",\"text\":\"重要紧急\"},{\"value\":\"2\",\"text\":\"重要不紧急\"},{\"value\":\"3\",\"text\":\"不重要紧急\"},{\"value\":\"4\",\"text\":\"不重要不紧急\"}]}', 23, 1, 0),
(29, '详细内容', 'content', 'TEXT', '', '', '待办事项详情', '3', 3, '{\"tip\":\"\",\"title\":\"详细内容\",\"class\":\"\",\"id\":\"\"}', 23, 1, 0),
(30, '是否完成', 'finished', 'TINYINT', '', '0', '1：完成 0：未完成', '6', 4, '{\"tip\":\"\",\"title\":\"是否完成\",\"class\":\"\",\"id\":\"\",\"radio\":[{\"checked\":true,\"text\":\"否\",\"value\":\"0\"},{\"checked\":false,\"text\":\"是\",\"value\":\"1\"}]}', 23, 1, 0),
(31, '所属分类', 'todo_list_category_id', 'INT', '', '', '所属分类id', '4', 5, '{\"tip\":\"\",\"title\":\"所属分类\",\"class\":\"\",\"id\":\"\",\"api\":\"https://github.com/airbnb/javascript#objects\",\"select\":[{\"value\":\"\",\"text\":\"\"}]}', 23, 1, 1),
(32, '分类名称', 'title', 'VARCHAR', '100', '', '待办事项分类', '1', 0, '{\"tip\":\"\",\"title\":\"分类名称\",\"class\":\"\",\"id\":\"\"}', 24, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `friend_link`
--

DROP TABLE IF EXISTS `friend_link`;
CREATE TABLE IF NOT EXISTS `friend_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FriendLinkTitle` varchar(64) NOT NULL COMMENT '友链标题',
  `FriendType` tinyint(4) NOT NULL COMMENT '友链类型 0:文字类型 1:图片类型',
  `URL` varchar(180) NOT NULL COMMENT '友链链接',
  `FriendLinkIcon` varchar(100) DEFAULT NULL COMMENT '友链图标',
  `FriendNofollow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '友链nofollow',
  `FriendSort` int(11) NOT NULL DEFAULT '0' COMMENT '友链排序',
  `FriendState` tinyint(4) NOT NULL COMMENT '友链状态 0:隐藏 1:显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `friend_link`
--

INSERT INTO `friend_link` (`id`, `FriendLinkTitle`, `FriendType`, `URL`, `FriendLinkIcon`, `FriendNofollow`, `FriendSort`, `FriendState`) VALUES
(4, 'LED', 1, 'http://www.blog.com/cms/', '\\uploads\\FriendLink\\20191108\\d47bf8113f652efd9bf8269d622396bd.png', 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `imgid` int(11) NOT NULL AUTO_INCREMENT,
  `belongId` int(11) DEFAULT NULL,
  `type` tinyint(2) NOT NULL,
  `article_aid` int(11) DEFAULT NULL,
  `url` varchar(250) NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`imgid`),
  KEY `fk_images_article1_idx` (`article_aid`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `images`
--

INSERT INTO `images` (`imgid`, `belongId`, `type`, `article_aid`, `url`, `name`) VALUES
(79, NULL, 1, NULL, '\\uploads\\20190927\\3b256dfa3d97a165edf382ffbd1f30f9.jpg', 'correspondence.jpg'),
(78, 17, 1, NULL, '\\uploads\\20190927\\16fa60b687ebea60cc20ed5d030e7eec.jpg', 'energetic.jpg'),
(47, 8, 1, NULL, '\\uploads\\20190926\\cbab592f116784d49f2440f346563274.jpg', 'alcohol.jpg'),
(48, 8, 1, NULL, '\\uploads\\20190926\\db35bc7b4d011aeaf7debf3e77cf2771.jpg', 'advice.jpg'),
(49, 8, 1, NULL, '\\uploads\\20190926\\0b6336b91ae7badafe373c183034b9a9.jpg', 'advertise.jpg'),
(50, 8, 1, NULL, '\\uploads\\20190926\\6eecabaa427d123ac0bb4c33e979be23.jpg', 'allowance.jpg'),
(76, 17, 1, NULL, '\\uploads\\20190927\\08ca6eef45ec36024b8a3d04844794b9.jpg', 'suppose.jpg'),
(77, 17, 1, NULL, '\\uploads\\20190927\\16fa60b687ebea60cc20ed5d030e7eec.jpg', 'exhibition.jpg'),
(75, 17, 1, NULL, '\\uploads\\20190927\\753d9e21465d18956b96f6cc31f1ce12.jpg', 'superb.jpg'),
(74, 15, 1, NULL, '\\uploads\\20190927\\799b1f609608da529090f0711ca81b85.jpg', 'explode.jpg'),
(73, 16, 1, NULL, '\\uploads\\20190927\\d5e64622f4943a19d37944d12c39ca0b.jpg', 'bulk.jpg'),
(72, 16, 1, NULL, '\\uploads\\20190927\\eab21063fdf1bde78a3bef66a2831805.jpg', 'branch.jpg'),
(71, 16, 1, NULL, '\\uploads\\20190927\\addf47431012b128760e6f45dd852d8e.jpg', 'channel.jpg'),
(80, NULL, 1, NULL, '\\uploads\\20190927\\4bf58cb42b6f8a6c786f976c1c0bf207.jpg', 'considerate.jpg'),
(81, 16, 1, NULL, '\\uploads\\20190927\\b428577a0cb6a6983020e2a89ff01fad.jpg', 'considerate.jpg'),
(82, 16, 1, NULL, '\\uploads\\20190927\\18564488dc4883c887905a180d4d1267.jpg', 'correspondence.jpg'),
(83, NULL, 1, NULL, '\\uploads\\20191022\\44a761fb46fc6ceb179e7032fc57f5f9.jpg', 'advertise.jpg'),
(84, NULL, 1, NULL, '\\uploads\\20191028\\4381571851517f851ac0f1c0ac463f7f.jpg', 'courtesy.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `search`
--

DROP TABLE IF EXISTS `search`;
CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL COMMENT '检索分类',
  `sort` tinyint(4) NOT NULL COMMENT '排序',
  `search_category_id` int(11) NOT NULL COMMENT '所属分类',
  `type_id` int(11) NOT NULL COMMENT '所属类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用于产品搜索的检索条件';

-- --------------------------------------------------------

--
-- 表的结构 `search_category`
--

DROP TABLE IF EXISTS `search_category`;
CREATE TABLE IF NOT EXISTS `search_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL COMMENT '检索分类名称',
  `type` tinyint(4) NOT NULL COMMENT '检索表单类型 1：单选框 2：复选框',
  `search` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否支持筛选 1：支持 0：不支持',
  `sort` tinyint(4) NOT NULL COMMENT '排序',
  `type_id` int(11) NOT NULL COMMENT '类型id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用于产品搜索的分类';

-- --------------------------------------------------------

--
-- 表的结构 `sys_column`
--

DROP TABLE IF EXISTS `sys_column`;
CREATE TABLE IF NOT EXISTS `sys_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SystemColumnName` varchar(60) NOT NULL COMMENT '系统栏目名称',
  `SystemColumnURL` varchar(100) NOT NULL COMMENT '系统栏目URL',
  `SystemState` tinyint(4) NOT NULL COMMENT '系统栏目状态 0：隐藏  1：显示',
  `SystemColumnIcon` varchar(60) DEFAULT NULL COMMENT '系统栏目图标',
  `SystemColumnSort` int(11) NOT NULL DEFAULT '0' COMMENT '系统栏目排序',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目分组id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sys_column`
--

INSERT INTO `sys_column` (`id`, `SystemColumnName`, `SystemColumnURL`, `SystemState`, `SystemColumnIcon`, `SystemColumnSort`, `group_id`, `pid`) VALUES
(4, '网站配置', '', 1, '', 1, 0, 0),
(5, '基本配置', '/WebsiteInfo.html', 1, '', 1, 0, 4),
(7, '上传设置', '/UploadSetting.html', 1, '', 2, 0, 4),
(8, '友情链接', '/FriendLink.html', 1, '', 3, 0, 4),
(9, '网站管理', '', 1, '', 2, 0, 0),
(10, '内容管理', '/ContentManagement.html', 1, '', 1, 0, 9),
(11, '栏目管理', '/Column.html', 1, '', 2, 0, 9),
(12, '栏目模块', '/ColumnModule.html', 1, '', 4, 0, 9),
(13, '系统管理', '', 1, '', 3, 0, 0),
(14, '系统配置', '/SystemInfo.html', 1, '', 1, 0, 13),
(15, '邮箱配置', '/EmailSetting.html', 1, '', 2, 0, 13),
(16, '系统栏目', '/SystemColumn.html', 1, '', 3, 0, 13),
(17, '数据备份', '/DataBackup.html', 0, '', 4, 0, 13),
(19, '待办事项', '/TodoList.html', 1, '', 4, 0, 0),
(20, '栏目类型', '/AddColumnType.html', 1, '', 4, 0, 9);

-- --------------------------------------------------------

--
-- 表的结构 `todo_list`
--

DROP TABLE IF EXISTS `todo_list`;
CREATE TABLE IF NOT EXISTS `todo_list` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SEOTitle` varchar(120) DEFAULT '' COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) DEFAULT '' COMMENT 'SEO关键词',
  `SEODescription` varchar(180) DEFAULT '' COMMENT 'SEO描述',
  `title` varchar(100) DEFAULT NULL COMMENT '待办事项标题',
  `importance` varchar(2) DEFAULT NULL COMMENT '1：重要紧急 2：重要不紧急 3：不重要紧急 4：不重要不紧急',
  `content` text COMMENT '待办事项详情',
  `finished` tinyint(4) DEFAULT '0' COMMENT '1：完成 0：未完成',
  `todo_list_category_id` int(11) DEFAULT NULL COMMENT '所属分类id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `todo_list`
--

INSERT INTO `todo_list` (`id`, `SEOTitle`, `SEOKeyword`, `SEODescription`, `title`, `importance`, `content`, `finished`, `todo_list_category_id`) VALUES
(5, '', '', '', 'v2ray的VPN搭建', '2', '<p>我一想到之前的大量被封，都是说ssr的，所以从那个时候就一直觉得这个还是不够好，不管是从原理来说还是加密方法来说。然后这个v2ray是基于https伪装加密的，所以运营商的后台就不会这么轻易检测了</p><p><br></p><p>你也可以去官网看看的，www.v2ray.com看看，上面又非常详细的说法以及跟ssr的区别</p><p><br></p><p>v2ray可去除Youtube广告</p><p><br></p><p>https://www.youtube.com/watch?v=-GH7DOlqe-M</p><p>v2ray相关配置视频（可以解封服务器）</p>', 0, 4),
(3, '', '', '', 'fdsgfds', '1', '<p>gdsfgds</p><p>gfdsgfdgfd</p><p>ghfredgfdg</p>', NULL, NULL),
(4, '', '', '', '543543', '1', '54365465', 0, NULL),
(6, '', '', '', '学习步骤', '2', '<p>留意主流技术，以大牛为中心出发（博客，推文等），注意新老技术的差异</p><p><br></p><p>最初一般考虑快速实现</p><p><br></p><p>第一阶段：了解----学习（存档学习记录）</p><p>第二阶段：实现----实操（可产出着重于实现过程记录的文章）&nbsp; 中小项目</p><p>第三阶段：拓展----应用和项目经验积累（在不同项目，场景能够较自如使用相关技术解决；可满足当前项目的需求；可控；能产出方案；可产出相关文章；新老技术的差异） 中型项目为主</p><p>第四阶段：原理----进一步学习整体架构（可修改，可自己实现部分功能；知其然，也知其所以然；可控；更深层次了解各方案优缺点；以文为主，以图【脑图等】为辅，产出优质深度文章）</p><p>第五阶段：轮子----造轮子&nbsp;</p>', 0, 5),
(7, '', '', '', '查找linux硬盘占用多的目录', '2', '<p>https://www.cnblogs.com/llxpbbs/articles/11088922.html</p>', 0, 4),
(8, '', '', '', '推荐两部电影', '4', '<p>对了，推荐两部美国电影。之前我看海龟救援的时候想如果有一种技术可以清除所以海洋的垃圾就好了。然后就找到《特种部队之眼镜蛇崛起》，《超验骇客》。这两部都讲的纳米技术的使用，所以如果有这种技术那么去除所有垃圾就不成问题了……</p>', 0, 4),
(9, '', '', '', '整理资料、文件等', '1', '', 0, 6),
(10, '', '', '', 'Linux上传下载', '2', '<p>yum -y install&nbsp;lrzsz</p><p>rz 上传</p><p>sz 下载</p>', 0, 4),
(11, '', '', '', '资料链接', '1', '<p><span>一个想帮你总结所有类型的上传漏洞的靶场：</span>https://github.com/c0ny1/upload-labs</p><p>php开源商场：https://github.com/gongfuxiang/shopxo</p><p>php安全：https://github.com/TideSec/WDScanner</p><p>前端好文：http://www.pingan8787.com/archives/&nbsp; &nbsp;https://imweb.io/</p><p>性能优化：https://www.cnblogs.com/pingan8787/p/11838028.html</p><p>阿里社区好文：https://developer.aliyun.com/article/739170?spm=a1z389.11499242.0.0.65452413dnZ7Al&amp;utm_content=g_1000096277</p><p>图片加解密：https://blog.csdn.net/xfgryujk/article/details/81058247</p><p>https://segmentfault.com/a/1190000002393107&nbsp; curl</p><p>https://www.jianshu.com/p/8cdbe4f4b533&nbsp; &nbsp;node linux安装</p><p>CGI和FastCGI https://www.cnblogs.com/tssc/p/10255590.html</p><p>https://www.cnblogs.com/xzwblog/p/7255364.html 负载均衡，分布式，集群</p><p>https://www.cnblogs.com/clsn/p/8150036.html&nbsp; mysql主从复制</p><p>非常好的文章：https://www.cnblogs.com/PeunZhang/p/3407453.html</p><p>对node工程进行压力测试和性能分析：https://juejin.im/post/5b827cbbe51d4538c021f2da</p><p>node应用排查手册：https://github.com/aliyun-node/Node.js-Troubleshooting-Guide</p><p>mqtt通讯流程：https://www.cnblogs.com/xiaohanlin/p/8683503.html</p><p><span>nodejs深入学习系列之libuv基础篇(一)：</span>https://zhuanlan.zhihu.com/p/86242398</p><p>tcp、http、socket：https://segmentfault.com/a/1190000014044351?utm_source=tag-newest</p><p><span>Node 案发现场揭秘 —— Coredump 还原线上异常：</span>https://zhuanlan.zhihu.com/p/41178823?spm=a2c4g.11186623.2.22.42a06df2LZjDEW</p><p><span>NodeJS的代码调试和性能调优：</span>https://yq.aliyun.com/articles/379053</p><p>node应用内存泄露分析方法论：https://help.aliyun.com/document_detail/64011.html</p><p>alinode新手：https://segmentfault.com/a/1190000016883737</p><p>node密集型CPU解决方案：https://www.cnblogs.com/peiyu1988/p/6700737.html</p><p>node cpu密集型任务：https://blog.csdn.net/shmnh/article/details/31972071</p><p>cpu过载内存溢出：https://blog.csdn.net/chenguohong88/article/details/79827763</p><p>php开源程序：https://juejin.im/post/5d0f3793518825324e5d7d64</p><p>免费的国外在线客服系统：https://dashboard.tawk.to/login</p><p>好文：https://blog.jimmylv.info/2017-06-30-serverless-in-action-build-personal-reading-statistics-system/</p><p>看看php：https://lvwenhan.com/sort/php</p><p><br></p>', 0, 5),
(12, '', '', '', '英语安排', '1', '<p>1、精听练习</p><p>2、预习</p>', 0, 6),
(13, '', '', '', 'QQ邮箱授权码', '1', '<p><span>账号：</span>1819540291@qq.com</p><p>服务器：smtp.qq.com</p><p><span>密码：rdyj</span><span>cluw</span><span>piua</span><span>cgjb</span></p>', 0, 5),
(14, '', '', '', 'PHP发送邮件', '2', '<p>nette/mail ： http://packagist.p2hp.com/packages/nette/mail</p><p>PHPEmail：https://github.com/PHPMailer/PHPMailer/&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;https://www.cnblogs.com/woider/p/6980456.html</p><p>第一个没有被列入垃圾邮箱，支持html，附件</p><p>1、tls和ssl&nbsp;&nbsp;https://www.php.net/manual/zh/function.stream-context-create.php</p><p>2、发件人，收件人的显示</p><p><br></p>', 0, 5);

-- --------------------------------------------------------

--
-- 表的结构 `todo_list_category`
--

DROP TABLE IF EXISTS `todo_list_category`;
CREATE TABLE IF NOT EXISTS `todo_list_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SEOTitle` varchar(120) DEFAULT '' COMMENT 'SEO标题',
  `SEOKeyword` varchar(160) DEFAULT '' COMMENT 'SEO关键词',
  `SEODescription` varchar(180) DEFAULT '' COMMENT 'SEO描述',
  `title` varchar(100) DEFAULT NULL COMMENT '待办事项分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `todo_list_category`
--

INSERT INTO `todo_list_category` (`id`, `SEOTitle`, `SEOKeyword`, `SEODescription`, `title`) VALUES
(4, '', '', '', '福全推荐'),
(5, '', '', '', '待整理'),
(6, '', '', '', '任务');

-- --------------------------------------------------------

--
-- 表的结构 `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) DEFAULT NULL COMMENT '类型名称',
  `availability` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否禁用 1：启用 0：禁用',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `parameter` text COMMENT '产品详细参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用于前端搜索和内容补充的类型表';

--
-- 转存表中的数据 `type`
--

INSERT INTO `type` (`id`, `name`, `availability`, `sort`, `parameter`) VALUES
(3, '5435436', 1, 3, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
