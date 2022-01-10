<?php

/**
 * Review form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReviewForm extends BaseReviewForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'user_id', 'specialist_id', 'body', 'informative', 'courtesy', 'approved', 'created_at'));

    $query = Doctrine::getTable('Question')
      ->createQuery('q')
      ->orderBy('q.body ASC')
      ;


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

    $this->widgetSchema['question_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Question', 'query' => $query, 'method' => 'getBody'), array('class' => 'chosen'));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 100, 'rows' => 10));

    $this->widgetSchema['created_at'] = new sfWidgetFormDate();
  }
}
