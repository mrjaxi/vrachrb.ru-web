<?php

/**
 * Message_error form base class.
 *
 * @method Message_error getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMessage_errorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(array(), array()),
      'body'       => new sfWidgetFormInputText(array(), array("size" => 65, "required" => true)),
      'photo'      => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true), array()),
      'status'     => new sfWidgetFormChoice(array('choices' => array('no_answer' => 'no_answer', 'detail' => 'detail', 'in_work' => 'in_work', 'completed' => 'completed', 'defect' => 'defect')), array()),
      'created_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'body'       => new sfValidatorPass(),
      'photo'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'status'     => new sfValidatorChoice(array('choices' => array(0 => 'no_answer', 1 => 'detail', 2 => 'in_work', 3 => 'completed', 4 => 'defect'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('message_error[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Message_error';
  }

}
