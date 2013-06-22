<?php echo "<?php\n"; ?>
/**
 * <?php echo ucfirst($this->className)."Action"; ?> class file.
 *
 * @author Nguyen Dinh Trung <nguyendinhtrung141@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 ISTT Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 1.0
 */
class <?php echo ucfirst($this->className).'Action'; ?> extends CViewAction
{
/**
	 * Name of the CModel class used to validate and/or save instance
	 * @var string $modelClass
	 */
	public $modelClass;
	/**
	 * CModel class instance
	 * @var CModel $_model
	 */
	public $_model;
	/**
	 * Perform ajax validation or not
	 * @var boolean $performAjaxValidation
	 */
	public $performAjaxValidation = TRUE;
	/**
	 * The base path for the views.
	 * @var string basePath
	 */
	public $basePath = "";
	/**
	 * The name of the default view when viewParam GET parameter is not provided by user.
	 * @var string defaultView
	 */
	public $defaultView = "<?php echo strtolower($this->className); ?>";

    public function run()
    {
    	$controller = $this->getController();
	    if (empty($this->modelClass)){
	    	$this->modelClass = ucfirst($controller->getId());
	    }
	    if(is_string($this->modelClass))
		{
			$this->_model=new $this->modelClass;
		}
		if(! ($this->_model instanceof CModel))
		throw new CException("Please specify the model attribute", 500);

		/** Your logic start here **/
		$this->_model->setScenario("<?php echo strtolower($this->className); ?>");
		if (isset($_POST[$this->modelClass])) {
			$this->_model->setAttributes($_POST[$this->modelClass]);
			if ($this->_model->validate()){
				$controller->redirect(Yii::app()->getUser()->getReturnUrl());
			}
		}
		/** Your logic end here **/

		$this->resolveView($this->getRequestedView());
		if($this->layout!==null)
		{
			$layout=$controller->layout;
			$controller->layout=$this->layout;
		}
		$this->onBeforeRender($event=new CEvent($this));
		if(!$event->handled) {
			$controller->render($this->view, array('model' => $this->_model));
			$this->onAfterRender(new CEvent($this));
		}
		if($this->layout!==null) $controller->layout=$layout;
    }
}