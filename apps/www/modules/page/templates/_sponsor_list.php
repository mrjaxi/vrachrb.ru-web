<?php
if(count($donate_sponsors) > 0)
{
  if($location == 'donate')
  {
    echo '<td>';
    echo '<div class="white_box donate_sponsors_list">';
    echo '<b>Нам помогли</b><i class="br10"></i>';
  }
  if($location == 'sponsor')
  {
    echo '<div style="margin-left: 0;width:880px;" class="white_box donate_sponsors_list">';
  }
  foreach ($donate_sponsors as $ds_key => $ds)
  {
    $resolution = false;
    if($location == 'donate' && $ds_key < 20)
    {
      $resolution = true;
    }
    elseif($location == 'sponsor')
    {
      $resolution = true;
    }
    if($resolution)
    {
      echo $ds['first_name'] . ' ' . $ds['middle_name'] . ' ' . $ds['second_name'];
      echo '<i class="br5"></i>';
    }
  }
  if($location == 'donate')
  {
    if(count($donate_sponsors) > 20)
    {
      echo '<i class="br15"></i>';
      echo '<a href="' . url_for('@donate_sponsor') . '">Показать все</a>';
    }
    echo '</div>';
    echo '</td>';
  }
  elseif($location == 'sponsor')
  {
    echo '</div>';
  }
}


