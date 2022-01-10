<?php
slot('title', 'Вопросы и ответы');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Вопросы и ответы');?></h1>
<a href="<?php echo url_for('@ask_question'); ?>" class="new_btn_all btn_all green_btn">Задать вопрос</a>
<i class="br10"></i>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%" style="position:relative;">
      <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">
      <div class="quest_page white_box">
      <?php
        include_component('question_answer', 'question_index');
      ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <?php
//      echo '<div style="min-width:265px;"></div>';
      include_component('main', 'specialty', array('table' => 'qa', 'advanced' => 'qa'));
      ?>
    </td>
  </tr>
  <tr>
    <td align="center">
      <?php
      if($sf_user->getAttribute('lpu'))
      {
        $lpu_str = ' AND (';
        foreach ($sf_user->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
        {
          $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 'qs.specialist_id = ' . $lpu_specialist;
        }
        $lpu_str .= ')';
      }
      echo Page::tipCountPage(10, 1, 'Question', $lpu_str);
      ?>
    </td>
    <td></td>
  </tr>
</table>