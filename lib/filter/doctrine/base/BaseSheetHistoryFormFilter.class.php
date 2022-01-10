<?php

/**
 * SheetHistory filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSheetHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'specialtys_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialty')),
    ));

    $this->setValidators(array(
      'title'           => new sfValidatorPass(array('required' => false)),
      'specialtys_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialty', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sheet_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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

    $query->leftJoin('r.SheetHistorySpecialty SheetHistorySpecialty')
          ->andWhereIn('SheetHistorySpecialty.specialty_id', $values);
  }

  public function getModelName()
  {
    return 'SheetHistory';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'title'           => 'Text',
      'specialtys_list' => 'ManyKey',
    );
  }
}
