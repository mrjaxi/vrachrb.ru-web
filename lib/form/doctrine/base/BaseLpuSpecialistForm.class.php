<?php

/**
 * LpuSpecialist form base class.
 *
 * @method LpuSpecialist getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLpuSpecialistForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lpu_id'        => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id' => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'lpu_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'lpu_id', 'required' => false)),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'specialist_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lpu_specialist[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'LpuSpecialist';
  }

}
