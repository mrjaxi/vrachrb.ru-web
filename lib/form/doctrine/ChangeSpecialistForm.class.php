<?php
class ChangeSpecialistForm extends BaseSpecialistForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'specialty_id', 'rating', 'answers_count', 'about', 'education', 'certificate', 'live_reception'));

    $this->widgetSchema['about'] = new sfWidgetFormTextarea(array(), array('style' => 'width:100%;resize:vertical;min-height:100px;', 'rows' => 5));
    $this->widgetSchema['education'] = new sfWidgetFormTextarea(array(), array('style' => 'width:100%;resize:vertical;min-height:200px;', 'rows' => 5, 'class' => 'rich'));
    $this->widgetSchema['certificate'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=certificate', 'multiple' => true,'with_text' => true, 'customInputFile' => true), array('required' => true));

    $this->validatorSchema['about'] = new sfValidatorString(array('required' => false), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['education'] = new sfValidatorString(array('required' => false), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['live_reception'] = new sfValidatorBoolean(array('required' => false), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}

?>