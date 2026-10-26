SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `naif_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `naif_db`;

CREATE TABLE `block` (
  `block_id` int(11) NOT NULL,
  `block_title` text NOT NULL,
  `block_active` int(11) NOT NULL,
  `block_dir` int(11) NOT NULL,
  `block_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `block` (`block_id`, `block_title`, `block_active`, `block_dir`, `block_text`) VALUES(1, 'قائمة يمين', 1, 1, 'محتوى قائمة يمين');
INSERT INTO `block` (`block_id`, `block_title`, `block_active`, `block_dir`, `block_text`) VALUES(2, 'قائمة وسط', 1, 2, 'محتوى قائمة وسط');
INSERT INTO `block` (`block_id`, `block_title`, `block_active`, `block_dir`, `block_text`) VALUES(3, 'قائمة يسار', 1, 3, 'محتوى قائمة يسار');

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL,
  `language_title` text NOT NULL,
  `language_version` text NOT NULL,
  `language_dir` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `language` (`language_id`, `language_title`, `language_version`, `language_dir`) VALUES(1, 'اللغة الافتراضية', '1.0', 'rtl');

CREATE TABLE `phrase` (
  `phrase_id` int(11) NOT NULL,
  `phrase_name` text NOT NULL,
  `phrase_text` text NOT NULL,
  `phrase_version` text NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `phrase` (`phrase_id`, `phrase_name`, `phrase_text`, `phrase_version`, `language_id`) VALUES(1, 'home', 'الرئيسية', '1.0', 1);

CREATE TABLE `setting` (
  `site_name` text NOT NULL,
  `site_url` text NOT NULL,
  `site_desc` text NOT NULL,
  `site_key` text NOT NULL,
  `site_open` int(11) NOT NULL,
  `site_copy` text NOT NULL,
  `site_masseg_close` text NOT NULL,
  `site_style` int(11) NOT NULL,
  `site_language` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `setting` (`site_name`, `site_url`, `site_desc`, `site_key`, `site_open`, `site_copy`, `site_masseg_close`, `site_style`, `site_language`) VALUES('اسم الموقع', 'http://localhost/naif/', 'موقع تجريبي', 'كلمات,مفتاحية', 1, 'جميع الحقوق محفوظة © {YEAR}', 'الموقع مغلق للصيانة', 1, 1);

CREATE TABLE `style` (
  `style_id` int(11) NOT NULL,
  `style_title` text NOT NULL,
  `style_version` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `style` (`style_id`, `style_title`, `style_version`) VALUES(1, 'الاستايل الافتراضي', '1.0');

CREATE TABLE `template` (
  `template_id` int(11) NOT NULL,
  `template_title` text NOT NULL,
  `template_name` text NOT NULL,
  `template_code` text NOT NULL,
  `template_version` text NOT NULL,
  `style_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(1, 'قالب هيدر الموقع', 'header', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <title>{PAGE_TITLE}</title>\r\n  <meta name=\"description\" content=\"{SITE_DESC}\">\r\n  <meta name=\"keywords\" content=\"{SITE_KEY}\">\r\n<style>\r\n           {TEMPLATE_CSS}\r\n</style>\r\n</head>\r\n<body>\r\n<div id=\"content\">\r\n<header>\r\n<h1><a href=\"index.php\">{SITE_NAME}</a></h1>\r\n</header>\r\n        <div id=\"navbar\">\r\n            <ul>\r\n                <li><a href=\"page.php\">الصفحات الخاصة</a></li>\r\n            </ul>\r\n        </div>', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(2, 'قالب القوائم الجانبية', 'block', '<div id=\"panelHead\">{TITLE}</div>\r\n<div id=\"panelBody\">{TEXT}</div>\r\n', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(3, 'قالب الصفحة الرئيسية للموقع', 'home_page', '<table border=\"0\" width=\"100%\" dir=\"RTL\">\r\n       <tr>\r\n              <td width=\"20%\" valign=\"top\">{BLOCKS_RIGHT}</td>\r\n              <td width=\"60%\" valign=\"top\">{BLOCKS_CENTER}</td>\r\n              <td width=\"20%\" valign=\"top\">{BLOCKS_LEFT}</td>\r\n       </tr>\r\n</table>\r\n', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(4, 'قالب فوتر الموقع', 'footer', '<footer>{SITE_COPY}</footer>\r\n</body>\r\n</html>\r\n', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(5, 'قالب تصميم CSS', 'CSS', '*{\r\n    margin: 0px;\r\n    outline: 0px;\r\n    padding: 0px;\r\n}\r\nbody{\r\n	background: linear-gradient(to bottom,  #b4b9c4 2%,#d7e5da 56%,#f5fff5 100%);\r\n	background-attachment:fixed;\r\n    font-family: \'Tahoma\';\r\n    direction: RTL;\r\n}\r\n#content{\r\n    max-width: 90%;\r\n    margin: 0 auto;\r\n    background-color: #ffffff;\r\n    border: 1px solid #555;\r\n}\r\nheader{\r\n	background: linear-gradient(to bottom,  #f5fff5 0%,#d7e5da 44%,#b4b9c4 98%);\r\n    padding: 40px;\r\n}\r\nfooter{\r\n	background: linear-gradient(to bottom,  #f5fff5 0%,#d7e5da 44%,#b4b9c4 98%);\r\n    padding: 10px;\r\n    text-align: center;\r\n	font-size:12px;\r\n  margin-top:5px;\r\n}\r\n#panelHead{\r\n	background: linear-gradient(to bottom,  #b4b9c4 2%,#d7e5da 56%,#f5fff5 100%);\r\n    padding: 5px;\r\n    text-align: center;\r\n    color: #000;\r\n}\r\n#panelBody{\r\n	background: linear-gradient(to bottom,  #d7e5da 67%,#b4b9c4 98%);\r\n    padding: 7px;\r\n    border: 1px solid #555;\r\n}\r\na,a:hover{\r\n    text-decoration: none;\r\n    color:#000;\r\n}\r\n#navbar{\r\n	background: linear-gradient(to bottom,  #f5fff5 0%,#d7e5da 44%,#b4b9c4 98%);\r\n    height: 33px;\r\n}\r\n#navbar ul{\r\n    list-style: none;\r\n}\r\n#navbar ul li{\r\n    float: right;\r\n    padding: 8px;\r\n    margin-left: 3px;\r\n}\r\n#navbar ul li a{\r\n    color: #555;\r\n}\r\n#navbar ul li a:hover{\r\n    color: #ff0000;\r\n}', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(6, 'قالب رسالة اغلاق الموقع', 'masseg_close', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <title>{PAGE_TITLE}</title>\r\n  <meta name=\"description\" content=\"{SITE_DESC}\">\r\n  <meta name=\"keywords\" content=\"{SITE_KEY}\">\r\n<style>\r\n           {TEMPLATE_CSS}\r\n</style>\r\n</head>\r\n<body>\r\n<div id=\"content\">\r\n<header>\r\n<h1>{MASSEG_CLOSE}</h1>\r\n</header>', '1.0', 1);
INSERT INTO `template` (`template_id`, `template_title`, `template_name`, `template_code`, `template_version`, `style_id`) VALUES(7, 'قالب صفحة عرض الصفحات الخاصة', 'page', '<table border=\"0\" width=\"100%\" dir=\"RTL\">\r\n       <tr>\r\n              <td width=\"20%\" valign=\"top\">{BLOCKS_RIGHT}</td>\r\n              <td width=\"60%\" valign=\"top\">{PAGE_CONTENT}</td>\r\n              <td width=\"20%\" valign=\"top\">{BLOCKS_LEFT}</td>\r\n       </tr>\r\n</table>\r\n', '1.0', 1);


ALTER TABLE `block`
  ADD PRIMARY KEY (`block_id`);

ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`);

ALTER TABLE `phrase`
  ADD PRIMARY KEY (`phrase_id`);

ALTER TABLE `style`
  ADD PRIMARY KEY (`style_id`);

ALTER TABLE `template`
  ADD PRIMARY KEY (`template_id`);


ALTER TABLE `block`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `phrase`
  MODIFY `phrase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `style`
  MODIFY `style_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
