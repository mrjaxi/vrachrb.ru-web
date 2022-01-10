<?php
echo '<div class="patient_card_left_block">';
  if(count($patient_card) > 0)
  {
    foreach ($patient_card as $pc)
    {
      if($pc['specialist_photo'])
      {
        $photo = 'style="background:url(\'/u/i/' . Page::replaceImageSize($pc['specialist_photo'], 'S') . '\') no-repeat 50% 50%;background-size:cover;"';
      }
      ?>
      <div class="pc_history__item ">
        <div class="pc_history__item__author">
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr valign="middle">
              <td style="padding-right: 6px;" width="1">
                <img <?php echo $photo;?> src="/i/n.gif" width="40" height="40" class="pc_history__item__author__img">
              </td>
              <td>
                <?php
                $str_about = Page::strCut(strip_tags($pc['specialist_about']), 200);
                echo '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $pc['Specialists'][0]['title_url'] . '/">' . str_replace('  ', ' ', $pc['specialist_name']) . '</a>. <span ' . ($str_about[1] == 'full' ? 'title="' . $pc['specialist_about'] . '"' : '') . '>' . $str_about[0] . '</span>';
                ?>
              </td>
              <?php
              if(!$pc['Review'][0] && !$doctor_account)
              {
                ?>
                <td valign="middle" align="right">
                  <button class="btn_all b_white_btn review_add_btn" data-question_id="<?php echo $pc['id'];?>" onclick="overflowHiddenScroll($('.pc_overlay_review'));questionIdForReview(this, <?php echo $pc['id'];?>);">Оставить отзыв</button>
                </td>
                <?php
              }
              ?>
            </tr>
          </table>
        </div>
        <?php
        $last_answer_exp = explode(':division:', $pc['last_answer']);
        $last_answer = 'Беседа закрыта';
        if($last_answer_exp[1] == $pc['specialist_user_id'])
        {
          $last_answer = $last_answer_exp[0];
        }
        $url = $doctor_account ? 'doctor_account_patient_card' : 'personal_account_now_dialog';
        echo '<a href="' . url_for('@' . $url . '_show?id=' . $pc['id']) . '" class="pc_history__item__link">' . $last_answer . '</a>';
        ?>
        <div class="pc_chat__item__bottom"><?php echo Page::rusDate($pc['closing_date']);?></div>
      </div>
      <?php
    }
  }
  else
  {
    ?>
    <div class="pc_not_dialog">
      <div class="pc_not_dialog__inner">
        Амбулаторная карта отсутствует
      </div>
    </div>
    <?php
  }
echo '</div>';
?>