<?php

/**
 * CreateConsilium_specialist form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateConsilium_specialistForm extends BaseConsiliumSpecialistForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    $this->useFields(array('consilium_id', 'specialist_id'));

    $this->validatorSchema['consilium_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['specialist_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
