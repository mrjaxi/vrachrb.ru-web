<?php

/**
 * Seo filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSeoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title_h'         => new sfWidgetFormFilterInput(),
      'title_tag'       => new sfWidgetFormFilterInput(),
      'description_tag' => new sfWidgetFormFilterInput(),
      'url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'title_h'         => new sfValidatorPass(array('required' => false)),
      'title_tag'       => new sfValidatorPass(array('required' => false)),
      'description_tag' => new sfValidatorPass(array('required' => false)),
      'url'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('seo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Seo';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'title_h'         => 'Text',
      'title_tag'       => 'Text',
      'description_tag' => 'Text',
      'url'             => 'Text',
    );
  }
}
