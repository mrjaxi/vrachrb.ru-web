<?php

/**
 * Specialty filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialtyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'        => new sfWidgetFormFilterInput(),
      'questions_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Question')),
      'sheet_history_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SheetHistory')),
    ));

    $this->setValidators(array(
      'title'              => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'questions_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Question', 'required' => false)),
      'sheet_history_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SheetHistory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialty_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addQuestionsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.QuestionSpecialty QuestionSpecialty')
          ->andWhereIn('QuestionSpecialty.question_id', $values);
  }

  public function addSheetHistoryListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.SheetHistorySpecialty SheetHistorySpecialty')
          ->andWhereIn('SheetHistorySpecialty.sheet_history_id', $values);
  }

  public function getModelName()
  {
    return 'Specialty';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'title'              => 'Text',
      'description'        => 'Text',
      'questions_list'     => 'ManyKey',
      'sheet_history_list' => 'ManyKey',
    );
  }
}
