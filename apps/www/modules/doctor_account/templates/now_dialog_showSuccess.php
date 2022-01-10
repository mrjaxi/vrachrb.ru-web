<?php
slot('title', 'Текущие беседы');
use_javascript('sexypicker.js');
use_javascript('now-dialog.js?06062016');
use_javascript('fotorama.js');
use_stylesheet('fotorama.css');
?>
<style type="text/css">
  .pc_user_page_wrap .pc_tabs{
    display: none;
  }
</style>
<script type="text/javascript">
  questionId = <?php echo $sf_request->getParameter('id');?>;
</script>
<div class="overlay overflow_attachment" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();"></div>
<div class="overlay dc_overlay_shhet_history_details" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();"></div>
</div>
<div class="overlay dc_overlay_patient_card_details" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();"></div>
</div>
<div class="overlay overlay_photo" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();"></div>
<div class="overlay dc_overlay_write_directions" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form id="please_analysis_form" class="overlay__white_box" onclick="event.stopPropagation();">
    <div class="please_analysis_form__item">
      <div class="fs_18" style="padding: 0 20px;">Назначение на анализы и обследования</div>
      <div class="ta_l overlay__white_box__body">

        <ol class="q_sheet_history__ol dc_history_inps">
          <li class="nw q_sheet_history__ol_inp">
            <select name="inp_0" class="plese_analysis_select">
            <?php
            foreach ($analysis_type as $a_type)
            {
              echo '<option value="' . $a_type->getId() . '">' . $a_type->getTitle() . '</option>';
            }
            ?>
            </select>
            <span class="delete_btn_all delete_btn_all_hidden" title="Удаление" onclick="$(this).parent().remove();vrb.formInpClone.indexed('.q_sheet_history__ol_inp');"></span>
          </li>
        </ol>

        <button class="btn_all green_btn" style="border-radius: 2px;margin-left: 20px;" onclick="vrb.formInpClone.add('.q_sheet_history__ol_inp');return false;">Добавить</button>

      </div>
      <div class="overlay__white_box__dialog clearfix">
        <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" onclick="nowDialogPleaseAnalysis();return false;">Отправить</button>
      </div>
    </div>
    <div class="please_analysis_form__thx">
      <b class="ta_c fs_18">Назначения прикреплены</b>
    </div>
  </form>
</div>

<div class="overlay dc_overlay_call_meeting" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form method="post" action="<?php echo url_for('@doctor_account_now_dialog_answer');?>" class="overlay__white_box consilium_specialist_add_form" onclick="event.stopPropagation();" onsubmit="nowDialogAddConsilium();return false;">
    <div class="fs_18" style="padding: 0 20px;">Добавьте врачей в консилиум</div>
    <div style="padding-bottom: 0;" class="ta_l overlay__white_box__body">
      <div class="dc_call_meeting"></div>
    </div>
    <div class="overlay__white_box__dialog clearfix">
      <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;">Добавить в консилиум</button>
    </div>
  </form>
</div>

<div class="overlay dc_overlay_now_dialog_complaint" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form method="post" action="<?php echo url_for('@doctor_account_now_dialog_answer');?>" class="overlay__white_box now_dialog_complaint_form" onclick="event.stopPropagation();">
    <div class="fs_18" style="padding: 0 20px;">Введите текст жалобы</div>
    <i class="br10"></i>
    <span class="fs_12">(после отправки жалобы беседа закроется до проверки администратором)</span>
    <div style="padding-bottom: 0;" class="ta_l overlay__white_box__body">
      <div class="dc_call_meeting">
        <?php
        echo '<input name="complaint[question_id]" value="' . $sf_request->getParameter('id') . '" type="hidden">';
        echo $complaint_form['body'];
        echo $complaint_form->renderGlobalErrors();
        echo $complaint_form->renderHiddenFields();
        ?>
      </div>
    </div>
    <div class="overlay__white_box__dialog clearfix">
      <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" disabled="true">Отправить</button>
    </div>
  </form>
</div>

<div class="overlay dc_overlay_invation" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form method="post" action="<?php echo url_for('@doctor_account_now_dialog_answer');?>" id="now_dialog_invite_live_reception_form" class="overlay__white_box" onclick="event.stopPropagation();" onsubmit="nowDialogInviteLiveReception();return false;">
    <div class="now_dialog_invite_live_reception_form_item">
      <div class="fs_18" style="padding: 0 20px;">Приглашение на очный приём</div>
      <div class="ta_l overlay__white_box__body">
        <i class="br30"></i>
        <?php
        echo $reception_form->renderHiddenFields();
        echo $reception_form->renderGlobalErrors();
        ?>
        <div id="now_dialog_work_place_wrap">
          <b>Место работы:</b>
          <i class="br5"></i>
          <div style="width: 440px;">
            У вас не указано ни одного места работы.<br>Пожалуйста, заполните данные в <a href="">Личном кабинете</a> для возможности приглашения на очный приём
          </div>
        </div>
        <i class="br30"></i>
        <b>Приём:</b>
        <i class="br5"></i>

        <div class="dc_price_admission_charge_wrap">
          <label style="margin-right: 20px;"><input type="radio" name="price_admission" checked value="free" onchange="$('.dc_price_admission_charge').slideUp(200);" />&nbsp;Бесплатный</label>
          <label><input type="radio" name="price_admission" value="money" onchange="$('.dc_price_admission_charge').slideDown(200);" />&nbsp;Платный</label>
          <div class="dc_price_admission_charge" style="display: none;">
            <i class="br10"></i>
            Цена за прием&nbsp;<input id="price_admission_money_number" type="text" name="price_admission_money_number" size="5" />&nbsp;<span class="rub">a</span>
            <i class="br10"></i>
            <label class="confirmation_checkbox fs_12"><input id="price_admission_price_institutions" name="price_admission_price_institutions" type="checkbox" />Согласно утвержденному прайсу учреждения</label>
            <i class="br10"></i>
          </div>
        </div>

        <i class="br30"></i>
        <div class="now_dialog_datetime_wrap">
          <b>Дата и время приёма:</b>
          <i class="br5"></i>
          <ol class="q_sheet_history__ol">
            <li class="dc_invation_inp">
              <input type="text" name="inp_0" value="" size="10" placeholder="дд.мм.гггг" />
              <select name="hh_0" id="" class="custom_select">
                <option value="">чч</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
              </select>
              :
              <select name="mm_0" id="" class="custom_select">
                <option value="">мм</option>
                <option value="09">00</option>
                <option value="10">10</option>
                <option value="11">15</option>
                <option value="12">30</option>
                <option value="13">45</option>
              </select>
              <span class="delete_btn_all delete_btn_all_hidden" title="Удаление" onclick="$(this).parent().remove();vrb.formInpClone.indexed('.dc_invation_inp');"></span>
            </li>
          </ol>
          <button class="btn_all green_btn" style="border-radius: 2px;margin-left: 20px;" onclick="vrb.formInpClone.add('.dc_invation_inp');return false;">Добавить</button>
          <script type="text/javascript">
            $(document).ready(function(){
              $('.dc_invation_inp input').spinpicker({lang:"ru"}).focus();
            });
          </script>
        </div>
      </div>
      <div class="overlay__white_box__dialog clearfix">
        <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width: 100%;">Отправить приглашение</button>
      </div>
    </div>
    <div class="live_reception_message"><b class="fs_16">Приглашение отправлено</b><i class="br30"></i></div>
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

<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%>
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('doctor_account', 'menu');
      echo '</div>';
      ?>
      <div class="pc_user_page_wrap da_now_dialog_show_wrap">
        <?php
        include_component('doctor_account', 'now_dialog', array('now_dialog_show' => true, 'answer_edit' => true));
        ?>
      </div>
    </td>
    <td style="width:300px;" width="300" style="padding-left: 20px;">
      <div style="width:300px;"></div>
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 's'));?>
      </div>
      <?php

      /*
            <div class="white_box dc_invitation_meeting">
        Вас добавили в консилиум.<br />Ваше мнение очень важно.
        <i class="br10"></i>
        <a href="" class="btn_all blue_btn">Перейти в консилиум</a>
      </div>

      <div class="white_box pc_curr_dialogues">
        <b>Текущие беседы</b>
        <div class="pc_curr_dialogues__item">
          <i class="fs_12">20 августа 2015</i>
          <i class="br5"></i>
          <a href="">Ольга Петровна, 23 года</a>
          <a href="" class="pc_curr_dialogues__item__status pc_curr_dialogues_s_green">Результаты анализов</a>
          <span class="dc_incoming_mes" title="Новое сообщение">1</span>
        </div>

        <div class="pc_curr_dialogues__item">
          <i class="fs_12">20 августа 2015</i>
          <i class="br5"></i>
          <a href="">Светлана Игоревна, 55 лет</a>
          <a href="" class="pc_curr_dialogues__item__status pc_curr_dialogues_s_green">Ответил администратор</a>
        </div>

        <div class="pc_curr_dialogues__item">
          <i class="fs_12">20 августа 2015</i>
          <i class="br5"></i>
          <a href="">Мирослав Витязевич, 33 года</a>
          <a href="" class="pc_curr_dialogues__item__status pc_curr_dialogues_s_red">Вы не ответили</a>
        </div>

      </div>
      */
      ?>
    </td>
  </tr>
</table>