<?php

/**
 * csSetting form base class.
 *
 * @method csSetting getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasecsSettingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'name'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'type'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'widget_options'  => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'value'           => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'setting_group'   => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'setting_default' => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'slug'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 255)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'widget_options'  => new sfValidatorString(array('required' => false)),
      'value'           => new sfValidatorString(array('required' => false)),
      'setting_group'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'setting_default' => new sfValidatorString(array('required' => false)),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'csSetting', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'csSetting', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('cs_setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'csSetting';
  }

}
