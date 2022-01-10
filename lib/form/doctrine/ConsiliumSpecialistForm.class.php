<?php

/**
 * ConsiliumSpecialist form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ConsiliumSpecialistForm extends BaseConsiliumSpecialistForm
{
  public function configure()
  {
    $this->useFields(array('consilium_id', 'specialist_id'));
  }
}
