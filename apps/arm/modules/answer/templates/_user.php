<?php
$user = $answer->getUser();
if($user->getFirstName())
{
  echo $user->getFirstName() . ' ' . $user->getSecondName();
}
else
{
  echo $user->getUsername();
}