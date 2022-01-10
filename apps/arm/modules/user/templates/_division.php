<?php
foreach($user->getUserDivision() as $division)
{
  echo $division->getDivision()->getTitle();
}
?>