<?php

/**
 * Receive_datetime form base class.
 *
 * @method Receive_datetime getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReceive_datetimeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(array(), array()),
      'reception_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reception_contract'), 'add_empty' => false), array("required" => true)),
      'datetime'     => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'reception_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Reception_contract'))),
      'datetime'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('receive_datetime[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Receive_datetime';
  }

}
