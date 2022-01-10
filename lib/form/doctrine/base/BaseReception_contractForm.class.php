<?php

/**
 * Reception_contract form base class.
 *
 * @method Reception_contract getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReception_contractForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(array(), array()),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'specialist_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => false), array("required" => true)),
      'question_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => false), array("required" => true)),
      'price'         => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8)),
      'is_activated'  => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'is_reject'     => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'reject_reason' => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'created_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'    => new sfWidgetFormDateTime(array(), array("required" => true)),
      'location_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist_work_place')),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'))),
      'question_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Question'))),
      'price'         => new sfValidatorInteger(array('required' => false)),
      'is_activated'  => new sfValidatorBoolean(array('required' => false)),
      'is_reject'     => new sfValidatorBoolean(array('required' => false)),
      'reject_reason' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
      'location_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist_work_place', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('reception_contract[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Reception_contract';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['location_list']))
    {
      $this->setDefault('location_list', $this->object->Location->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveLocationList($con);

    parent::doSave($con);
  }

  public function saveLocationList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['location_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Location->getPrimaryKeys();
    $values = $this->getValue('location_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Location', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Location', array_values($link));
    }
  }

}
