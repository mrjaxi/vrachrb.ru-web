<?php

/**
 * Notice form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateNoticeCsrfForm extends BaseNoticeForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'type', 'inner_id', 'event'));

    unset($this['id']);
  }
}
