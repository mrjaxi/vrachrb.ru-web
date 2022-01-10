<?php

/**
 * Prompt form base class.
 *
 * @method Prompt getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePromptForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => false), array("required" => true)),
      'specialty_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialty'), 'add_empty' => false), array("required" => true)),
      'title'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'title_url'     => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
      'photo'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'video'         => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'description'   => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'body'          => new sfWidgetFormInputText(array(), array("size" => 65)),
      'created_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'))),
      'specialty_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialty'))),
      'title'         => new sfValidatorString(array('max_length' => 255)),
      'title_url'     => new sfValidatorString(array('max_length' => 1000)),
      'photo'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'video'         => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'description'   => new sfValidatorString(array('max_length' => 255)),
      'body'          => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('prompt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Prompt';
  }

}
