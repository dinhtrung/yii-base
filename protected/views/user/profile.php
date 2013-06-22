<?php
$this->breadcrumbs=array(
	Yii::t('user', "Profile"),
);
?>

<h1><?php echo $this->pageTitle = Yii::t('user', 'Your profile'); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes' => array(
			'username',
			'email',
			'createtime:datetime',
			'updatetime:datetime',
			'role',
		)
	));
?>

<?php echo TbHtml::formActions(array(
		TbHtml::link(Yii::t('user', 'Change Password'), array('changepassword'), array('class' => 'btn btn-danger')),
		TbHtml::link(Yii::t('user', 'Update Profile'), array('edit'), array('class' => 'btn btn-info')),
	)); ?>