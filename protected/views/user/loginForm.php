<?php
$this->layout = '//layouts/column1'; 
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'user-form',
		'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
		'enableAjaxValidation'=>false,
)); ?>
<div class="span6 offset2">
	<fieldset>
		<legend><?php echo $this->pageTitle = Yii::t('app', 'User Login'); ?></legend>

	<?php echo TbHtml::well(Yii::t('app', 'Fields with <span class="required">*</span> are required.'))?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'username', array('class' => 'span4')); ?>

	<?php echo $form->passwordFieldControlGroup($model,'password', array('class' => 'span4')); ?>

	<?php echo $form->checkBoxControlGroup($model,'rememberMe'); ?>

	</fieldset>

	<?php echo TbHtml::formActions(array(
		TbHtml::submitButton(Yii::t('app', 'Login'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		TbHtml::link(Yii::t('user', 'Register'), array('user/registration'), array('class' => 'btn btn-success')),
		TbHtml::link(Yii::t('user', 'Lost Password?'), array('user/recovery'), array('class' => 'btn btn-warning')),
	)); ?>

<?php $this->endWidget(); ?>
</div>
