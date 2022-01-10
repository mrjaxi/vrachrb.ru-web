<?php

/**
 * Specialty form base class.
 *
 * @method Specialty getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialtyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(array(), array()),
      'title'              => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255, "required" => true)),
      'description'        => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'questions_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Question')),
      'sheet_history_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SheetHistory')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 255)),
      'description'        => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'questions_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Question', 'required' => false)),
      'sheet_history_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SheetHistory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialty[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specialty';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['questions_list']))
    {
      $this->setDefault('questions_list', $this->object->Questions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['sheet_history_list']))
    {
      $this->setDefault('sheet_history_list', $this->object->SheetHistory->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveQuestionsList($con);
    $this->saveSheetHistoryList($con);

    parent::doSave($con);
  }

  public function saveQuestionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['questions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Questions->getPrimaryKeys();
    $values = $this->getValue('questions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Questions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Questions', array_values($link));
    }
  }

  public function saveSheetHistoryList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sheet_history_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->SheetHistory->getPrimaryKeys();
    $values = $this->getValue('sheet_history_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('SheetHistory', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('SheetHistory', array_values($link));
    }
  }

}
