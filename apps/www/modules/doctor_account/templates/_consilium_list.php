<?php
$councils_count = count($councils);
$consilium_answer_count = count($councils[0]['Consilium_answer']);

if(count($sf_user->getAccount()->getOpenQuestions('consilium')) > 0)
{
  foreach ($sf_user->getAccount()->getOpenQuestions('consilium') as $element)
  {
    $close_text = $element['Consilium'][0]['closed'] == 1 ? 'close_consilium' : '';
    include_partial('doctor_account/c_item', array('element' => $element, 'type' => 'list', 'close_text' => $close_text));
  }
}
else
{
  ?>
  <div class="pc_not_dialog">
    <div class="pc_not_dialog__inner">
      Нет текущих консилиумов
    </div>
  </div>
  <?php
}
?>
