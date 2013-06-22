<?php echo "<?php\n"; ?>

class DefaultController extends WebBaseController
{
	public function allowedActions(){
		return 'index';
	}


	public function actionIndex()
	{
		$this->render('index');
	}
}