<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the CSaveRelationsBehavior
 * to the model class definition.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
$dbcols = array();
$pkeys = array();
$idx = array();
foreach ($columns as $colname => $coldata){
	$dbcols[$colname] = $coldata->type;
	if ($coldata->isPrimaryKey) $pkeys[] = $colname;
	if ($coldata->isForeignKey) $idx[] = $colname;
}
?>
<?php echo "<?php\n"; ?>

class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	public function connectionId(){
<?php if ($module): ?>
		return Yii::app()->hasComponent('<?php echo $module; ?>Db')?'<?php echo $module; ?>Db':'db';
<?php else: ?>
		return 'db';
<?php endif; ?>
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

<?php if (! empty($relations)): ?>
	public function relations()
	{
		return array(
<?php
		foreach($relations as $name=>$relation) {
			echo "\t\t\t'$name' => $relation,\n";
		}
?>
		);
	}

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('<?php echo implode(', ', array_keys($relations)); ?>', 'safe', 'on' => 'insert,update'),
		));
	}


	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
<?php
		foreach($relations as $name=>$relation) {
			echo "\t\t\t'$name' => Yii::t('".($module)?$module:'app' . "', '$name', 'dbmessage'),\n";
		}
?>
		));
	}
<?php endif; ?>

	protected function createTable(){
		$columns = <?php var_export($dbcols); ?>;
		$this->getDbConnection()->createCommand(
			$this->getDbConnection()->getSchema()->createTable($this->tableName(), $columns)
		)->execute();
<?php if (! empty($pkeys)): ?>
		$this->getDbConnection()->createCommand(
			$this->getDbConnection()->getSchema()->addPrimaryKey('<?php echo implode('_', $pkeys); ?>', $this->tableName(), '<?php echo implode(',', $pkeys); ?>')
		)->execute();
<?php endif; ?>
<?php if (! empty($idx)): ?>
		$this->getDbConnection()->createCommand(
			$this->getDbConnection()->getSchema()->createIndex('<?php echo implode('_', $idx); ?>', $this->tableName(), '<?php echo implode(',', $idx); ?>')
		)->execute();
<?php endif; ?>
	}
}
