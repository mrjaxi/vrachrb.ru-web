<?php
if(!$ajax)
{
  echo '<div class="analysis_photo_wrap" style="display: none;">';
  if(!$doctor_account)
  {
    echo $analysis_form->renderGlobalErrors();
    echo $analysis_form->renderHiddenFields();
    echo $analysis_form['photo'] . $analysis_form['photo']->renderError();
  }
  echo '</div>';
}
?>
<div class="pc_user_page_wrap">
  <div class="white_box pc_user_page">
    <b>Текущая беседа</b>
    <div class="pc_chat">
      <?php
      $question_id = $q_id ? $q_id : $sf_request->getParameter('id');
      $reception_id_arr = array();
      $answer_id_arr = array();
      $analysis_arr = array();
      foreach($questions as $element_key => $element)
      {
        if($element['mode'] == 'q')
        {
          $user_name = $element['user_name'];
          $specialist_arr = array(
            'specialist_user_id' => $element['specialist_user_id'],
            'specialist_id' => $element['specialist_id'],
            'title_url' => $element['s_title_url'],
            'name' => $element['specialist_name'],
            'photo' => $element['specialist_photo'],
            'about' => $element['s_about'],
            'review' => $element['review']
          );
          if($element['q_closed_by'] && $element['q_closed_by'] != 0)
          {
            $closed_dialog = 'closed-:-' . $element['q_closing_date'];
            $no_touch_reservation = true;
            if($element['q_closed_by'] == $specialist_arr['specialist_user_id'])
            {
              $closed_dialog = 'closed_author-:-' . $element['q_closing_date'];
              $no_touch_reservation = false;
            }
          }
        }
        if(!in_array($element['r_id'], $reception_id_arr) && $element['a_type'] != 'give_analysis')
        {
          include_partial('personal_account/p_item', array('ajax' => $ajax, 'element' => $element, 'type' => 'one', 'location' => 'now_dialog', 'user_name' => $user_name, 'specialist_arr' => $specialist_arr, 'no_touch_reservation' => $no_touch_reservation, 'analysis_form' => $analysis_form, 'doctor_account' => $doctor_account));
        }
        if($element['r_id'] && $element['mode'] == 'r')
        {
          $reception_id_arr[] = $element['r_id'];
        }
        if($element['mode'] == 'a' && $element['a_type'] == 'give_analysis')
        {
          $analysis_arr[] = $element['an_info'];
          if(!in_array($element['a_id'], $answer_id_arr))
          {
            include_partial('personal_account/p_item', array('ajax' => $ajax, 'element' => $element, 'type' => 'one', 'location' => 'now_dialog', 'user_name' => $user_name, 'specialist_arr' => $specialist_arr, 'no_touch_reservation' => $no_touch_reservation, 'analysis_form' => $analysis_form, 'analysis_arr' => $analysis_arr, 'doctor_account' => $doctor_account));
          }
          $answer_id_arr[] = $element['a_id'];
        }
      }
      if($closed_dialog)
      {
        include_partial('personal_account/p_item', array('ajax' => $ajax, 'type' => 'one', 'location' => 'now_dialog', 'user_name' => $user_name, 'specialist_arr' => $specialist_arr, 'closed_dialog' => $closed_dialog, 'analysis_form' => $analysis_form, 'doctor_account' => $doctor_account));
      }
      ?>
    </div>
  </div>
  <?php
  if(!$closed_dialog)
  {
    ?>
    <form id="pa_now_dialog_form" method="post" onsubmit="paNowDialogAnswerAdd();return false;" action="<?php echo url_for('@personal_account_now_dialog_answer');?>" class="white_box pc_chat_add_mess">
      <?php
      echo $notice_form->renderGlobalErrors();
      echo $notice_form->renderHiddenFields();
      echo $form->renderGlobalErrors();
      echo $form->renderHiddenFields();
      echo '<div class="answer_body__wrap"><div class="dc_chat__links__upload dc_chat__links" onclick="attachmentUpload();" title="Прикрепить файлы"></div>' . $form['body'] . $form['body']->renderError() . '</div>';
      echo '<div class="attachment__wrap">' . $form['attachment'] . $form['attachment']->renderError() . '</div>';
      echo '<input type="hidden" value="' . $question_id . '" name="answer[question_id]">';
      ?>
      <i class="br10"></i>
      <span class="fs_13">Нажимая кнопку «Отправить»... я подтверждаю, что понимаю, что рекомендации консультантов носят предварительно-информативный характер и не могут заменить очную консультацию специалиста.</span>
      <i class="br30"></i>
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr valign="top">
          <td width="100%"><a href="<?php echo url_for('@donate');?>" class="btn_all blue_btn">Поддержать проект</a></td>
          <td width="1" style="padding-right: 20px;">
            <button class="btn_all green_btn" onclick="paNowDialogReceptionAdd();return false;" style="border-radius: 2px;">Записаться на очный прием</button>
          </td>
          <td width="1"><button class="btn_all blue_btn">Отправить</button></td>
        </tr>
      </table>
    </form>
    <?php
  }
  ?>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    showPhotoAnalysis.init();
    $('.pc_chat__item__checkbox_result').removeAttr('checked');
    $('.give_analysis_btn').attr('disabled', 'disabled');
    $('.pc_chat__item__click_uploader').removeAttr('disabled');
  });
</script>