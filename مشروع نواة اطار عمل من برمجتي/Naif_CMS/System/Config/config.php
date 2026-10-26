<?php

/* App Setting */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'naif_cms');
define('APP_URL', 'http://localhost/Naif_CMS/');
define('APP_LANGUAGE', 'Arabic');

/* Directory Setting */
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', realpath(dirname(__FILE__)) . DS . '..' . DS . '..' . DS);
define('SYSTEM_PATH', APP_PATH . 'System' . DS);
define('LIBRARY_PATH', SYSTEM_PATH . 'Library' . DS);
define('CONFIG_PATH', SYSTEM_PATH . 'Config' . DS);
define('UPLOAD_PATH', APP_PATH . 'Uploads' . DS);
define('SESSION_SAVE_PATH', APP_PATH . 'Sessions' . DS);
define('VIEW_PATH', 'View' . DS);
define('TEMPLATE_PATH', 'Template' . DS);
define('LANGUAGE_PATH', 'Language' . DS);
define('PUBLIC_PATH', APP_URL . 'Public' . '/');
define('ASSET_PATH', PUBLIC_PATH . 'Asset' . '/');

/* Time Setting */
define('TIME_ZONE', 'Asia/Riyadh');
define('DATE_FORMAT', 'Y-m-d');
define('TIME_FORMAT', 'h:i');

/* Encryption Setting */
define('ENCRYPTION_KEY', '');
define('ENCRYPTION_ALGORITHM', 'AES-256-CBC');

/* File Upload Setting */
define('ALLOWED_FILE_EXTENSION', ['bmp', 'jpg', 'jpeg', 'gif', 'png']);
define('ALLOWED_FILE_SIZE', 1024 * 1024 * 5);

/* Session Setting */
define('SESSION_NAME', 'USER_SESSION');
define('SESSION_LIFE_TIME', 60 * 60);
define('SESSION_ACTIVITY_TIME', 60 * 5);
define('SESSION_USE_SSL', false);
define('SESSION_ENCRYPTION', false);

/* Pagination Setting */
define('PAGINATION_COUNTER', 15);
