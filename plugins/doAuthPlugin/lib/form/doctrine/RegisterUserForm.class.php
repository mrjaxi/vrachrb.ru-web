<?php

class RegisterUserForm extends BaseRegisterUserForm 
{
  /**
   *
   */
  public function configure()
  {
    $this->widgetSchema['repeat_password'] = new sfWidgetFormInputPassword(array(), array('required' => 'required'));

    $this->useFields(array('username', 'first_name', 'second_name', 'middle_name', 'gender', 'birth_date', 'email', 'phone', 'photo', 'password', 'repeat_password'));

    $this->widgetSchema['username'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array('label' => 'Эл. почта'), array('size' => '50'));
    $this->widgetSchema['birth_date'] = new sfWidgetFormDate(array('label' => 'Дата рождения'));
    $this->widgetSchema['first_name'] = new sfWidgetFormInputText(array(), array('required' => 'required', 'data-xp' => 'required: true'));
    $this->widgetSchema['second_name'] = new sfWidgetFormInputText(array(), array());
    $this->widgetSchema['middle_name'] = new sfWidgetFormInputText(array(), array());
    $this->widgetSchema['birth_date'] = new sfWidgetFormDate(array(), array('data-xp' => 'required: true'));
    $this->widgetSchema['phone'] = new sfWidgetFormInputText(array(), array('data-xp' => "type:'phone', required: false"));
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array(), array('data-xp' => "type:'email', required: true"));
    $this->widgetSchema['gender'] = new sfWidgetFormChoice(array(
      'choices' => array('м', 'ж'),
      'expanded' => false,
    ));


//    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(), array('required' => 'true'));



    $obj = $this->getObject();
    if(!$obj->isNew())
    {
      //$this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('required' => 'required', 'label' => 'Пароль', 'empty' => true), array('size' => '50', 'placeholder' => 'Новый пароль'));
    }
    else
    {
      $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('label' => 'Пароль'), array('size' => '50'));
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
    $this->validatorSchema['password'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['repeat_password'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['birth_date'] = new sfValidatorDate(array('max' => time()), array('max' => 'Введите правильную дату'));

    //$request = sfContext::getInstance()->getRequest();
    //$this->widgetSchema->setHelp('email', '<span class="fc">Создать ящик@' . $request->getHost() . '</span>');
    
//    $this->widgetSchema->setHelp('password', '<span class="fc" onclick="setPassword();">Сгенерировать</span>');
    sfWidgetFormSchema::setDefaultFormFormatterName('inline');
    
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('password', '==', 'repeat_password'),
      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'email')),
      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'username')),
    )));
    
  }
}

?>