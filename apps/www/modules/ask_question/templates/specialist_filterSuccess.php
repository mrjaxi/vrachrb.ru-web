<?php
$specialist_count = count($specialists);
if($specialist_count > 1)
{
  $rand_arr = array();
  foreach ($specialists as $specialist)
  {
    $rand_arr[] = $specialist['id'];
  }
?>
  <label class="specialist_page__item fl_l">
    <input type="radio" class="n_cust rating_doctors__item_inp" checked="checked" name="q_specialist" value="<?php echo $select_admin ? 51 : $rand_arr[array_rand($rand_arr)];?>">
    <div class="rating_doctors__item rating_doctors__item_any_doctor">
      <div class="rating_doctors__item__any_name">Любой врач</div>
      <div class="rating_doctors__item__any_name_about">Мы сами выберем какому специалисту адресовать Ваш вопрос</div>
    </div>
  </label>
<?php
}

foreach ($specialists as $specialist)
{
  $time_difference = strtotime(date('Y-m-d' . ' ' . 'H:i:s')) - strtotime($specialist['Specialist_online'][0]['date']);
  $doctor_status = $time_difference < sfConfig::get('app_waiting_time_online') ? 'rating_doctors_online' : '';
?>
  <label class="specialist_page__item fl_l">
    <input type="radio" <?php echo $specialist_count == 1 ? 'checked="checked"' : '';?> class="n_cust rating_doctors__item_inp" name="q_specialist" value="<?php echo $specialist['id'];?>" />
    <div class="rating_doctors__item <?php echo $doctor_status;?>">
      <?php
      $photo = ($specialist['User']['photo'] ? Page::replaceImageSize($specialist['User']['photo'],'S') : false) ;
      echo '<div ' . ($photo ? 'data-photo_url="' . $photo . '"' : '') . ' class="rating_doctors__item__photo" ' . ($photo ? 'style="background-image: url(\'/u/i/' . $photo . '\');"' : '') . '></div>';
      echo '<a class="rating_doctors__item__link" href="' . url_for('@specialist_index') . $specialist['title_url'] . '/">' . $specialist['User']['first_name'] . ' ' . $specialist['User']['second_name'] . '</a>';
      ?>
      <i class="br5"></i>
      <div class="rating_doctors__item__pos"><?php echo $specialist['about'];?></div>
      <table cellpadding="0" cellspacing="0" class="rating_doctors__item__num">
        <tr valign="top">
          <?php
          echo '<td class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;"><span class="fs_20">' . number_format($specialist['rating'], 1, ',', '') . '</span><br/><span class="fs_13">рейтинг</span></td>';
          $answer_count = number_format($specialist['answers_count'], 0, ',', ' ');
          echo '<td class="tcolor_red" style="padding-left: 10px;"><span class="fs_20">' . $answer_count . '</span><br/><span class="fs_13">консультаци' . Page::niceRusEnds($answer_count, 'я', 'и', 'й') . '</span></td>';
          ?>
        </tr>
      </table>
    </div>
  </label>
<?php
}
?>
