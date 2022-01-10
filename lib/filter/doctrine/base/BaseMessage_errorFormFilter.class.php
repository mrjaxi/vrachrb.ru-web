<?php

/**
 * Message_error filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMessage_errorFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'body'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'photo'      => new sfWidgetFormFilterInput(),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'status'     => new sfWidgetFormChoice(array('choices' => array('' => '', 'no_answer' => 'no_answer', 'detail' => 'detail', 'in_work' => 'in_work', 'completed' => 'completed', 'defect' => 'defect'))),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'body'       => new sfValidatorPass(array('required' => false)),
      'photo'      => new sfValidatorPass(array('required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'status'     => new sfValidatorChoice(array('required' => false, 'choices' => array('no_answer' => 'no_answer', 'detail' => 'detail', 'in_work' => 'in_work', 'completed' => 'completed', 'defect' => 'defect'))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('message_error_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Message_error';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'body'       => 'Text',
      'photo'      => 'Text',
      'user_id'    => 'ForeignKey',
      'status'     => 'Enum',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
