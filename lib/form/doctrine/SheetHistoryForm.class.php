<?php

/**
 * SheetHistory form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SheetHistoryForm extends BaseSheetHistoryForm
{
  public function configure()
  {
    $this->useFields(array('title', 'specialtys_list'));
    
    $this->widgetSchema['specialtys_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialty', 'method' => 'getTitle', 'label' => 'Специальности'), array('class' => 'chosen', 'multiple' => true));
  }
}
