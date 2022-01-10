<?php

/**
 * Review form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateReviewForm extends BaseReviewForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'user_id', 'specialist_id', 'body', 'informative', 'courtesy'));

    unset($this['id'], $this['question_id'], $this['user_id'], $this['specialist_id']);

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:vertical;', 'rows' => 5, 'placeholder' => 'Пожалуйста, заполните это поле'));

    $this->validatorSchema['question_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['user_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['specialist_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['body'] = new sfValidatorString(array('required' => false), array('required' => 'Обязательное поле.','invalid' => 'Не верный формат.'));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Review', 'column' => array('question_id')))
    );
  }
}
