<?php

/**
 * Lpu form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LpuForm extends BaseLpuForm
{
  public function configure()
  {
    $this->useFields(array('title', 'location', 'token'));

//    $q_s = Doctrine::getTable('Specialist')
//      ->createQuery('s')
//      ->leftJoin('s.User u')
//      ->orderBy('u.second_name ASC')
//    ;
//
//    $this->widgetSchema['specialists_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Specialist', 'query' => $q_s, 'method' => 'getSFM', 'label' => 'Специалисты'), array('class' => 'chosen', 'multiple' => true));
  }
}
