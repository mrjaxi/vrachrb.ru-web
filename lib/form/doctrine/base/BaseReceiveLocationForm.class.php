<?php

/**
 * ReceiveLocation form base class.
 *
 * @method ReceiveLocation getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReceiveLocationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'reception_id'  => new sfWidgetFormInputHidden(array(), array()),
      'work_place_id' => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'reception_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'reception_id', 'required' => false)),
      'work_place_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'work_place_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('receive_location[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReceiveLocation';
  }

}
