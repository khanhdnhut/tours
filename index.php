<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
//define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);
define('APP', 'application' . DIRECTORY_SEPARATOR);
// Load application config
require APP . 'config/config.php';

require APP . 'lib/helper.php';

require APP . 'config/const.php';
// Load language file

define('LANGUAGE', 'en');

if (LANGUAGE == 'en') {
    require APP . 'config/language_en.php';
} elseif (LANGUAGE == 'vn') {
    require APP . 'config/language_vn.php';
}

// Load auto-loader
require APP . 'config/autoload.php';

// Start the application - Use autoload class
$app = new Application();

if (!isset($_SESSION['options'])){
    require APP . 'controller/option_ctrl.php';
    $optionCtrl = new OptionCtrl();
    $optionCtrl->loadOption();
}
