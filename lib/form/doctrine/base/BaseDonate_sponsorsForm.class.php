<?php

/**
 * Donate_sponsors form base class.
 *
 * @method Donate_sponsors getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDonate_sponsorsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(array(), array()),
      'first_name'  => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'second_name' => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'middle_name' => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'amount'      => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
      'sender'      => new sfWidgetFormInputText(array(), array("size" => 40, "maxlength" => 40, "required" => true)),
      'anonymous'   => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'json'        => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
      'created_at'  => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'  => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'first_name'  => new sfValidatorString(array('max_length' => 255)),
      'second_name' => new sfValidatorString(array('max_length' => 255)),
      'middle_name' => new sfValidatorString(array('max_length' => 255)),
      'amount'      => new sfValidatorInteger(),
      'sender'      => new sfValidatorPass(),
      'anonymous'   => new sfValidatorBoolean(array('required' => false)),
      'json'        => new sfValidatorString(array('max_length' => 10000)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('donate_sponsors[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Donate_sponsors';
  }

}
