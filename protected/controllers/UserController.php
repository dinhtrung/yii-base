<?php
class UserController extends WebBaseController {
	public function allowedActions(){
		return 'login,logout,recovery,activate,profile';
	}
	
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		if (Yii::app()->user->isGuest) $this->redirect(array('login'));
		$model = User::model()->findByPk(Yii::app()->user->id);
		if (isset($_POST['User'])){
			$model->attributes = $_POST['User'];
			if ($model->validate()){
				$model->password = CPasswordHelper::hashPassword($model->password);
				if ($model->save())
					Yii::app()->user->setFlashData('success', Yii::t('Successfully update your profile'));
			}
		}
		$this->render('profile',array(
				'model'=>$model,
		));
	}


	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$user = User::model()->findByPk(Yii::app()->user->id);
					$user->saveAttributes(array('updatetime' => time()));
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$this->render('loginForm',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->user->returnUrl);
	}

	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
			$this->redirect(Yii::app()->controller->module->returnUrl);
		} else {
			$email = ((isset($_GET['email']))?$_GET['email']:'');
			$activkey = ((isset($_GET['activkey']))?$_GET['activkey']:'');
			if ($email&&$activkey) {
				$form2 = new UserChangePassword;
				$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
				if(isset($find)&&$find->activkey==$activkey) {
					if(isset($_POST['UserChangePassword'])) {
						$form2->attributes=$_POST['UserChangePassword'];
						if($form2->validate()) {
							$find->password = Yii::app()->controller->module->encrypting($form2->password);
							$find->activkey=Yii::app()->controller->module->encrypting(microtime().$form2->password);
							if ($find->status==0) {
								$find->status = 1;
							}
							$find->save();
							Yii::app()->user->setFlash('recoveryMessage',Yii::t('user', "New password is saved."));
							$this->redirect(Yii::app()->controller->module->recoveryUrl);
						}
					}
					$this->render('changepassword',array('form'=>$form2));
				} else {
					Yii::app()->user->setFlash('recoveryMessage',Yii::t('user', "Incorrect recovery link."));
					$this->redirect(Yii::app()->controller->module->recoveryUrl);
				}
			} else {
				if(isset($_POST['UserRecoveryForm'])) {
					$form->attributes=$_POST['UserRecoveryForm'];
					if($form->validate()) {
						$user = User::model()->notsafe()->findbyPk($form->user_id);
						$activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl(implode(Yii::app()->controller->module->recoveryUrl),array("activkey" => $user->activkey, "email" => $user->email));
							
						$subject = Yii::t('user', "You have requested the password recovery site {site_name}",
								array(
										'{site_name}'=>Yii::app()->name,
								));
						$message = Yii::t('user', "You have requested the password recovery site {site_name}. To receive a new password, go to {activation_url}.",
								array(
										'{site_name}'=>Yii::app()->name,
										'{activation_url}'=>$activation_url,
								));
							
						UserModule::sendMail($user->email,$subject,$message);

						Yii::app()->user->setFlash('recoveryMessage',Yii::t('user', "Please check your email. An instructions was sent to your email address."));
						$this->refresh();
					}
				}
				$this->render('recovery',array('form'=>$form));
			}
		}
	}

	/**
	 * Registration user
	 */
	public function actionRegistration() {
		$model = new UserRegistrationForm;
		$this->performAjaxValidation($model, 'registration-form');
		
		// Redirect the user to profile page if he logged in...
		if (Yii::app()->user->id) {
			$this->redirect(array('profile'));
		} else {
			if(isset($_POST['UserRegistrationForm'])) {
				$model->attributes=$_POST['UserRegistrationForm'];
				if($model->validate())
				{
					// @FIXME: Re-write this
					$soucePassword = $model->password;
					$model->activkey=CPasswordHelper::hashPassword(microtime().$model->password);
					$model->password=CPasswordHelper::hashPassword($model->password);
					$model->verifyPassword=CPasswordHelper::hashPassword($model->verifyPassword);
					$model->createtime=time();
					$model->updatetime=((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin)?time():0;
					$model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);

					if ($model->save()) {
						// Send Activation Mail 
						if (Yii::app()->controller->module->sendActivationMail) {
							$activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
							Yii::import('ext.mail.*');
							$message = new YiiMailMessage;
							$message->setBody(Yii::t('user', "Please activate you account go to {activation_url}",array('{activation_url}'=>$activation_url)), 'text/plain');
							$message->setSubject(Yii::t('user', "You registered from {site_name}",array('{site_name}'=>Yii::app()->name)));
							// Send a reply for notice the user
							$message->setFrom(array(Yii::app()->setting->get('Webtheme', 'serverEmail', Yii::app()->params['adminEmail']) => Yii::app()->setting->get('Webtheme', 'siteName', Yii::app()->name)));
							$message->addTo($model->email, $model->name);
							Yii::app()->mail->send($message);
						}

						if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
							$identity=new UserIdentity($model->username,$soucePassword);
							$identity->authenticate();
							Yii::app()->user->login($identity,0);
							$this->redirect(Yii::app()->controller->module->returnUrl);
						} else {
							if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
								Yii::app()->user->setFlash('success',Yii::t('user', "Thank you for your registration. Contact Admin to activate your account."));
							} elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
								Yii::app()->user->setFlash('success',Yii::t('user', "Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(Yii::t('user', 'Login'),Yii::app()->controller->module->loginUrl))));
							} elseif(Yii::app()->controller->module->loginNotActiv) {
								Yii::app()->user->setFlash('success',Yii::t('user', "Thank you for your registration. Please check your email or login."));
							} else {
								Yii::app()->user->setFlash('success',Yii::t('user', "Thank you for your registration. Please check your email."));
							}
							$this->refresh();
						}
					}
				}
			}
			$this->render('registration',array('model'=>$model));
		}
	}

	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {
				$this->render('message',array('title'=>Yii::t('user', "User activation"),'content'=>Yii::t('user', "You account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = CPasswordHelper::hashPassword(microtime());
				$find->status = 1;
				$find->save();
				$this->render('message',array('title'=>Yii::t('user', "User activation"),'content'=>Yii::t('user', "You account is activated.")));
			} else {
				$this->render('message',array('title'=>Yii::t('user', "User activation"),'content'=>Yii::t('user', "Incorrect activation URL.")));
			}
		} else {
			$this->render('message',array('title'=>Yii::t('user', "User activation"),'content'=>Yii::t('user', "Incorrect activation URL.")));
		}
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;
		if (Yii::app()->user->id) {
			
			$this->performAjaxValidation($model, 'changepassword-form');
	
			if(isset($_POST['UserChangePassword'])) {
				$model->attributes=$_POST['UserChangePassword'];
				if($model->validate()) {
					$new_password = User::model()->findbyPk(Yii::app()->user->id);
					$new_password->password = CPasswordHelper::hashPassword($model->password);
					$new_password->activkey = CPasswordHelper::hashPassword( microtime().$model->password );
					if ($new_password->save()){
						Yii::app()->user->setFlash('success', Yii::t('user', 'Successfully change your password'));
						$this->redirect(array("profile"));
					}
				}
			}
			$this->render('changepassword',array('model'=>$model));
		}
	}
}
