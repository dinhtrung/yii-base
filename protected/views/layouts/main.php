<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<title><?php echo CHtml::encode($this->pageTitle); ?> | <?php echo CHtml::encode(Yii::app()->name); ?></title>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/form.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main.css" media="screen, projection" />
</head>

<body>

<div class="container" id="page">
	<div id="topnav">
		<div class="topnav_text right prepend-top append-1">
			<?php echo CHtml::link(Yii::t('app', 'Hello, ') . ' <strong>' .Yii::app()->getUser()->getName().'</strong>', array('/user/profile')); ?> |
			<?php echo CHtml::link(Yii::t('app', 'Logout'), array('/user/logout')); ?>
		</div>
	</div>
	<div id="header">
		<h1 class="loud"><?php echo CHtml::encode(Yii::app()->name); ?></h1>
	</div>
	<!-- header -->

<div id="mainMbMenu">
<?php if (! empty($this->mainMenu)) $this->widget('ext.widgets.mbmenu.MbMenu',array(
            'items'=> $this->mainMenu,
    ));?>
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php foreach (Yii::app()->getUser()->getFlashes() as $key => $message):?>
		<div class='right flash-<?php echo $key; ?>'><?php echo $message; ?></div>
	<?php endforeach;?>

	<?php echo $content; ?>

	<div id="footer">
		<?php //shortcut
$translate=Yii::app()->dbtranslate;
//in your layout add
echo $translate->dropdown();
//adn this
if($translate->hasMessages()){
  //generates a to the page where you translate the missing translations found in this page
  echo $translate->translateLink('Translate');
  //or a dialog
  echo $translate->translateDialogLink('Translate','Translate page title');
}
//link to the page where you edit the translations
echo $translate->editLink('Edit translations page');
//link to the page where you check for all unstranslated messages of the system
echo $translate->missingLink('Missing translations page');

?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>