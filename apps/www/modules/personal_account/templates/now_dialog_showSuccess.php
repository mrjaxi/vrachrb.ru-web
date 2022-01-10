<?php
use_javascript('jquery.liteuploader.js');
use_javascript('atmaUI.js');
use_javascript('fotorama.js');
use_stylesheet('fotorama.css');

$sf_prefix = isset($_SERVER['PATH_PREFIX']) ? $_SERVER['PATH_PREFIX'] : '';
$sf_user = $sf_user->isAuthenticated() ? $sf_user->getUserId() : 'false';

slot('download_photo_script');
sprintf($path_prefix = isset($_SERVER['PATH_PREFIX']) ? $_SERVER['PATH_PREFIX'] : '');
echo sprintf('<script>');
echo sprintf('var sf_app = "' . sfConfig::get('sf_app') . '";');
echo sprintf('var sf_prefix = "' . $sf_prefix . '";');
echo sprintf('var sf_user = "' . $sf_user . '";');
echo sprintf('var sf_ws_pub = "' . sfConfig::get('app_ws_pub') . '";');
echo sprintf('</script>');
end_slot();

slot('title', 'Текущая беседа');
use_javascript('pa-now-dialog.js');
?>
<script type="text/javascript">
  questionId = <?php echo $sf_request->getParameter('id');?>;
</script>
<?php
include_component('personal_account', 'review_form');
?>
<div class="overlay overflow_attachment" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();"></div>
<div class="overlay overlay_photo" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();"></div>
<div class="overlay pc_overlay_invation" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form id="pc_now_dialog_my_reception_form" action="<?php echo url_for('@personal_account_now_dialog_answer');?>" method="post" class="overlay__white_box" onclick="event.stopPropagation();">

    <div class="pc_now_dialog_my_reception_form__item">
      <div class="fs_18">Приглашение на очный приём</div>
      <div class="ta_l overlay__white_box__body">
        <i class="br30"></i>
        <b>Место приёма:</b>
        <i class="br5"></i>
        <div id="pc_overlay_invation__receive_location">
          Центр Тибетской медицины<br />г. Уфа, проспект Октября, 5/1
        </div>
        <i class="br30"></i>
        <b>Цена приёма:</b>
        <i class="br5"></i>
        <div id="pc_overlay_invation__receive_price">
          Согласно утверждённому прайсу учреждения
        </div>
        <i class="br30"></i>
        <b>Время и дата</b>
        <i class="br5"></i>
        <div id="pc_overlay_invation__receive_datetime">
          <label style="margin: 0 20px 5px 0;"><input type="radio" name="time_n_date" />4 октября, 15:00</label>
          <label style="margin: 0 20px 5px 0;"><input type="radio" name="time_n_date" />4 октября, 17:00</label>
          <label style="margin: 0 20px 5px 0;"><input type="radio" name="time_n_date" />6 октября, 19:00</label>
          <label style="margin: 0 20px 5px 0;"><input type="radio" name="time_n_date" />6 октября, 20:00</label>
        </div>
        <div id="pc_overlay_invation__receive_reject_reason">
          <i class="br30"></i>
          <b>Причина отказа:</b>
          <i class="br5"></i>
          <textarea placeholder="Пожалуйста, укажите причину отказа" name="reception_answer_reject_reason" rows="2" style="width: 100%;resize:vertical;"></textarea>
        </div>
      </div>
      <div class="overlay__white_box__dialog clearfix">
        <button onclick="paNowDialogMyReceptionAnswer('n');return false;" class="btn_all overlay__white_box__dialog__btn red_btn">Отказаться</button>
        <button onclick="paNowDialogMyReceptionAnswer('y');return false;" class="btn_all overlay__white_box__dialog__btn blue_btn">Согласиться</button>
      </div>
    </div>

    <div class="live_reception_message" style="display: none;"><b class="fs_16">Согласие на прием отправлено.<i class="br5"></i>Врач примет вас в указанное время.</b><i class="br30"></i></div>
  </form>
</div>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('personal_account', 'menu');
      echo '</div>';
      include_component('personal_account', 'now_dialog');
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