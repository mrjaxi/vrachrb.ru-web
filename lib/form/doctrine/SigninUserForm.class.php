<?php

/**
 * Signin form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SigninUserForm extends PluginUserForm
{
  public function configure()
  {
    $this->useFields(array('username', 'password'));
    $this->setValidators(array(

      'email' => new sfValidatorEmail(array('required'=> true)),
      'password' => new sfValidatorString(array('required'=> true)),

    ));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('email', '==', 're@mail.ru'),
//      new sfValidatorSchemaCompare('email', '==', '1234'),
//      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'email')),
//      new sfValidatorDoctrineUnique(array('model'=> 'User','column'=> 'username')),
    )));
  }
}
