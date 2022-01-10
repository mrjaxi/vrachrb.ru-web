<?php

/**
 * LpuSpecialist form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LpuSpecialistForm extends BaseLpuSpecialistForm
{
  public function configure()
  {
    $this->useFields(array('lpu_id', 'specialist_id'));
  }
}
