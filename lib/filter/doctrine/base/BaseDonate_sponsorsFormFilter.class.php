<?php

/**
 * Donate_sponsors filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDonate_sponsorsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'first_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'second_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'middle_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sender'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'anonymous'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'json'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'first_name'  => new sfValidatorPass(array('required' => false)),
      'second_name' => new sfValidatorPass(array('required' => false)),
      'middle_name' => new sfValidatorPass(array('required' => false)),
      'amount'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sender'      => new sfValidatorPass(array('required' => false)),
      'anonymous'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'json'        => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('donate_sponsors_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Donate_sponsors';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'first_name'  => 'Text',
      'second_name' => 'Text',
      'middle_name' => 'Text',
      'amount'      => 'Number',
      'sender'      => 'Text',
      'anonymous'   => 'Boolean',
      'json'        => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
