<?php

/**
 * Specialist form base class.
 *
 * @method Specialist getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecialistForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'specialty_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specialty'), 'add_empty' => false), array("required" => true)),
      'title_url'       => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70, "required" => true)),
      'rating'          => new sfWidgetFormInputText(array(), array("size" => 2, "maxlength" => 2)),
      'answers_count'   => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8)),
      'about'           => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'education'       => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'live_reception'  => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'certificate'     => new sfWidgetFormTextarea(array(), array("rows" => 5, "cols" => 70)),
      'question_count'  => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8)),
      'prompt_count'    => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8)),
      'article_count'   => new sfWidgetFormInputText(array(), array("size" => 8, "maxlength" => 8)),
      'questions_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Question')),
      'consiliums_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Consilium')),
      'lpus_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lpu')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'specialty_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specialty'))),
      'title_url'       => new sfValidatorString(array('max_length' => 1000)),
      'rating'          => new sfValidatorNumber(array('required' => false)),
      'answers_count'   => new sfValidatorInteger(array('required' => false)),
      'about'           => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'education'       => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'live_reception'  => new sfValidatorBoolean(array('required' => false)),
      'certificate'     => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'question_count'  => new sfValidatorInteger(array('required' => false)),
      'prompt_count'    => new sfValidatorInteger(array('required' => false)),
      'article_count'   => new sfValidatorInteger(array('required' => false)),
      'questions_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Question', 'required' => false)),
      'consiliums_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Consilium', 'required' => false)),
      'lpus_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lpu', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specialist[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specialist';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['questions_list']))
    {
      $this->setDefault('questions_list', $this->object->Questions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['consiliums_list']))
    {
      $this->setDefault('consiliums_list', $this->object->Consiliums->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['lpus_list']))
    {
      $this->setDefault('lpus_list', $this->object->Lpus->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveQuestionsList($con);
    $this->saveConsiliumsList($con);
    $this->saveLpusList($con);

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

  public function saveConsiliumsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['consiliums_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Consiliums->getPrimaryKeys();
    $values = $this->getValue('consiliums_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Consiliums', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Consiliums', array_values($link));
    }
  }

  public function saveLpusList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['lpus_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Lpus->getPrimaryKeys();
    $values = $this->getValue('lpus_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Lpus', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Lpus', array_values($link));
    }
  }

}
