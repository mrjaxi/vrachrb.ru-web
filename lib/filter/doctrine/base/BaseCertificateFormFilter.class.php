<?php

/**
 * Certificate filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCertificateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => true)),
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'photo'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'specialist_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Specialist'), 'column' => 'id')),
      'name'          => new sfValidatorPass(array('required' => false)),
      'photo'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('certificate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Certificate';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'specialist_id' => 'ForeignKey',
      'name'          => 'Text',
      'photo'         => 'Text',
    );
  }
}
