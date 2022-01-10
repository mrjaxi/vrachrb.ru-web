<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<?php
if($sf_user->isAuthenticated())
{
  echo '<td width="1"><img src="/i/header.png" width="18" height="80" style="margin-right:20px;" /></td>';
  echo '<td>
      <div class="user_box load_work" data-title="Профиль" data-href="' . url_for('@whoami') . '">
        <img src="/i/n.gif"' . ($user_info ? ' style="background-image:url(\'data:image/jpeg;charset=utf-8;base64,' . $user_info['Photo'] . '\')"' : '') . ' width="40" height="40" class="user_box__img" data-mifare="' . $sf_user->getUsername() . '" /><span class="user_box__text">';
        if(!$user_info)
        {
          echo 'Администратор<br />&nbsp;';
        }
        else
        {
          echo '<span style="font-size:18px;">' . $user_info['FirstName'] . ' ' . $user_info['MiddleName'] . '</span><br /><span style="font-size:12px">' . $user_info['position'] . ', <i>' . $user_info['workplace'] . '</i></span>';
        }
        echo '</span>
      </div>
    </td>';
  if($sf_user->isAdmin())
  {
    echo '<td><button data-href="' . url_for('@arm') . '" class="button read_btn load_work fuck_return" style="padding:5px 15px;font-size:16px;">АРМ</button></td>';
  }

  
$bad = 0;
$warn = 0;
$good = 0;
$total = 0;

$bar_buff = '';

$bar = $user_info['bar'];

foreach($bar as $itemk => $item)
{
  if($bar[$itemk]['total'] == 0)
  {
    continue;
  }
  $bar_buff .= '<tr class="load_work" data-href="/' . $itemk . '/"><td width="40%"><b>' . $item['title'] . '</b></td>';
  $bar_buff .= '<td width="50%"><div class="user_bar__drop__row"><span class="user_bar__' . ($bar[$itemk]['bad'] > 0 ? ($bar[$itemk]['good'] == 0 ? 'zero' : 'low') : ($bar[$itemk]['warn'] > 0 ? 'mid' : ('hight'))) . '" style="width:' . round(($bar[$itemk]['good'] == 0 ? $bar[$itemk]['total'] : $bar[$itemk]['good']) / $bar[$itemk]['total'] * 100) . '%;"></span></div></td>
                  <td width="10%" class="fs_14">' . $bar[$itemk]['good'] . '/' . $bar[$itemk]['total'] . '</td>
                </tr>';
  $bad += $item['bad'];
  $warn += $item['warn'];
  $good += $item['good'];
  $total += $item['total'];;
}

  if($bar_buff != '')
  {
    echo '<td align="right">
            <div class="user_bar" onclick="userBar(true);">
              <div class="user_bar__drop">
                <table cellspacing="0" cellpadding="10" width="100%">';
    echo $bar_buff;
    echo '</table>
              </div>
              <div style="width:100%;height:100%;border-radius: 4px;overflow:hidden;"><div class="user_bar__line user_bar__' . ($bad > 0 ? 'low' : ($warn > 0 ? 'mid' : 'hight')) . '" style="width:' . round($good / $total * 100) . '%;"></div></div>
              <span class="user_bar__line__txt">' . $good . '/' . $total . '</span>
            </div>
            <div class="user_message" style="visibility:hidden">
              <b class="user_message__txt">99</b>
            </div>
          </td>';
    
  }
  echo '<td align="right" width="1"><nobr>';
  //echo '<button class="button help_btn"></button>';
  echo '<button class="button close_btn load_work exit_btn" data-href="' . url_for('@signout') . '" data-title="json"></button>';
  echo '</nobr></td>';
}
else
{
  echo '<td align="right"></td>';
}
?>

</tr>
</table>