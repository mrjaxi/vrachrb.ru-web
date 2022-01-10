<?php
if($ajax)
{
  $label = 'custom_input_label';
  $checkbox = 'custom_input';
  $span = '<span class="custom_input custom_input_checkbox"></span>';
}
$count = 0;
foreach ($agreement as $ag)
{
  if($ag['ac_count'] == 0)
  {
    if($count != 0)
    {
      echo '<i class="br10"></i>';
    }
    echo '<label class="agreement ' . $label . '">';
    echo '<input class="agreement__check ' . $checkbox . '" onchange="agreementCheck();" type="checkbox" name="agreement[' . ($count + 1) . ']" value="' . $ag['id'] . '">' . $span;
    echo $ag['description'];
    if($ag['body'] != '')
    {
      echo ' <a target="_blank" href="' . url_for('@agreement_show?id=' . $ag['id']) . '">Подробнее</a>';
    }
    echo '</label>';
    $count ++;
  }
}
echo '<i class="br20"></i>';
if($btn)
{
  echo '<div style="text-align: right;"><button onclick="agreementCheck(this, \'submit\');return false;" style="visibility: visible;" id="ask_question_form_submit_btn" class="btn_all blue_btn agreement_btn disabled_btn">Принимаю</button></div>';
}