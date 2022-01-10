<?php

/**
 * Message_error form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateMessage_errorForm extends BaseMessage_errorForm
{
  public function configure()
  {
    $this->useFields(array('body', 'user_id'));
    unset($this['id']);

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => '2', 'placeholder' => 'Ошибка или пожелание', 'required' => true));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=message_error', 'multiple' => false, 'customInputFile' => true), array('required' => false));

    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['photo'] = new sfValidatorString(array('required' => false), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
