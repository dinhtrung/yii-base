<?php
$this->breadcrumbs=array(
	Yii::t('user', "Profile") => array('profile'),
	Yii::t('user', "Change Password"),
);
?>

<h1><?php echo $this->pageTitle = Yii::t('user', "Change password"); ?></h1>

<?php echo TbHtml::well(Yii::t('user', 'Fields with <span class="required">*</span> are required.')); ?></p>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'changepassword-form',
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->passwordFieldControlGroup($model,'password', array('help' => Yii::t('user', "Minimal password length 4 symbols."))); ?>

	<?php echo $form->passwordFieldControlGroup($model,'verifyPassword'); ?>
	
	<?php echo TbHtml::formActions(array(
		TbHtml::submitButton(Yii::t('app', 'Submit'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		TbHtml::resetButton(Yii::t('app', 'Reset')),
	)); ?>

<?php $this->endWidget(); ?>
