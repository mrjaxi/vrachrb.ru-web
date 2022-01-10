<?php

/**
 * UserGroupUsers form base class.
 *
 * @method UserGroupUsers getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserGroupUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormInputHidden(array(), array()),
      'user_group_id' => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_id', 'required' => false)),
      'user_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_group_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_group_users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserGroupUsers';
  }

}
