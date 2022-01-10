<?php

/**
 * Lpu filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLpuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location'         => new sfWidgetFormFilterInput(),
      'token'            => new sfWidgetFormFilterInput(),
      'specialists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
      'specialist_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist')),
      'lpu_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
    ));

    $this->setValidators(array(
      'title'            => new sfValidatorPass(array('required' => false)),
      'location'         => new sfValidatorPass(array('required' => false)),
      'token'            => new sfValidatorPass(array('required' => false)),
      'specialists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
      'specialist_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'required' => false)),
      'lpu_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lpu_filters[%s]');

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

    $query->leftJoin('r.LpuSpecialist LpuSpecialist')
          ->andWhereIn('LpuSpecialist.specialist_id', $values);
  }

  public function addSpecialistListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('LpuSpecialist.specialist_id', $values);
  }

  public function addLpuListColumnQuery(Doctrine_Query $query, $field, $values)
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
    return 'Lpu';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'title'            => 'Text',
      'location'         => 'Text',
      'token'            => 'Text',
      'specialists_list' => 'ManyKey',
      'specialist_list'  => 'ManyKey',
      'lpu_list'         => 'ManyKey',
    );
  }
}
