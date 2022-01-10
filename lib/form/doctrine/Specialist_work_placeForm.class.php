<?php

/**
 * Specialist_work_place form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Specialist_work_placeForm extends BaseSpecialist_work_placeForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'title', 'hidden'));




    $specialists = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->innerJoin('s.User u')
      ->orderBy('u.second_name ASC')
      ->execute();

    $arr = array();

    foreach($specialists as $specialist)
    {
      $arr[$specialist->getId()] = $specialist->getUser()->getSecondName() . ' ' . $specialist->getUser()->getFirstName();
    }

    $this->widgetSchema['specialist_id'] = new sfWidgetFormChoice(array('choices' => $arr), array('class' => 'chosen'));
  }
}
