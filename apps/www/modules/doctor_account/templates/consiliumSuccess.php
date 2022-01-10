<?php
  slot('title', 'Консилиумы');
  use_javascript('consilium.js');

  $councils_count = count($councils);
  $consilium_answer_count = count($councils[0]['Consilium_answer']);
  $question = $councils[0]['Question'];
  $question_user = $question['User'];
  $account_specialist = $sf_user->getAccount()->getSpecialist();
  $question_specialist = $councils[0]['Question']['Specialists'][0];
  $consilium_closed = $councils[0]['closed'] == 1 ? true : false;
  $consilium_id = $councils[0]['id'];

  if($account_specialist[0] == $question_specialist['id'])
  {
    $responsible_specialist = true;
  }
?>
<div class="overlay dc_overlay_call_meeting" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form class="overlay__white_box consilium_specialist_add_form" onclick="event.stopPropagation();">
    <div class="fs_18" style="padding: 0 20px;">Добавьте врачей в консилиум</div>
    <div style="padding-bottom: 0;" class="ta_l overlay__white_box__body">
      <div class="dc_call_meeting"></div>
    </div>
    <div class="overlay__white_box__dialog clearfix">
      <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" onclick="consiliumSpecialistAdd();return false;">Добавить в консилиум</button>
    </div>
  </form>
</div>
<div class="overlay dc_overlay_consilium_details" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();"></div>
</div>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('doctor_account', 'menu');
      echo '</div>';
      ?>
      <div class="pc_user_page_wrap">
        <div style="padding-top: 0;" class="white_box">
        <?php
        include_partial('doctor_account/consilium_list');
        ?>
        </div>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 's'));?>
      </div>
      <div style="min-width:300px;"></div>
    </td>
  </tr>
</table>