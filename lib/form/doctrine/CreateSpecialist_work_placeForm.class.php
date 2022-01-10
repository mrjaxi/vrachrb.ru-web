<?php

/**
 * Specialist_work_place form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateSpecialist_work_placeForm extends BaseSpecialist_work_placeForm
{
  public function configure()
  {
    $this->disableCSRFProtection();
    $this->useFields(array('specialist_id', 'title', 'hidden'));

    unset($this['id']);

    $this->validatorSchema['specialist_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['title'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['hidden'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
