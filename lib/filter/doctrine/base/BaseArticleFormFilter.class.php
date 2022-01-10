<?php

/**
 * Article filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => true)),
      'title'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title_url'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'photo'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'video'         => new sfWidgetFormFilterInput(),
      'description'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'body'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_activated'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'specialist_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Specialist'), 'column' => 'id')),
      'title'         => new sfValidatorPass(array('required' => false)),
      'title_url'     => new sfValidatorPass(array('required' => false)),
      'photo'         => new sfValidatorPass(array('required' => false)),
      'video'         => new sfValidatorPass(array('required' => false)),
      'description'   => new sfValidatorPass(array('required' => false)),
      'body'          => new sfValidatorPass(array('required' => false)),
      'is_activated'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Article';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'specialist_id' => 'ForeignKey',
      'title'         => 'Text',
      'title_url'     => 'Text',
      'photo'         => 'Text',
      'video'         => 'Text',
      'description'   => 'Text',
      'body'          => 'Text',
      'is_activated'  => 'Boolean',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
