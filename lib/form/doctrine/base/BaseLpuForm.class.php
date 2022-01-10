<?php

/**
 * Lpu form base class.
 *
 * @method Lpu getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLpuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'title'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'location'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'token'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'specialists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
      'specialist_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist')),
      'lpu_list'         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 255)),
      'location'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'token'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'specialists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
      'specialist_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'required' => false)),
      'lpu_list'         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lpu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lpu';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['specialists_list']))
    {
      $this->setDefault('specialists_list', $this->object->Specialists->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['specialist_list']))
    {
      $this->setDefault('specialist_list', $this->object->Specialist->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['lpu_list']))
    {
      $this->setDefault('lpu_list', $this->object->Lpu->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSpecialistsList($con);
    $this->saveSpecialistList($con);
    $this->saveLpuList($con);

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

  public function saveSpecialistList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['specialist_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Specialist->getPrimaryKeys();
    $values = $this->getValue('specialist_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Specialist', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Specialist', array_values($link));
    }
  }

  public function saveLpuList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['lpu_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Lpu->getPrimaryKeys();
    $values = $this->getValue('lpu_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Lpu', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Lpu', array_values($link));
    }
  }

}
