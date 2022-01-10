<?php

/**
 * Specialist filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialistFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'specialty_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialty'), 'add_empty' => true)),
      'title_url'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rating'          => new sfWidgetFormFilterInput(),
      'answers_count'   => new sfWidgetFormFilterInput(),
      'about'           => new sfWidgetFormFilterInput(),
      'education'       => new sfWidgetFormFilterInput(),
      'live_reception'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'certificate'     => new sfWidgetFormFilterInput(),
      'question_count'  => new sfWidgetFormFilterInput(),
      'prompt_count'    => new sfWidgetFormFilterInput(),
      'article_count'   => new sfWidgetFormFilterInput(),
      'questions_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Question')),
      'consiliums_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Consilium')),
      'lpus_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'specialty_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Specialty'), 'column' => 'id')),
      'title_url'       => new sfValidatorPass(array('required' => false)),
      'rating'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'answers_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'about'           => new sfValidatorPass(array('required' => false)),
      'education'       => new sfValidatorPass(array('required' => false)),
      'live_reception'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'certificate'     => new sfValidatorPass(array('required' => false)),
      'question_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'prompt_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'article_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'questions_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Question', 'required' => false)),
      'consiliums_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Consilium', 'required' => false)),
      'lpus_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialist_filters[%s]');

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

    $query->leftJoin('r.QuestionSpecialist QuestionSpecialist')
          ->andWhereIn('QuestionSpecialist.question_id', $values);
  }

  public function addConsiliumsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.ConsiliumSpecialist ConsiliumSpecialist')
          ->andWhereIn('ConsiliumSpecialist.consilium_id', $values);
  }

  public function addLpusListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.LpuSpecialist LpuSpecialist')
          ->andWhereIn('LpuSpecialist.lpu_id', $values);
  }

  public function getModelName()
  {
    return 'Specialist';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'user_id'         => 'ForeignKey',
      'specialty_id'    => 'ForeignKey',
      'title_url'       => 'Text',
      'rating'          => 'Number',
      'answers_count'   => 'Number',
      'about'           => 'Text',
      'education'       => 'Text',
      'live_reception'  => 'Boolean',
      'certificate'     => 'Text',
      'question_count'  => 'Number',
      'prompt_count'    => 'Number',
      'article_count'   => 'Number',
      'questions_list'  => 'ManyKey',
      'consiliums_list' => 'ManyKey',
      'lpus_list'       => 'ManyKey',
    );
  }
}
