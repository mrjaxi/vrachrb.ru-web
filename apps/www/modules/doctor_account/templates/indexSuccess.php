<?php
slot('title', 'Текущие беседы');
use_javascript('sexypicker.js');
?>

<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<div class="ready_flash filter_selector">
  Статус вопроса:
  <select autocomplete="off" onchange="getFilter('doctor', $(this).val())">
    <option value="all" selected>Все</option>
    <option value="open">Открытые</option>
    <option value="close">Закрытые</option>
  </select>
  <img class="filter_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
</div>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('doctor_account', 'menu');
      echo '</div>';
      echo '<div class="pc_user_page_wrap">';
        $questions = $sf_user->getAccount()->getOpenQuestions('question_list', false);
        if(count($questions) > 0)
        {
          echo '<div class="white_box pc_user_page" style="padding-top: 0;">';
          foreach ($questions as $element)
          {
            $close_text = $element['closed_by'] && $element['closed_by'] != 0 ? 'close_question' : '';
            if(count($element['Review']) > 0)
            {
              if(time() - strtotime($element['closing_date']) < 86400)
              {
                include_partial('c_item', array('questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text));
              }
            }
            else
            {
              $message = false;
              if(!$element['closed_by'] || $element['closed_by'] == '')
              {
                $text = 'Ожидает вашего ответа';
                $color = 'blue';
                if(count($element['Answer']) > 0 && $element['Answer'][0]['user_id'] != $element['user_id'])
                {
                  $text = 'Вы ответили';
                  $color = 'green';
                }
                $message = '<span class="pc_chat__item__name_answered pc_chat__s_' . $color . '">' . $text . '</span>';
              }

              include_partial('c_item', array('name_answered' => $message, 'questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text));
            }
          }
          echo '</div>';
        }
        else
        {
        ?>
          <div class="pc_not_dialog">
            <div class="pc_not_dialog__inner">
              Нет текущих бесед
            </div>
          </div>
        <?php
        }
      echo '</div>';
      ?>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 's'));?>
      </div>
      <div style="min-width:300px;"></div>
    </td>
  </tr>
</table>
<?php
if($sf_user->hasFlash('question_redirect_admin') || $sf_user->hasFlash('question_complaint'))
{
  ?>
  <script type="text/javascript">
    alert('<?php echo ($sf_user->hasFlash('question_redirect_admin') ? $sf_user->getFlash('question_redirect_admin') : $sf_user->getFlash('question_complaint'));?>');
  </script>
  <?php
}
?>