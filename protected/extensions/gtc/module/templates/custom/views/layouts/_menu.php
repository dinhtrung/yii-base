<?php echo "<?php\n"; ?>
$this->mainMenu['<?php echo $this->moduleID; ?>'] = array(
	'label' => Yii::t('<?php echo $this->moduleID; ?>', '<?php echo ucfirst($this->moduleID); ?>'),
	'url'=>array('/<?php echo $this->moduleID; ?>/default/index'),
	'visible' => Yii::app()->user->checkAccess('<?php echo ucfirst($this->moduleID); ?>.Default.Index'),
	'items'=>array(
		array(
				'label' => Yii::t('<?php echo $this->moduleID; ?>', 'Controller.Action'),
				'url'=>array('/<?php echo $this->moduleID; ?>/controller/action'),
				'visible' => Yii::app()->user->checkAccess('<?php echo ucfirst($this->moduleID); ?>.Controller.Action')
			),
	),
);