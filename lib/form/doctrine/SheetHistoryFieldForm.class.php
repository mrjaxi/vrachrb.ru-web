<?php

/**
 * SheetHistoryField form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SheetHistoryFieldForm extends BaseSheetHistoryFieldForm
{
  public function configure()
  {
    $this->useFields(array('title', 'field_type', 'field_options'));
  }
}
