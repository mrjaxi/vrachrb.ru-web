<?php
if($result)
{
  include_component('personal_account', 'now_dialog', array('q_id' => $question_id, 'ajax' => true));
}
?>