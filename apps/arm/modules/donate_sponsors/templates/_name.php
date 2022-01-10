<?php
if($donate_sponsors->getFirstName() != 'Неизвестно')
{
  echo $donate_sponsors->getFirstName() . ($donate_sponsors->getMiddleName() != 'Неизвестно' ? ' ' . $donate_sponsors->getMiddleName() : '') . ' ' . $donate_sponsors->getSecondName();
}
else
{
  echo $donate_sponsors->getFirstName();
}