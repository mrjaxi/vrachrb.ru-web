<?php
if($user->getIname() != '')
{
  echo 'Здравствуйте ' . $user->getIname();
  echo "\n\n";
}

$parser = new sfYamlParser();
$generator = $parser->parse(file_get_contents(sfConfig::get('sf_app_module_dir') . '/user/config/generator.yml'));
$fields = $generator['generator']['param']['config']['fields'];

$is_new = false;
$is_locked = false;
$change_fields = array();
$old = $user->getModified(true, true);
$new = $user->getModified(false, true);

foreach($old as $fk => $fv)
{
  if(is_null($fv))
  {
    $is_new = true;
    break;
  }
  if($fk == 'is_active' && $new[$fk] == '0' && $fv == '1')
  {
    $is_locked = true;
    break;
  }
  if($fk != 'updated_at' && $fk != 'created_at' && $fk != 'is_super_admin' && $fk != 'is_activated')
  {
    $change_fields[] = $fields[$fk]['label'] . "\n" . ($fk == 'password' ? $password : $new[$fk]);
  }
}
if($is_new)
{
  echo 'Вам предоставлен доступ к «АРМ предприятия» ' . sfConfig::get('app_company_short_title');
  echo "\n\n";
  echo sfConfig::get('app_arm_host') . '/arm/';
  echo "\n\n";
  echo 'Логин для входа в систему' . "\n" . $user->getUsername();
  echo "\n";
  echo "\n";
  echo 'Пароль' . "\n" . $password;
  echo "\n\n";
  echo "--\n";
  echo 'Пароль для входа в систему не может быть изменен самостоятельно из соображений безопасности на время тестового периода';
}
elseif($is_locked)
{
  echo 'Доступ к «АРМ предприятия» ' . sfConfig::get('app_company_short_title') . ' под вашей учетной записью заблокирован';
  echo "\n\n";
}
else
{
  echo 'Изменены данные в «АРМ предприятия» ' . sfConfig::get('app_company_short_title');
  echo "\n\n";
  echo sfConfig::get('app_arm_host') . '/arm/';
  echo "\n\n";
  echo 'Логин для входа в систему' . "\n" . $user->getUsername();
  echo "\n";
  echo "\n";
  echo implode("\n\n", $change_fields);
}

?>