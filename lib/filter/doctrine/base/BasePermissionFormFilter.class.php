<?php

/**
 * Permission filter form base class.
 *
 * @package    sf
 * @subpackage filter
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePermissionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'credential'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'     => new sfWidgetFormFilterInput(),
      'user_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'User')),
      'user_group_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup')),
    ));

    $this->setValidators(array(
      'credential'      => new sfValidatorPass(array('required' => false)),
      'description'     => new sfValidatorPass(array('required' => false)),
      'user_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'User', 'required' => false)),
      'user_group_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('permission_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addUserListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('UserPermissions.user_id', $values);
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

    $query->leftJoin('r.UserGroupPermissions UserGroupPermissions')
          ->andWhereIn('UserGroupPermissions.user_group_id', $values);
  }

  public function getModelName()
  {
    return 'Permission';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'credential'      => 'Text',
      'description'     => 'Text',
      'user_list'       => 'ManyKey',
      'user_group_list' => 'ManyKey',
    );
  }
}
