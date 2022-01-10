<?php

/**
 * Question_data form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Question_dataForm extends BaseQuestion_dataForm
{
  public function configure()
  {
    $this->useFields(array('specialty_id', 'specialist_id'));
  }
}
