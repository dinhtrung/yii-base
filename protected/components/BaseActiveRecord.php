<?php
class BaseActiveRecord extends MultiActiveRecord {
	public $dbtable;
	protected $_attributeLabels;
	public static $relations = array();

	public function __construct($scenario = 'insert'){
		try {
			return parent::__construct($scenario);
		} catch (CDbException $e){
			if (! $this->createTable()) throw $e;
			$this->refreshMetaData();
			return parent::__construct($scenario);
		}
	}

	public static function model($className = __CLASS__){
		return parent::model($className);
	}

	public function  __toString() {
		if (Yii::app() instanceof CWebApplication){
			return CVarDumper::dumpAsString($this->getAttributes(), 3, TRUE);
		} else {
			return CVarDumper::dumpAsString($this->getAttributes(), 3, FALSE);
		}
	}

	public function getTitle(){
		if (! $this->title) {
			return Yii::t('app', '@TODO: Please override getTitle for :class', array(':class' => get_class($this)));
		} else return $this->title;
	}
	/**
	 * Return attribute label for an attribute
	 * @see CActiveRecord::getAttributeLabel()
	 */
	public function getAttributeLabel($attribute){
		return Yii::t(__CLASS__, $attribute);
	}

    /**
     * Return the default dataProvider for searching
     */
    public function search($criteria = NULL){
    	if (is_null($criteria)) $criteria=new CDbCriteria;
    	foreach ($this->tableSchema->columns as $col){
    		if ($col->type == 'string') $criteria->compare($col->name, $this->{$col->name}, TRUE);
    		else $criteria->compare($col->name, $this->{$col->name});
    	}
    	return new CActiveDataProvider(get_class($this), array(
    			'criteria'=>$criteria,
    	));
    }


    /**
     * Create the table if needed
     *
    pk: an auto-incremental primary key type, will be converted into "int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY"
    string: string type, will be converted into "varchar(255)"
    text: a long string type, will be converted into "text"
    integer: integer type, will be converted into "int(11)"
    boolean: boolean type, will be converted into "tinyint(1)"
    float: float number type, will be converted into "float"
    decimal: decimal number type, will be converted into "decimal"
    datetime: datetime type, will be converted into "datetime"
    timestamp: timestamp type, will be converted into "timestamp"
    time: time type, will be converted into "time"
    date: date type, will be converted into "date"
    binary: binary data type, will be converted into "blob"
		$columns = array(
				'id'	=>	'pk',
				'title'	=>	'string',
				'body'	=>	'text',
				'status'	=>	'boolean',
				'comment_cnt'	=>	'int',
				'rating'		=>	'float',
		);
		try {
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->createTable($this->tableName(), $columns)
			)->execute();
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->addPrimaryKey('id_lang', $this->tableName(), 'id,language')
			)->execute();
			// Reference tables
			$ref = new RefTable();
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->addForeignKey('fk_block_blocktheme', $this->tableName(), 'bid', $ref->tableName(), 'block')
			)->execute();
		} catch (CDbException $e){ Yii::log($e->getMessage(), 'warning'); }
		$this->refreshMetaData();
     */
    protected function createTable(){
    	return FALSE;
    }
}
