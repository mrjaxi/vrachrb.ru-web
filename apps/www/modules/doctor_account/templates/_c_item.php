<?php
$closed_dialog_exp = explode('-:-', $closed_dialog);
if($closed_dialog_exp[0] == 'closed')
{
  $created_at = $closed_dialog_exp[1];
}
else
{
  $created_at = $element['Consilium'][0]['created_at'];
  $url_show = $location == 'now_dialog' ? url_for('@doctor_account_now_dialog_show?id=' . $element['id']) : url_for('@doctor_account_consilium_show?id=' . $element['Consilium'][0]['id']);
  $body_tags = 'a';
  $details_btn = '<a class="pc_chat__item__details" onclick="consiliumDetails();" title="Открыть подробности беседы">Подробности беседы<img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40"></a>';
  $element_class = 'dc_patient_concilium';
  $body_attr = 'class="live_band__item__link" href="' . $url_show . '"';
  if($location == 'now_dialog')
  {
    $created_at = $first_question ? $question_values['created_at'] : $element['created_at'];
    if($closed_dialog_exp[0] == 'closed_author')
    {
      $created_at = $closed_dialog_exp[1];
    }

    if($type == 'one')
    {
      $body_tags = 'div';
      $body_attr = 'class="pc_chat__item__edit_text"';
    }
    $details_btn = '';

    $element_class = '';
    if(($closed_dialog_exp[0] == 'closed_author' && !$first_question) || $question_values['specialist_user_id'] == $element['user_id'] || $question_values['specialist_id'] == $element['specialist_id'])
    {
      if(!$first_question && $type != 'list')
      {
        $element_class = 'pc_chat__item__answer';
      }
    }
  }
}
?>
<div class="pc_chat__item <?php echo $element_class;?>">
  <div class="all_link_item">
    <?php
    if(($closed_dialog_exp[0] == 'closed_author' && !$first_question) || $question_values['specialist_user_id'] == $element['user_id'] || $question_values['specialist_id'] == $element['specialist_id'] && $type != 'list')
    {
      $specialist = $sf_user->getAccount()->getSpecialist();
      if($reception == 'reception')
      {
        $created_at = $questions[0]['Reception_contract'][0]['created_at'];
      }
    ?>
      <div class="pc_chat__item__author">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr valign="middle">
            <td style="padding-right: 6px;" width="1">
              <img src="/i/n.gif" <?php echo $question_values['specialist_photo'] ? 'style="background: url(\'/u/i/' . Page::replaceImageSize($question_values['specialist_photo'], 'S') . '\') no-repeat center;background-size: cover;"' : '';;?> width="40" height="40" class="pc_chat__item__author__img">
            </td>
            <td>
              <?php
              echo '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $question_values['specialist_title_url'] . '/">' . $question_values['specialist_name'] . '</a>. <span>' . $question_values['specialist_about'] . '</span>';
              ?>
            </td>
          </tr>
        </table>
      </div>
    <?php
    }
    else
    {
      echo '<div class="pc_chat__item__name">';
      if($type == 'list')
      {
        $age = date('Y') - $element['User']['birth_date'];
        echo '<b>' . $element['User']['first_name'] . ' ' . $element['User']['second_name'] . ($age != date('Y') ? ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '') . '</b>';
      }
      else
      {
        echo '<b>' . $question_values['user_name'] . '</b>';
      }
      if ($element['user_about_id'] || $question_values['user_about_id'])
      {
        echo '<div class="pc_chat__item__note pc_chat__item__note__show_user" onclick="noteShowUser(this);">';
        echo 'Вопрос касается члена семьи';
        echo '<div class="pc_chat__item__note__show_user__item">' . $question_values['user_about_username'] . '<br>' . $question_values['user_about_age_gender'] . '</div>';
        echo '</div>';
      }
      echo '</div>';
      if ($type != 'list' && $location != 'now_dialog')
      {
        echo '<span class="pc_chat__item__status pc_chat__s_green">Сообщение от пациента</span>';
      }
      if($close_text)
      {
        $close_description = $close_text == 'close_consilium' ? 'Консилиум' : 'Беседа';
        if($close_text == 'close_question')
        {
          $closed_admin_description = $element['closed_by'] != $element['QuestionSpecialist'][0]['Specialist']['user_id'] ? ' администратором' : '';
        }
        if($close_description)
        {
          echo '<span class="pc_chat__item__status pc_chat__s_red">' . $close_description . ' закрыт' . (mb_substr($close_description, -1) == 'а' ? 'a' : '') . $closed_admin_description . '</span>';
        }
      }
    }
    if($closed_dialog_exp[0] != 'closed' && $closed_dialog_exp[0] != 'closed_author')
    {
      if($element['type'] != 'email_reminder' && $element['type'] != 'user_reception' && !$element['specialist_id'] && $element['type'] != 'please_analysis' && $element['type'] != 'give_analysis' && $element['type'] != 'please_analysis_complete')
      {
        if($answer_edit && ($question_values['specialist_user_id'] == $element['user_id'] || $question_values['specialist_id'] == $element['specialist_id']))
        {
          echo '<div title="Редактировать ответ" class="pc_chat__item__edit dc_chat__links" onclick="nowDialogAnswerEdit(\'open\', this);"></div>';
          echo '<div class="pc_chat__item__edit__wrap">';
        }
        $output_body = ($element['body'] ? $element['body'] : $question_values['question_body']);

        echo '<div class="live_band__item__link_wrap">';
        echo '<' . $body_tags . ' ' . $body_attr . '>' . ($type == 'list' ? str_replace('<br>', '', $output_body) : $output_body) . '</' . $body_tags . '>';
        echo '</div>';

        if($answer_edit && ($question_values['specialist_user_id'] == $element['user_id'] || $question_values['specialist_id'] == $element['specialist_id']))
        {
          echo '<textarea class="pc_chat__item__edit__textarea" style="width:100%;min-height:100px;resize:vertical"></textarea>';
          echo '<div style="text-align: right;"><div class="pc_chat__item__edit__btn" onclick="nowDialogAnswerEdit(\'submit\', ' . $element['id'] . ');">Сохранить</div></div>';
          echo '</div>';
        }
      }
      elseif($element['type'] == 'please_analysis' || $element['type'] == 'please_analysis_complete')
      {
        $please_analysis = json_decode(htmlspecialchars_decode($element['body']), true);
        if(count($please_analysis) > 0)
        {
          ?>
          <div class="pc_chat__item_grey_box">
            <b>Анализы и обследования</b>
            <i class="br20"></i>
            <?php
            foreach ($please_analysis as $p_analysis_key => $p_analysis)
            {
              $p_analysis_exp = explode(':', $p_analysis);
              echo ($p_analysis_key + 1) . '. ' . $p_analysis_exp[1];
              echo '<i class="br5"></i>';
            }
            ?>
          </div>
          <div class="pc_chat__item__notice">Отправлена анкета для анализов</div>
          <?php
        }
      }
      elseif($element['type'] == 'give_analysis')
      {
        $give_analysis = $element['Analysis'];
        if(count($give_analysis) > 0)
        {
          echo '<i class="br10"></i>';
          if($question_sheet_history)
          {
            echo '<div class="dc_chat__links dc_chat__links_sheet_history" onclick="sheetHistoryDetails();return false;" title="Лист анамнеза"></div>';
          }
          ?>
          <div class="dc_chat__links_wrap">
            <a target="_blank" href="<?php echo url_for('@doctor_account_patient_card?id=' . $element['user_id']);?>" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>
          </div>
          <?php
          echo '<div class="da_show_analysis_wrap">';
          foreach ($give_analysis as $ga_key => $ga)
          {
            echo '<i class="br20"></i>';
            echo ($ga_key + 1) . '. ' . $ga['Analysis_type']['title'];
            echo '<i class="br10"></i>';
            echo '<div class="show_photo_analysis" onclick="showPhotoAnalysis.onClick(this, event);" data-analysis_id="' . $ga['id'] . '" data-answer_id="' . $ga['answer_id'] . '" style="width:420px;height:240px;background: url(\'/u/i/' . $ga['photo'] . '\') no-repeat 50% 50%;background-size:cover;"></div>';
          }
          echo '</div>';
        }
      }
    }
    if($element['type'] == 'email_reminder')
    {
      echo '<div class="pc_chat__item__notice">Отправили напоминание на электронную почту</div>';
    }
    if($element['specialist_id'])
    {
      if($element['is_activated'] == 1)
      {
        $reception_contract = Doctrine_Query::create()
          ->select("rc.*, rl.*, rd.*")
          ->from("Reception_contract rc")
          ->innerJoin("rc.Location rl")
          ->innerJoin("rc.Receive_datetime rd")
          ->fetchArray()
        ;

        echo '<div style="margin-bottom: 0;" class="pc_chat__item__notice">Пациент согласился на очный прием</div>';
        echo '<div class="ta_c">';
          echo '<i class="br10"></i>';
          echo '<b>Место приёма:</b> ' . $reception_contract[0]['Location'][0]['title'] . '<br>';
          echo '<b>Время приёма:</b> ' . Page::rusDate($reception_contract[0]['Receive_datetime'][0]['datetime']) . ' года в ' . substr($reception_contract[0]['Receive_datetime'][0]['datetime'], 11, 5);
        echo '</div>';
      }
      elseif($element['is_reject'] == 1)
      {
        echo '<div style="margin-bottom: 0;color:#000;" class="pc_chat__item__notice">Пациент отказался от очного приема<br><b>Причина: </b>' . $element['reject_reason'] . '</div>';
      }
      else
      {
        echo '<div style="margin-bottom: 0;" class="pc_chat__item__notice">Отправили приглашение на очный приём</div>';
      }
    }
    if($element['type'] == 'user_reception')
    {
    ?>
      <div class="pc_chat__item__invitation">
        Пациент хочет записаться на приём
        <i class="br20"></i>
        <button class="btn_all blue_btn" onclick="nowDialogInviteLiveReceptionInfo();return false;">Отправить приглашение</button>
      </div>
    <?php
    }
    if($closed_dialog_exp[0] == 'closed_author' || $closed_dialog_exp[0] == 'closed')
    {
      $closed_admin = $closed_dialog_exp[0] == 'closed' ? 'администратором' : '';
    ?>
      <div class="pc_chat__item__q_closed dc_chat__dialog_closed">
        Беседа закрыта <?php echo $closed_admin;?>
      </div>
    <?php
      if((time() - strtotime($closed_dialog_exp[1])) < 86400 && $questions[0]['QuestionSpecialist'][0]['Specialist']['user_id'] == $questions[0]['closed_by'] && !$doctor_account)
      {
      ?>
        <div class="dc_chat__item__res_dialog">
          В течении суток вы можете возобновить беседу
          <i class="br10"></i>
          <button class="btn_all blue_btn" onclick="nowDialogAnswerOpen();">Возобновить беседу</button>
        </div>
      <?php
      }
    }
    elseif($name_answered)
    {
      echo htmlspecialchars_decode($name_answered);
    }
    ?>
  </div>
  <?php
  if($element['type'] != 'email_reminder' && $reception != 'reception' && $element['type'] != 'user_reception' && ($closed_dialog_exp[0] != 'closed_author' && $closed_dialog_exp[0] != 'closed'))
  {
    echo '<i class="br10"></i>';
  }
  if ($type != 'list' && $element['type'] != 'give_analysis' && $element['type'] != 'please_analysis' && $element['type'] != 'please_analysis_complete' && $question_values['specialist_user_id'] != $element['user_id'] && !$closed_dialog)
  {
    echo '<div class="dc_chat__links_wrap">';
    if($question_sheet_history)
    {
      echo '<div class="dc_chat__links dc_chat__links_sheet_history" onclick="sheetHistoryDetails();return false;" title="Лист анамнеза"></div>';
    }
    echo '<a target="_blank" href="' . url_for('@doctor_account_patient_card?id=' . ($question_user_id ? $question_user_id : $element['user_id'])) . '" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>';
    echo $details_btn;
    echo '</div>';
  }
  if($element['attachment'] != '')
  {
    include_component('main', 'attachment', array('files' => $element['attachment']));
  }
  ?>
  <div class="pc_chat__item__bottom"><?php echo Page::rusDate($created_at) . ', ' . substr($created_at, 10, 6);?></div>
</div>