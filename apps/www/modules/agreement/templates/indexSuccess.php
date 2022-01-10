<?php
if(count($agreement) > 0)
{
  ?>
  <style type="text/css">
    .registration_form__wrap{
      display: none;
    }
    .auth_form__wrap{
      border: none !important;
      padding: 0 !important;
    }
  </style>
  <?php
  echo '<h2>Для пользования порталом вам необходимо прочесть и принять соглашения:</h2>';
  echo '<form method="post" action="' . url_for('@agreement_index') . '" id="agreement__form" class="white_box">';
  $count = 1;
  foreach ($agreement as $a)
  {
    $write = true;
    if(count($agreement_complete) > 0)
    {
      foreach ($agreement_complete as $agc)
      {
        if($agc['agreement_id'] == $a->getId())
        {
          $write = false;
          break;
        }
      }
    }
    if($write)
    {
      if($count != 1)
      {
        echo '<i class="br20"></i>';
      }
      echo '<label class="agreement">';
      echo '<input class="agreement__check" onchange="agreementCheck();" type="checkbox" name="agreement[' . $count . ']" value="' . $a->getId() . '">';
      echo $a->getDescription(ESC_RAW);
      if($a->getBody() != '')
      {
        echo ' <a target="_blank" href="' . url_for('@agreement_show?id=' . $a->getId()) . '">Подробнее</a>';
      }
      echo '</label>';
      $count ++;
    }
  }
  echo '<i class="br20"></i>';
  echo '<div style="text-align: right;"><button onclick="agreementCheck(this, \'submit\');return false;" style="visibility: visible;" id="ask_question_form_submit_btn" class="btn_all blue_btn agreement_btn disabled_btn">Принимаю</button></div>';
  echo '</form>';
}
?>