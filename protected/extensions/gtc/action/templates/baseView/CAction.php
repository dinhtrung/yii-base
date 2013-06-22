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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '<?php echo $this->baseClass; ?>.php';
class <?php echo ucfirst($this->className).'Action'; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * The name of the default view when viewParam GET parameter is not provided by user.
	 * @var string defaultView
	 */
	public $defaultView = "<?php echo strtolower($this->className); ?>";

	/**
	* Place your logic here.
	*/
    public function process()
    {
    	$this->_model->setAttributes($_POST[$this->modelClass]);
    	$this->getController()->redirect("index");
   	}
   	/**
   	* Override the loading of model if needed.
   	* @see <?php echo $this->baseClass; ?>::model()
   	public function model(){}
   	*/
   	/**
   	* Override the render process needed.
   	* @see <?php echo $this->baseClass; ?>::render()
   	public function render(){}
   	*/
}