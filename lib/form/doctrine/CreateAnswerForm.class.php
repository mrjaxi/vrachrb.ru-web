<?php
class CreateAnswerForm extends BaseAnswerForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'question_id', 'body', 'type', 'attachment', 'created_at'));

    unset($this['id'], $this['created_at']);

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'placeholder' => 'Ваше сообщение', 'style' => 'width:100%;min-height:100px;resize:vertical', 'onclick' => 'dialogCheckTextArea(this,\'click\');'));
    $this->widgetSchema['type'] = new sfWidgetFormInputText(array(), array('required' => false));
    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->widgetSchema['attachment'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg,application/x-download,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'script' => '/uploader?key=attachment', 'multiple' => true), array('required' => false));
  }
}
