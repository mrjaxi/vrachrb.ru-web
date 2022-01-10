<div class="white_box pc_user_page" style="padding-top: 0;">
  <?php
  $no_review_count = $questions[0]['close_question_count'] - $questions[0]['review_count'];
  if(count($questions) > 0 && $no_review_count > 0)
  {
    ?>
    <div class="pc_not_dialog">
      <div class="pc_not_dialog__inner">
        <?php
        echo '</i>Не забудьте оставить отзывы для <a href="' . url_for('@personal_account_patient_card') . '">' . $no_review_count . ' врач' . Page::niceRusEnds($no_review_count, 'а', 'ей', 'ей') . '</a>';
        ?>
      </div>
    </div>
    <?php
  }

  if(count($questions) > 0)
  {
    $filter_pa_count = 0;
    foreach ($questions as $element_key => $element)
    {
      $message = false;

      if ($filter_pa_list == 'close' && !((time() - strtotime($element['closing_date'])) < sfConfig::get('app_waiting_time_patient_card')))
      {
        $filter_pa_count++;
        break;
      }

      if(($element['closed_by'] && $element['closed_by'] != 0 && (time() - strtotime($element['closing_date'])) < sfConfig::get('app_waiting_time_patient_card')) || (!$element['closed_by'] || $element['closed_by'] == 0))
      {
        $text = 'Ожидает ответа врача';
        $color = 'blue';
        if(count($element['Answer']) > 0 && $element['Answer'][0]['user_id'] != $element['user_id'])
        {
          $text = 'Вам ответили';
          $color = 'green';
        }
        $message = '<span class="pc_chat__item__name_answered pc_chat__s_' . $color . '">' . $text . '</span>';

        $user_name = $element['User']['second_name'] . ' ' . $element['User']['first_name'];
        $no_specialist = $element['Specialists'][0] ? false : true;
        $close_text = $element['closed_by'] && $element['closed_by'] != 0 ? 'close_question' : '';
        include_partial('personal_account/p_item', array('name_answered' => $message, 'questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text, 'no_specialist' => $no_specialist, 'user_name' => $user_name));
      }
    }
  }
  
  if(count($questions) == $filter_pa_count)
  {
    ?>
    <div class="pc_not_dialog">
      <div class="pc_not_dialog__inner">
        У вас нет текущего диалога с врачем
        <i class="br20"></i>
        <a href="<?php echo url_for('@ask_question'); ?>" class="btn_all green_btn">Задать вопрос</a>
        <?php
        if($no_review_count > 0)
        {
          echo '<i class="br20">';
          echo '</i>Не забудьте оставить отзывы для <a href="' . url_for('@personal_account_patient_card') . '">' . $no_review_count . ' врач' . Page::niceRusEnds($no_review_count, 'а', 'ей', 'ей') . '</a>';
        }
        ?>
      </div>
    </div>
    <?php
  }
  ?>
</div>