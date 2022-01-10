<?php

class ResetPasswordForm extends BaseResetPasswordForm
{
  public function configure()
  {
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array('label' => 'Эл. почта'), array('style' => 'width:233px'));
    $this->validatorSchema['email'] = new sfValidatorEmail(array(), array('required' => 'Обязательное поле', 'invalid' => 'Неверный формат'));
  }
}
