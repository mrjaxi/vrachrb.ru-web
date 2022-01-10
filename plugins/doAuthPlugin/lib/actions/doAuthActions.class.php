<?php

class doAuthActions extends sfActions 
{
  public function executeSignin($request) {
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      return $this->redirect('@homepage');
    }
    $this->part = $request->isXmlHttpRequest() ? '1' : '0';
    $this->form = new SigninForm();
    
    $this->preSignin($request);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('signin'), $request->getFiles('signin'));
      if ($this->form->isValid()) {
        $values = $this->form->getValues();
        $this->getUser()->signIn($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

        $this->postSignin($request);

        // always redirect to a URL set in app.yml
        // or to the referer
        // or to the homepage
        $signinUrl = sfConfig::get('app_doAuth_signin_url', $user->getReferer($request->getReferer()));
        if($request->isXmlHttpRequest())
        {
          die('refresh');
        }
        return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
      }
    }
    else
    {

      // if we have been forwarded, then the referer is the current URL
      // if not, this is the referer of the current request
      $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());

      $module = sfConfig::get('sf_login_module');
      if ($this->getModuleName() != $module) {
        $this->getLogger()->warning('User is accessing signin action which is currently not configured in settings.yml. Please secure this action or update configuration');
      }
    }
  }


  public function executeSignout($request) {
    $this->getUser()->signOut();

    $signoutUrl = sfConfig::get('app_doAuth_signout_url', $request->getReferer());

    //$this->redirect('/' . $this->getUser()->getCulture() . '/');
    $this->redirect('' != $signoutUrl ? $signoutUrl : '@homepage');
  }

  public function executeRegister(sfWebRequest $request) {
    
    $this->form = new RegisterUserForm();
    $this->part = $request->isXmlHttpRequest() ? '1' : '0';  
    $this->dispatcher->notify(new sfEvent($this, 'user.pre_register'));
    
    $this->preRegister($request);

    if ($request->isMethod('post')) {
      
      $this->form->bind($request->getParameter('user'),$request->getParameter('user'));
      if ($this->form->isValid()) {
        $this->form->save();
        $user = $this->form->getObject();
        $user->setPassword($this->form->getValue('password'));
        $user->save();

        $this->user = $user;

        $this->dispatcher->notify(new sfEvent($this, 'user.registered',array('password'=> $this->form->getValue('password'))));
        $this->postRegister($request);

        if (!sfConfig::get('app_doAuth_activation',false)) {
          $user->setIsActive(1);
          $user->save();
          $this->firstSignin();
        } else {
          $this->getUser()->setFlash('notice','Please check your email to finish registration process');
        }
        
        $this->setTemplate('registerok');
      }
    }
  }

  public function executeActivate(sfWebRequest $request) {

    $this->preActivate($request);

    $activation = Doctrine::getTable('UserActivationCode')->createQuery('a')->
      innerJoin('a.User u')->
      where('a.code = ?', $request->getParameter('code'))->fetchOne();

    $this->forward404Unless($activation,'wrong activation code used');

    $user = $activation->getUser();
    $user->setIsActive(1);
    $user->save();
    $activation->delete();

    $this->user = $user;

    $this->dispatcher->notify(new sfEvent($this, 'user.activated'));    
    $this->postActivate($request);

    $this->getUser()->getAttributeHolder()->removeNamespace('doUser');
    $this->firstSignin();

    $this->redirect(sfConfig::get('app_doAuth_register_redirect','@homepage'));
  }

  public function executeSecure($request) {
    $this->getResponse()->setStatusCode(403);
  }

  public function executeReset(sfWebRequest $request) {
    // i like how it is made in sfGuardUser: =)
    // throw new sfException('This method is not yet implemented.');
    
    if ($request->hasParameter('user')) {      
      $user = Doctrine::getTable('User')->findOneById(array($request->getParameter('user')));
      $this->forward404Unless($user);
      if ($request->getParameter('code') != doAuthTools::passwordResetCode($user)) {
        $this->getUser()->setFlash('error', 'Password reset code is invalid');
        $this->forward404();        
      }
      $password = doAuthTools::generatePassword();
      doAuthMailer::sendNewPassword($this,$user,$password);
      $user->setPassword($password);
      $user->save();
      $this->getUser()->setFlash('notice','We have sent a new password on your email');
      $this->redirect(sfConfig::get('app_doAuth_reset_password_url', '@homepage'));
    }

    $this->form = new ResetPasswordForm();
    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('reset_password'));
      if ($this->form->isValid()) {
        $user = Doctrine::getTable('User')->findOneByUsername(array($request->getParameter('email')));
        $this->forward404Unless($user);
        doAuthMailer::sendPasswordRequest($this, $user);
        //$this->getUser()->setFlash('notice','You have requested a new password. Please, check your email and follow the instructions.');
        //$this->redirect(sfConfig::get('app_doAuth_reset_password_url', '@homepage'));
      }
      die('<br><br>Письмо со ссылкой на сброс пароля отправлено на указанный почтовый ящик.');
    }   
  }
  public function executeChpass(sfWebRequest $request) {
    $this->ok = false;
    $this->message = false;
    if ($request->isMethod('post') && $request->getParameter('new_password')) {
      if($request->getParameter('new_password') == '')
      {
        $this->message = 'Пустой пароль не допустим.';
      }
      else
      {
        $user = Doctrine::getTable('User')->findOneById(array($this->getUser()->getUserId()));
        $this->forward404Unless($user);
        $user->setPassword($request->getParameter('new_password'));
        $user->save();
        $this->ok = true;
      }
    }
   
  }

  /**
   *
   * Automaticaly signs in current user after registration 
   */

  protected function firstSignin()
  {
    if (sfConfig::get('app_doAuth_register_signin',true)) {
      $this->getUser()->signIn($this->user);
      $this->getUser()->setFlash('notice','Congratulations! You are now registered.');
    } else {
      $this->getUser()->setFlash('notice','Congratulations! You are now registered. Please, sign in');
    }
  }

  // use this methods in your class to extend a functionality

  protected function preSignin(sfWebRequest $request) {}
  protected function postSignin(sfWebRequest $request) {}

  protected function preRegister(sfWebRequest $request) {}
  protected function postRegister(sfWebRequest $request) {}

  protected function preActivate(sfWebRequest $request) {}
  protected function postActivate(sfWebRequest $request) {}

}
