<?php

/**
 * Answer form base class.
 *
 * @method Answer getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAnswerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(array(), array()),
      'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'question_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => false), array("required" => true)),
      'body'        => new sfWidgetFormInputText(array(), array("size" => 65, "required" => true)),
      'type'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'comment_id'  => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'attachment'  => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'created_at'  => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'  => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'question_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Question'))),
      'body'        => new sfValidatorPass(),
      'type'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'comment_id'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'attachment'  => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Answer';
  }

}
