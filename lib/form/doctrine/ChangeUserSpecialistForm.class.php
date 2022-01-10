<?php
class ChangeUserSpecialistForm extends BaseRegisterUserForm
{
  public function configure()
  {
    $this->useFields(array('first_name', 'second_name', 'middle_name', 'email', 'photo'));

    $this->widgetSchema['first_name'] = new sfWidgetFormInputText(array(), array('required' => 'required', 'data-xp' => 'required: true'));
    $this->widgetSchema['second_name'] = new sfWidgetFormInputText(array(), array());
    $this->widgetSchema['middle_name'] = new sfWidgetFormInputText(array(), array());
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array(), array('required' => true));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=user', 'multiple' => false, 'customInputFile' => true), array('required' => true));

    $this->validatorSchema['first_name'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['email'] = new sfValidatorEmail(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}

?>