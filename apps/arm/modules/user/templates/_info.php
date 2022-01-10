<?php
echo '<span class="lui_pseudo_link lui_popover">';
echo $user->getUsername();

$uinfo = array();
$ufio = array();
if($user->getFirstName())
{
  $ufio[] = $user->getFirstName();
}
if($user->getSecondName())
{
  $ufio[] = $user->getSecondName();
}
if($user->getMiddleName())
{
  $ufio[] = $user->getMiddleName();
}
if(count($ufio) > 0)
{
  $uinfo[] = '<b>' . implode(' ', $ufio). '</b>';
}
//foreach($user->getUserDivision() as $ud)
//{
//  $uinfo[] = '<nobr>' . $ud->getDivision()->getTitle() . '</nobr>';
//}

$userdata = Doctrine::getTable('Userdata')->findOneByUserIdAndDataType($user->getId(), 'contacts');
if($userdata)
{
  $uinfo[] = '<nobr>' . $userdata->getDataValue() . '</nobr>';
}
if(count($uinfo))
{
  echo '<div class="lui_popover__window' . (isset($right) ? ' lui_popover__window-right' : '') . '">';
  echo implode('<i class="br5"></i>', $uinfo);
  echo '</div>';
}
echo '</span>';
?>