<?php
if(count($now_councils) > 0)
{
?>
  <div class="white_box dc_curr_consiliums">
    <b>Текущие консилиумы</b>
    <?php
    foreach ($now_councils as $now_consilium)
    {
      $now_consilium_specialist = $now_consilium['Consilium']['Question']['Specialists'][0];
      $now_consilium_user = $now_consilium_specialist['User'];
      ?>
      <div class="dc_curr_consiliums__item">
        <i class="fs_12"><?php echo Page::rusDate($now_consilium['Consilium']['created_at']);?></i>
        <i class="br1"></i>
        <?php
        $now_councils_name = $now_consilium_user['first_name'] ? $now_consilium_user['first_name'] . ' ' . $now_consilium_user['middle_name'] . ' ' . $now_consilium_user['second_name'] : $now_consilium_user['username'];
        echo '<a href="' . url_for('@specialist_index') . $now_consilium_specialist['title_url'] . '/">' . $now_councils_name . '</a><i class="br1"></i>';
        ?>
        <span class="fs_12"><?php echo $now_consilium_specialist['about'];?></span>
        <a href="<?php echo url_for('@doctor_account_consilium_show?id=' . $now_consilium['Consilium']['id']);?>" class="dc_consilium_page_icon dc_curr_consiliums__go_mess" title="Перейти в консилиум"></a>
      </div>
      <?php
    }
    ?>
  </div>
<?php
}
?>
