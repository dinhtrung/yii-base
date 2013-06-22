<?php

class User extends BaseActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANED=-1;

	public $role = array();

	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
	 * @var integer $createtime
	 * @var integer $updatetime
	 * @var string $role
	 * @var integer $status
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users}}';
	}

	public function getTitle(){
		if (Yii::app() instanceof CWebApplication)
			return Yii::t('user', '<abbr title=":email">:name</abbr>', array(':name' => $this->username, ':email' =>  $this->email ));
		else
			return Yii::t('user', ':name <:email>', array(':name' => $this->username, ':email' =>  $this->email ));
	}

	public function connectionId(){
		return Yii::app()->hasComponent('userDb')?'userDb':'db';
	}

	/*
	 * CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `username` varchar(20) NOT NULL,
	  `password` varchar(128) NOT NULL,
	  `email` varchar(128) NOT NULL,
	  `activkey` varchar(128) NOT NULL DEFAULT '',
	  `createtime` int(10) NOT NULL DEFAULT '0',
	  `updatetime` int(10) NOT NULL DEFAULT '0',
	  `status` int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `username` (`username`),
	  UNIQUE KEY `email` (`email`),
	  KEY `status` (`status`),
	  KEY `superuser` (`superuser`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
	*/
	protected function createTable(){
		$columns = array(
				'id'		=>	'pk',
				'username'	=>	'string',
				'password'	=>	'string',
				'email'		=>	'string',
				'activkey'	=>	'string',
				'createtime'	=>	'int',
				'updatetime'	=>	'int',
				'status'	=>	'boolean',
		);
		try {
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->createTable($this->tableName(), $columns)
			)->execute();
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->createIndex('username', $this->tableName(), 'username', TRUE)
			)->execute();
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->createIndex('email', $this->tableName(), 'email', TRUE)
			)->execute();
		} catch (CDbException $e){
			Yii::log($e->getMessage(), 'warning');
		}

		$this->refreshMetaData();
		/* Create default user */
		$this->setAttributes(array(
				'username' => 'admin',
				'password' => CPasswordHelper::hashPassword('admin'),
				'email' => 'admin@example.com',
				'status'	=>	self::STATUS_ACTIVE,
		));
		$this->setIsNewRecord(TRUE);
		$this->save();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		if (Yii::app()->user->id==$this->id) $rules = array(
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => Yii::t('user', "Incorrect username (length between 3 and 20 characters).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => Yii::t('user', "This user's name already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => Yii::t('user', "Incorrect symbols (A-z0-9).")),
			array('email', 'unique', 'message' => Yii::t('user', "This user's email address already exists.")),
		);
		else $rules = array(
			array('password', 'required', 'on' => 'insert'),
			array('password, role', 'safe'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => Yii::t('user', "Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => Yii::t('user', "Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => Yii::t('user', "This user's name already exists.")),
			array('email', 'unique', 'message' => Yii::t('user', "This user's email address already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => Yii::t('user', "Incorrect symbols (A-z0-9).")),
			array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE,self::STATUS_ACTIVE,self::STATUS_BANED)),
			array('username, email, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
		);
		return array_merge(parent::rules(), $rules);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'site'	=>	array(self::BELONGS_TO, 'Organization', 'org'),
		);
	}

	public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactvie'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            'banned'=>array(
                'condition'=>'status='.self::STATUS_BANED,
            ),
            'role'=>array(
                'condition'=>'role=1',
            ),
            'notsafe'=>array(
            	'select' => 'id, username, password, email, activkey, createtime, updatetime, role, status',
            ),
        );
    }

	public static function statusOptions($code=NULL) {
		$options = array(
			self::STATUS_ACTIVE => Yii::t('user', 'Active'),
			self::STATUS_BANED => Yii::t('user', 'Banned'),
			self::STATUS_NOACTIVE => Yii::t('user', 'Not active'),
		);
		if (is_null($code)) return $options; 
		elseif (array_key_exists($code, $options)) return $options[$code];
		return $code;
	}

	/*
	 * Assign the Rights module authorizer if needed...
	 */
	protected function afterFind(){
		$assignedItems = Yii::app()->authManager->getRoles($this->getPrimaryKey());
		$this->role = array_keys($assignedItems);
		return parent::afterFind();
	}
}