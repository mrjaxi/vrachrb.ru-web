<?php

/**
 * Seo form base class.
 *
 * @method Seo getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSeoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'title_h'         => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'title_tag'       => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'description_tag' => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'url'             => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title_h'         => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'title_tag'       => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'description_tag' => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'url'             => new sfValidatorString(array('max_length' => 1000)),
    ));

    $this->widgetSchema->setNameFormat('seo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Seo';
  }

}
