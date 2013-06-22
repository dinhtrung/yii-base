<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */

$pk = '\'id\' => $model->primaryKey';
if (is_array($this->getTableSchema()->primaryKey)) $pk = '$model->primaryKey';
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('app', '$label')	=>	array('index'),
	\$model->title,
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'Update <?php echo $this->modelClass; ?>', 'url'=>array('update', <?php echo $pk; ?>)),
	array('label'=>'Delete <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete', <?php echo $pk; ?>),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1><?php echo "<?php echo \$this->pageTitle = Yii::t('app', 'Details of {$this->modelClass} :title', array(':title' => \$model->title)); ?>"?></h1>

<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>