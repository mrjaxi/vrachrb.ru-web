<?php

/**
 * CreateComplaint form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateComplaintForm extends BaseComplaintForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'specialist_id', 'body'));

    unset($this['id']);

    $this->widgetSchema['question_id'] = new sfWidgetFormInputText(array(), array('required' => true));
    $this->widgetSchema['specialist_id'] = new sfWidgetFormInputText(array(), array('required' => true));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => false, 'rows' => 5, 'style' => 'width:100%;min-height:100px;resize:vertical', 'placeholder' => 'Текст жалобы (обязательное поле)'));

    $this->validatorSchema['body'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['question_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['specialist_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
