<?php

/**
 * ConsiliumSpecialist form base class.
 *
 * @method ConsiliumSpecialist getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseConsiliumSpecialistForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'consilium_id'  => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id' => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'consilium_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'consilium_id', 'required' => false)),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'specialist_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('consilium_specialist[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ConsiliumSpecialist';
  }

}
