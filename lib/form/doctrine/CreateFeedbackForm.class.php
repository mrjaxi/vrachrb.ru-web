<?php

/**
 * Feedback form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateFeedbackForm extends BaseFeedbackForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'body', 'created_at'));

    unset($this['id'], $this['created_at']);

    $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:vertical;', 'rows' => 8));

    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
