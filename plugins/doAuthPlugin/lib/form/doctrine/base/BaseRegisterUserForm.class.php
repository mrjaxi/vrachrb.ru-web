<?php

class BaseRegisterUserForm extends PluginUserForm {

  public function configure()
  {
    $this->setWidget('password',  new sfWidgetFormInputPassword());
    $this->setWidget('repeat_password',  new sfWidgetFormInputPassword());

    $this->setValidators(array(
//      'id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
//      'first_name' => new sfValidatorString(array('required'=> true)),
//      'second_name' => new sfValidatorString(array('required'=> false)),
//      'middle_name' => new sfValidatorString(array('required'=> false)),
//      'gender' => new sfValidatorString(array('required'=> true)),
//      'birth_date' => new sfValidatorDateTime(array('required'=> true)),
//      'phone' => new sfValidatorString(array('required'=> false)),
//      'photo' => new sfValidatorFile(array('required'=> false)),
//      'email' => new sfValidatorEmail(array('required'=> true)),
//      'password' => new sfValidatorString(array('required'=> true)),
//      'repeat_password' => new sfValidatorString(array('required'=> true)),
    ));

    unset($this['id'],$this['is_active'],$this['is_super_admin'],$this['last_login'],$this['created_at'],$this['updated_at']);

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('password', '==', 'repeat_password'),
      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'email')),
      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'username')),
    )));
  }
}

?>