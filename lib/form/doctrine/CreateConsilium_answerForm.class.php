<?php

/**
 * Feedback form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateConsilium_answerForm extends BaseConsilium_answerForm
{
  public function configure()
  {
    $this->useFields(array('consilium_id', 'specialist_id', 'body', 'created_at'));

    unset($this['id'], $this['created_at']);

    $this->widgetSchema['specialist_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'style' => 'width:100%;min-height:100px;resize:vertical', 'onclick' => 'consiliumCheckTextArea(this);'));

    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
