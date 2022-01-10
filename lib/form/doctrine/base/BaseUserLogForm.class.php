<?php

/**
 * UserLog form base class.
 *
 * @method UserLog getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(array(), array()),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'log_type'   => new sfWidgetFormInputText(array(), array("size" => 50, "maxlength" => 50, "required" => true)),
      'model'      => new sfWidgetFormInputText(array(), array("size" => 50, "maxlength" => 50, "required" => true)),
      'model_id'   => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'log'        => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'created_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at' => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'log_type'   => new sfValidatorString(array('max_length' => 50)),
      'model'      => new sfValidatorString(array('max_length' => 50)),
      'model_id'   => new sfValidatorInteger(),
      'log'        => new sfValidatorString(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('user_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserLog';
  }

}
