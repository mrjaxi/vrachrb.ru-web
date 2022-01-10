<?php
include_component('personal_account', 'review_form');
?>
<script type="text/javascript">
  questionId = <?php echo $sf_request->getParameter('id');?>;
</script>

<div class="overlay dc_overlay_shhet_history_details" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();"></div>
</div>

<div class="overlay overlay_photo" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();"></div>

<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Амбулаторная карта пациента</h2>

<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
      include_component('doctor_account', 'menu');
      echo '</div>';
      include_component('personal_account', 'now_dialog', array('doctor_account' => true));
      ?>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice');?>
      </div>
      <div style="min-width:300px;"></div>
    </td>
  </tr>
</table>