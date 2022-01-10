<?php

/**
 * QuestionSheetHistory filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuestionSheetHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'question_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => true)),
      'sheet_history_field_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistoryField'), 'add_empty' => true)),
      'value'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'question_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Question'), 'column' => 'id')),
      'sheet_history_field_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SheetHistoryField'), 'column' => 'id')),
      'value'                  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('question_sheet_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuestionSheetHistory';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'question_id'            => 'ForeignKey',
      'sheet_history_field_id' => 'ForeignKey',
      'value'                  => 'Text',
    );
  }
}
