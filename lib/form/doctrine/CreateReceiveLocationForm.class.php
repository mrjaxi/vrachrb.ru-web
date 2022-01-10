<?php

/**
 * ReceiveLocation form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateReceiveLocationForm extends BaseReceiveLocationForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    $this->useFields(array('reception_id', 'work_place_id'));

    $this->validatorSchema['reception_id'] = new sfValidatorNumber(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['work_place_id'] = new sfValidatorNumber(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
