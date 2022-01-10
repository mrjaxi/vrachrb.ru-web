<?php

/**
 * QuestionSheetHistory form base class.
 *
 * @method QuestionSheetHistory getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuestionSheetHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(array(), array()),
      'question_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => false), array("required" => true)),
      'sheet_history_field_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistoryField'), 'add_empty' => false), array("required" => true)),
      'value'                  => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'question_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Question'))),
      'sheet_history_field_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistoryField'))),
      'value'                  => new sfValidatorString(array('max_length' => 100000)),
    ));

    $this->widgetSchema->setNameFormat('question_sheet_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuestionSheetHistory';
  }

}
