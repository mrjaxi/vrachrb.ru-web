<?php

/**
 * Question filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuestionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'user_about_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserAbout'), 'add_empty' => true)),
      'body'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_anonymous'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'approved'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'comment_id'       => new sfWidgetFormFilterInput(),
      'topic_id'         => new sfWidgetFormFilterInput(),
      'vk_notice'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'closed_by'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserClosed'), 'add_empty' => true)),
      'closing_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'specialists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist')),
      'specialtys_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialty')),
    ));

    $this->setValidators(array(
      'user_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'user_about_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserAbout'), 'column' => 'id')),
      'body'             => new sfValidatorPass(array('required' => false)),
      'is_anonymous'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'approved'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'comment_id'       => new sfValidatorPass(array('required' => false)),
      'topic_id'         => new sfValidatorPass(array('required' => false)),
      'vk_notice'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'closed_by'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserClosed'), 'column' => 'id')),
      'closing_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'specialists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'required' => false)),
      'specialtys_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialty', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('question_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addSpecialistsListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('QuestionSpecialist.specialist_id', $values);
  }

  public function addSpecialtysListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('QuestionSpecialty.specialty_id', $values);
  }

  public function getModelName()
  {
    return 'Question';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'user_id'          => 'ForeignKey',
      'user_about_id'    => 'ForeignKey',
      'body'             => 'Text',
      'is_anonymous'     => 'Boolean',
      'approved'         => 'Boolean',
      'comment_id'       => 'Text',
      'topic_id'         => 'Text',
      'vk_notice'        => 'Boolean',
      'closed_by'        => 'ForeignKey',
      'closing_date'     => 'Date',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'specialists_list' => 'ManyKey',
      'specialtys_list'  => 'ManyKey',
    );
  }
}
