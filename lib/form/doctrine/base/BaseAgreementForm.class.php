<?php

/**
 * Agreement form base class.
 *
 * @method Agreement getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAgreementForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'title'            => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
      'description'      => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
      'body'             => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'file'             => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'in_documentation' => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'created_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 1000)),
      'description'      => new sfValidatorString(array('max_length' => 1000)),
      'body'             => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'file'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'in_documentation' => new sfValidatorBoolean(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('agreement[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Agreement';
  }

}
