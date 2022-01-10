<?php
if(!$quick_open)
{
  slot('title', 'Вопросы и ответы');
?>
  <div class="breadcrumbs">
    <a href="/">Главная</a>
    <a href="<?php echo url_for('@question_answer_index'); ?>">Вопросы и ответы</a>
  </div>
<?php
}

$questions_raw = $questions->getRawValue();
foreach ($questions_raw as $question)
{
  $age = date('Y') - substr($question['User']['birth_date'], 0, 4);
  $sp_id = $question['QuestionSpecialist'][0]['specialist_id'];
  if(!$quick_open)
  {
  ?>
  <h1 class="question_answer_page__h1"><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : mb_substr(str_replace('<br>','', $question['body']), 0, 230));?></h1>
  <i class="br20"></i>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="100%">

        <div class="question_answer_page__left_white_box">
          <div class="quest_n_ans_page white_box">
          <?php

            include_component('main', 'one_question_tip', array(
              'type' => 'answer',
              'location' => 'answer',
              's_about' => '',
              's_name' => '',
              's_id' => '',
              'sp_about' => '',
              'sp_id' => '',
              'u_birth_date' => $question['User']['birth_date'],
              'u_gender' => $question['User']['gender'],
              'q_id' => '',
              'q_body' => strip_tags(nl2br($question['body'])),
              'q_closed_by' => '',
              'q_created_at' => $question['created_at'],
              'a_count' => '',
              'a_last_date' => ''
            ));

          }

          if($quick_open)
          {
            if(count($question['Answer']) > 0)
            {
              echo '<div class="quick_open__block">';
            }
          }

            $c = 0;
            foreach ($question['Answer'] as $answer)
            {

              if($answer['type'] != 'user_reception' && $answer['type'] != 'email_reminder' && $answer['type'] != 'give_analysis' && $answer['type'] != 'please_analysis' && $answer['type'] != 'please_analysis_complete')
              {

                if($c == 1 && $quick_open)
                {
                  echo $full_block = '<div class="quick_open__full_block">';
                }

                if($answer['User']['Specialist'][0])
                {
                  include_component('main', 'one_question_tip', array(
                    'type' => 'answer',
                    'location' => 'answer',
                    's_about' => $answer['User']['Specialist'][0]['about'],
                    's_name' => $answer['User']['first_name'] . ($answer['User']['middle_name'] ? ' ' . $answer['User']['middle_name'] : '') . ' ' . $answer['User']['second_name'],
                    's_title_url' => $answer['User']['Specialist'][0]['title_url'],
                    's_id' => $answer['User']['Specialist'][0]['id'],
                    's_photo' => $answer['User']['photo'],
                    'sp_about' => $answer['User']['Specialist'][0]['about'],
                    'sp_id' => '',
                    'u_birth_date' => $question['User']['birth_date'],
                    'u_gender' => $question['User']['gender'],
                    'q_id' => '',
                    'q_body' => $answer['body'],
                    'q_closed_by' => '',
                    'q_created_at' => $answer['created_at'],
                    'a_count' => '',
                    'a_last_date' => '',
                    'quick_open_second' => ($quick_open && $c == 0 ? true : false)
                  ));

                }
                else
                {
                  include_component('main', 'one_question_tip', array(
                    'type' => 'answer',
                    'location' => 'answer',
                    's_about' => '',
                    's_name' => '',
                    's_id' => '',
                    'sp_about' => '',
                    'sp_id' => '',
                    'u_birth_date' => $question['User']['birth_date'],
                    'u_gender' => $question['User']['gender'],
                    'q_id' => '',
                    'q_body' => $answer['body'],
                    'q_closed_by' => '',
                    'q_created_at' => $answer['created_at'],
                    'a_count' => '',
                    'a_last_date' => '',
                    'quick_open_second' => ($quick_open && $c == 0 ? true : false)
                  ));
                }


                if($c == 0 && $quick_open)
                {
                  echo '</div>';
                }

                $c ++;
              }
            }

            if($quick_open && count($question['Answer']) > 2)
            {
              echo '<div class="quick_open__full_btn"><div onclick="questionAnswerQuickOpen.full();return false;" class="quick_open__full_btn__item"><a>Ещё ' . ($c - 1) . '</a><span class="quick_open__full_btn__arrow"></span></div></div>';
            }

          if($quick_open && $full_block)
          {
            echo '</div>';
          }

  }

          if($quick_open)
          {
            echo '<div title="Закрыть" onclick="questionAnswerQuickOpen.close(\'ok\');" class="quick_open__close_btn">×</div>';
          }

if(!$quick_open)
{
?>
          </div>
        </div>

    </td>
    <td class="question_answer_page__right_white_box" width="1" style="padding-left: 20px;">
      <?php include_component('article', 'article', array('answer' => 'y', 'categories_id' => $sc_id, 'question_answer_articles' => true));?>
      <img src="/i/n.gif" width="245" height="1" />
    </td>
  </tr>
</table>

<?php
  include_component('main', 'similar_post', array(
    'module' => 'Question',
    'cut' => array('title' => 260),
    'url' => 'question_answer_show',
    'where_sql' => 'e.approved = 1',
    'element_id' => $question['id'],
    'fields' => array('title' => 'body', 'link' => 'id'),
    'style' => ''));
?>

  <script type="text/javascript">
    $(document).ready(function(){
      var aItem = $('.articles_page__item');
      var leftH = $('.question_answer_page__left_white_box').outerHeight();
      var rightH = $('.articles_vertical').outerHeight();
      var leftItemH = 0;
      var c = 0;
      var aVertical = $('.articles_vertical');

      aItem.each(function(_idx, _elem){
        if(leftH > rightH){
          rightH = rightH + $(_elem).outerHeight() + 20;
        }
        if(leftH > rightH){
          $(_elem).show();
          c ++;
        }
      });

      if(c > 0){
        aVertical.css('opacity', 1);
      }else{
        aVertical.hide();
      }
    });
  </script>

<?php
}
?>