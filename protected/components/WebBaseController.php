<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class WebBaseController extends BaseController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $mainMenu=array();
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * @var string Current page title, used in the header <head> tags
	 */
	public $pageTitle;
	/**
	 * @var string website Name
	 */
	public $siteName;
	/**
	 * @var string website Slogan
	 */
	public $siteSlogan;
	/**
	 * @var string website logo image URL
	 */
	public $siteLogo;
	/**
	 * @var array contain various regional contents
	 */
	public $page = array();

	/*
	 * @var The permission string of current action
	 */
	public $authItem;

	/* (non-PHPdoc)
	 * @see CController::init()
	 */
	public function init() {
		parent::init();
		$this->siteSlogan = Yii::app()->setting->get('website', 'siteSlogan');
		$this->layout = Yii::app()->setting->get('website', 'layout', '//layouts/column2');
		/*
		 * Load all modules/views/layouts/_menu files to render the global file
		 */
		$this->mainMenu = array();
		$modules = Yii::app()->getModules();
		try {
			Yii::app()->getController()->renderPartial("//layouts/_menu");
		} catch (CException $e){
			Yii::log('Catch error while loading module menu: '.$e->getMessage(), 'debug');
		}
		foreach ($modules as $m => $info){
			try {
				Yii::app()->getController()->renderPartial("//../modules/$m/views/layouts/_menu");
			} catch (CException $e){
				Yii::log('Catch error while loading module menu: '.$e->getMessage(), 'debug');
			}
		}
	}

	function filters() {
		return array(
			"Language",
// 			"Rights",
			array(
            	"ext.components.ESetReturnUrlFilter + index, admin, view",
			)
        );
	}

	/**
	* The filter method for 'rights' access filter.
	* This filter is a wrapper of {@link CAccessControlFilter}.
	* @param CFilterChain $filterChain the filter chain that the filter is on.
	*/
	public function filterRights($filterChain)
	{
		$filter = new RightsFilter;
		$filter->allowedActions = $this->allowedActions();
		$filter->filter($filterChain);
		$this->authItem = $filter->authItem;
	}
	/**
	 * Choose the correct language stored in user session...
	 */
	public function filterLanguage($filterChain){
		Yii::app()->setLanguage(Yii::app()->getUser()->getState("language", Yii::app()->language));
		$filterChain->run();
	}

	/**
	* @return string the actions that are always allowed separated by commas.
	*/
	public function allowedActions()
	{
		return '';
	}
	/**
	* Denies the access of the user.
	* @param string $message the message to display to the user.
	* This method may be invoked when access check fails.
	* @throws CHttpException when called unless login is required.
	*/
	public function accessDenied($message=null)
	{
		if( $message===null )
			$message = Yii::t('rights', 'You are not authorized to perform this action.');

		$user = Yii::app()->getUser();
		if( $user->isGuest===true )
			$user->loginRequired();
		else
			throw new CHttpException(403, $message);
	}

	/**
	 * Perform Ajax Validation
	 * @param CModel $model	: The model to perform validation
	 * @param string $form  : The ID of the form
	 */
	protected function performAjaxValidation($model, $form) {
		if(isset($_POST['ajax']) && $_POST['ajax'] == $form) {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	function render($view, $data = NULL, $return = FALSE, $renderBlock = TRUE) {
		if (Yii::app()->request->isAjaxRequest){
			$this->renderPartial($view, $data);
		} else {
			if ($renderBlock && Yii::app()->hasModule('core')){
				$this->renderBlocks();
			}
			parent::render($view, $data, $return);
		}
	}

	protected function renderBlocks() {
		$blockTypes = new Blocktype('search');
		$blocks = new Block('search');
		$blockTheme = new Blocktheme('search');
		$theme = (Yii::app()->theme instanceof CTheme)?Yii::app()->theme->name:Yii::app()->theme;
		$blocks = $blockTheme->with('owner')->findAllByAttributes(
				array("theme" => $theme)
			);
		foreach ($blocks as $block){
			if (array_key_exists($block->region, $this->page))
				$this->page[$block->region] .= $block->owner->render();
			else 
				$this->page[$block->region] = $block->owner->render();
		}
	}
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
				$this->_model = CActiveRecord::model($model)->findbyPk(urldecode($_GET['id']));
			} else {
				$pkeys = CActiveRecord::model($model)->getTableSchema()->primaryKey;
				if (is_string($pkeys) && isset($_GET[$pkeys])){
					$this->_model = CActiveRecord::model($model)->findbyPk(urldecode($_GET[$pkeys]));
				} elseif (is_array($pkeys)){
					$tmp = array_flip($pkeys);
					foreach ($pkeys as $field) {
						if (isset($_GET[$field])) $tmp[$field] = urldecode($_GET[$field]);
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
