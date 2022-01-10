<?php
if(!$ajax)
{
  slot('title', 'Амбулаторная карта');
  use_javascript('pa-now-dialog.js');
  include_component('personal_account', 'review_form');
?>
  <div class="breadcrumbs">
    <a href="/">Главная</a>
  </div>
  <h2>Личный кабинет</h2>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="100%">
        <?php
        echo '<div class="da_menu_wrap">';
          include_component('personal_account', 'menu');
        echo '</div>';
        ?>
        <div class="white_box pc_user_page pa_patient_card_page">
          <b>История обращений</b>
          <div class="pc_history">
<?php
}

          echo '<div id="patient_card_left_block">';
            include_partial('personal_account/patient_card_item', array('patient_card' => $patient_card));
          echo '</div>';

if(!$ajax)
{
?>
          </div>
        </div>
      </td>
      <td width="1" style="padding-left: 20px;">
        <div class="notice_wrap">
          <?php
          include_component('main', 'notice');
          ?>
        </div>
<?php
}
if($ajax != 'y' || $ajax == 'update')
{
?>
        <div id="patient_card_right_block">

          <?php
          if(count($patient_card) > 0)
          {
          ?>
            <form id="p_card_sp_filter" class="white_box filter_block" style="width:300px;">
              <b>Фильтр по кабинетам врачей</b>
              <i class="br20"></i>
              <?php
              $specialty_arr = array();
              foreach ($patient_card as $pc_specialy)
              {
                $pc_specialy_item = $pc_specialy['Specialists'][0]['Specialty'];

                if(!in_array($pc_specialy_item['title'] . '_' . $pc_specialy_item['id'], $specialty_arr))
                {
                  echo '<div class="filter_block__item"><label><input class="p_card_sp_filter_input" onclick="patientCardSpecialtyFilter();" type="checkbox" name="p_card_sp_filter[]" value="' . $pc_specialy_item['id'] . '" />&nbsp;' . $pc_specialy_item['title'] . '</label></div>';
                }
                $specialty_arr[] = $pc_specialy_item['title'] . '_' . $pc_specialy_item['id'];
              }
              ?>

              <i class="br20"></i>
              <a href="" class="btn_all blue_btn enter_filter" style="width:100%;" onclick="patientCardSpecialtyFilter();return false;">Применить</a>
              <i class="br5"></i>
              <a href="" class="btn_all b_blue_btn clear_filter" style="width:100%;" onclick="patientCardSpecialtyFilter('all');return false;">Сбросить</a>
            </form>
          <?php
          }
          include_component('main', 'now_analysis');
          ?>
        </div>
<?php
}
if(!$ajax)
{
?>
      </td>
    </tr>
  </table>
<?php
}
?>