<?php
  slot('title', __('Поиск'));

$search_cfg = $search_cfg->getRawValue();

if(!empty($query))
{
  $cres = $res->getRaw(0);
  
  if($cres && $cres['total_found'] > 0)
  {
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Поиск</h2>
<div class="white_box">
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="200">
        <ul class="search_menu_l">
          <li><span onclick="$('.search__item').show();$('.search_menu_l__link').removeClass('search_menu_l__link_active');$(this).addClass('search_menu_l__link_active');" class="search_menu_l__link search_menu_l__link_active">Все (<?php echo $cres['total_found'];?>)</span></li>
          <?php
          $buff = '';
          $crs = $rs->getRawValue();
          foreach($crs as $rcat => $ritem)
          {
            $search_cfg_item = $search_cfg[$rcat];
            echo '<li><span onclick="$(\'.search__item\').hide();$(\'.search__item__' . $rcat . '\').show();$(\'.search_menu_l__link\').removeClass(\'search_menu_l__link_active\');$(this).addClass(\'search_menu_l__link_active\');" class="search_menu_l__link">' . $search_cfg_item['cat'] . ' (' . count($ritem) . ')</span></li>';
            foreach($ritem as $itemk => $item)
            {
              $buff .= '<p class="search__item search__item__' . $rcat . '">';
              if($item['title'] != '-')
              {
                $url = $item['link'];
                if(substr_count($url, 'question_answer/show') > 0)
                {
                  $url_exp = explode('=', $url);
                  $url = '@question_answer_show?id=' . $url_exp[1];
                }
                $buff .= '<a href="' . url_for($url) . '">' . $item['title'] . '</a>';
              }
              if($item['desc'])
              {
                if($item['title'] != '-')
                {
                  $buff .= '<i class="br1"></i>';
                }
                $buff .= $item['desc'];
              }
              $buff .= '</p>';
            }
          }
          ?>
        </ul>
      </td>
      <td>

        <?php
          echo $buff;
        ?>

      </td>
    </tr>
  </table>
</div>

<?php
  }
  else
  {
    echo '<div class="i">' . __('Ничего не найдено') . '</div>';
  }
}
?>