<?php
if($message_error->getUserId())
{
	$specialist = $message_error->getUser()->getSpecialist();
  $user = $message_error->getUser();
  if($user->getFirstName())
  {
    $user_result = $user->getFirstName() . ' ' . $user->getSecondName();
  }
  else
  {
    $user_result = $user->getUsername();
  }
  echo $user_result . '<br>' . (count($specialist) > 0 ? 'Специалист' : 'Пользователь');
}