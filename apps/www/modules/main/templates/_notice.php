<?php
if(count($notice) > 0)
{
  echo '<div style="min-width: 300px;" class="white_box pc_curr_dialogues">';
    echo '<b>Уведомления</b>';
    $notice_exist_arr = array();
    foreach ($notice as $notice_item)
    {
      if($profile != 's')
      {
        $name = $notice_item['User']['Question'][0]['Specialists'][0]['User'];
        $about = $notice_item['User']['Question'][0]['Specialists'][0]['about'];
        $name_tags = $notice_item['User']['Question'][0]['Specialists'][0]['id'] != 51 ? 'a' : 'div';
      }
      else
      {
        $name = $notice_item['type'] == 'consilium' ? $notice_item['User']['Specialist'][0]['Questions'][0]['User'] : $notice_item['User']['Specialist'][0]['Questions'][0]['User'];
        $about = '';
        $age_value = (date('Y') - substr($name['birth_date'], 0, 4));
        $age = $age_value != date('Y') ? ', ' . (date('Y') - substr($name['birth_date'], 0, 4)) . ' ' . Page::niceRusEnds((date('Y') - substr($name['birth_date'], 0, 4)), 'год', 'года', 'лет') : '';
        $name_tags = 'div';
      }
      if(!in_array($notice_item['type'] . $notice_item['inner_id'] . $notice_item['event'], $notice_exist_arr))
      {
        ?>
        <div class="pc_curr_dialogues__item">
          <?php
          echo '<div class="pc_curr_dialogues__item__close" onclick="noticeDelete(this, \'' . $notice_item['id'] . '\')">×</div>';
          echo '<' . $name_tags . ($notice_item['User']['Question'][0]['Specialists'][0]['id'] != 51 ? ' href="' . url_for('@specialist_index') . $notice_item['User']['Question'][0]['Specialists'][0]['title_url'] . '/"' : '') . '>' .  ($notice_item['User']['Question'][0]['Specialists'][0]['id'] == 51 ? 'Врач РБ' : $name['second_name'] . ' ' . $name['first_name'] . $age) . '</' . $name_tags . '>';
          ?>
          <i class="br5"></i>
          <span class="pc_curr_dialogues__item__pos"><?php echo $about;?></span>
          <?php
          $link_class = 'pc_curr_dialogues_s_green';
          $notice_tag = 'a';
          if(substr($notice_item['event'], -6) == 'closed' || $notice_item['event'] == 'reception_no' || $notice_item['event'] == 'consilium_specialist_delete')
          {
            $link_class = 'pc_curr_dialogues_s_grey';
          }
          if($notice_item['event'] == 'consilium_specialist_delete')
          {
            $notice_tag = 'div';
          }
          echo '<' . $notice_tag . ' href="' . Page::notice($notice_item['type'], $notice_item['inner_id'], $notice_item['event'], 'link', $profile) . '" class="pc_curr_dialogues__item__status ' . $link_class . '">' . Page::notice($notice_item['type'], $notice_item['inner_id'], $notice_item['event'], 'name') . '</' . $notice_tag . '>';
          ?>
        </div>
        <?php
      }
      $notice_exist_arr[] = $notice_item['type'] . $notice_item['inner_id'] . $notice_item['event'];
    }
  echo '</div>';
}
?>