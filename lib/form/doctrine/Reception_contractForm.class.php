<?php

/**
 * Reception_contract form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Reception_contractForm extends BaseReception_contractForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'specialist_id', 'question_id', 'price', 'is_activated', 'is_reject', 'reject_reason', 'created_at'));

    $this->widgetSchema['price'] = new sfWidgetFormInputText(array(), array('required' => true, 'size' => 10));

    $this->widgetSchema['reject_reason'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => '4'));

    $q_user = Doctrine::getTable('User')
      ->createQuery('u')
      ->leftJoin('u.Specialist s')
      ->groupBy('u.id')
      ->having('COUNT(s.id) = 0')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $q_user, 'method' => 'getSFM'), array('class' => 'chosen'));

    $q_specialist = Doctrine::getTable("User")
      ->createQuery("u")
      ->innerJoin('u.Specialist s')
      ->groupBy('u.id')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['specialist_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $q_specialist, 'method' => 'getSFM'), array('class' => 'chosen'));
  }
}
