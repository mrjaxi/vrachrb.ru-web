<?php

/**
 * CreateConsilium form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateConsiliumForm extends BaseConsiliumForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    $this->useFields(array('question_id'));

    $this->validatorSchema['question_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}