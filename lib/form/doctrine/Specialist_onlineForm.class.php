<?php

/**
 * Specialist_online form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Specialist_onlineForm extends BaseSpecialist_onlineForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'date'));
  }
}
