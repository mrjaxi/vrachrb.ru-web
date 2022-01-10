<?php

/**
 * Consilium form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ConsiliumForm extends BaseConsiliumForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'specialists_list', 'closed', 'closing_date', 'created_at'));

    $query = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->leftJoin('s.User u')
      ->orderBy('u.second_name ASC')
      ;

    $this->widgetSchema['specialists_list'] = new sfWidgetFormDoctrineChoice(array('label' => 'Специалисты', 'multiple' => true, 'model' => 'Specialist', 'method' => 'getSFM'), array( 'class' => 'chosen', 'multiple' => 1));
    $this->widgetSchema['question_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Question', 'method' => 'getBody'), array('class' => 'chosen'));

  }
}
