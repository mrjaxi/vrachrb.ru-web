<?php

/**
 * Consilium_specialist form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Consilium_specialistForm extends BaseConsilium_specialistForm
{
  public function configure()
  {
    $this->useFields(array('consilium_id', 'specialist_id'));
  }
}
