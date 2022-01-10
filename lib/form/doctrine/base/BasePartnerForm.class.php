<?php

/**
 * Partner form base class.
 *
 * @method Partner getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePartnerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(array(), array()),
      'title'       => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'logo'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'body'        => new sfWidgetFormInputText(array(), array("size" => 65)),
      'link'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'order_field' => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8, "required" => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255)),
      'logo'        => new sfValidatorString(array('max_length' => 255)),
      'body'        => new sfValidatorPass(array('required' => false)),
      'link'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order_field' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('partner[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Partner';
  }

}
