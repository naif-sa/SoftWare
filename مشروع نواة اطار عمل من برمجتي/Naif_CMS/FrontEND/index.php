<?php

namespace NAIF_CMS;

use NAIF_CMS\System\Library\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'init.php';
$templateConfig = require_once CONFIG_PATH . 'templateConfig.php';

$Application = Application::getInstance();

$Application->run($templateConfig);
