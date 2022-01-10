<?php

/**
 * Certificate form base class.
 *
 * @method Certificate getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCertificateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => true), array()),
      'name'          => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'photo'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 255)),
      'photo'         => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('certificate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Certificate';
  }

}
