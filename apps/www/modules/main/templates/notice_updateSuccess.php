<?php
$prefix = $notice['profile'] == 's' ? 'doctor' : 'personal';
echo '<div class="notice_ajax">';
  if($notice['location'] == 'dialog_index')
  {
    if($notice['event'] == 'question' || $notice['event'] == 'closed' || $notice['event'] == 'resume')
    {
      if($notice['profile'] == 's')
      {
        include_component('doctor_account', 'menu');
        $questions = $sf_user->getAccount()->getOpenQuestions();
        if(count($questions) > 0)
        {
          echo '<div class="white_box pc_user_page" style="padding-top: 0;">';
          foreach ($questions as $element)
          {
            $close_text = $element['closed_by'] && $element['closed_by'] != 0 ? 'close_question' : '';
            include_partial('doctor_account/c_item', array('questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text));
          }
          echo '</div>';
        }
      }
      else
      {
        include_component('personal_account', 'now_dialog_list');
      }
    }
  }
  elseif($notice['location'] == 'dialog_show')
  {
    if($notice['show_id'] == $notice['inner_id'])
    {
      if($notice['profile'] == 's')
      {
        include_component('doctor_account', 'menu');
        $answer_edit = true;
      }
      echo '<div class="dialog_list_notice">';
        include_component($prefix . '_account', 'now_dialog', array('answer_edit' => $answer_edit, 'q_id' => $notice['show_id'], 'now_dialog_show' => true, 'ajax' => true));
      echo '</div>';
    }
  }
  elseif($notice['location'] == 'consilium_show')
  {
    echo '<div class="dialog_list_notice">';
      include_component('doctor_account', 'consilium', array('ajax' => 'y', 'consilium_id' => $notice['show_id']));
    echo '</div>';
  }
  elseif($notice['location'] == 'consilium_index')
  {
    echo '<div class="dialog_list_notice">';
      include_component('doctor_account', 'menu');
      include_partial('doctor_account/consilium_list');
    echo '</div>';
  }
  elseif($notice['location'] == 'review')
  {
    echo '<div class="dialog_list_notice">';
      include_component('doctor_account', 'review');
    echo '</div>';
    echo '<div class="advanced_list_notice">';
      include_component('doctor_account', 'ha_patient_filter');
    echo '</div>';
  }
  $valid_menu_update = array('review', 'consilium_specialist_add', 'consilium_specialist_delete', 'question', 'closed', 'resume');
  if(substr_count($notice['full_location'], '/' . $prefix . '-account/') && in_array($notice['event'], $valid_menu_update))
  {
    echo '<div class="da_menu_notice">';
      include_component($prefix . '_account', 'menu');
    echo '</div>';
  }

  $location_exp = explode('_', $notice['location']);
  if($location_exp[1] == 'show')
  {
    if($notice['type'] != $location_exp[0])
    {
      include_component('main', 'notice', array('profile' => $notice['profile'], 'ajax' => 'y'));
    }
    else
    {
      if($notice['show_id'] != $notice['inner_id'])
      {
        include_component('main', 'notice', array('profile' => $notice['profile'], 'ajax' => 'y'));
      }
    }
  }
  elseif($notice['event'] != $notice['location'])
  {
    include_component('main', 'notice', array('profile' => $notice['profile'], 'ajax' => 'y'));
  }
echo '</div>';
?>