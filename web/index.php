<?php
error_reporting(E_ERROR);
ini_set("display_errors","On");

function cache_shutdown_error() {

    $_error = error_get_last();

    if ($_error && in_array($_error['type'], array(1, 4, 16, 64, 256, 4096, E_ALL))) {

        echo '<font color=red>code error: </font></br>';
        echo 'error:' . $_error['message'] . '</br>';
        echo 'file:' . $_error['file'] . '</br>';
        echo 'num:' . $_error['line'] . '</br>';
    }
}

register_shutdown_function("cache_shutdown_error");
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
