<style type="text/css">
  .sheet_history_photo{
    display: inline-block;
    position: relative;
    vertical-align: top;
  }
  .sheet_history_photo img{
    max-width: 200px;
    max-height: 200px;
    margin: 0 10px 10px 0;
    cursor: pointer;
  }
  .sheet_history_photo__show{
    display: block;
  }
  .sheet_history_photo__show img{
    margin: 0 10px 10px 0;
    max-width: 740px !important;
    max-height: none !important;
  }
  .sheet_history_photo__show:hover{
    opacity: 1 !important;
  }
  .sheet_history_photo:hover{
    opacity: 0.8;
  }
  .sheet_history_photo__close{
    display: none;
  }
  .sheet_history_photo__show:hover .sheet_history_photo__close{
    display: inline-block;
    position: absolute;
    top: 0;
    right: 0;
  }
</style>
<?php
echo '<div id="question_sheet_history_true"></div>';

foreach ($question_sheet_history[0]['QuestionSheetHistory'] as $qsh_key => $qsh)
{
  $qsh_value = json_decode(htmlspecialchars_decode($qsh['value']), true);

  if($qsh_key != 0)
  {
    echo '<i class="br10"></i>';
  }

  echo '<b>' . $qsh['SheetHistoryField']['title'] . '</b>';
  echo '<i class="br10"></i>';

  if($qsh_value['bool'])
  {
    $sqh_result = 'Нет';
    if($qsh_value['bool'] == 'Да')
    {
      $sqh_result = 'Да, ' . $qsh_value['val'];
    }
    echo $sqh_result;
    echo '<i class="br10"></i>';
  }
  else
  {
    if(is_array($qsh_value['choices']))
    {
      foreach ($qsh_value['choices'] as $choice)
      {
        echo '<i class="br5"></i>';
        echo '-&nbsp;' . $choice;
      }
    }
    else
    {
      echo $qsh_value['val'] ? $qsh_value['val'] : 'Нет';
      if($qsh_value['file'])
      {
        echo '<i class="br10"></i>';
        $file_exp = explode(';', $qsh_value['file']);
        foreach ($file_exp as $file)
        {
          echo '<div onclick="sheetHistoryPhoto(this, false);" class="sheet_history_photo">';
            echo '<img src="/u/i/' . Page::replaceImageSize($file, 'S') . '">';
            echo '<div class="overlay__close sheet_history_photo__close" onclick="sheetHistoryPhoto(this, \'close\');event.stopPropagation();">×</div>';
          echo '</div>';
        }
      }
    }
  }
}
?>