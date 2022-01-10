<?php

/**
 * Question form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QuestionForm extends BaseQuestionForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'user_about_id', 'body', 'is_anonymous', 'approved', 'closed_by', 'closing_date', 'specialists_list', 'specialtys_list', 'created_at'));

    $q_user = Doctrine::getTable('User')
      ->createQuery('u')
      ->leftJoin('u.Specialist s')
      ->groupBy('u.id')
      ->having('COUNT(s.id) = 0')
//      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $q_user, 'method' => 'getSFM'), array('class' => 'chosen'));

    $this->widgetSchema['user_about_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true, 'method' => 'getSFM'), array('class' => 'chosen'));

    $q_specialist = Doctrine::getTable('User')
      ->createQuery('u')
      ->innerJoin('u.Specialist s')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['closed_by'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true, 'query' => $q_specialist, 'method' => 'getSFM'), array('class' => 'chosen'));

    $q_s = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->leftJoin('s.User u')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['specialists_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'add_empty' => true, 'model' => 'Specialist', 'query' => $q_s, 'method' => 'getSFM', 'label' => 'Отвечает специалист'), array('class' => 'chosen'));

    $this->widgetSchema['specialtys_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'add_empty' => true, 'model' => 'Specialty', 'method' => 'getTitle', 'label' => 'Специальность'), array('class' => 'chosen'));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 80, 'rows' => 10));
  }
}
