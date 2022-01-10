<?php

/**
 * Comment form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AddCommentForm extends BaseCommentForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'body', 'prompt_id', 'article_id'));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'rows' => '5', 'placeholder' => 'Ваш комментарий...'));
    $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['prompt_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['article_id'] = new sfWidgetFormInputHidden();

    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
