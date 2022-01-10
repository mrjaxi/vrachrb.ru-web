<?php

/**
 * Receive_datetime form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Receive_datetimeForm extends BaseReceive_datetimeForm
{
  public function configure()
  {
    $this->useFields(array('reception_id', 'datetime'));
  }
}
