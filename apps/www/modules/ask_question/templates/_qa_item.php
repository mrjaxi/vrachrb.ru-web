<?php
$dt_params = array(
  'date' => $item['created_at'],
  'format' => 'l d F Y',
);

?>
<div class="pc_chat__item dc_patient_concilium">
  <i><?php echo RUtils::dt()->ruStrFTime($dt_params);?></i>
  <i class="br5"></i>
  <span class="pc_chat__item__status pc_chat__s_green">Сообщение от пациента</span>
  <div class="pc_chat__item__name">
    <?php
    echo '<b>' . 1 . '</b>';
    if($item['user_about_id'])
    {
      echo '<div class="pc_chat__item__note">Вопрос касается члена семьи</div>';
    }
    ?>
  </div>
  <i class="br10"></i>
  <?php echo $question['body'];?>
  <i class="br10"></i>
  <a href="" class="dc_chat__links dc_chat__links_sheet_history" title="Лист анамнеза"></a>
  <a href="" class="dc_chat__links dc_chat__links_patient_card" title="Амбулаторная карта"></a>
  <a class="pc_chat__item__details" onclick="consiliumDetails();" title="Открыть подробности беседы">
    Подробности беседы
    <img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
  </a>
</div>