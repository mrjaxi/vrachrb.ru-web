<?php
/**
 * Listener for doAuthActions, recives notifies and sends emails
 *
 * @author Davert
 */
class doAuthMailer {

  public static function sendActivation(sfEvent $event) {

    $controller = $event->getSubject();
    $user = $controller->user;

    // already activated
    if ($user->getIsActive()) return;

    // save password to send in register email
    $controller->getUser()->setAttribute('user_password',$event['password'],'doPreUser');

    $activation = new UserActivationCode();
    $activation->setUserId($user->getId());
    $activation->setCode(doAuthTools::activationCode($user));
    $activation->save();

    $subject = 'Активация аккаунта';

    // message should be sent immediately 
    $controller->getMailer()->composeAndSend(
      sfConfig::get('app_doAuth_email_from','do-not-reply@'.$controller->getRequest()->getHost()),
      array($user->getEmail()),
      $subject,
      $controller->getPartial(sfConfig::get('app_doAuth_email_module',$controller->getModuleName()).'/mail_activation', array('code'=> $activation->getCode())),'text/plain');
  }

  public static function sendRegistration(sfEvent $event) {

    $controller = $event->getSubject();
    $user = $controller->user;
    $password = $event->offsetExists('password') ? $event['password'] : $controller->getUser()->getAttribute('user_password',null,'doPreUser');

    $subject = 'Спасибо за регистрацию';

    // message should be sent immediately
    $controller->getMailer()->composeAndSend(
      sfConfig::get('app_doAuth_email_from','do-not-reply@'.$controller->getRequest()->getHost()),
      array($user->getEmail()),
      $subject,
      $controller->getPartial(sfConfig::get('app_doAuth_email_module',$controller->getModuleName()).'/mail_registration', array('user'=> $controller->user, 'password'=> $password)),'text/plain');
  }

  public static function sendPasswordRequest($controller, User $user) {

    $subject = 'Сброс пароля';

    $code = doAuthTools::passwordResetCode($user);

    $controller->getMailer()->composeAndSend(
      sfConfig::get('app_doAuth_email_from','do-not-reply@'.$controller->getRequest()->getHost()),
      array($user->getEmail()),
      $subject,
      $controller->getPartial(sfConfig::get('app_doAuth_email_module',$controller->getModuleName()).'/mail_reset_password', array('user'=> $user, 'code'=> $code)),'text/plain');
  }

  public static function sendNewPassword($controller, User $user, $password) {

    $subject = 'Новый пароль';

    $controller->getMailer()->composeAndSend(
      sfConfig::get('app_doAuth_email_from','do-not-reply@'.$controller->getRequest()->getHost()),
      array($user->getEmail() ),
      $subject,
      $controller->getPartial(sfConfig::get('app_doAuth_email_module',$controller->getModuleName()).'/mail_new_password', array('user'=> $user, 'password'=> $password)),'text/plain');
  }
  
}
?>
