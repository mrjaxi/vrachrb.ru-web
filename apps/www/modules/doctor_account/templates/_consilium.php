<?php
if(count($councils) > 0)
{
  $form = new CreateConsilium_answerForm();

  $councils_count = count($councils);
  $consilium_answer_count = count($councils[0]['Consilium_answer']);

  $question = $councils[0]['Question'];
  $question_user = $question['User'];
  $account_specialist = $sf_user->getAccount()->getSpecialist();
  $question_specialist = $councils[0]['Question']['Specialists'][0];
  $consilium_closed = $councils[0]['closed'] == 1 ? true : false;
  $consilium_id = $councils[0]['id'];

  if($ajax != 'y')
  {
  ?>
    <script type="text/javascript">
      questionId = <?php echo $councils[0]['question_id'];?>;
      consiliumId = <?php echo $councils[0]['id'];?>;
    </script>
  <?php
  }
  ?>

  <div class="white_box pc_user_page">
    <b>Текущий консилиум</b>
    <div class="pc_chat">
      <?php


      if($account_specialist[0] == $question_specialist['id'])
      {
        $responsible_specialist = true;
      }

      $closed_html = '';
      if($consilium_closed)
      {
        $closed_html = '<div class="pc_chat__item__q_closed dc_chat__dialog_closed">Консилиум закрыт</div>';
        if($responsible_specialist)
        {
          if((time() - strtotime($councils[0]['closing_date'])) < 86400)
          {
            $closed_html .= '<div class="dc_chat__item__res_dialog">В течении суток вы можете возобновить консилиум<i class="br10"></i>';
            $closed_html .= '<button class="btn_all blue_btn" onclick="consiliumAnswer(\'open_' . $consilium_id . '\');return false;">Возобновить консилиум</button></div>';
          }
        }
      }

      if($consilium_closed)
      {
        $closed_end_answer = '';
        $closed_end_answer .= '<div class="pc_chat__item pc_chat__item__answer">';
          $closed_end_answer .= '<div class="pc_chat__item__author">';
            $closed_end_answer .= '<table cellpadding="0" cellspacing="0" width="100%">';
              $closed_end_answer .= '<tr valign="middle">';
                $closed_end_answer .= '<td style="padding-right: 6px;" width="1">';
                  $closed_end_answer .= '<img src="/i/n.gif" width="40" height="40" ' . ($question_specialist['User']['photo'] ? 'style="background:url(/u/i/' . Page::replaceImageSize($question_specialist['User']['photo'], 'S') . ') no-repeat 50% 50%;background-size:cover;"' : '') . 'class="pc_chat__item__author__img">';
                $closed_end_answer .= '</td>';
                $closed_end_answer .= '<td>';
                  $closed_end_answer .= '<i class="br1"></i>';

                  $name = $question_specialist['User']['first_name'] ? $question_specialist['User']['first_name'] . ' ' . $question_specialist['User']['middle_name'] . ' ' . $question_specialist['User']['second_name'] : $question_specialist['User']['username'];

                  $closed_end_answer .= '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $question_specialist['title_url'] . '/">' .$name . '</a>. <span>' . $question_specialist['about'] . '</span>';
                $closed_end_answer .= '</td>';
              $closed_end_answer .= '</tr>';
            $closed_end_answer .= '</table>';
          $closed_end_answer .= '</div>';
          $closed_end_answer .= $closed_html;
          $closed_end_answer .= '<div class="pc_chat__item__bottom">' . Page::rusDate($councils[0]['closing_date']) . ', ' . substr($councils[0]['closing_date'], 10, 6) . '</div>';
        $closed_end_answer .= '</div>';
      }

      $age = date('Y') - substr($question_user['birth_date'], 0, 4);
      $name = !$question_user['first_name'] ? $question_user['username'] : $question_user['first_name'] . ' ' . $question_user['middle_name'] . ' ' . $question_user['second_name'] . ', ' . ($age > 0 ? $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '');

      ?>
        <div class="pc_chat__item dc_patient_concilium">
          <span class="pc_chat__item__status pc_chat__s_green">Сообщение от пациента</span>
          <div class="pc_chat__item__name">
          <?php
          echo '<b>' . $name . '</b>';
          if($question['user_about_id'])
          {
            $user_about_age = date('Y') - substr($question['UserAbout']['birth_date'], 0, 4);
            echo '<div class="pc_chat__item__note pc_chat__item__note__show_user" onclick="noteShowUser(this);">';
              echo 'Вопрос касается члена семьи';
              echo '<div class="pc_chat__item__note__show_user__item">' . $question['UserAbout']['username'] . '<br>' . page::nameAge($user_about_age, $question['UserAbout']['gender']) . ', ' . $user_about_age . ' ' . Page::niceRusEnds($user_about_age, 'год', 'года', 'лет') . '</div>';
            echo '</div>';
          }
          ?>
          </div>
          <i class="br15"></i>
          <div class="pc_chat__item__body"><?php echo $question['body'];?></div>


          <div class="dc_chat__links_wrap">
            <div class="dc_chat__links dc_chat__links_sheet_history" onclick="sheetHistoryDetails('consilium');" title="Лист анамнеза"></div>
            <a target="_blank" href="<?php echo url_for('@doctor_account_patient_card?id=' . $question['user_id']);?>" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>
            <a class="pc_chat__item__details" onclick="consiliumDetails();" title="Открыть подробности беседы">
            Подробности беседы
            <img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
            </a>
          </div>
          <i class="br10"></i>
          <div class="pc_chat__item__bottom"><?php echo Page::rusDate($question['created_at']) . ', ' . substr($question['created_at'], 10, 6);?></div>
      </div>

      <?php
      if(count($councils[0]['Consilium_answer']) > 0)
      {
        foreach ($councils[0]['Consilium_answer'] as $consilium_answer_key => $consilium_answer)
        {
          $specialist_user = $consilium_answer['Specialist']['User'];

          $class_tag = '';
          $class_tag_item = '<div class="pc_chat__item__name">';
          $br = '';


          $class_tag_item = '<div class="pc_chat__item__author">';
          if($question_specialist['id'] == $consilium_answer['specialist_id'])
          {
            $class_tag = 'pc_chat__item__answer';
            $class_tag_item = '<div class="pc_chat__item__author">';
            $br = '';
          }
          ?>
          <div class="pc_chat__item pc_chat__item_doctor <?php echo $class_tag;?>">
            <?php echo $class_tag_item;?>
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr valign="middle">
                  <td style="padding-right: 6px;" width="1">
                    <img src="/i/n.gif" width="40" height="40" <?php echo $specialist_user['photo'] ? 'style="background:url(/u/i/' . Page::replaceImageSize($specialist_user['photo'], 'S') . ') no-repeat 50% 50%;background-size:cover;"' : '';?> class="pc_chat__item__author__img">
                  </td>
                  <td>
                    <?php
                    $name = $specialist_user['first_name'] ? $specialist_user['first_name'] . ' ' . $specialist_user['middle_name'] . ' ' . $specialist_user['second_name'] : $specialist_user['username'];
                    echo '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $consilium_answer['Specialist']['title_url'] . '/">' .$name . '</a>. <span">' . $consilium_answer['Specialist']['about'] . '</span>';
                    ?>
                  </td>
                </tr>
              </table>
            </div>
            <?php
            echo $br;
            echo '<div class="pc_chat__item__body">' . $consilium_answer['body'] . '</div>';
            if($councils[0]['closed'] == 1 && ($consilium_answer_key + 1) == $consilium_answer_count && $question_specialist['id'] == $consilium_answer['specialist_id'])
            {
              $closed_message = true;
              echo $closed_html;
            }
            ?>
            <div class="pc_chat__item__bottom"><?php echo Page::rusDate($consilium_answer['created_at']) . ', ' . substr($consilium_answer['created_at'], 10, 6);?></div>
          </div>
          <?php
          if(!$closed_message && ($consilium_answer_key + 1) == $consilium_answer_count && $consilium_closed)
          {
            echo $closed_end_answer;
          }
        }
      }
      elseif($councils[0]['closed'] == 1)
      {
        echo $closed_end_answer;
      }
      ?>
    </div>
  </div>

<?php
}
else
{
?>
 <div class="pc_not_dialog">
    <div class="pc_not_dialog__inner">Вас отстранили от участия в консилиуме</div>
  </div>
<?php
}

if(!$consilium_closed && $councils_count > 0)
{
?>
<form method="post" action="<?php echo url_for('@doctor_account_consilium_answer');?>" class="white_box pc_chat_add_mess" onsubmit="consiliumAnswer('add_1');return false;">
  <?php
  echo $form->renderGlobalErrors();
  echo $form->renderHiddenFields();
  echo '<input name="consilium_answer[consilium_id]" id="consilium_answer_consilium_id" value="' . $consilium_id . '" type="hidden">';
  echo $form['body'] . $form['body']->renderError();
  ?>
  <i class="br20"></i>
  <table width="100%" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td>
        <?php
        if(!$consilium_closed && $responsible_specialist)
        {
          ?>
          <button onclick="consiliumAnswer('close_<?php echo $consilium_id;?>');return false;" class="btn_all red_btn fl_l" title="Отправка сообщения с последующим закрытием консилиума">Закрыть консилиум</button>
          <?php
        }
        ?>
      </td>
      <td align="right"><button class="btn_all blue_btn">Отправить</button></td>
    </tr>
  </table>
</form>
<?php
}
?>