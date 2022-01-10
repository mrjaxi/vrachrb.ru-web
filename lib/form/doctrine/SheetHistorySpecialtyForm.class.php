<?php

/**
 * SheetHistorySpecialty form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SheetHistorySpecialtyForm extends BaseSheetHistorySpecialtyForm
{
  public function configure()
  {
    $this->useFields(array('sheet_history_id', 'specialty_id'));
  }
}
