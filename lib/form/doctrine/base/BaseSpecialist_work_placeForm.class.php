<?php

/**
 * Specialist_work_place form base class.
 *
 * @method Specialist_work_place getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialist_work_placeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'), 'add_empty' => false), array("required" => true)),
      'title'                   => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'hidden'                  => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'reception_contract_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Reception_contract')),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'specialist_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialist'))),
      'title'                   => new sfValidatorString(array('max_length' => 255)),
      'hidden'                  => new sfValidatorBoolean(array('required' => false)),
      'reception_contract_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Reception_contract', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialist_work_place[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specialist_work_place';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['reception_contract_list']))
    {
      $this->setDefault('reception_contract_list', $this->object->Reception_contract->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveReception_contractList($con);

    parent::doSave($con);
  }

  public function saveReception_contractList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['reception_contract_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Reception_contract->getPrimaryKeys();
    $values = $this->getValue('reception_contract_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Reception_contract', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Reception_contract', array_values($link));
    }
  }

}
