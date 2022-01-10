<?php

/**
 * Notice form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class NoticeForm extends BaseNoticeForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'type', 'inner_id', 'event', 'created_at'));

    $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
      'choices'  => array(
        'dialog' => 'Диалоги',
        'consilium' => 'Консилиумы',
        'question' => 'Вопросы'
      ),
      'expanded' => false,
    ));

    $this->widgetSchema['event'] = new sfWidgetFormChoice(array(
      'choices'  => Notice::noticeType('list'),
      'expanded' => false,
    ));

    $q_user = Doctrine::getTable('User')
      ->createQuery('u')
      ->leftJoin('u.Specialist s')
      ->groupBy('u.id')
      ->having('COUNT(s.id) = 0')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $q_user, 'method' => 'getSFM'), array('class' => 'chosen', 'required' => true));
  }
}
