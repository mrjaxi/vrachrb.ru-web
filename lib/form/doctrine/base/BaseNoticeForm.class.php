<?php

/**
 * Notice form base class.
 *
 * @method Notice getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(array(), array()),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'type'       => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'inner_id'   => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'event'      => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'created_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'type'       => new sfValidatorString(array('max_length' => 255)),
      'inner_id'   => new sfValidatorInteger(),
      'event'      => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Notice';
  }

}
