<?php

/**
 * Specialist_work_place filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialist_work_placeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specialist_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => true)),
      'title'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hidden'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'reception_contract_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Reception_contract')),
    ));

    $this->setValidators(array(
      'specialist_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Specialist'), 'column' => 'id')),
      'title'                   => new sfValidatorPass(array('required' => false)),
      'hidden'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'reception_contract_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Reception_contract', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialist_work_place_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addReceptionContractListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.ReceiveLocation ReceiveLocation')
          ->andWhereIn('ReceiveLocation.reception_id', $values);
  }

  public function getModelName()
  {
    return 'Specialist_work_place';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'specialist_id'           => 'ForeignKey',
      'title'                   => 'Text',
      'hidden'                  => 'Boolean',
      'reception_contract_list' => 'ManyKey',
    );
  }
}
