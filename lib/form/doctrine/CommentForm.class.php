<?php

/**
 * Comment form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentForm extends BaseCommentForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'body', 'article_id', 'prompt_id', 'created_at'));

    $q_user = Doctrine::getTable('User')
      ->createQuery('u')
      ->leftJoin('u.Specialist s')
      ->groupBy('u.id')
      ->having('COUNT(s.id) = 0')
      ->orderBy('u.second_name ASC')
    ;
    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'method' => 'getSFM'), array('class' => 'chosen'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => '6'));
  }
}
