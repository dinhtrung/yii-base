<?php $this->breadcrumbs=array(
	Yii::t('user', "Registration"),
);
?>

<h1><?php echo $this->pageTitle = Yii::t('user', "Registration"); ?></h1>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo Yii::t('user', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary(array($model)); ?>

	<div class="row">
	<?php echo $form->labelEx($model,'username'); ?>
	<?php echo $form->textField($model,'username'); ?>
	<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password'); ?>
	<?php echo $form->error($model,'password'); ?>
	<p class="hint">
	<?php echo Yii::t('user', "Minimal password length 4 symbols."); ?>
	</p>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'verifyPassword'); ?>
	<?php echo $form->passwordField($model,'verifyPassword'); ?>
	<?php echo $form->error($model,'verifyPassword'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'email'); ?>
	<?php echo $form->textField($model,'email'); ?>
	<?php echo $form->error($model,'email'); ?>
	</div>

	<?php if (UserModule::doCaptcha('registration')): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<?php $this->widget('CCaptcha'); ?>
		<p class="hint"><?php echo Yii::t('user', "Please enter the letters as they are shown in the image above."); ?>
		<br/><?php echo Yii::t('user', "Letters are not case-sensitive."); ?></p>
	</div>
	<div class="row">

		<?php echo $form->textField($model,'verifyCode'); ?>
		<?php echo $form->error($model,'verifyCode'); ?>

	</div>
	<?php endif; ?>

	<div class="row submit">
		<?php echo CHtml::submitButton(Yii::t('user', "Register")); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>