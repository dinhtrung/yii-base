<?php

class SiteController extends WebBaseController
{
	function allowedActions(){
		return 'index, error, contact, captcha, page, language, previewMarkdown';
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
 		if (Yii::app()->getHomeUrl() && (Yii::app()->getHomeUrl() != '/site/index'))
 			$this->forward(Yii::app()->getHomeUrl());
		else
			$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$message = new YiiMailMessage;
				$message->setBody($model->body, 'text/html');
				$message->setSubject($model->subject);
				// Send the requested email to contactEmail setting.
				$message->setFrom(array($model->email => $model->name));
				$message->addTo(Yii::app()->setting->get('Webtheme', 'contactEmail', Yii::app()->params['adminEmail']));
				Yii::app()->mail->send($message);
				// Send a reply for notice the user
				$message->setFrom(array(Yii::app()->setting->get('Webtheme', 'serverEmail', Yii::app()->params['adminEmail']) => Yii::app()->setting->get('Webtheme', 'siteName', Yii::app()->name)));
				$message->addTo($model->email, $model->name);
				Yii::app()->mail->send($message);
				$model = new ContactForm();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	/**
	 * Set User language
	 */
	public function actionLanguage(){
		if (! isset($_GET['lang']) || (! in_array($_GET['lang'], Yii::app()->getLocale()->getLocaleIDs()))){
			throw new CHttpException(404, "The requested page is not found.");
		} else {
			Yii::app()->getUser()->setState("language", $_GET['lang']);
			$this->redirect(Yii::app()->getUser()->getReturnUrl());
		}
	}

	/**
	 * Markdown previewer
	 */
	public function actionPreviewMarkdown()
	{
		if (isset($_POST['data'])){
		    $parser=new CMarkdownParser;
		    $parsedText = $parser->safeTransform($_POST['data']);
		    echo $parsedText;
		}
	}
}