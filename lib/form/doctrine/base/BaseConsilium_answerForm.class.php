<?php

/**
 * Consilium_answer form base class.
 *
 * @method Consilium_answer getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseConsilium_answerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(array(), array()),
      'consilium_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Consilium'), 'add_empty' => false), array("required" => true)),
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => false), array("required" => true)),
      'body'          => new sfWidgetFormInputText(array(), array("size" => 65, "required" => true)),
      'created_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'consilium_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Consilium'))),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'))),
      'body'          => new sfValidatorPass(),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('consilium_answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Consilium_answer';
  }

}
