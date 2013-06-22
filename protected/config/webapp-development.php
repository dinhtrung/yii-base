<?php
/**
 * Configuration for Dev1
 */
return CMap::mergeArray(require (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'webapp.php') , array(
	'import' => array(
		'ext.gtc.components.*',	// Gii
	) ,
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'myroot',
			'ipFilters' => array(
			) ,
			'generatorPaths' => array(
				'ext.gtc',
				'bootstrap.gii',
			)
		) ,
		'myuser',
	) ,
	'components' => array(
		'ussdbrDb'=>array(
				'class'	=>	'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=yii_core',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => 'myroot',
				'charset' => 'utf8',
		),
	)
));
