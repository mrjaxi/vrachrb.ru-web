<?php

/**
 * SheetHistorySpecialty form base class.
 *
 * @method SheetHistorySpecialty getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSheetHistorySpecialtyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sheet_history_id' => new sfWidgetFormInputHidden(array(), array()),
      'specialty_id'     => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'sheet_history_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'sheet_history_id', 'required' => false)),
      'specialty_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'specialty_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sheet_history_specialty[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SheetHistorySpecialty';
  }

}
