<?php echo "<?php\n"; ?>
/*
 * Use this file to create models for this module
 */
class DefaultModel extends BaseActiveRecord
{
	public function connectionId(){
		return Yii::app()->hasComponent('<?php echo $this->moduleID; ?>Db')?'<?php echo $this->moduleID; ?>Db':'db';
	}

	public static function model($className = __CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{<?php echo $this->moduleID; ?>}}';
	}
}