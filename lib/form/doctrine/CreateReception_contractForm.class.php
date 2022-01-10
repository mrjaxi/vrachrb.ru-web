<?php

/**
 * CreateReception_contract form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateReception_contractForm extends BaseReception_contractForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'specialist_id', 'question_id', 'price', 'is_activated'));

    unset($this['id']);

    $this->validatorSchema['user_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['specialist_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['question_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['price'] = new sfValidatorInteger(array(), array('invalid' => 'Не верный формат.'));
    $this->validatorSchema['price'] = new sfValidatorNumber(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
