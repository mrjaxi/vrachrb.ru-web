<?php
if($result)
{
  include_component('doctor_account', 'menu');
  include_component('doctor_account', 'now_dialog', array('q_id' => $question_id, 'answer_edit' => true));
}
?>