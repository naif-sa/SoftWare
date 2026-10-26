<?php
include_once"../includes/config.php";
include_once"../includes/class.language.php";
include_once"../includes/functions.php";
include_once"includes/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>لوحة التحكم</title>
<meta charset="UTF-8" />
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div id="home">
<table>
<tr>
<td><a href="index.php">لوحة التحكم</a></td>
<td><a target="_blank" href="../index.php">رئيسية الموقع</a></td>
</tr>
</table>
<table>
<tr>
<td><a href="index.php?action=setting">إعدادات الموقع</a></td>
<td><a href="block.php?action=setting">القوائم الجانبية</a></td>
<td><a href="template.php?action=setting">الاستايلات و القوالب</a></td>
<td><a href="language.php?action=setting">اللغات و العبارات</a></td>
</tr>
</table>