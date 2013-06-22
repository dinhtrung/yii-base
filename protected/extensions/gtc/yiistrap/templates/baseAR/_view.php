<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $data <?php echo $this->getModelClass(); ?> */
?>

<div class="view">
<?php if (is_array($this->getTableSchema()->primaryKey)) { ?>				
	<h3><?php echo "<?php echo CHtml::link(\$data->title, array('view', \$data->primaryKey)); ?>"; ?></h3>
<?php } else { ?>					
	<h3><?php echo "<?php echo CHtml::link(\$data->title, array('view', 'id' => \$data->primaryKey)); ?>"; ?></h3>
<?php } ?>					


<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
	if(++$count==7)
		echo "\t<?php /*\n";
	echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
	echo "\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
}
if($count>=7)
	echo "\t*/ ?>\n";
?>

</div>