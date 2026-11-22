<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','forum_db');

	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		$config = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		mysqli_set_charset($config, 'utf8');
		return $config;
	} catch (mysqli_sql_exception $e) {
		echo 'DATABASE CONNECT ERROR';
		exit();
	}