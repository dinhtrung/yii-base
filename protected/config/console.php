<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(require (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'main.php') , array(
	'name'=>'My Console Application',
	// application components
	'components'=>array(
		// database is set in main.php
		
	),
	'commandMap' => array(
        /* 'command-name' => array(
        		'class'	=>	'path.alias.to.command',
        		'param' => 'command parameters'
        	),
        */
    ),
));
