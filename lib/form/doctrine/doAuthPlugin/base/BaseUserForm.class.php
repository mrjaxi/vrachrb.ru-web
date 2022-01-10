<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(array(), array()),
      'username'              => new sfWidgetFormInputText(array(), array("size" => 32, "maxlength" => 128, "required" => true)),
      'first_name'            => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'second_name'           => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'middle_name'           => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'gender'                => new sfWidgetFormInputText(array(), array("size" => 1, "maxlength" => 1, "required" => true)),
      'birth_date'            => new sfWidgetFormDateTime(array(), array("required" => true)),
      'email'                 => new sfWidgetFormInputText(array(), array("size" => 32, "maxlength" => 128)),
      'phone'                 => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'salt'                  => new sfWidgetFormInputText(array(), array("size" => 32, "maxlength" => 128)),
      'password'              => new sfWidgetFormInputText(array(), array("size" => 32, "maxlength" => 128)),
      'photo'                 => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'is_active'             => new sfWidgetFormInputCheckbox(array(), array()),
      'is_super_admin'        => new sfWidgetFormInputCheckbox(array(), array()),
      'last_login'            => new sfWidgetFormDateTime(array(), array()),
      'password_check'        => new sfWidgetFormInputText(array(), array("size" => 64, "maxlength" => 255)),
      'created_at'            => new sfWidgetFormDateTime(array(), array("required" => true)),
      'updated_at'            => new sfWidgetFormDateTime(array(), array("required" => true)),
      'user_permissions_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Permission')),
      'user_group_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'username'              => new sfValidatorString(array('max_length' => 128)),
      'first_name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'second_name'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'middle_name'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'gender'                => new sfValidatorString(array('max_length' => 1)),
      'birth_date'            => new sfValidatorDateTime(),
      'email'                 => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'phone'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'salt'                  => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'              => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'photo'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_active'             => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'        => new sfValidatorBoolean(array('required' => false)),
      'last_login'            => new sfValidatorDateTime(array('required' => false)),
      'password_check'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
      'user_permissions_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Permission', 'required' => false)),
      'user_group_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'User', 'column' => array('username')))
    );

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['user_permissions_list']))
    {
      $this->setDefault('user_permissions_list', $this->object->UserPermissions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['user_group_list']))
    {
      $this->setDefault('user_group_list', $this->object->UserGroup->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveUserPermissionsList($con);
    $this->saveUserGroupList($con);

    parent::doSave($con);
  }

  public function saveUserPermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['user_permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UserPermissions->getPrimaryKeys();
    $values = $this->getValue('user_permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UserPermissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UserPermissions', array_values($link));
    }
  }

  public function saveUserGroupList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['user_group_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UserGroup->getPrimaryKeys();
    $values = $this->getValue('user_group_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UserGroup', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UserGroup', array_values($link));
    }
  }

}
