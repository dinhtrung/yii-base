<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/bootstrap');
return CMap::mergeArray(require (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'main.php') , array(
	// autoloading model and component classes
	'theme' => 'bootstrap',
	'preload' => array('bootstrap'),
	'import' => array(
		// Yii-user
		'application.modules.user.components.*',
		'application.modules.user.models.*',
		// Core
		'application.modules.core.components.*',
		'application.modules.core.models.*',
		// Extension Main Components
		'ext.components.*',
		'ext.helpers.*',
        // Image
		"ext.image.*",
		// Bootstrap
		"bootstrap.helpers.TbHtml",
	) ,
	'modules' => array(
		// Translate for Database. Use: Yii::t('source', 'message', array(), 'dbmessage')
		'core',
		'translate',
	) ,
	// application components
	'components' => array(
		'request' => array(
			'enableCsrfValidation' => FALSE,
		) ,
		'bootstrap' => array(
			'class' => 'bootstrap.components.TbApi',
		) ,
		// Add XWebDebugRouter for Yii
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CWebLogRoute',
					'levels' => 'error, warning, trace, profile, info',
					'enabled' => YII_DEBUG,
				) ,
			) ,
		) ,
		'user' => array(
			'class' => 'WebUser',
			'allowAutoLogin' => true,
		) ,
// 		'urlManager' => array(
// 			'urlFormat'=>'path',
// 			'rules'=>array(
// 				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
// 				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
// 				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
// 			),
// 		) ,
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
			'assignmentTable' => 'authassignment',
			'itemChildTable'	=>	'authitemchild',
			'itemTable'	=>	'authitem',
		) ,
		'setting' => array(
			'class' => 'application.components.Settings',
		) ,
		// DB Translator
		'dbtranslate'=>array(
				'class'=>'translate.components.MPTranslate',
				'acceptedLanguages'=>array(
						'en'=>'English',
						'vi'=>'Vietnamese',
				),
		),
		// @TODO: Move me to another preferences
		'widgetFactory'=> array(
				'class' => 'CWidgetFactory',
				'widgets' => array(
						'CGridView'=>array(
							'itemsCssClass'=>'item-class',
							'pagerCssClass'=>'pager-class'
						),
						'CJuiTabs'=>array(
							'htmlOptions'=>array('class'=>'shadowtabs'),
						),
						'CJuiAccordion'=>array(
							'htmlOptions'=>array('class'=>'shadowaccordion'),
						),
						'CJuiProgressBar'=>array(
							'htmlOptions'=>array('class'=>'shadowprogressbar'),
						),
						'CJuiSlider'=>array(
							'htmlOptions'=>array('class'=>'shadowslider'),
						),
						'CJuiSliderInput'=>array(
							'htmlOptions'=>array('class'=>'shadowslider'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'shadowbutton'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'shadowbutton'),
						),
						'CJuiButton'=>array(
							'htmlOptions'=>array('class'=>'button green'),
						),
				)
		),
	) ,
));
