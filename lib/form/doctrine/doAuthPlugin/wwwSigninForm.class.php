<?php

class wwwSigninForm extends BaseSigninForm
{
  public function configure()
  {
    $this->disableLocalCSRFProtection();
    $this->useFields(array('username', 'password'));
    $this->widgetSchema['username'] = new sfWidgetFormInputText(array('label' => '&nbsp;'), array('size' => 24, 'placeholder' => 'Логин', 'required' => true, 'autocomplete' => 'off', 'autofocus' => 'on'));
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('label' => '&nbsp;'), array('size' => 24, 'placeholder' => 'Пароль', 'required' => true, 'autocomplete' => 'off'));
  }
}
