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

      <div class="dc_overlay_posting__thx">Статья добавлена!</div>

      <form id="posting_article_form" action="<?php echo url_for('@doctor_account_posting_article');?>" onclick="event.stopPropagation();" method="post">
        <?php
        echo $article_form->renderGlobalErrors();
        echo $article_form->renderHiddenFields();
        ?>
        <div class="fs_18" style="padding: 0 20px;">Добавление статьи</div>
        <div class="ta_l overlay__white_box__body">
          Название статьи
          <i class="br5"></i>
          <?php echo $article_form['title'] . $article_form['title']->renderError();?>
          <i class="br20"></i>
          Краткое описание (255 символов)
          <i class="br5"></i>
          <?php echo $article_form['description'] . $article_form['description']->renderError();?>
          <i class="br20"></i>
          Текст статьи
          <i class="br5"></i>
          <div class="article_body_wrap">
            <?php echo $article_form['body'] . $article_form['body']->renderError();?>
          </div>
          <i class="br20"></i>
          Добавить главную фотографию
          <i class="br5"></i>
          <div class="dc_data__photo">
            <?php echo $article_form['photo'] . $article_form['photo']->renderError();?>
          </div>
          <i class="br20"></i>
          <label><?php echo $article_form['is_activated'] . $article_form['is_activated']->renderError();?>Реклама</label>
          <i class="br20"></i>

          <?php
          /*
          Опубликовать через соц. сети:
          <i class="br5"></i>
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr valign="top">
              <td><label><input type="checkbox" />Вконтакте</label></td>
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
          <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" onclick="$('.redactor_btn_html').click();">Опубликовать совет</button>
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
        <?php
        echo '<div class="da_menu_wrap">';
          include_component('doctor_account', 'menu');
        echo '</div>';
        ?>

        <b style="margin-right: 40px;">Публикации</b>

        <?php
        /*
         <!--        <div class="pagination_years" style="margin-right: 50px;">-->
<!--          <span class="pagination_years__item">2016</span>-->
<!--          <a href="" class="pagination_years__item">2013</a>-->
<!--        </div>-->
         */
        ?>

        <i class="br10"></i>
        <div class="white_box dc_posting_page">
          <div class="dc_posting__menu clearfix">
            <a href="/doctor-account/posting/" class="dc_posting__menu__link fl_l">Советы</a>
            <span class="dc_posting__menu__link fl_l dc_posting__menu__link_active">Статьи</span>
          </div>
          <button class="btn_all b_green_btn fl_r dc_posting__add_btn" onclick="overflowHiddenScroll($('.dc_overlay_posting'));">Написать статью</button>
          <div class="dc_posting">

            <?php include_component('doctor_account', 'doctor_posting', array('type' => 'article'));?>

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
<?php
if($message_true)
{
  ?>
  <script type="text/javascript">
    $('.dc_overlay_posting__thx').show();
    $('.dc_overlay_posting').find('form').hide();
    overflowHiddenScroll($('.dc_overlay_posting'));
    $('.dc_overlay_posting').click(function () {
      $(this).find('form').show();
      $('.dc_overlay_posting__thx').hide();
    });
  </script>
  <?php
}
?>
<script type="text/javascript">
  $(document).ready(function () {
    $('#article_photo___uploader').attr('required', 1);
  });
</script>
