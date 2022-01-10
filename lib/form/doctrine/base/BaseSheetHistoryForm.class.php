<?php

/**
 * SheetHistory form base class.
 *
 * @method SheetHistory getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSheetHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'title'           => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'specialtys_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialty')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'specialtys_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialty', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sheet_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SheetHistory';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['specialtys_list']))
    {
      $this->setDefault('specialtys_list', $this->object->Specialtys->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSpecialtysList($con);

    parent::doSave($con);
  }

  public function saveSpecialtysList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['specialtys_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Specialtys->getPrimaryKeys();
    $values = $this->getValue('specialtys_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Specialtys', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Specialtys', array_values($link));
    }
  }

}
