<?php
if($type == 'question' || $type == 'answer')
{
?>
  <div class="live_band__item new_live_band__item <?php echo ($type == 'answer' && $s_name != '' ? 'live_band__item_answer' : '') . ($type == 'answer' && $s_name == '' ? ' live_band__item_patient' : '');?>">
    <table cellpadding="0" cellspacing="0" height="100%" width="100%">
      <tbody>
      <?php
      if($s_name != '')
      {
      ?>
      <tr>
        <td colspan="2" class="live_band__item__author live_band__item__block" height="30">
          <table cellpadding="0" cellspacing="0" height="100%" width="100%">
            <tbody><tr valign="middle">
              <td class="<?php echo ($type != 'answer' ? 'live_band__item__author__name' : '');?>" width="1">
                <?php
                echo ($type == 'answer' ? '<img style="background:' . ($s_photo != '' ? 'url(/u/i/' . Page::replaceImageSize($s_photo, 'S') . ') no-repeat 50% 50%;background-size:cover' : '#fff') . ';" src="/i/n.gif" class="live_band__item_answer__doc_img" height="40" width="40">' : ($s_title_url != 'отвечает:' ? '' : ''));
                ?>
              </td>
              <td>
                <?php
                $str_about = Page::strCut(strip_tags($s_about), 200);
                if($s_title_url != '')
                {
                  echo $s_id == 51 ? '<span>Врач РБ. </span><span>Сервис медицинской консультации </span>' : '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $s_title_url . '/">' . $s_name . '</a>. <span ' . ($str_about[1] == 'full' ? 'title="' . $s_about . '"' : '') . '>' . $str_about[0] . '</span>';
                }
                ?>
              </td>
              <td class="live_band__item__author__name" width="1">
                <?php
                if($sp_id != '')
                {
                  echo 'кабинет:';
                }
                ?>
              </td>
              <td width="1">
                <?php
                if($sp_id != '')
                {
                  $sp_about_str = Page::strCut($sp_about, 50);
                  echo '<a href="' . url_for('@categories_transition_filter?id=' . $sp_id) . '" class="live_band__item__author_link" ' . ($sp_about_str[1] == 'full' ? 'title="' . $sp_about . '"' : '') . '>' . $sp_about_str[0] . '</a>';
                }
                ?>
              </td>
            </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <?php
      }
      else
      {
      ?>
        <tr>
          <td colspan="2" class="live_band__item__block live_band__item_patient_author" height="40">
            <?php
            $age = date('Y') - substr($u_birth_date, 0, 4);
            echo '<b>' . Page::nameAge($age, $u_gender) . ($age == date('Y') ? '' : ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет')) . '</b>';
            ?>
          </td>
        </tr>
      <?php
      }
      ?>
      <tr>
        <td colspan="2" class="live_band__item__block">
          <?php
          echo '<' . ($q_id != '' ? 'a href="' . url_for('@question_answer_show?id=' . $q_id) . '"' : 'div') . ' class="live_band__item__body ' . ($type == 'answer' ? 'live_band__item__body_answer' : '') . '">';
          echo '<div class="live_band__item__body__text' . ($type == 'answer' ? ' live_band__item__body__text_answer' : '') . '">' . $q_body . '</div>';
          echo '</' . ($q_id != '' ? 'a' : 'div') . '>';
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="live_band__item__block">
          <div class="live_band__item__bottom_line"></div>
        </td>
      </tr>
      <tr class="live_band__item__bottom">
        <td colspan="2" class="live_band__item__block" height="30">
          <table cellpadding="0" cellspacing="0" width="100%">
            <tbody><tr>
              <td align="left" width="145"><?php echo Page::rusDate($q_created_at) . ($type == 'answer' ? ', ' . substr($q_created_at, 10, 6) : '');?></td>
              <td align="left" width="200">
                <?php
                if($s_name != '' && $type != 'answer')
                {
                  $live_ribbon_answer_value = 'Без ответа';
                  if($a_count > 0)
                  {
                    $a_last_date = strtotime($a_last_date);
                    $q_last_date = strtotime($q_created_at);
                    if(isset($a_last_date) && isset($q_last_date) && $a_last_date > $q_last_date)
                    {
                      $time = $a_last_date - $q_last_date;
                      $live_ribbon_answer_value = 'Ответ получен (' . Page::timeUnit($time, array(), 'cut') . ')';
                    }
                  }
                  echo $live_ribbon_answer_value;
                }
                ?>
              </td>
              <td>
                <?php
                if($s_name != '' && $type != 'answer')
                {
                  echo (isset($q_closed_by) ? '✓&nbsp;Вопрос закрыт' : 'В обсуждении') . ' (' . $a_count . '&nbsp;сообщ.)';
                }
                ?>
              </td>
              <td width="1">
                <?php
                if($s_name != '' && $type != 'answer')
                {
                  echo '<a href="' .url_for('@question_answer_index') . '" class="live_band__item__adv_link">Вопросы и ответы</a>';
                }
                ?>
              </td>
            </tr></tbody>
          </table>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
<?php
}
?>