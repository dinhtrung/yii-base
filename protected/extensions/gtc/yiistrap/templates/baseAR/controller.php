<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends WebBaseController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		$this->render('view<?php echo $this->modelClass; ?>',array(
			'model'=>$this->loadModel('<?php echo $this->modelClass; ?>'),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;

		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass); ?>-form');

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if ($model->validate()){
				// More customization goes here
				
				if($model->save())
<?php if (is_array($this->getTableSchema()->primaryKey)) { ?>				
					$this->redirect(array('view',$model->primaryKey));
<?php } else { ?>					
					$this->redirect(array('view','id'=>$model->primaryKey));
<?php } ?>					
			}
		}

		$this->render('create<?php echo $this->modelClass; ?>',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel('<?php echo $this->modelClass; ?>');

		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass); ?>-form');

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->validate()){
				// More customization goes here
				if ($model->save())
<?php if (is_array($this->getTableSchema()->primaryKey)) { ?>				
					$this->redirect(array('view',$model->primaryKey));
<?php } else { ?>					
					$this->redirect(array('view','id'=>$model->primaryKey));
<?php } ?>					
			}
		}

		$this->render('update<?php echo $this->modelClass; ?>',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel('<?php echo $this->modelClass; ?>')->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$this->render('index<?php echo $this->modelClass; ?>',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

		$this->render('admin<?php echo $this->modelClass; ?>',array(
			'model'=>$model,
		));
	}
}