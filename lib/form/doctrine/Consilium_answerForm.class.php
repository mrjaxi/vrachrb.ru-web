<?php

/**
 * Consilium_answer form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Consilium_answerForm extends BaseConsilium_answerForm
{
  public function configure()
  {
    $this->useFields(array('consilium_id', 'specialist_id', 'body'));

    $q = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->leftJoin('s.User u')
      ->orderBy('u.second_name ASC')
      ;

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 10));

    $this->widgetSchema['consilium_id'] = new sfWidgetFormInputHidden();

    $this->widgetSchema['specialist_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Specialist', 'method' => 'getSFM'), array('class' => 'chosen'));

  }
}
