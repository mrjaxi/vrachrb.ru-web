<?php
class ChangeUserForm extends BaseRegisterUserForm
{
  public function configure()
  {
    $this->useFields(array('first_name', 'second_name', 'middle_name', 'gender', 'birth_date', 'email', 'phone', 'photo'));

    $this->widgetSchema['email'] = new sfWidgetFormInputText(array('label' => 'Эл. почта'), array('size' => '50'));
    
    $this->widgetSchema['first_name'] = new sfWidgetFormInputText(array(), array('required' => 'required', 'data-xp' => 'required: true'));
    $this->widgetSchema['second_name'] = new sfWidgetFormInputText(array(), array());
    $this->widgetSchema['middle_name'] = new sfWidgetFormInputText(array(), array());

    $this->widgetSchema['birth_date'] = new sfWidgetFormDate(array(), array('data-xp' => 'required: true'));
    $this->widgetSchema['phone'] = new sfWidgetFormInputText(array(), array('data-xp' => "type:'phone', required: false"));
    $this->widgetSchema['email'] = new sfWidgetFormInputText(array(), array('data-xp' => "type:'email', required: true"));
    $this->widgetSchema['gender'] = new sfWidgetFormChoice(array(
      'choices' => array('м', 'ж'),
      'expanded' => false,
    ));

    $this->validatorSchema['email'] = new sfValidatorEmail(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['birth_date'] = new sfValidatorDate(array('max' => time()), array('max' => 'Введите правильную дату'));
  }
}

?>