<?php
  use_javascript('jquery.liteuploader.js');
  use_javascript('atmaUI.js');

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
  slot('title', 'Личные данные');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('doctor_account', 'menu');
      echo '</div>';
      ?>
      <div class="white_box pc_user_page">
        <b>Личные данные</b>
        <i class="br20"></i>
        <?php
        if($save_true)
        {
          echo '<div class="save_true_message">Личные данные сохранены</div>';
        }
        ?>
        <form method="post" action="<?php echo url_for('@doctor_account_data');?>" onsubmit="generateWorkPlace('submit');">
          <?php
          echo $form_user->renderGlobalErrors();
          echo $form_user->renderHiddenFields();
          echo $form_specialist->renderGlobalErrors();
          echo $form_specialist->renderHiddenFields();
          ?>
          <table cellpadding="0" cellspacing="0" class="pc_p_data_table" width="100%">
            <tr>
              <td class="pc_p_data_label"><b>Имя</b></td>
              <td class="pc_p_data_inps"><?php echo $form_user['first_name'] . $form_user['first_name']->renderError();?></td>
            </tr>
            <tr>
              <td class="pc_p_data_label">Фамилия</td>
              <td class="pc_p_data_inps"><?php echo $form_user['second_name'] . $form_user['second_name']->renderError();?></td>
            </tr>
            <tr>
              <td class="pc_p_data_label">Отчество</td>
              <td class="pc_p_data_inps"><?php echo $form_user['middle_name'] . $form_user['middle_name']->renderError();?></td>
            </tr>
            <tr>
              <td class="pc_p_data_label"><b>Эл.почта</b></td>
              <td class="pc_p_data_inps"><?php echo $form_user['email'] . $form_user['email']->renderError();?></td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label">
                <i class="br5"></i>
                Образование
              </td>
              <td class="pc_p_data_inps">
                <?php echo $form_specialist['education'] . $form_specialist['education']->renderError();?>
              </td>
            </tr>
            <tr>
              <td class="pc_p_data_label" valign="top">Научная степень</td>
              <td class="pc_p_data_inps">
                <?php echo $form_specialist['about'] . $form_specialist['about']->renderError();?>
              </td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label">
                <i class="br10"></i>
                Место работы
                <input type="hidden" name="work_place" id="specialist_work_place">
              </td>
              <td class="pc_p_data_inps">
                <?php
                if(count($specialist_work_place) > 0)
                {
                  echo '<ol class="dc_p_data_job_ol">';
                    foreach ($specialist_work_place as $swp_key => $swp)
                    {
                      echo '<li class="dc_p_data_job_ol_inp" data-work_place_id="' . $swp['id'] . '"><input type="text" name="inp_' . ($swp_key + 1) . '" value="' . $swp['title'] . '" /><label title="Если поставить галочку «Скрыть», то данное место работы не отображается на публичной странице, но отображается в при составлении приглашения на очный приём"><input type="checkbox" ' . ($swp['hidden'] == 1 ? 'checked="checked"' : '') . ' name="inp_hidden_' . ($swp_key + 1) . '" id="" />&nbsp;Скрыть</label><span class="delete_btn_all" title="Удаление" onclick="$(this).parent().remove();vrb.formInpClone.indexed(\'.dc_p_data_job_ol_inp\');"></span></li>';
                    }
                  echo '</ol>';
                }
                else
                {
                  echo '<ol class="dc_p_data_job_ol">';
                    echo '<li class="dc_p_data_job_ol_inp"><input type="text" name="inp_1" value="" /><label title="Если поставить галочку «Скрыть», то данное место работы не отображается на публичной странице, но отображается в при составлении приглашения на очный приём"><input type="checkbox" name="inp_hidden_1" id="" />&nbsp;Скрыть</label><span class="delete_btn_all" title="Удаление" onclick="$(this).parent().remove();vrb.formInpClone.indexed(\'.dc_p_data_job_ol_inp\');"></span></li>';
                  echo '</ol>';
                }
                ?>
                <a href="#" class="btn_all green_btn" style="border-radius: 2px;"  onclick="vrb.formInpClone.add('.dc_p_data_job_ol_inp');return false;">Добавить ещё</a>
              </td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label">Фотография</td>
              <td class="pc_p_data_inps">
                <div class="dc_data__photo">
                  <?php echo $form_user['photo'] . $form_user['photo']->renderError();?>
                </div>
              </td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label">
                Сертификаты
              </td>
              <td class="pc_p_data_inps">
                <div class="certificate_wrap">
                  <?php echo $form_specialist['certificate'] . $form_specialist['certificate']->renderError();?>
                </div>
              </td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label"></td>
              <td class="pc_p_data_inps">
                <i class="br20"></i>
                <label>
                  <?php echo $form_specialist['live_reception'] . $form_specialist['live_reception']->renderError();?>
                  Принимаю очно
                </label>
              </td>
            </tr>
            <tr valign="top">
              <td class="pc_p_data_label"></td>
              <td class="pc_p_data_inps">
                <button class="btn_all blue_btn">Сохранить информацию</button>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="pc_p_data_g_line"><i class="br1" style="background-color: #d8dfe0;"></i></td>
            </tr>
          </table>
        </form>
        <b>Сменить пароль</b>
        <i class="br20"></i>
        <?php include_partial('user/change_password', array('change_password_form' => $change_password_form));?>
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
  var generateWorkPlace = function (elem) {
    var strArr;
    if(elem == 'submit'){
      var str = '';
      $('.dc_p_data_job_ol_inp').each(function (i, work) {
        var inputVal = $(work).find('input[type="text"]').val();
        if(inputVal != ''){
          if(i != 0){
            str += ':division:';
          }
          str += inputVal;
          str += $(work).find('.custom_input').is(':checked') ? 1 : 0;
        }
      });
      $('#specialist_work_place').val(str);
    }
  };
</script>