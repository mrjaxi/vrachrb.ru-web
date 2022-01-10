<?php

/**
 * Answer form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AnswerForm extends BaseAnswerForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'question_id', 'type', 'body', 'created_at'));

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'order_by' => array('second_name', 'asc'), 'method' => 'getSFM'), array('class' => 'chosen'));

    $this->widgetSchema['question_id'] = new sfWidgetFormInputHidden();

    $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
      'choices'  => Answer::AnswerType(false, 'list'),
      'expanded' => false,
    ));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 70, 'rows' => 5));
  }
}
