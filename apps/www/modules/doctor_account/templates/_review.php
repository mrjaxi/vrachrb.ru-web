<?php
if(count($reviews) > 0)
{
  if($ajax != 'y')
  {
    echo '<b style="margin-right: 40px;">История обращений</b>';
    if(count($reviews) > 1)
    {
    ?>
      <div class="sorting">
        <a href="" class="sorting__link sorting__link_active sorting_desc" onclick="HaReviewFilter.onFilter('date');return false;"><span>по дате</span></a>
      </div>
    <?php
    }
    echo '<i class="br10"></i>';
  }
  if($ajax != 'y')
  {
  ?>
    <div class="white_box dc_history_appeal_page">
      <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">
      <div class="pc_history">
  <?php
  }
    foreach ($reviews as $review)
    {
      ?>
      <div class="pc_history__item">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr valign="top">
            <td style="padding-right: 20px;">
              <i class="fs_13"><?php echo Page::rusDate($review['Question']['created_at']);?></i>
              <i class="br1"></i>
              <?php
              $user = $review['Question']['User'];
              $name = !$user['first_name'] ? $user['username'] : $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['second_name'];

              echo '<b class="pc_history__item__name">' . $name . '</b>';

              if($review['Question']['Consilium'][0])
              {
                echo '<a href="' . url_for('@doctor_account_consilium_show?id=' . $review['Question']['Consilium'][0]['id']) . '" class="dc_history_consiium_link">История консилиума</a>';
              }
              ?>
              <i class="br10"></i>
              <?php
              echo '<a href="' . url_for('@doctor_account_now_dialog_show?id=' . $review['question_id']) . '" class="pc_history__item__link">' . mb_substr($review['Question']['body'], 0, 380) . '...</a>';
              ?>
            </td>
            <td width="1">
              <img src="/i/n.gif" width="155" height="0" />
              <?php
              echo $review['body'] ? '<button class="btn_all blue_btn" onclick="readReview($(this));">Прочитать отзыв</button>' : '<span style="color: #00bcd4;">Отзыв не оставлен</span>';
              ?>
              <div class="read_review"><?php echo $review['body'];?></div>
              <i class="br10"></i>
              <span class="fs_12">Информативность</span>
              <i class="br5"></i>
              <div class="informative_stars">
                <input type="text" value="<?php echo $review['informative'];?>" class="stars_plugin" />
              </div>
              <i class="br10"></i>
              <span class="fs_12">Вежливость</span>
              <i class="br5"></i>
              <div class="courtesy_stars">
                <input type="text" value="<?php echo $review['courtesy'];?>" class="stars_plugin " />
              </div>
            </td>
          </tr>
        </table>
      </div>
      <?php
    }
    if($ajax != 'y')
    {
    ?>
      </div>
    </div>
    <?php
    }
}
else
{
?>
  <div class="pc_not_dialog">
    <div class="pc_not_dialog__inner">
      Обращения отсутствуют
    </div>
  </div>
<?php
}