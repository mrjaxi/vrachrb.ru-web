<?php

/**
 * Consilium form base class.
 *
 * @method Consilium getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseConsiliumForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'question_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Question'), 'add_empty' => false), array("required" => true)),
      'closed'           => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'closing_date'     => new sfWidgetFormDateTime(array(), array()),
      'created_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
      'specialists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'question_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Question'))),
      'closed'           => new sfValidatorBoolean(array('required' => false)),
      'closing_date'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'specialists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('consilium[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Consilium';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['specialists_list']))
    {
      $this->setDefault('specialists_list', $this->object->Specialists->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSpecialistsList($con);

    parent::doSave($con);
  }

  public function saveSpecialistsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['specialists_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Specialists->getPrimaryKeys();
    $values = $this->getValue('specialists_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Specialists', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Specialists', array_values($link));
    }
  }

}
