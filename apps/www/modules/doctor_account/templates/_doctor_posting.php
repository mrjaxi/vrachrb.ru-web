<?php
if(count($doctor_posting) > 0)
{
  foreach ($doctor_posting as $item)
  {
    ?>
    <div class="dc_posting__item all_link_item">
      <table>
        <tr valign="top">
          <td class="all_link_item">
            <?php
            if($item['photo'])
            {
              echo '<img src="/i/n.gif" width="115" height="85" style="margin-right: 20px;background:url(/u/i/' . Page::replaceImageSize($item['photo'], 'S') . ') no-repeat center;background-size:cover;" class="imgs_grey_shd" />';
            }
            ?>
          </td>
          <td class="all_link_item" <?php echo !$item['photo'] ? 'style="overflow:hidden;"' : '';?>>
            <i class="fs_12"><?php echo Page::rusDate($item['created_at']);?></i>
            <i class="br1"></i>
            <?php
            echo '<a href="' . url_for('@' . ($type == 'tip' ? 'tip' : 'article') . '_index') . $item['title_url'] . '/" class="dc_posting__item__link fs_16"><b>' . $item['title'] . '</b></a>';
            ?>
            <i class="br5"></i>
            <?php echo $item['description'];?>
          </td>
        </tr>
      </table>
    </div>
    <?php
  }
}
else
{
?>
  <div class="pc_not_dialog">
    <div class="pc_not_dialog__inner">
      Нет публикаций
    </div>
  </div>
<?php
}
