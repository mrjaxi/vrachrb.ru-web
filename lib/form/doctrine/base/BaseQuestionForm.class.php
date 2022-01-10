<?php

/**
 * Question form base class.
 *
 * @method Question getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuestionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'user_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false), array("required" => true)),
      'user_about_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserAbout'), 'add_empty' => true), array()),
      'body'             => new sfWidgetFormInputText(array(), array("size" => 65, "required" => true)),
      'is_anonymous'     => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'approved'         => new sfWidgetFormInputCheckbox(array(), array("required" => true)),
      'comment_id'       => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'topic_id'         => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'vk_notice'        => new sfWidgetFormInputCheckbox(array(), array()),
      'closed_by'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserClosed'), 'add_empty' => true), array()),
      'closing_date'     => new sfWidgetFormDateTime(array(), array()),
      'created_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'       => new sfWidgetFormDateTime(array(), array("required" => true)),
      'specialists_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist')),
      'specialtys_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialty')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'user_about_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserAbout'), 'required' => false)),
      'body'             => new sfValidatorPass(),
      'is_anonymous'     => new sfValidatorBoolean(array('required' => false)),
      'approved'         => new sfValidatorBoolean(array('required' => false)),
      'comment_id'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'topic_id'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'vk_notice'        => new sfValidatorBoolean(array('required' => false)),
      'closed_by'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserClosed'), 'required' => false)),
      'closing_date'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'specialists_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'required' => false)),
      'specialtys_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Specialty', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('question[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Question';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['specialists_list']))
    {
      $this->setDefault('specialists_list', $this->object->Specialists->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['specialtys_list']))
    {
      $this->setDefault('specialtys_list', $this->object->Specialtys->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSpecialistsList($con);
    $this->saveSpecialtysList($con);

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
