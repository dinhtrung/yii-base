<?php
/**
 * If you want to develope multiple instance, use the following statements.
 *
 * Configure your _hosts_ file and add the domain name point to local host.
 *     127.0.0.1	dev1.localhost  dev2.localhost
 * Then create the configuration file in application.config folder with the same name:
 * 		dev1.localhost.php : Configuration file for dev1.localhost
 * 		dev2.localhost.php : Configuration file for dev2.localhost


 */
if (! empty($_SERVER['HTTP_HOST'])){
	$config = $_SERVER['HTTP_HOST'];
} elseif (! empty($_SERVER['SERVER_ADDR'])) {
	$config = $_SERVER['SERVER_ADDR'];
}
$config = 'webapp-development';

$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/'.$config.'.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',TRUE);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',9);

require_once($yii);
Yii::createWebApplication($config)->run();
