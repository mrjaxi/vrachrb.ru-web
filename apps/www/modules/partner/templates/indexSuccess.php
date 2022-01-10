<?php
  slot('title', 'Партнёры');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Партнёры');?></h1>
<div class="white_box">
  <table width="100%" cellpadding="0" cellspacing="0">

    <?php
    foreach ($partner as $partner_item)
    {
      $partner_item_logo_png = str_replace('.png', '-S.png', $partner_item['logo']);
      $partner_item_logo_jpg = str_replace('.jpg', '-S.jpg', $partner_item_logo_png);
    ?>
      <tr valign="top">
        <td width="100%">
          <span class="fs_16"><?php echo $partner_item['title'];?></span>
          <i class="br5"></i>
          <span><?php echo $partner_item['body'];?></span>
          <i class="br5"></i>
          <a target="_blank" href="http://<?php echo $partner_item['link'];?>"><?php echo $partner_item['link'];?></a>
        </td>
        <td width="1" style="padding-left: 80px;">
          <img src="/i/n.gif" style="margin-bottom:10px;background:url(/u/i/<?php echo $partner_item_logo_jpg; ?>) no-repeat center;" width="250" height="85"  />
        </td>
      </tr>
      <tr>
        <td colspan="2"><i class="br30"></i></td>
      </tr>
    <?php
    }
    ?>

  </table>
</div>
