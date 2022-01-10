<?php
slot('title', 'Мои курсы');
?>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" class="table_cont">
<tr valign="top">
<td class="">
<div class="cont__cont_r">
<div class="cont__cont_r__pad">
<div class="fs_30">Мои курсы</div>
<?php
$courses = $user_info['courses'];
if(count($courses) > 0)
{

  $ckeys = array(
    'none' => array('title' => 'Не пройденные', 'rows' => array()),
    'low' => array('title' => 'Просроченные', 'rows' => array()),
    'mid' => array('title' => 'Переаттестация менее чем через месяц', 'rows' => array()),
    'hight' => array('title' => 'Переаттестация более чем через месяц', 'rows' => array()),
    'gray' => array('title' => 'Переаттестация не требуется', 'rows' => array()),
  );

  foreach($courses as $ck => $course)
  {
    $diff = 'нет';
    $days = 100000;
    $row = '';
    if($course['ProlongationDate'] != '')
    {
      $days = (strtotime($course['ProlongationDate']) - time()) / 60 / 60 / 24;
    }
    if($course['Period'] > 0)
    {
      
      $diff = Page::niceRusEnds(round($course['Period'] / 12), '1 раз в год', '1 раз в %s года' , '1 раз в %s лет');
      if($course['CreateStatementDate'])
      {
        $ckey = $days <= 0 ? 'low' : ($days > 0 && $days < 30 ? 'mid' : 'hight');
      }
      else
      {
        $ckey = 'none';
        
      }
    }
    else
    {
      $ckey = 'gray';
    }
    
    $row .= '<tr>';
    //$row .= '<td><img src="/i/n.gif" width="33" height="33" class="elements_other ' . ($days <= 0 || $ckey == 'none' ? 'cross_red' : 'tick_green') . '" /></td>';
    $row .= '<td>' . $course['Name'] . '</td>';
    $row .= '<td align="center">' . $diff . '</td>';
    $row .= '<td align="center">' . ($course['CreateStatementDate'] ? Page::rusDate($course['CreateStatementDate'], false) : 'не пройден') . '</td>';
    $row .= '<td align="center">' . (($course['ProlongationDate'] != '') ? Page::rusDate($course['ProlongationDate'], false) : ($ckey == 'none' ? '-' : 'не требуется')) . ($days < 30 && $days > 0 ? '<br /><b>(через ' . Page::niceRusEnds(round($days), '%s день', '%s дня' , '%s дней') . ')</b>' : '') . '</td><td></td>';
    $row .= '</tr>';
    $ckeys[$ckey]['rows'][] = $row;
  }
    
  foreach($ckeys as $itemk => $item)
  {
    if(count($item['rows']) == 0)
    {
      continue;
    }
    echo '<i class="br20"></i>';
    echo '<table width="100%" cellpadding="10" class="user_bar__' . ($itemk == 'none' ? 'low' : $itemk) . ' c__table" cellspacing="0" border="0"><tbody>';
    foreach($item['rows'] as $tr)
    {
      echo $tr;
    }
    echo '</tbody>';
    echo '<thead>';
    echo '<tr>';
      echo '<th>' . $item['title'] . '<span class="c__table__count">' . count($item['rows']) . '</span></th>';
      echo '<th style="width:180px">Периодичность</th>';
      echo '<th style="width:180px"><nobr>Дата прохождения</nobr></th>';
      echo '<th style="width:180px"><nobr>Дата переаттестации</nobr></th>';
      echo '<th><div class="c__table__toggle"></div></th>';
    echo '</tr>';
    echo '</thead>';
    echo '</table>';
  }
}
?>
</div>
</div>
</td>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
  
});
</script>