<?php

/**
 * SheetHistoryField form base class.
 *
 * @method SheetHistoryField getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSheetHistoryFieldForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'sheet_history_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistory'), 'add_empty' => false), array("required" => true)),
      'title'            => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'field_type'       => new sfWidgetFormInputText(array(), array("size" => 50, "maxlength" => 50, "required" => true)),
      'field_options'    => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'order_field'      => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'is_required'      => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'sheet_history_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistory'))),
      'title'            => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'field_type'       => new sfValidatorString(array('max_length' => 50)),
      'field_options'    => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'order_field'      => new sfValidatorInteger(array('required' => false)),
      'is_required'      => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sheet_history_field[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SheetHistoryField';
  }

}
