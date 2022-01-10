<?php

/**
 * User filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'first_name'            => new sfWidgetFormFilterInput(),
      'second_name'           => new sfWidgetFormFilterInput(),
      'middle_name'           => new sfWidgetFormFilterInput(),
      'gender'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'birth_date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'email'                 => new sfWidgetFormFilterInput(),
      'phone'                 => new sfWidgetFormFilterInput(),
      'salt'                  => new sfWidgetFormFilterInput(),
      'password'              => new sfWidgetFormFilterInput(),
      'photo'                 => new sfWidgetFormFilterInput(),
      'is_active'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_super_admin'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_login'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'password_check'        => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'user_permissions_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Permission')),
      'user_group_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup')),
    ));

    $this->setValidators(array(
      'username'              => new sfValidatorPass(array('required' => false)),
      'first_name'            => new sfValidatorPass(array('required' => false)),
      'second_name'           => new sfValidatorPass(array('required' => false)),
      'middle_name'           => new sfValidatorPass(array('required' => false)),
      'gender'                => new sfValidatorPass(array('required' => false)),
      'birth_date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'email'                 => new sfValidatorPass(array('required' => false)),
      'phone'                 => new sfValidatorPass(array('required' => false)),
      'salt'                  => new sfValidatorPass(array('required' => false)),
      'password'              => new sfValidatorPass(array('required' => false)),
      'photo'                 => new sfValidatorPass(array('required' => false)),
      'is_active'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_super_admin'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_login'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'password_check'        => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_permissions_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Permission', 'required' => false)),
      'user_group_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addUserPermissionsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.UserPermissions UserPermissions')
          ->andWhereIn('UserPermissions.permission_id', $values);
  }

  public function addUserGroupListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.UserGroupUsers UserGroupUsers')
          ->andWhereIn('UserGroupUsers.user_group_id', $values);
  }

  public function getModelName()
  {
    return 'User';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'username'              => 'Text',
      'first_name'            => 'Text',
      'second_name'           => 'Text',
      'middle_name'           => 'Text',
      'gender'                => 'Text',
      'birth_date'            => 'Date',
      'email'                 => 'Text',
      'phone'                 => 'Text',
      'salt'                  => 'Text',
      'password'              => 'Text',
      'photo'                 => 'Text',
      'is_active'             => 'Boolean',
      'is_super_admin'        => 'Boolean',
      'last_login'            => 'Date',
      'password_check'        => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'user_permissions_list' => 'ManyKey',
      'user_group_list'       => 'ManyKey',
    );
  }
}
