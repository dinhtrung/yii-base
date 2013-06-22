<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="note"><?php echo "<?php echo Yii::t('app', 'Fields with <span class=\"required\">*</span> are required.'); ?>"?></p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
echo '<?php $this->beginClip("' . $this->class2id($this->modelClass) . '"); ?>';
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<div class="row">
		<?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
		<p class="hint"><?php $hint = $column->comment?$column->comment:'@HINT FOR $column->name'; echo "<?php echo Yii::t('{$this->getModule()->getId()}', '$hint'); ?>"?></p>
	</div>

<?php
}
echo '<?php $this->endClip(); ?>';
echo "\n\n";

echo '<?php
$this->widget("CTabView", array(
	"tabs"	=>	array(
	    "'.$this->class2id($this->modelClass).'"=>array(
	          "title"	=>	Yii::t("app", "' . $this->class2id($this->modelClass) . '"),
	          "content"	=>	$this->clips["'.$this->class2id($this->modelClass).'"],
	    ),
	)
));
?>';
?>
	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton(Yii::t('app', 'Save')); ?>\n"; ?>
		<?php echo "<?php echo CHtml::resetButton(Yii::t('app', 'Reset')); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->