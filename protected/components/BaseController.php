<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseController extends CController
{
	protected $_model;
	/**
	 * Load Model, based on the Primary key
	 * @param string $model  The model class name
	 * @throws CHttpException
	 */
	protected function loadModel($model = false) {
		if(!$model)
			$model = str_replace('Controller', '', get_class($this));

		if($this->_model === null) {
			if(isset($_GET['id'])){
				$this->_model = CActiveRecord::model($model)->findbyPk($_GET['id']);
			} else {
				$pkeys = CActiveRecord::model($model)->getTableSchema()->primaryKey;
				if (is_string($pkeys) && isset($_GET[$pkeys])){
					$this->_model = CActiveRecord::model($model)->findbyPk($_GET[$pkeys]);
				} elseif (is_array($pkeys)){
					$tmp = array_flip($pkeys);
					foreach ($pkeys as $field) {
						if (isset($_GET[$field])) $tmp[$field] = $_GET[$field];
						else { $tmp = FALSE; break; }
					}
					if (! empty($tmp))
					$this->_model = CActiveRecord::model($model)->findbyPk($tmp);
				}
			}
			if($this->_model===null)
				throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		}
		return $this->_model;
	}
}