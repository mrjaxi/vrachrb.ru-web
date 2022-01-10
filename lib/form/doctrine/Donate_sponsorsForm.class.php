<?php

/**
 * Donate_sponsors form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Donate_sponsorsForm extends BaseDonate_sponsorsForm
{
  public function configure()
  {
    $this->useFields(array('first_name', 'second_name', 'middle_name', 'amount', 'sender', 'anonymous', 'json', 'created_at'));

    $this->widgetSchema['json'] = new sfWidgetFormInputHidden();
  }
}
