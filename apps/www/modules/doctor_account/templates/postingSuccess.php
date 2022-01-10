<?php
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
slot('redactor_css', '<link rel="stylesheet" type="text/css" media="screen" href="/js/arm/redactor/css/redactor.css" />');
  use_javascript('jquery-migrate-1.2.1.min.js');
  use_javascript('arm/plugins.js');
  use_javascript('arm/perfect-scrollbar.min.js');
  use_javascript('chosen.jquery.js');
  use_javascript('arm/redactor/redactor.js');
  use_javascript('jquery.liteuploader.js');
  use_javascript('atmaUI.js');
  use_javascript('arm/arm.js');
  use_javascript('arm/init.d.js');
  slot('title', 'Публикации');
?>
<div class="overlay dc_overlay_posting" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div style="min-width: 600px;" class="overlay__white_box">
    <div class="dc_overlay_posting__thx">Совет добавлен!</div>
    <form class="dc_posting_form" action="<?php echo url_for('@doctor_account_posting');?>" onclick="event.stopPropagation();" method="post">
      <?php
      echo $prompt_form->renderGlobalErrors();
      echo $prompt_form->renderHiddenFields();
      ?>
      <div class="fs_18" style="padding: 0 20px;">Добавление совета</div>
      <div class="ta_l overlay__white_box__body">
        Название совета
        <i class="br5"></i>
        <?php echo $prompt_form['title'] . $prompt_form['title']->renderError();?>
        <i class="br20"></i>
        Краткое описание (255 символов)
        <i class="br5"></i>
        <?php echo $prompt_form['description'] . $prompt_form['description']->renderError();?>
        <i class="br20"></i>
        Текст совета
        <i class="br5"></i>
        <div class="prompt_body_wrap">
          <?php echo $prompt_form['body'] . $prompt_form['body']->renderError();?>
        </div>
        <i class="br20"></i>
        Специализация
        <i class="br5"></i>
        <?php echo $prompt_form['specialty_id'] . $prompt_form['specialty_id']->renderError();?>
        <i class="br20"></i>
        Добавить главную фотографию
        <i class="br5"></i>
        <div class="dc_data__photo">
          <?php echo $prompt_form['photo'] . $prompt_form['photo']->renderError();?>
        </div>
        <?php
        /*
         * <!--          <i class="br20"></i>-->
         Опубликовать через соц. сети:
        <i class="br5"></i>
                  <table cellpadding="0" cellspacing="0" width="100%">
          <tr valign="top">
            <td><label><label><input type="checkbox" />Вконтакте</label></td>
                      <td><label><input type="checkbox" />Facebook</label></td>
          </tr>
                  <tr><td colspan="2"><i class="br5"></i></td></tr>
                  <tr valign="top">
                    <td><label><input type="checkbox" />Twitter</label></td>
                    <td><label><input type="checkbox" />Instagram</label></td>
                  </tr>
        </table>
        */
        ?>
      </div>
      <div class="overlay__white_box__dialog clearfix">
        <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" onclick="console.log($('.redactor_toolbar').find('.redactor_btn_html').click());">Опубликовать совет</button>
      </div>
    </form>
  </div>
</div>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <div class="da_menu_wrap">

      <?php include_component('doctor_account', 'menu');?>

      </div>
      <b style="margin-right: 40px;">Публикации</b>
      <i class="br10"></i>
      <div class="white_box dc_posting_page">
        <div class="dc_posting__menu clearfix">
          <span class="dc_posting__menu__link fl_l dc_posting__menu__link_active">Советы</span>
          <a href="/doctor-account/posting-article/" class="dc_posting__menu__link fl_l">Статьи</a>
        </div>
        <button class="btn_all b_green_btn fl_r dc_posting__add_btn" onclick="overflowHiddenScroll($('.dc_overlay_posting'));$('input, select').customizeFormWww();">Написать совет</button>
        <div class="dc_posting">

          <?php include_component('doctor_account', 'doctor_posting', array('type' => 'tip'));?>

        </div>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">

        <?php include_component('main', 'notice', array('profile' => 's'));?>

      </div>
      <div style="width: 300px;"></div>
    </td>
  </tr>
</table>
<script type="text/javascript">
  do_customInput($('#prompt_photo___uploader'), 'file');
  <?php
  if($message_true || $message_error)
  {
  ?>

    $('.dc_posting_form').find('input, textarea').val('');

    var dcOverlayPosting = $('.dc_overlay_posting');
    var dcOverlayPostingThx = $('.dc_overlay_posting__thx');
    <?php
    if($message_true)
    {
    ?>
    dcOverlayPostingThx.show();
    dcOverlayPosting.find('form').hide();
    <?php
    }
    ?>
    overflowHiddenScroll(dcOverlayPosting);
    dcOverlayPosting.click(function () {
      $(this).find('form').show();
      dcOverlayPostingThx.hide();
    });
  <?php
  }
  ?>
</script>
