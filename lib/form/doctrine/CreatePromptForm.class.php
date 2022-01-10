<?php

/**
 * Prompt form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreatePromptForm extends BasePromptForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'specialty_id', 'title', 'photo', 'description', 'body'));

    $this->widgetSchema['specialist_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['title'] = new sfWidgetFormInputText(array(), array('required' => true, 'style' => 'width:100%;'));
    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array('required' => true, 'rows' => '3', 'style' => 'resize:vertical;width:100%;'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 40, 'rows' => 5, 'class' => 'rich'));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=prompt', 'multiple' => false), array('required' => true));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorDoctrineUnique(array('model'=> 'Prompt','column'=> 'title')),
    )));
    $this->validatorSchema['title'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['description'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
