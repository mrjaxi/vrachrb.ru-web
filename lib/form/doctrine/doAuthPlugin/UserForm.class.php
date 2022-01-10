<?php
class UserForm extends PluginUserForm
{
  public function configure()
  {
    $this->useFields(array('username', 'password', 'photo', 'gender', 'birth_date', 'email', 'phone', 'is_super_admin', 'is_active', 'first_name', 'second_name', 'middle_name', /*'user_group_list'*/));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=user', 'multiple' => false, 'jcrop' => true), array('required' => true));
    $this->widgetSchema['birth_date'] = new sfWidgetFormDate(array(), array('required' => true));
    $this->widgetSchema['gender'] = new sfWidgetFormChoice(array(
      'choices'  => array('м', 'ж'),
      'expanded' => false,
    ));

    $this->validatorSchema['birth_date'] = new sfValidatorDate(array('min' => mktime(0, 0, 0, 00, 00, 1970), 'max' => time()), array('max' => 'Введите правильную дату'));

    //$this->widgetSchema['user_groups_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'UserGroup', 'expanded' => true));
    //$this->widgetSchema['user_permissions_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Permission', 'expanded' => true));
  }
}
