<?php
if ($form->isNew() == false)
{
  ?>
  <style type="text/css">
    .comment_item{
      border-bottom: 1px solid rgba(0,0,0,0.1);
      max-width: 700px;
    }
  </style>
  <i class="br20"></i>
  <div class="fs_18">Ответы</div>
  <i class="br10"></i>
  <?php
  $answers = Doctrine_Query::create()
    ->select("a.*")
    ->from("Answer a")
    ->where("a.question_id = " . $form->getObject()->getId())
    ->orderBy("a.created_at ASC")
    ->execute()
  ;
  ?>
  <div class="wrap_comments">
    <?php
    foreach($answers as $k_answer => $answer)
    {
      if($answer->getAttachment() != '')
      {
        $attachment_exp = explode(';', $answer->getAttachment());
        echo '<div class="attachment__files">';
        foreach ($attachment_exp as $att)
        {
          $att_exp = explode('.', $att);
          $att_format = $att_exp[count($att_exp) - 1];
          if($att_format != 'png' && $att_format != 'jpg' && $att_format != 'jpeg')
          {
            echo '<a ' . ($att_format == 'pdf' ? 'target="_blank"' : '') . ' href="/u/i/' . htmlspecialchars_decode($att) . '" class="uploader_preview__item ui-sortable-handle" data-val="' . htmlspecialchars_decode($att) . '"><div class="attachment document_file_icon"></div></a>';
          }
          else
          {
            echo '<a target="_blank" href="/u/i/' . htmlspecialchars_decode(Page::replaceImageSize($att, 'M')) . '">Вложенная фотография</a>&nbsp;&nbsp;&nbsp;';
          }
        }
        echo '</div>';
      }
      $specialist = $answer->getUser()->getSpecialist();
      ?>
      <div class="comment_item">
        <table cellspacing="0" cellpadding="">
          <tr valign="top" align="left">
            <td style="padding-right: 20px" width="600">
              <div class="comment_item_field">
                <?php
                echo '<b>' . (count($specialist) > 0 ? 'Специалист' : 'Пользователь') . ': </b>' . $answer->getUser()->getSecondName() . ' ' . $answer->getUser()->getFirstName() . ' ' . $answer->getUser()->getMiddleName();
                ?>
              </div>
              <div class="comment_item_field">
                <?php
                echo $answer->getType() ? '<b>Уведомление: </b>' . Answer::AnswerType($answer->getType()) : '<b>Текст: </b>' . htmlspecialchars($answer->getBody());
                if(substr_count($answer->getType(), 'analysis'))
                {
                  if($answer->getType() == 'give_analysis')
                  {
                    $analysis_value = $answer->getAnalysis();
                  }
                  else
                  {
                    $json_analysis = json_decode($answer->getBody(), true);
                    if(is_array($json_analysis))
                    {
                      $analysis_value = $json_analysis;
                    }
                  }
                  if($analysis_value)
                  {
                    echo '<br><b>Анализы: </b>';
                    foreach ($analysis_value as $v)
                    {
                      if(is_array($analysis_value))
                      {
                        echo $v . '; ';
                      }
                      else
                      {
                        echo '<a target="_blank" href="/u/i/' . $v->getPhoto() . '">' . $v->getAnalysis_type()->getTitle() . '</a>; ';
                      }
                    }
                  }
                }
                ?>
              </div>
              <div class="comment_item_field">
                <b>Дата: </b><?php echo Page::rusDate($answer->getCreatedAt(), true) ?>
              </div>
            </td>
            <td width="100">
              <a href="<?php echo url_for('answer_edit', array('id' => $answer->getId())) ?>">Редактировать</a>
              <i class="br3"></i>
              <a href="<?php echo url_for('answer') . '/' . $answer->getId() . '/delete' ?>" onclick="comment.remove($(this), event)" class="red_link">Удалить</a>
            </td>
          </tr>
        </table>
      </div>
    <?php
    }
    ?>
    <i class="br5"></i>
    <a class="lui_pseudo" href="<?php echo url_for('answer_new') ?>?id=<?php echo $form->getObject()->getId() ?>">Добавить ответ</a>
  </div>
<?php
  $question_sheet_history = Doctrine_Query::create()
    ->select("q.*, qsh.*, shf.*, qs.*")
    ->from("Question q")
    ->innerJoin("q.QuestionSheetHistory qsh")
    ->leftJoin("qsh.SheetHistoryField shf")
    ->leftJoin("q.QuestionSpecialist qs")
    ->where("q.id = " . $form->getObject()->getId())
    ->fetchArray()
  ;
  if(count($question_sheet_history) > 0)
  {
  ?>
    <style type="text/css">
      #question_sheet_history{
        display: none;
        margin-bottom: 20px;
      }
      .sheet_history_photo{
        display: inline-block;
        position: relative;
      }
      .sheet_history_photo img{
        max-width: 200px;
        max-height: 200px;
        margin: 0 10px 10px 0;
        cursor: pointer;
        vertical-align: top;
      }
      .sheet_history_photo__show img{
        margin: 0 10px 10px 0;
        max-width: 740px !important;
        max-height: none !important;
        cursor: default !important;
      }
      .sheet_history_photo__show:hover{
        opacity: 1 !important;
      }
      .sheet_history_photo:hover{
        opacity: 0.8;
      }
      .sheet_history_photo__close{
        display: none;
        font-size: 40px;
        position: absolute;
        top: -22px;
        right: -14px;
        cursor: pointer;
      }
      .sheet_history_photo__show .sheet_history_photo__close{
        display: inline-block;
      }
      .sheet_history_photo__close:hover{
        opacity: 0.7;
      }
    </style>
    <i class="br40"></i>
    <div class="fs_18">Лист анамнеза</div>
    <i class="br20"></i>
  <?php
    echo '<div id="question_sheet_history">';
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
          $sqh_result = 'Да, ' . htmlspecialchars($qsh_value['val']);
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
            echo '-&nbsp;' . htmlspecialchars($choice);
          }
        }
        else
        {
          echo $qsh_value['val'] ? htmlspecialchars($qsh_value['val']) : 'Нет';
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
    echo '</div>';
    echo '<a class="lui_pseudo" onclick="if($(\'#question_sheet_history\').is(\':visible\')){$(\'#question_sheet_history\').hide();$(this).text(\'Показать лист анамнеза\');}else{$(\'#question_sheet_history\').show();$(this).text(\'Скрыть лист анамнеза\');};return false;">Показать лист анамнеза</a>';
  ?>
    <script type="text/javascript">
      var sheetHistoryPhoto = function(_this, type){
        var img = $(_this).find('img');

        $('.sheet_history_photo__show').removeClass('sheet_history_photo__show');
        if(type != 'close'){
          $(_this).addClass('sheet_history_photo__show');
          img.attr('src', img.attr('src').replace('-S.', '-M.'));
        }
      };
    </script>
  <?php
  }
}
?>