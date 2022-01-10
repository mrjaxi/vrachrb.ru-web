<?php

/**
 * Review form base class.
 *
 * @method Review getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReviewForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(array(), array()),
      'question_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => false), array("required" => true)),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => false), array("required" => true)),
      'body'          => new sfWidgetFormInputText(array(), array("size" => 65)),
      'informative'   => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'courtesy'      => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'approved'      => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'created_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'question_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Question'))),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'))),
      'body'          => new sfValidatorPass(array('required' => false)),
      'informative'   => new sfValidatorInteger(array('required' => false)),
      'courtesy'      => new sfValidatorInteger(array('required' => false)),
      'approved'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('review[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Review';
  }

}
