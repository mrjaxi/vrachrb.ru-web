<?php
slot('title', 'Специалисты');
use_javascript('masonry.pkgd.min.js');
use_javascript('fotorama.js');
use_stylesheet('fotorama.css');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
  <a href="<?php echo url_for('@specialist_index'); ?>">Специалисты</a>
</div>
<h1 style="margin-bottom: 0;"><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $user->getFirstName() . ' ' . $user->getMiddleName() . ' ' . $user->getSecondName());?></h1>
<i class="br1"></i>
<?php echo $specialists->getAbout();?>
<i class="br20"></i>
<div class="specialist_show_page inner_page">
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="1">
        <div class="inner_page__l">
          <img style="background: url('/u/i/<?php echo Page::replaceImageSize($user->getPhoto(),'M');?>') no-repeat center" src="/i/n.gif" width="230" height="230" class="inner_page__l__photo_doc" />
            <table cellpadding="0" cellspacing="0" width="100%" style="margin-left: -20px;">
              <tr valign="top">
                <td width="50%" class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;" align="right"><span class="fs_22"><?php echo $specialists->getRating() != '' ? number_format($specialists->getRating(), 1, ',', ' ') : '&mdash;';?></span><br/><span>рейтинг</span></td>
                <td width="50%" class="tcolor_red" style="padding-left: 10px;">
                  <span class="fs_22">
                    <?php
                    echo $consultation = $specialists->getAnswersCount() ? $specialists->getAnswersCount() : 0;
                    ?>
                  </span>
                  <br/>
                  <span>консультац<?php echo Page::niceRusEnds($consultation,'ия','ии','ий');?></span>
                </td>
            </tr>
          </table>
          <i class="br20"></i>
          <a href="<?php echo url_for('@ask_question_select_specialist?id=' . $specialists->getId()); ?>" class="btn_all blue_btn inner_page__l__ask_quest_btn" style="width:100%;">Задать вопрос</a>
          <i class="br10"></i>
          <a href="<?php echo url_for('@donate'); ?>" class="btn_all b_orange_btn inner_page__l__donate_btn" style="width:100%;">Поддержать проект</a>
        </div>
      </td>
      <td width="100%" class="inner_page__r">
        <div class="tabs_wrap">
          <ul class="ftext inner_page__r__menu tabs_menu">
            <li data-tab="0">Общее</li>
            <li data-tab="1">Отзывы</li>
            <li data-tab="2">Советы</li>
            <li data-tab="3">Статьи</li>
            <li data-tab="4">Консультации</li>
          </ul>
          <div class="tabs_content" data-tab="0" style="display: none;">
            <?php
            echo '<div class="white_box" style="padding: 40px;">';
            if(trim(strip_tags($specialists->getEducation(ESC_RAW))) != '')
            {
              if(trim(strip_tags($specialists->getEducation(ESC_RAW))) != '')
              {
                echo '<div class="specialist_show__education">';
                  echo '<b>Образование и работа:</b>';
                  echo '<i class="br10"></i>';
                  echo '<p>' . $specialists->getEducation(ESC_RAW) . '</p>';
                echo '</div>';
              }
              if(count($specialist_work_place) > 0)
              {
                echo '<div class="specialist_work_place_wrap">';
                  echo '<b>Место работы:</b>';
                  echo '<i class="br10"></i>';

                  foreach ($specialist_work_place as $specialist_wp)
                  {
                    echo '<p>' . $specialist_wp['title'] . '</p>';
                  }
                echo '</div>';
              }
            }
            include_component('specialist', 'certificate', array('specialist_id' => $specialists->getId()));
            echo '</div>';
            ?>
          </div>
          <div class="tabs_content" data-tab="1" style="display: none;" data-init="docInnerMasonry();">

            <?php
            if(count($reviews) > 0)
            {
              echo '<div class="white_box" style="padding: 10px;">';
                echo '<div class="reviews clearfix">';
                  foreach ($reviews as $review)
                  {
                    $score = ($review['informative'] + $review['courtesy']) / 2;
                    ?>
                    <div class="reviews__item_wrap fl_l">
                      <div class="reviews__item">
                        <i class="reviews__item__date"><?php echo Page::rusDate($review['created_at']);?></i>
                        <div class="reviews__item__stars">
                          <?php
                          echo '<input type="text" value="' . $score . '" class="stars_plugin" />';
                          ?>
                        </div>
                        <i class="br1"></i>
                        <?php
                        $now_age = $review['created_at'] - $review['User']['birth_date'];
                        echo '<b>' . Page::nameAge($now_age, strtolower($review['User']['gender'])) . ($now_age != date('Y') ? ', ' . $now_age . ' ' . Page::niceRusEnds($now_age, 'год', 'года', 'лет') : '') . '</b>';
                        ?>
                        <i class="br5"></i>
                        <div><?php echo $review['body'];?></div>
                        <div class="reviews__item__q_link">
                          <a target="_blank" href="<?php echo url_for('@question_answer_show?id=' . $review['question_id']);?>">Перейти к вопросу</a>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                echo '</div>';
              echo '</div>';
            }
            ?>

          </div>
          <div class="tabs_content" data-tab="2" style="display: none;" data-init="docInnerMasonry();">
            <?php
            if(count($prompts) > 0)
            {
              echo '<div class="tips_page white_box clearfix">';
                echo '<div class="tips_page_wrap">';
                  foreach ($prompts as $prompt)
                  {
                    ?>
                    <div class="tips_page__item fl_l">
                      <div style="padding: 15px 20px 20px;" class="live_band__item live_band__item_tips">
                        <div class="live_band__item__tags clearfix">
                        </div>
                        <i class="live_band__item__date"><?php echo Page::rusDate($prompt['created_at']);?></i>
                        <i class="br5"></i>
                        <div class="all_link_item">
                          <a href="<?php echo url_for('@tip_index') . $prompt['title_url'];?>/" class="live_band__item__link"><b><?php echo $prompt['title'];?></b></a>
                          <i class="br10"></i>
                          <?php
                          if($prompt['photo'])
                          {
                            echo '<img src="/i/n.gif" width="270" height="140" style="background:url(/u/i/' . Page::replaceImageSize($prompt['photo'],'S') . ') no-repeat center;" class="imgs_grey_shd" />';
                            echo '<i class="br10"></i>';
                          }
                          echo $prompt->getDescription(ESC_RAW);
                          ?>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                echo '</div>';
              echo '</div>';
            }
            ?>
          </div>
          <div class="tabs_content" data-tab="3" style="display: none;">
            <?php include_component('article', 'article', array('specialist_id' => $specialists->getId(), 'specialists' => true));?>
          </div>

          <div class="tabs_content" data-tab="4" style="display: none;">
          <?php
          if(count($question_answer) > 0)
          {
            echo '<div class="white_box">';
            foreach ($question_answer as $question_answer_item)
            {
              include_component('main', 'one_question_tip', array(
                'type' => 'question',
                'location' => 'specialists',
                's_about' => $question_answer_item['Specialists'][0]['about'],
                's_name' => $question_answer_item['Specialists'][0]['User']['first_name'] . ($question_answer_item['Specialists'][0]['User']['middle_name'] ? ' ' . $question_answer_item['Specialists'][0]['User']['middle_name'] : '') . ' ' . $question_answer_item['Specialists'][0]['User']['second_name'],
                's_id' => '',
                'sp_about' => $question_answer_item['Specialists'][0]['Specialty']['title'],
                'sp_id' => $question_answer_item['Specialists'][0]['specialty_id'],
                'u_birth_date' => $question_answer_item['User']['birth_date'],
                'u_gender' => $question_answer_item['User']['gender'],
                'q_id' => $question_answer_item['id'],
                'q_body' => $question_answer_item['body'],
                'q_closed_by' => $question_answer_item['closed_by'],
                'q_created_at' => $question_answer_item['created_at'],
                'a_count' => $question_answer_item['a_count'],
                'a_last_date' => $question_answer_item['a_last_date']
                ));
            }
            echo '</div>';
          }
          ?>
          </div>
          
        </div>
      </td>
    </tr>
  </table>
</div>
<script type="text/javascript">
  var docInnerMasonry = function(){
    $('.tips_page_wrap').masonry({
      itemSelector: '.tips_page__item',
      percentPosition: true,
      transitionDuration: 0
    });
    $('.reviews').masonry({
      itemSelector: '.reviews__item_wrap',
      percentPosition: true,
      transitionDuration: 0
    });
  };
</script>

<?php
/*
<script type="text/javascript">
  $(document).ready(function () {
    $('.specialist_work_place_wrap').append('<ol class="dc_p_data_job_ol"><li class="dc_p_data_job_ol_inp"><input type="text" name="inp_1" value="" /><label class="custom_input_label" title="Если поставить галочку «Скрыть», то данное место работы не отображается на публичной странице, но отображается в при составлении приглашения на очный приём"><input style="top:-1px;" type="checkbox" name="inp_hidden_1" id="" class="check_work_place" />&nbsp;Скрыть</label><span class="delete_btn_all delete_btn_all_hidden" title="Удаление"></span></li></ol><input class="add_elem" type="submit" value="Добавить ещё" />');

//    $('.add_elem').click(function () {
//      add('.dc_p_data_job_ol_inp');
//      return false;
//    });

    $('.sf_admin_form_field_work_place').on('click', '.delete_btn_all', function () {
      $(this).parent().remove();
      indexed('.dc_p_data_job_ol_inp');
      return false;
    });

    generateWorkPlace('start');

    $('form').attr("onsubmit", "generateWorkPlace('submit')");

  });

  var add = function(cloned){
    var _this = this;
    var $cloned = $(cloned);
    var clone = $cloned.first().clone();

    $.each(clone.find('input'), function(i, inp){
      var $inp = $(inp);

      switch($inp.attr('type')) {
        case 'email':
        case 'text':
          $inp.val('');
          break;
        case 'checkbox':
          $inp.removeAttr('checked');
          break;
        case 'radio':
          break;
      }
    });

    clone.find('.delete_btn_all').removeClass('delete_btn_all_hidden');
    $cloned.last().after(clone);
    if($('.dc_invation_inp input[type="text"]').length > 0){
      $('.dc_invation_inp input[type="text"]').spinpicker({lang:"ru"});
    }
    _this.indexed(cloned);
  };

  var indexed = function(elems){
    var _this = this;
    var $elems = $(elems);
    $.each($elems, function(i, elem){
      $(elem).find('input, select').each(function(j, el){
        var $el = $(el);
        var name = $el.attr('name').replace(/\d/g, '');
        $el.attr('name', name + i);
      });
    });
    if($elems.length > 1){
      $elems.first().find('.delete_btn_all').removeClass('delete_btn_all_hidden');
    }else{
      $elems.first().find('.delete_btn_all').addClass('delete_btn_all_hidden');
    }
  };

  var generateWorkPlace = function (elem) {
    var strArr;
    if(elem == 'start'){
      var strArr = ($('#specialist_work_place_val').val()).split(':division:');

      console.log(strArr);

      if(strArr.length != 0){
        strArr.forEach(function (item, i) {
          if(i != 0){
            add('.dc_p_data_job_ol_inp');
          }
          $('.sf_admin_form_field_work_place').find('.dc_p_data_job_ol_inp').eq(i).find('input[type="text"]').val(item.slice(0, -1));
          if(item.slice(-1) == 1){
            $('.sf_admin_form_field_work_place').find('.dc_p_data_job_ol_inp').eq(i).find('.custom_input_checkbox').click();
          }
        });
      }
    }else if(elem == 'submit'){
      var str = '';
      $('.dc_p_data_job_ol_inp').each(function (i, elem) {
        var inputVal = $(elem).find('input[type="text"]').val();
        if(inputVal != ''){
          if(i != 0){
            str += ':division:';
          }
          str += inputVal;
          str += $(elem).find('.check_work_place').is(':checked') ? '0' : '1';
        }
      });
      $('#specialist_work_place').val(str);
    }
  };
</script>

*/
?>