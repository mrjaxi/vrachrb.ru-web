<?php
if(count($analysis) > 0)
{
  echo '<div class="white_box  pc_history_test" ' . ($specialist ? 'style="margin-left:20px;"' : '') . '>';
  echo '<a href="' . url_for('@account_history_test?user_id=' . $user_id) . '" class="h_link anb"><b>История анализов</b></a>';
  foreach ($analysis as $a)
  {
    ?>
    <div class="pc_history_test__item">
      <i class="fs_13 pc_history_test__item__date"><?php echo Page::rusDate($a['created_at']);?></i>
      <i class="br1"></i>
      <a href="<?php echo url_for('@account_history_test_show?user_id=' . $user_id . '&id=' . $a['id']);?>" class="pc_history_test__item__link now_analysis_link"><?php echo $a['Analysis_type']['title'];?></a>
    </div>
    <?php
  }
  echo '</div>';
}
?>