<?php
/**
 * Settings Model
 *
 *
 * DATABASE STRUCTURE:

 CREATE TABLE IF NOT EXISTS `settings` (
 `category` varchar(64) NOT NULL default 'system',
 `key` varchar(255) NOT NULL,
 `value` text NOT NULL,
 PRIMARY KEY  (`id`),
 KEY `category_key` (`category`,`key`)
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 */
class Settings extends CApplicationComponent
{

	protected $_saveItemsToDatabase=array();
	protected $_deleteItemsFromDatabase=array();
	protected $_deleteCategoriesFromDatabase=array();
	protected $_cacheNeedsFlush=array();

	protected $_items=array();

	/**
	 * CmsSettings::init()
	 *
	 * @return
	 */
	public function init()
	{
		if (YII_DEBUG && ! Yii::app()->getDb()->getSchema()->getTable('{{settings}}')){
			Yii::app()->db->createCommand(
				Yii::app()->getDb()->getSchema()->createTable('{{settings}}', array(
					'category' => 'string',
					'key' => 'string',
					'value' => 'text')
				)
			)->execute();
			Yii::app()->db->createCommand(
				Yii::app()->getDb()->getSchema()->addPrimaryKey('category_key', '{{settings}}', 'category,key')
			)->execute();
		}
		Yii::app()->attachEventHandler('onEndRequest', array($this, 'commit'));
	}


	/**
	 * CmsSettings::set()
	 *
	 * @param string $category
	 * @param mixed $key
	 * @param string $value
	 * @param bool $toDatabase
	 * @return
	 */
	public function set($category='system', $key, $value='', $toDatabase=true)
	{
		if(is_array($key))
		{
			foreach($key AS $k=>$v)
				$this->set($category, $k, $v, $toDatabase);
		}
		else
		{
			if($toDatabase)
				$this->_saveItemsToDatabase[$category][$key]=$value;
			$this->_items[$category][$key]=$value;
		}

	}

	/**
	 * CmsSettings::get()
	 *
	 * @param string $category
	 * @param string $key
	 * @param string $default
	 * @return
	 */
	public function get($category='system', $key='', $default='')
	{
		if(!isset($this->_items[$category]))
			$this->load($category);

		if(empty($key)&&empty($default)&&!empty($category))
			return isset($this->_items[$category])?$this->_items[$category]:null;

		if(isset($this->_items[$category][$key]))
			return $this->_items[$category][$key];
		return !empty($default)?$default:null;
	}

	/**
	 * CmsSettings::delete()
	 *
	 * @param string $category
	 * @param string $key
	 * @return
	 */
	public function delete($category='system', $key='')
	{
		if(!empty($category)&&empty($key))
		{
			$this->_deleteCategoriesFromDatabase[]=$category;
			return;
		}
		if(is_array($key))
		{
			foreach($key AS $k)
				$this->delete($category, $k);
		}
		else
		{
			if(isset($this->_items[$category][$key]))
			{
				unset($this->_items[$category][$key]);
				$this->_deleteItemsFromDatabase[$category][]=$key;
			}
		}
	}

	/**
	 * CmsSettings::load()
	 *
	 * @param mixed $category
	 * @return
	 */
	public function load($category)
	{
		$items=Yii::app()->cache->get($category.'_settings');
		if(!$items)
		{
			$connection=Yii::app()->getDb();
			$command=$connection->createCommand('SELECT * FROM {{settings}} WHERE category=:cat');
			$command->bindParam(':cat',$category);
			$result=$command->queryAll();

			if(empty($result))
			{
				$this->set($category, '{empty}', '{empty}', false);
				return;
			}

			$items=array();
			foreach($result AS $row)
				$items[$row['key']] = @unserialize($row['value']);

			Yii::app()->cache->add($category.'_settings', $items);
		}
		$this->set($category, $items, '', false);
		return $items;
	}

	/**
	 * CmsSettings::toArray()
	 *
	 * @return
	 */
	public function toArray()
	{
		return $this->_items;
	}


	/**
	 * CmsSettings::addDbItem()
	 *
	 * @param string $category
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	private function addDbItem($category='system', $key, $value)
	{
		$connection=Yii::app()->db;
		$command=$connection->createCommand('SELECT * FROM {{settings}} WHERE `category`=:cat AND `key`=:key LIMIT 1');
		$command->bindParam(':cat',$category,PDO::PARAM_STR);
		$command->bindParam(':key',$key,PDO::PARAM_STR);
		$result=$command->queryRow();
		$_value=@serialize($value);

		if(!empty($result))
		{
			$command=$connection->createCommand('UPDATE {{settings}} SET `value`=:value WHERE `category`=:cat AND `key`=:key');
			$command->bindParam(':value',$_value,PDO::PARAM_STR);
			$command->bindParam(':key',$key,PDO::PARAM_STR);
			$command->bindParam(':cat',$category,PDO::PARAM_STR);
			$command->execute();
		}
		else
		{
			$command=$connection->createCommand('INSERT INTO {{settings}} (`category`,`key`,`value`) VALUES(:cat,:key,:value)');
			$command->bindParam(':cat',$category,PDO::PARAM_STR);
			$command->bindParam(':key',$key,PDO::PARAM_STR);
			$command->bindParam(':value',$_value,PDO::PARAM_STR);
			$command->execute();
		}
	}

	/**
	 * CmsSettings::whenRequestEnds()
	 *
	 * @return
	 */
	public function commit()
	{
		$this->_cacheNeedsFlush=array();

		if(count($this->_deleteCategoriesFromDatabase)>0)
		{
			foreach($this->_deleteCategoriesFromDatabase AS $catName)
			{
				$connection=Yii::app()->db;
				$command=$connection->createCommand('DELETE FROM {{settings}} WHERE `category`=:cat');
				$command->bindParam(':cat', $catName);
				$command->execute();
				$this->_cacheNeedsFlush[]=$catName;

				if(isset($this->_deleteItemsFromDatabase[$catName]))
					unset($this->_deleteItemsFromDatabase[$catName]);
				if(isset($this->_saveItemsToDatabase[$catName]))
					unset($this->_saveItemsToDatabase[$catName]);
			}
		}

		if(count($this->_deleteItemsFromDatabase)>0)
		{
			foreach($this->_deleteItemsFromDatabase AS $catName=>$keys)
			{
				$params=array();
				$i=0;
				foreach($keys AS $v)
				{
					if(isset($this->_saveItemsToDatabase[$catName][$v]))
						unset($this->_saveItemsToDatabase[$catName][$v]);
					$params[':p'.$i]=$v;
					++$i;
				}
				$names=implode(',', array_keys($params));

				$connection=Yii::app()->db;
				$query='DELETE FROM {{settings}} WHERE `category`=:cat AND `key` IN('.$names.')';
				$command=$connection->createCommand($query);
				$command->bindParam(':cat', $catName);

				foreach($params AS $key=>$value)
					$command->bindParam($key, $value);

				$command->execute();
				$this->_cacheNeedsFlush[]=$catName;
			}
		}

		if(count($this->_saveItemsToDatabase)>0)
		{
			foreach($this->_saveItemsToDatabase AS $catName=>$keyValues)
			{
				foreach($keyValues AS $k=>$v)
					$this->addDbItem($catName, $k, $v);
				$this->_cacheNeedsFlush[]=$catName;
			}
		}

		if(count($this->_cacheNeedsFlush)>0)
		{
			foreach($this->_cacheNeedsFlush AS $catName)
				Yii::app()->cache->delete($catName.'_settings');
		}
	}
}
