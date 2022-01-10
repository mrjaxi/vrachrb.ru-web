<?php

/**
 * Feedback form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeedbackForm extends BaseFeedbackForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'body', 'created_at'));

    $query = Doctrine::getTable('User')
      ->createQuery('u')
      ->where('u.is_active = 1')
      ->orderBy('u.second_name ASC')
      ->groupBy('u.id')
      ;

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $query, 'method' => 'getSFM'), array('class' => 'chosen'));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => 10));
  }
}
