<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
//define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);
define('APP', 'application' . DIRECTORY_SEPARATOR);
// Load application config
require APP . 'config/config.php';

require APP . 'lib/helper.php';
// Load language file

define('LANGUAGE', 'en');

if (ENVIRONMENT == 'en') {
    require APP . 'config/language_en.php';
} elseif (ENVIRONMENT == 'vn') {
    require APP . 'config/language_vn.php';
}

// Load auto-loader
require APP . 'config/autoload.php';

// Start the application - Use autoload class
$app = new Application();