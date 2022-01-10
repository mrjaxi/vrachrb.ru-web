<?php
$user = $analysis->getUser();
if($user->getFirstName())
{
  echo $user->getFirstName() . ' ' . $user->getSecondName();
}
else
{
  echo $user->getUsername();
}