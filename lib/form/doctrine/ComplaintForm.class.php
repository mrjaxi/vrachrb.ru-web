<?php

/**
 * Complaint form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ComplaintForm extends BaseComplaintForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'specialist_id', 'body', 'created_at'));

    $this->widgetSchema['question_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Question', 'method' => 'getBody'), array('class' => 'chosen'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => 10));

    $q_specialist = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->innerJoin('s.User u')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['specialist_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Specialist', 'add_empty' => true, 'query' => $q_specialist, 'method' => 'getSFM'), array('class' => 'chosen'));
  }
}
