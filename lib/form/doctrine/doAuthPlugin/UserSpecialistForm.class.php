<?php
class UserSpecialistForm extends PluginUserForm
{
  public function configure()
  {
    $this->useFields(array('username', 'photo', 'gender', 'birth_date', 'email', 'phone', 'is_active', 'first_name', 'second_name', 'middle_name'));

    $this->widgetSchema['username'] = new sfWidgetFormInputText(array('label' => 'Логин'), array('size' => 20, 'required' => true));

    $this->widgetSchema['new_password'] = new sfWidgetFormInputText(array('label' => 'Пароль'), array('size' => 20));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('label' => 'Фото', 'allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=user', 'multiple' => false), array('required' => true));

    $this->widgetSchema['gender'] = new sfWidgetFormChoice(array('label' => 'Пол', 'choices' => array('м' => 'м', 'ж' => 'ж')));

    $this->widgetSchema['birth_date'] = new sfWidgetFormDate(array('label' => 'Дата рождения'), array('required' => true));

    $this->widgetSchema['email'] = new sfWidgetFormInputText(array('label' => 'Эл.почта'), array('required' => true));

    $this->widgetSchema['phone'] = new sfWidgetFormInputText(array('label' => 'Телефон'), array('required' => true));

    $this->widgetSchema['is_active'] = new sfWidgetFormInputCheckbox(array('label' => 'Активен'));
    $this->widgetSchema['first_name'] = new sfWidgetFormInputText(array('label' => 'Имя'), array('required' => true));
    $this->widgetSchema['second_name'] = new sfWidgetFormInputText(array('label' => 'Фамилия'), array('required' => true));
    $this->widgetSchema['middle_name'] = new sfWidgetFormInputText(array('label' => 'Отчество', 'requared' => true));
  }
}
