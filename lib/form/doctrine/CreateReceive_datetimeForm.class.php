<?php

/**
 * Receive_datetime form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateReceive_datetimeForm extends BaseReceive_datetimeForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    $this->useFields(array('reception_id', 'datetime'));

    $this->validatorSchema['reception_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['datetime'] = new sfValidatorDateTime(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
