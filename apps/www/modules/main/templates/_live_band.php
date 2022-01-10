<?php
foreach ($live_ribbons_clone as $live_ribbon)
{
  $author_link = 'Врач РБ';
  $author_about = 'Сервис медицинской консультации';
  if($live_ribbon['sp_id'] != 51)
  {
    $author_link_prefix = $live_ribbon['type'] == 'q' ? 'q' : 'sp';
    $str_about = Page::strCut($live_ribbon['sp_about'], 180);
    $author_about = $str_about[0];
    if($str_about[1] == 'full')
    {
      $author_about = '<span title="' . $live_ribbon['sp_about'] . '">' . $str_about[0] . '</span>';
    }
    $author_link = '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $live_ribbon['s_title_url'] . '/">' . $live_ribbon[$author_link_prefix . '_name'] . '</a>';
  }
  if($live_ribbon['type'] == 'q')
  {
    $age = date('Y') - substr($live_ribbon['q_birth_date'], 0, 4);
    ?>
    <div class="live_band__item new_live_band__item">
      <table width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" class="live_band__item__author live_band__item__block" height="30">

            <div class="live_band__item__author__div">
              <table class="live_band__item__author__div__table" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr valign="middle">
                  <td class="live_band__item__author__name" width="1">отвечает:</td>
                  <td>
                    <?php echo $author_link . '. ' . $author_about;?>
                  </td>
                  <td width="1" class="live_band__item__author__name">кабинет:</td>
                  <td width="1">
                    <?php
                    $str = Page::strCut($live_ribbon['sp_title'], 50);
                    echo '<a href="' . url_for('@categories_transition_filter?id=' . $live_ribbon['spy_id']) . '" class="live_band__item__author_link" ' . ($str[1] == 'full' ? 'title="' . $live_ribbon['sp_title'] . '"' : '') . '>' .   str_replace(' -', '&nbsp;-', $str[0]) . '</a>';
                    ?>
                  </td>
                </tr>
              </table>
            </div>

          </td>
        </tr>
        <tr>
          <td colspan="2" height="40" class="live_band__item__block">
            <b><?php echo Page::nameAge($age, $live_ribbon['q_gender']) . ($age == date('Y') ? '' : ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет'));?></b>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block">
            <?php
            echo '<a ' . (sfConfig::get('app_question_quick_open') ? 'onclick="questionAnswerQuickOpen.open(this, ' . $live_ribbon['id'] . ');return false;"' : '') . 'href="' . url_for('@question_answer_show?id=' . $live_ribbon['id']) . '" class="live_band__item__body">';
            $live_ribbon_raw  = $live_ribbon->getRawValue();
            echo '<div class="live_band__item__body__text">' . strip_tags($live_ribbon_raw['p_description_q_body']) . '</div>';
            echo '</a>';
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block">
            <div class="live_band__item__bottom_line"></div>
          </td>
        </tr>
        <tr class="live_band__item__bottom">
          <td colspan="2" height="30" class="live_band__item__block">
            <table width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" width="110">
                  <?php echo Page::rusDate($live_ribbon['created_at']);?>
                </td>
                <td width="200" align="left">
                  <?php
                  $live_ribbon_answer_value = 'Без ответа';
                  if($live_ribbon['a_count'] > 0)
                  {
                    $a_last_date = strtotime($live_ribbon['a_last_date']);
                    $q_last_date = strtotime($live_ribbon['created_at']);
                    if(isset($a_last_date) && isset($q_last_date) && $a_last_date > $q_last_date)
                    {
                      $time = $a_last_date - $q_last_date;
                      $live_ribbon_answer_value = 'Ответ получен (' . Page::timeUnit($time, array(), 'cut') . ')';
                    }
                  }
                  echo $live_ribbon_answer_value;
                  ?>
                </td>
                <td>
                  <?php
                  echo (isset($live_ribbon['q_closed_by']) ? '✓&nbsp;Вопрос закрыт' : 'В обсуждении') . ' (' . $live_ribbon['a_count'] . '&nbsp;сообщ.)';
                  ?>
                </td>
                <td width="1">
                  <a onclick="yaCounter36726625.reachGoal('LIVEASK');" href="<?php echo url_for('@question_answer_index');?>" class="live_band__item__adv_link">Вопросы и ответы</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <?php
  }
  else
  {
    ?>
    <div class="live_band__item live_band__item_tips new_live_band__item_tips new_live_band__item">
      <table width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" class="live_band__item__author" height="30">
            <div class="live_band__item__author__item live_band__item__block"><span class="live_band__item__author__name">автор:</span> <?php echo $author_link . '. ' . $author_about;?></div>
          </td>
        </tr>
        <tr>
          <td class="live_band__item__block" colspan="2" height="40">
            <b><?php echo $live_ribbon['title'];?></b>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block">
            <a href="<?php echo url_for('@tip_index') . $live_ribbon['p_title_url'] . '/';?>" class="live_band__item__body">
              <?php
              $live_ribbon_raw  = $live_ribbon->getRawValue();
              echo '<div class="live_band__item__body__text">' . $live_ribbon_raw['p_description_q_body'] . '</div>';
              ?>
            </a>
          </td>
        </tr>
        <tr>
          <td class="live_band__item__block" colspan="2">
            <div class="live_band__item__bottom_line"></div>
          </td>
        </tr>
        <tr class="live_band__item__bottom">
          <td class="live_band__item__block" height="30"><?php echo Page::rusDate($live_ribbon['created_at']);?></td>
          <td width="1" class="live_band__item__block" align="right">
            <a href="<?php echo url_for('@tip_index');?>" class="live_band__item__adv_link">Советы</a>
          </td>
        </tr>
      </table>
    </div>
    <?php
  }
}
?>

<?php
if($type == 'general')
{
?>
  <script type="text/javascript">
    var liveBandTime = null;
    var randValue = 15000;
    var min = 10000;
    var max = 20000;
    var shaStr = '<?php echo $sha_str;?>';
    var liveBandElem = $('.live_band');
    var liveBandOp = $('.live_band__opacity');
    var cgSpecialtyId = false;
    var cgCheckbox = $('.categories_general_checkbox');
    var mDoctors = null;
    var mDoctorsHeight = null;
    specialistOnline = [<?php echo $specialist_on;?>];
    
    var specialistOnlineUpdate = function () {
      for(var dap = 0; dap < doctorItemArr.length; dap ++){
        for(var dap1 = 0; dap1 < specialistOnline.length; dap1 ++){
          if(doctorAllParam[dap]['spt_id'] == parseInt(specialistOnline[dap1])){
            doctorAllParam[dap]['online'] = true;
          }
        }
      }
    };

    var generalSize = function (_type) {
      if(liveBandElem.outerHeight() >= mDoctors.outerHeight()){
        mDoctors.outerHeight(liveBandElem.outerHeight());
      }else{
        mDoctors.outerHeight(mDoctorsHeight);
      }

      if(_type != 'start'){
        vrb.elemRes.mainHeightRes();
      }

      var liveBandOpH = liveBandOp.outerHeight();
      if(liveBandOpH > 0){
        liveBandOp.css('height', liveBandOpH);
      }
    };

    var liveBandUpdate = function(_update){
      if(_update == 'update'){
        shaStr = 12345;
      }

      $.post('/live-band-update/', 'update=' + shaStr + (cgSpecialtyId && cgSpecialtyId != '' ? '&specialty_id_str=' + cgSpecialtyId : '') , function(data){
        if(data != ''){
          var startSpecialistPos = data.indexOf('update_specialist_start');
          var endSpecialistPos = data.indexOf('update_specialist_end');
          var specialistStr = data.slice(startSpecialistPos + 23, endSpecialistPos);

          if(specialistStr != ''){
            specialistOnline = specialistStr.split('and');

            specialistOnlineUpdate();
          }

          if(data.indexOf('-:update_none:-') != -1){

          }else{
            var startPos = data.indexOf('update_key_start');
            var endPos = data.indexOf('update_key_end');
            shaStr = data.slice(startPos + 16, endPos);
            var html = data.replace('update_key_start' + shaStr + 'update_key_end', '').replace('update_specialist_start' + specialistStr + 'update_specialist_end', '');

            if($('.quick_open').length == 0 || _update == 'update'){

              var liveBandOpacityElem = $('.live_band__opacity');

              liveBandOpacity(true);

              liveBandOp.html(html);

              liveBandOpacityElem.addClass('live_band__opacity_height');
              liveBandOpacityElem.css('height', liveBandOpacityElem.outerHeight() + 20);
              liveBandOpacityElem.removeClass('live_band__opacity_height');

              generalSize();
              if(_update == 'update'){
                $('.live_band').removeClass('live_band__update');
              }
            }else{
              $('.live_band').addClass('live_band__update');
            }
          }
        }
        liveBandOpacity(false);
        clearTimeout(liveBandTime);
        liveBand();
      });

      randValue = parseInt(Math.random() * (max - min) + min);

      liveBand();
    };

    var liveBand = function(){
      if(liveBandTime){
        clearTimeout(liveBandTime);
      }
      liveBandTime = setTimeout(function(){
        liveBandUpdate();
      },randValue);
    };

    var liveBandOpacityValue = false;
    var liveBandOpacityInterval = null;
    var liveBandOpacity = function(param){
      if(param == 'update'){
        liveBandOp.css('opacity', 0.2);
        setTimeout(function(){
          liveBandOp.css('opacity', 1);
        }, 2000);
      }else if(param){
        cgCheckbox.prop('disabled', 'disabled');

        liveBandOp.css('opacity', 0.2);
        liveBandOpacityValue = true;
        setTimeout(function(){
          liveBandOpacityValue = false;
        }, 400);
      }else{
        var liveBandOpacityInterval = setInterval(function(){
          if(!liveBandOpacityValue){
            clearInterval(liveBandOpacityInterval);
            cgCheckbox.removeProp('disabled');
            liveBandOp.css('opacity', 1);
          }
        }, 100);
      }
    };

    $(window).load(function(){
      mDoctors = $('.menu_doctors');
      mDoctorsHeight = mDoctors.outerHeight();
      $('.categories_general_checkbox, .specialist_sorting__radio').removeProp('checked');
      generalSize('start');
      liveBand();
      $('.rating_doctors__item_wrap').perfectScrollbar({
        wheelSpeed: 2
      });

      $('.live_band__opacity').perfectScrollbar({
        wheelSpeed: 2
      });

      specialistOnlineUpdate();
      questionAnswerQuickOpen.init();

      $('.main_cont').css('opacity', 1);
    });
  </script>
<?php
}
?>