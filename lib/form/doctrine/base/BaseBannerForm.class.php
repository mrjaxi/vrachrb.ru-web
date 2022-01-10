<?php

/**
 * Banner form base class.
 *
 * @method Banner getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseBannerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(array(), array()),
      'title'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'link'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'photo'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'is_activated' => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'order_field'  => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 255)),
      'link'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'photo'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_activated' => new sfValidatorBoolean(array('required' => false)),
      'order_field'  => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('banner[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Banner';
  }

}
