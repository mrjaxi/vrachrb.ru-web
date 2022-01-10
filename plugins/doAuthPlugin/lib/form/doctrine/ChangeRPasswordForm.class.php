<?php
class ChangeRPasswordForm extends BaseRegisterUserForm 
{
  public function configure()
  {
    $this->useFields(array('password'));
    
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('label' => 'Пароль'), array('size' => 30, 'autocomplete' => 'off'));
    $this->widgetSchema['confirm_password'] = new sfWidgetFormInputPassword(array('label' => 'Подтвердите новый пароль'), array('size' => 30, 'autocomplete' => 'off'));
    
    $this->validatorSchema['password'] = new sfValidatorAnd(array(
      $this->validatorSchema['password'],
      new sfValidatorRegex(array('pattern' => '/[!@#$%^&*()+:;,.-_a-zA-Z0-9]{6,20}/'), array('invalid' => 'Слишком простой пароль'))
    ));
    $this->validatorSchema['confirm_password'] = new sfValidatorPass();
    $this->widgetSchema->setNameFormat('user_password[%s]');
    
  }
}
?>