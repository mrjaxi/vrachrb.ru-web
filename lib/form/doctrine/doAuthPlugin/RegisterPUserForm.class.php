<?php

class RegisterPUserForm extends BaseRegisterUserForm
{
  public function configure()
  {
    $this->useFields(array('email', 'password'));
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array('label' => 'Эл. почта'), array('size' => '50'));
    
    $obj = $this->getObject();
    if(!$obj->isNew())
    {
      $this->widgetSchema['password'] = new sfWidgetFormInputText(array('label' => 'Пароль', 'empty' => true), array('size' => '50', 'placeholder' => 'Новый пароль'));
    }
    else
    {
      $this->widgetSchema['password'] = new sfWidgetFormInputText(array('label' => 'Пароль'), array('size' => '50'));
    }
    /*
    $this->widgetSchema['captcha_img'] = new sfWidgetFormInputFileEditable(array(
      'label' => '&nbsp;',
      'file_src'  => '',
      'file_url'  => '',
      'file_id'  => 'null',
      'is_image'  => true,
      'edit_mode' => true,
      'template'  => '<img style="cursor:pointer" onclick="this.src=\'/captcha?r=\' + Math.random() + \'&amp;reload=1\';$(\'user_captcha\').set(\'value\', \'\');$(\'user_captcha\').focus()" src="/captcha?r=' . mt_rand(10000, 99999) . '" width="' . sfConfig::get('app_sf_captchagd_image_width')  . '" height="' . sfConfig::get('app_sf_captchagd_image_height') . '" />',
    ));
    $this->widgetSchema['captcha'] = new sfWidgetFormInputText(array('label' => 'Код'), array('size' => '5', 'class' => 'minLength:5', 'maxlength' => '5', 'autocomplete' => 'off'));
    $this->validatorSchema['captcha'] = new sfCaptchaGDValidator(array('length' => 5), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный код.'));
    */
    $this->validatorSchema['email'] = new sfValidatorEmail(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    
    //$request = sfContext::getInstance()->getRequest();
    //$this->widgetSchema->setHelp('email', '<span class="fc">Создать ящик@' . $request->getHost() . '</span>');
    
    $this->widgetSchema->setHelp('password', '<span class="fc" onclick="setPassword();">Сгенерировать</span>');
    sfWidgetFormSchema::setDefaultFormFormatterName('inline');
  }
}

?>