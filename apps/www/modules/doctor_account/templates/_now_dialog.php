<div class="pc_user_page_notice">
  <div class="overlay_alert" onclick="overflowHiddenScroll($('.overlay_alert'));">
    <div class="overlay_alert__message_wrap">
      <div class="overlay__close">×</div>
      <div class="overlay_alert__message" onclick="event.stopPropagation();">
        <div class="overlay_alert__message__h">Закрыть беседу?</div>
        <div class="overlay_alert__message__body">
          После закрытия пациент не сможет продолжить диалог с вами в текущей беседе.<br>Пожалуйста, закрывайте беседу только когда уверены что пациент завершил диалог.
        </div>
        <table class="overlay_alert__message__btn_table" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="top" width="50%">
              <div class="overlay_alert__message__btn btn_all red_btn" onclick="nowDialogAnswerClose('submit');">Закрыть</div>
            </td>
            <td align="center" valign="top" width="50%">
              <div class="overlay_alert__message__btn btn_all blue_btn" onclick="overflowHiddenScroll($('.overlay_alert'));">Отмена</div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="white_box pc_user_page">
    <b>Текущая беседа</b>
    <div class="pc_chat">
      <?php
      $question_id = $q_id ? $q_id : $sf_request->getParameter('id');
      $questions = $sf_user->getAccount()->getOpenQuestions('question', $question_id);
      if($questions[0]['closed_by'] != 0 && $questions[0]['closed_by'])
      {
        $answer_edit = false;
      }
      $elements_arr = array();
      foreach ($questions[0]['Answer'] as $key => $answer)
      {
        $elements_arr[] = $answer;
      }
      foreach ($questions[0]['Reception_contract'] as $key => $reception)
      {
        $elements_arr[] = $reception;
      }
      $question_sheet_history = count($questions[0]['QuestionSheetHistory']) > 0 ? true : false;
      $age = date('Y') - substr($questions[0]['User']['birth_date'], 0, 4);
      $name = $questions[0]['User']['first_name'] . ' ' . $questions[0]['User']['second_name'] . ($age != date('Y') ? ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '');

      if($questions[0]['is_anonymous'] == 1)
      {
        $name = 'Анонимно, ' . mb_strtolower(Page::nameAge($age, $questions[0]['User']['gender'])) . ' ' . ($age != date('Y') ? $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '');
      }

      $user_about_age = date('Y') - substr($questions[0]['UserAbout']['birth_date'], 0, 4);

      $question_values = array(
        'question_id' => $questions[0]['id'],
        'user_id' => $questions[0]['user_id'],
        'user_about_id' => $questions[0]['user_about_id'],
        'user_about_username' => $questions[0]['UserAbout']['username'],
        'user_about_age_gender' => page::nameAge($user_about_age, $questions[0]['UserAbout']['gender']) . ($user_about_age != date('Y') ? ', ' . $user_about_age . ' ' . Page::niceRusEnds($user_about_age, 'год', 'года', 'лет') : ''),
        'specialist_id' => $questions[0]['QuestionSpecialist'][0]['specialist_id'],
        'specialist_title_url' => $questions[0]['QuestionSpecialist'][0]['Specialist']['title_url'],
        'specialist_user_id' => $questions[0]['QuestionSpecialist'][0]['Specialist']['user_id'],
        'specialist_name' => $questions[0]['QuestionSpecialist'][0]['Specialist']['User']['first_name'] . ' ' . $questions[0]['QuestionSpecialist'][0]['Specialist']['User']['second_name'],
        'specialist_about' => $questions[0]['QuestionSpecialist'][0]['Specialist']['about'],
        'specialist_photo' => $questions[0]['QuestionSpecialist'][0]['Specialist']['User']['photo'],
        'user_about_id' => $questions[0]['user_about_id'],
        'question_body' => $questions[0]['body'],
        'created_at' => $questions[0]['created_at'],
        'user_name' => $name
      );

      include_partial('doctor_account/c_item', array('answer_edit' => $answer_edit, 'question_user_id' => $questions[0]['user_id'], 'type' => 'one', 'location' => 'now_dialog', 'question_values' => $question_values, 'first_question' => true, 'question_sheet_history' => $question_sheet_history));

      usort($elements_arr, function($a, $b)
      {
        if(strtotime($a['created_at']) == strtotime($b['created_at']))
        {
          return 0;
        }
        return strtotime($a['created_at']) > strtotime($b['created_at']) ? 1 : -1;
      });

      foreach ($elements_arr as $key_elemet => $element)
      {
        include_partial('doctor_account/c_item', array('answer_edit' => $answer_edit, 'element' => $element, 'type' => 'one', 'location' => 'now_dialog', 'question_values' => $question_values, 'question_sheet_history' => $question_sheet_history, 'now_dialog_show' => $now_dialog_show));
      }

      if($questions[0]['closed_by'] != 0 && $questions[0]['closed_by'])
      {
        if($questions[0]['closed_by'] == $questions[0]['QuestionSpecialist'][0]['Specialist']['user_id'])
        {
          $closed_dialog = 'closed_author-:-' . $questions[0]['closing_date'];
        }
        else
        {
          $closed_dialog = 'closed-:-' . $questions[0]['closing_date'];
        }
        include_partial('doctor_account/c_item', array('answer_edit' => $answer_edit, 'questions' => $questions, 'type' => 'one', 'location' => 'now_dialog', 'question_values' => $question_values,   'closed_dialog' => $closed_dialog, 'doctor_account' => $doctor_account, 'question_sheet_history' => $question_sheet_history, 'now_dialog_show' => $now_dialog_show));
      }
      ?>
    </div>
  </div>
  <?php
  if(!$questions[0]['closed_by'])
  {
    $consilium_check = Doctrine::getTable("Consilium")->findOneByQuestionId($question_id);
    ?>
    <form method="post" action="<?php echo url_for('@doctor_account_now_dialog_answer');?>" id="now_dialog_form" class="white_box pc_chat_add_mess" onsubmit="nowDialogAnswerAdd();return false;">
      <div class="clearfix dc_chat__btns">
        <?php
        echo '<div class="da_consilium_add_btn_wrap fl_l" style="display: inline-block;">';
          if(!$consilium_check)
          {
            echo '<button class="btn_all blue_btn fl_l da_consilium_add_btn" onclick="nowDialogAddConsiliumInfo(this);return false;">Созвать консилиум</button>';
          }
          else
          {
            echo '<a href="' . url_for('@doctor_account_consilium_show?id=' . $consilium_check) . '" class="btn_all blue_btn fl_l" >Перейти в консилиум</a>';
          }
        echo '</div>';

        echo '<button class="btn_all green_btn fl_l" style="border-radius: 2px;" onclick="nowDialogPleaseAnalysis(\'clear\');return false;">Выписать направления на анализы</button>';

        ?>
        <button class="btn_all red_btn fl_l" title="Отправка сообщения с последующим закрытием беседы" onclick="nowDialogAnswerClose('check');return false;">Закрыть беседу</button>

        <?php
        echo '<button class="btn_all blue_btn fl_l" onclick="nowDialogRedirectAdmin();">Перенаправить вопрос администратору</button>';
        $question_email_user = Doctrine::getTable("Question")->find($question_id)->getUser();
        if($question_email_user->getEmail() && $question_email_user->getEmail() != '')
        {
          echo '<button class="btn_all blue_btn fl_l" onclick="nowDialogEmailReminder();return false;">Отправить электронное письмо с напоминанием</button>';
        }
        echo '<button class="btn_all blue_btn fl_l" onclick="nowDialogInviteLiveReceptionInfo();return false;">Пригласить на очный приём</button>';

        /*
        echo '<button class="btn_all blue_btn fl_l" onclick="return false;">Просмотреть анализы пациента</button>';
        */

        ?>
        <button class="btn_all dark_red_btn fl_l" onclick="overflowHiddenScroll($('.dc_overlay_now_dialog_complaint'));return false;">Пожаловаться</button>
      </div>
      <?php
      echo $form->renderGlobalErrors();
      echo $form->renderHiddenFields();
      echo '<div class="answer_body__wrap"><div class="dc_chat__links__upload dc_chat__links" onclick="attachmentUpload();" title="Прикрепить файлы"></div>' . $form['body'] . $form['body']->renderError() . '</div>';
      echo '<div class="attachment__wrap">' . $form['attachment'] . $form['attachment']->renderError() . '</div>';
      echo '<input type="hidden" value="' . $question_id . '" name="answer[question_id]">';
      ?>
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr valign="top">
          <td align="right"><button class="btn_all blue_btn dialog_submit_btn">Ответить</button></td>
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
  });
</script>