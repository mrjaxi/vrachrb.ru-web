<?php
slot('title', 'Текущий консилиум');
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

<script type="text/javascript">
  questionId = <?php echo $councils[0]['question_id'];?>;
</script>

<div class="overlay dc_overlay_shhet_history_details" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();"></div>
</div>

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
        <?php
        include_partial('doctor_account/consilium', array('councils' => $councils));
        ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <?php
      echo '<div class="notice_wrap">';
      include_component('main', 'notice', array('profile' => 's'));
      echo '</div>';
      if($councils_count > 0)
      {
        echo '<div class="white_box dc_members_concilium">';
        echo '<b>Участники консилиума</b>';
        echo '<div class="dc_members_concilium_ajax_wrap">';
        foreach ($councils[0]['ConsiliumSpecialist'][0]['Consilium']['Specialists'] as $consilium_specialist_key => $consilium_specialist)
        {
          if($consilium_specialist['id'] != $account_specialist[0])
          {
            echo '<div class="dc_members_consilium__item">';
            $name = $consilium_specialist['User']['first_name'] ? $consilium_specialist['User']['first_name'] . ' ' . $consilium_specialist['User']['middle_name'] . ' ' . $consilium_specialist['User']['second_name'] : $consilium_specialist['User']['username'];
            echo '<a class="members_of_council_link" href="' . url_for('@specialist_index') . $consilium_specialist['title_url'] . '/">' . $name . '</a>';
            echo '<i class="br1"></i>';
            echo '<span class="fs_12">' . $consilium_specialist['about'] . '</span>';
            if($responsible_specialist)
            {
              echo '<span class="dc_consilium_page_icon dc_members_concilium__delete" onclick="consiliumSpecialistDelete($(this),\'' . $consilium_id . '_' . $consilium_specialist['id'] . '\');" title="Удалить из консилиума"></span>';
            }
            echo '</div>';
          }
        }

        echo '</div>';

        if($responsible_specialist)
        {
          ?>
          <i class="br20"></i>
          <div class="consilium_specialist_add_btn_wrap">
            <button class="btn_all green_btn dc_members_concilium__btn consilium_specialist_add_btn" onclick="consiliumSpecialistAddInfo();">Добавить врача</button>
            <img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
          </div>
          <?php
        }

        echo '</div>';

        include_component('doctor_account', 'now_councils', array('consilium_id' => $consilium_id));
      }
      ?>
    </td>
  </tr>
</table>