<?php
/* MAIN CONFIGURATION FILE
 *
 * This is shared by the following config files:
 *	- console.php	: The console application profile.
 *  -
 */
return array(
	// Application directory
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	// Application Name
	'name'=>'Yii Application Website',
	// Application Language
	'language'	=>	'vi',
	'sourceLanguage'	=>	'en',
	// preloading 'log' component
	'preload'	=>	array('log', 'cache', 'settings'),
	// autoloading model and component classes
	'import'	=>	array(
		'application.models.*',
		'application.components.*',
		// SwiftMailer
// 		'ext.mail.YiiMailMessage',

	),
	// application components
	'components'=>array(
// 		'db'=>array(
// 			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/yii-code.db',
// 			'username' => 'root',
// 			'password' => '',
// 			'charset' => 'utf8',
// 			'tablePrefix' => '',
// 			'emulatePrepare' => YII_DEBUG,
// 			'enableParamLogging' => YII_DEBUG,
// 			'enableProfiling' => YII_DEBUG
// 		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yii_core',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'myroot',
			'charset' => 'utf8',
			'tablePrefix' => '',
			'enableParamLogging' => TRUE,
			'enableProfiling' => TRUE
		),
		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		) ,
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=> (YII_DEBUG)?'trace,info,profile,warning,error':'info',
				),
			),
		),
		// Extra message source
		'messages' => array(
			'class'	=>	'ext.components.EMessageSource',
			'sources' => array(
				// @XXX: The first message source we should check
				'phpMessage' => array(
					'class'	=>	'CPhpMessageSource',
					'onMissingTranslation'	=>	array('CPhpMessageTranslator', 'appendMessage'),
				),
				// @XXX: Another MessageSource
				'dbMessage' => array(
					'class'	=>	'CDbMessageSource',
					'sourceMessageTable'	=>	'sourcemessage',
					'translatedMessageTable'	=>	'message',
					'onMissingTranslation'	=> array('CDbMessageTranslator', 'appendMessage'),
				),   
				// @XXX: Another brick in the wall
				'gettextMessage' => array(
						'class'	=>	'CGettextMessageSource',
						'onMissingTranslation'	=> array('CGettextMessageTranslator', 'appendMessage'),
				),
			),
		),
		// Caching method - for Debug is CDummyCache but for production is CFileCache
		'cache' => array(
			'class' => (YII_DEBUG)?'system.caching.CDummyCache':'system.caching.CFileCache',
		) ,
		'format'	=>	array(
			'class'	=>	'ext.components.EFormatter',
		),

/*		Un-comment if using YiiMail to send mail instead of PHP default mailer
		'mail' => array(
			'class' => 'ext.mail.YiiMail',
			'transportType' => 'php', 		//Can be either 'php' or 'smtp'
			'viewPath' => 'application.views.mail',
			'transportOptions' => array(	//See the SwiftMailer documentaion for the option meanings
				'host'	=>	NULL,
				'username' => NULL,
				'password'	=> NULL,
				'port'	=>	NULL,
				'encryption'	=>	NULL,
				'timeout'	=> NULL,
				'extensionHandlers'	=>	NULL,
			),
			'logging' => true,
			'dryRun' => TRUE
		),*/
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
	),
);
