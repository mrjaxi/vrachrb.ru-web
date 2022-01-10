<?php
$search_cfg = $search_cfg->getRawValue();
if(!empty($query))
{
  $cres = $res->getRaw(0);
  if($cres && $cres['total_found'] > 0)
  {
    $buff = '';
    $crs = $rs->getRawValue();
    foreach($crs as $rcat => $ritem)
    {
      $search_cfg_item = $search_cfg[$rcat];
      $count = 0;
      $count_elem = count($ritem);

      foreach($ritem as $itemk => $item)
      {
        if($item['title'] != '')
        {
          $buff .= '<div class="' . ($count > 1 ? 'aq__q_tip__body__tip_hide' : '') . ' aq__q_tip__body__tip search__item__' . $rcat . '">';
          if($item['title'] != '-')
          {
            $buff .= '<a target="_blank" class="aq__q_tip__body__link" href="' . url_for('@question_answer_show?id=' . $item['link']) . '">' . $item['title'] . '</a>';
          }
          if($item['desc'])
          {
            if($item['title'] != '-')
            {
              $buff .= '<i class="br1"></i>';
            }
            $buff .= $item['desc'];
          }
          $buff .= '</div>';

          if($count == 1 && $count_elem > 2)
          {
            $buff .= '<div class="aq__q_tip__body__open_btn_wrap"><div class="aq__q_tip__body__open_btn" onclick="$(this).closest(\'.aq__q_tip__body\').addClass(\'aq__q_tip__body_show\');">Показать все</div></div>';
          }
          $count ++;
        }
      }
    }
    echo $buff;
  }
  else
  {
    echo 'no_result';
  }
}
?>