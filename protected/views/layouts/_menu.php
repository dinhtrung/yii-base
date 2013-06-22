<?php
$this->mainMenu['user'] = array(
		'label' => Yii::t('user', 'User'),
		'items'=>array(
				array(
						'label' => Yii::t('user', 'Admin'),
						'url'=>array('/user/admin'),
						'visible'=>Yii::app()->user->checkAccess('User.Admin' ),
				),
				array(
						'label' => Yii::t('user', 'List'),
						'url'=>array('/user'),
						'visible'=>Yii::app()->user->checkAccess('User.User.Index')

				),
				array(
						'label' => Yii::t('user', 'Login'),
						'url'=>array('/user/login'),
						'visible'=>Yii::app()->user->isGuest

				),
				array(
						'label' => Yii::t('user', 'Profile'),
						'url'=>array('/user/profile'),
						'visible'=>!Yii::app()->user->isGuest

				),
				array(
						'label' => Yii::t('user', 'Logout'),
						'url'=>array('/user/logout'),
						'visible'=>!Yii::app()->user->isGuest

				),
				array(
						'label' => Yii::t('user', 'Register'),
						'url'=>array('/user/registration'),
						'visible'=>Yii::app()->user->isGuest

				),
				array(
						'label' => Yii::t('user', 'Recovery'),
						'url'=>array('/user/recovery'),
						'visible'=>Yii::app()->user->isGuest

				),
		),
);

$this->mainMenu['auth'] = array(
	'label' => Yii::t('user', 'Authorization'),
	'items'=>array(
		array(
				'label' => Yii::t('user', 'Assignment'),
				'url'=>array('/user/assignment'),
				'visible' => Yii::app()->user->checkAccess('User.Assignment.View')
		),
		array(
				'label' => Yii::t('user', 'Permissions'),
				'url'=>array('/user/authitem/permissions'),
				'visible' => Yii::app()->user->checkAccess('User.Authitem.Permissions')
		),
		array(
				'label' => Yii::t('user', 'Roles'),
				'url'=>array('/user/authitem/roles'),
				'visible' => Yii::app()->user->checkAccess('User.Authitem.Roles')
		),
		array(
				'label' => Yii::t('user', 'Tasks'),
				'url'=>array('/user/authitem/tasks'),
				'visible' => Yii::app()->user->checkAccess('User.Authitem.Tasks')
		),
		array(
				'label' => Yii::t('user', 'Operations'),
				'url'=>array('/user/authitem/operations'),
				'visible' => Yii::app()->user->checkAccess('User.Authitem.Operations')
		),
	)
);