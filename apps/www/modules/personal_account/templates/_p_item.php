<?php
$closed_dialog_exp = explode('-:-', $closed_dialog);
if($closed_dialog_exp[0] == 'closed')
{
  $created_at = $closed_dialog_exp[1];
}
else
{
  if($location == 'now_dialog')
  {
    $url_show =  url_for('@personal_account_now_dialog_show?id=' . $element['id']);
  }

  $body_tags = 'a';
  $details_btn = '<a class="pc_chat__item__details" onclick="consiliumDetails();" title="Открыть подробности беседы">Подробности беседы<img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40"></a>';
  $element_class = 'dc_patient_concilium';
  $body_attr = 'class="live_band__item__link" href="' . $url_show . '"';
  if($location == 'now_dialog')
  {
    $created_at = $element['created_at'];

    if($closed_dialog_exp[0] == 'closed_author')
    {
      $created_at = $closed_dialog_exp[1];
    }

    if($type == 'one' || $no_specialist)
    {
      $body_tags = 'div';
      $body_attr = '';
    }
    $details_btn = '';

    $element_class = '';

    if(($element['user_id'] == $specialist_arr['specialist_user_id'] && $element['mode'] != 'q') || $element['mode'] == 'r' || $closed_dialog_exp[0] == 'closed' || $closed_dialog_exp[0] == 'closed_author')
    {
      $element_class = 'pc_chat__item__answer';
    }
  }
}
?>
<div class="pc_chat__item <?php echo $element_class;?>">
  <div class="all_link_item">
    <?php
    if(($element['user_id'] == $specialist_arr['specialist_user_id'] && $element['mode'] != 'q') || $element['mode'] == 'r' || $closed_dialog_exp[0] == 'closed_author')
    {
    ?>
      <div class="pc_chat__item__author">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr valign="middle">
            <td style="padding-right: 6px;" width="1">
              <img src="/i/n.gif" <?php echo $specialist_arr['photo'] ? 'style="background: url(\'/u/i/' . Page::replaceImageSize($specialist_arr['photo'], 'S') . '\') no-repeat center;background-size: cover;"' : '';;?> width="40" height="40" class="pc_chat__item__author__img">
            </td>
            <td>
              <?php
              $str_about = Page::strCut($specialist_arr['about'], 200);
              echo $specialist_arr['specialist_id'] == 51 ? 'Врач РБ онлайн' : '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $specialist_arr['title_url'] . '/">' . $specialist_arr['name'] . '</a>. <span ' . ($str_about[1] == 'full' ? 'title="' . $specialist_arr['about'] . '"' : '') . '>' . $str_about[0] . '</span>';
              ?>
            </td>
          </tr>
        </table>
      </div>
    <?php
    }
    else
    {
      if($no_specialist)
      {
        echo '<span class="pc_chat__item__status pc_chat__s_blue">Ожидает ответа</span>';
      }
      if ($type != 'list' && $location != 'now_dialog')
      {
        echo '<span class="pc_chat__item__status pc_chat__s_green">Сообщение от пациента</span>';
      }
      if($close_text)
      {
        $close_description = $close_text == 'close_consilium' ? 'Консилиум' : 'Беседа';

        if($close_text == 'close_question')
        {
          $closed_admin_description = $element['closed_by'] != $element['Specialists'][0]['user_id'] ? ' администратором' : '';
        }
        if($close_description)
        {
          echo '<span class="pc_chat__item__status pc_chat__s_red">' . $close_description . ' закрыт' . (mb_substr($close_description, -1) == 'а' ? 'a' : '') . $closed_admin_description . '</span>';
        }
      }
      elseif($name_answered)
      {
        echo htmlspecialchars_decode($name_answered);
      }
      if($closed_dialog_exp[0] != 'closed')
      {
        echo '<div class="pc_chat__item__name">';
          echo '<b>' . $user_name . '</b>';
          if ($element['user_about_id'] || $element['ua_info'])
          {
            $ua_info = explode('_', $element['ua_info']);
            $user_about_age = date('Y') - substr($ua_info[1], 0, 4);

            echo '<div class="pc_chat__item__note pc_chat__item__note__show_user" onclick="noteShowUser(this);">';
              echo 'Вопрос касается члена семьи';
              echo '<div class="pc_chat__item__note__show_user__item">' . $ua_info[0] . '<br>' . page::nameAge($user_about_age, $ua_info[2]) . ', ' . $user_about_age . ' ' . Page::niceRusEnds($user_about_age, 'год', 'года', 'лет') . '</div>';
            echo '</div>';
          }
        echo '</div>';
        echo '<i class="br15"></i>';
      }
    }
    if($closed_dialog_exp[0] != 'closed')
    {
      if($element['type'] != 'email_reminder' && $element['a_type'] != 'user_reception' && $element['a_type'] != 'please_analysis' && $element['a_type'] != 'give_analysis' && $element['a_type'] != 'please_analysis_complete')
      {
        echo '<div class="pc_chat__item__body">';
        echo '<' . $body_tags . ' ' . $body_attr . '>' . $element['body'] . '</' . $body_tags . '>';
        echo '</div>';
      }
      if($element['a_type'] == 'please_analysis' || $element['a_type'] == 'please_analysis_complete')
      {
        $tag = 'form';
        $btn = '<button class="btn_all blue_btn give_analysis_btn" onclick="paNowDialogGiveAnalysis(this, ' . $element['a_id'] .');return false;" disabled="disabled">Отправить результаты</button>';

        /*$print_link = '<a href="/print/test/" target="_blank" class="anb print_link fl_r"><img src="/i/print.png" title="Распечатать" /></a>';*/

        if($element['a_type'] == 'please_analysis_complete' || $element['q_closed_by'])
        {
          $tag = 'div';
          $print_link = '';
          $btn = $element['q_closed_by'] ? '' : '<b style="color: #66CC99;">Анализы прикреплены</b>';
        }

        $please_analysis_arr = json_decode(htmlspecialchars_decode($element['body']), true);
        if(count($please_analysis_arr) > 0)
        {
          echo '<' . $tag . ' class="pc_chat__item__tests">';
            echo '<b>Анализы и обследования</b>';
            echo $print_link;
            echo '<i class="br20"></i>';
            foreach ($please_analysis_arr as $p_analysis_key => $p_analysis)
            {
              $p_analysis_exp = explode(':', $p_analysis);
              echo '<div class="pc_chat__item__tests__item">';
              if($element['a_type'] == 'please_analysis')
              {
                echo '<label class="custom_input_label" title="Галочка появится после успешной загрузки"><input type="checkbox" onclick="return false;" data-value="' . $p_analysis_exp[0] . '" class="pc_chat__item__checkbox_result custom_input" />' . $p_analysis_exp[1] . ($ajax ? '<span class="custom_input custom_input_checkbox"></span>' : '') . '</label>';
                echo '<i class="br10"></i>';
                echo '<span style="font-size: 12px;">Прикрепите фотографии анализов</span>';
                echo '<i class="br5"></i>';
                echo '<div onclick="emulationUploaderClick(this, \'click\');return false;" class="pc_chat__item__click_uploader" data-title="' . $p_analysis . '">Выберите файл</div>';
              }
              else
              {
                echo ($p_analysis_key + 1) . '. ' . $p_analysis_exp[1];
                echo '<i class="br5"></i>';
              }
              echo '</div>';
            }
            echo $btn;
          echo '</' . $tag . '>';
        }
      }
      elseif($element['a_type'] == 'give_analysis')
      {
        $give_analysis = Doctrine::getTable("Analysis")->findByAnswerId($element['a_id']);
        if(count($give_analysis) > 0)
        {

          /*<i class="br10"></i>
          <div class="dc_chat__links dc_chat__links_sheet_history" onclick="sheetHistoryDetails();return false;" title="Лист анамнеза"></div>
          <a href="" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>*/

          foreach ($give_analysis as $ga_key => $ga)
          {
            echo '<i class="br20"></i>';
            echo ($ga_key + 1) . '. ' . $ga->getAnalysis_type()->getTitle();
            echo '<i class="br10"></i>';
            echo '<div class="show_photo_analysis"onclick="showPhotoAnalysis.onClick(this, event);" data-analysis_id="' . $ga->getId() . '" data-answer_id="' . $element['a_id'] . '" style="width:420px;height:240px;background: url(\'/u/i/' . Page::replaceImageSize($ga->getPhoto(), 'S') . '\') no-repeat 50% 50%;background-size:cover;"></div>';
          }
        }
      }
    }
    if($element['type'] == 'email_reminder')
    {
      echo '<div style="margin-bottom: 0;" class="pc_chat__item__notice">Отправили напоминание на электронную почту</div>';
    }
    if($element['body'] == 'user_reception')
    {
      echo '<div style="margin-top:0;" class="pc_chat__item__notice">Отправили просьбу записи на очный приём</div>';
    }
    if($element['mode'] == 'r')
    {
      echo '<div class="pc_chat__item__invitation">';
      if($element['is_activated'])
      {
        echo 'Вы записались на очный прием<br><i class="br10"></i>';
        echo '<b>Место приёма:</b> ' . $element['r_location'] . '<br>';
        echo '<b>Время приёма:</b> ' . Page::rusDate($element['r_datetime']) . ' года в ' . substr($element['r_datetime'], 11, 5);
      }
      elseif($element['is_reject'])
      {
        echo 'Вы отказались от очного приема';
      }
      else
      {
        echo 'Вас пригласили на очный приём';
        if(!$no_touch_reservation)
        {
          echo '<i class="br20"></i><button class="btn_all blue_btn" onclick="paNowDialogMyReceptionInfo(' . $element['a_type'] . ');return false;">Подробнее</button>';
        }
      }
      echo '</div>';
    }
    if($closed_dialog_exp[0] == 'closed_author' || $closed_dialog_exp[0] == 'closed')
    {
      $closed_admin = $closed_dialog_exp[0] == 'closed' ? 'администратором' : '';
    ?>
      <div class="pc_chat__item__q_closed dc_chat__dialog_closed">
        <div class="chat_close_msg">
          <div class="chat_close_msg__title">
            Беседа закрыта <?php echo $closed_admin;?>
          </div>
          <div class="chat_close_msg__text">
            Спасибо, что воспользовались сервисом «Врач Онлайн».<br />
            Мы рады помочь вам.
            <?php
            if($closed_dialog_exp[0] == 'closed_author' && !$specialist_arr['review'] && !$doctor_account)
            {
              echo "<br />Вы можете оставить отзыв и оценить работу специалиста.";
            }
            ?>
          </div>
        </div>
      </div>
      <?php
      if($closed_dialog_exp[0] == 'closed_author' && !$specialist_arr['review'] && !$doctor_account)
      {
        echo '<div class="ta_c">';
          echo '<button class="btn_all blue_btn review_add_btn" onclick="overflowHiddenScroll($(\'.pc_overlay_review\'));questionIdForReview(this);">Оставить отзыв</button>';
        echo '</div>';
      }
    }
    ?>
  </div>
  <?php
//  if($element['type'] != 'email_reminder' && $element['mode'] != 'r' && $closed_dialog_exp[0] != 'closed' && $closed_dialog_exp[0] != 'closed_author')
//  {
//  }
  if ($type != 'list' && $first_question)
  {
    echo '<div class="dc_chat__links dc_chat__links_sheet_history" onclick="sheetHistoryDetails(\'consilium\');return false;" title="Лист анамнеза"></div>';
    echo '<a href="" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>';

    echo $details_btn;
  }
  if($element['a_attachment'] != '')
  {
    include_component('main', 'attachment', array('files' => $element['a_attachment']));
  }
  ?>
  <div class="pc_chat__item__bottom"><?php echo Page::rusDate($created_at) . ', ' . substr($created_at, 10, 6);?></div>
</div>