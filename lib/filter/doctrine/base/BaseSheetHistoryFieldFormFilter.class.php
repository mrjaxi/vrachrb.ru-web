<?php

/**
 * SheetHistoryField filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSheetHistoryFieldFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sheet_history_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SheetHistory'), 'add_empty' => true)),
      'title'            => new sfWidgetFormFilterInput(),
      'field_type'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'field_options'    => new sfWidgetFormFilterInput(),
      'order_field'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_required'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'sheet_history_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SheetHistory'), 'column' => 'id')),
      'title'            => new sfValidatorPass(array('required' => false)),
      'field_type'       => new sfValidatorPass(array('required' => false)),
      'field_options'    => new sfValidatorPass(array('required' => false)),
      'order_field'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_required'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('sheet_history_field_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SheetHistoryField';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'sheet_history_id' => 'ForeignKey',
      'title'            => 'Text',
      'field_type'       => 'Text',
      'field_options'    => 'Text',
      'order_field'      => 'Number',
      'is_required'      => 'Boolean',
    );
  }
}
