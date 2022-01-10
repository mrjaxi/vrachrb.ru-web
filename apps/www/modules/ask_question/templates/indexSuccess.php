<?php
slot('title', 'Задать вопрос');
use_javascript('//ulogin.ru/js/ulogin.js');
use_javascript('sexypicker.js');

echo '<div class="analysis_photo_wrap">';
  echo $analysis_form->renderGlobalErrors();
  echo $analysis_form->renderHiddenFields();
  echo $analysis_form['photo'] . $analysis_form['photo']->renderError();
echo '</div>';
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Задать вопрос</h2>
<?php
/*<form method="post" enctype="multipart/form-data" id="ask-question-form">*/
?>
<div class="ready_flash ask_q_page">
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="100%">
        <table cellpadding="0" cellspacing="0" width="100%" class="question_tabs">
          <tr>
            <td class="question_tabs__item" data-tab="0" valign="middle">
              <div class="question_tabs__item__elem">
                <div class="question_tabs__item__elem__text">1.&nbsp;Выбор специалиста<br>или кабинета врача</div>
              </div>
            </td>
            <td class="question_tabs__item" data-tab="1" valign="middle">
              <div class="question_tabs__item__elem">
                <div class="question_tabs__item__elem__text">2.&nbsp;Описание симптомов</div>
              </div>
            </td>
            <td class="question_tabs__item" data-tab="2" valign="middle" onclick="sheetHistoryUpdate(this);">
              <div class="question_tabs__item__elem">
                <div class="question_tabs__item__elem__text">3.&nbsp;Лист анамнеза</div>
              </div>
            </td>
            <td class="question_tabs__item" data-tab="3" valign="middle">
              <div class="question_tabs__item__elem">
                <div class="question_tabs__item__elem__text">4.&nbsp;Личные данныe</div>
              </div>
            </td>
          </tr>
        </table>
        <div style="position:relative;" class="ask-question-form-wrap">
          <form method="post" enctype="multipart/form-data" id="ask-question-form">
            <div class="question_cont_tab" data-tab="0">
              <div class="ask_quest_page__section ask_qp_section_first">
                <h3>Выберите кабинет</h3>
                <div class="q_cabinets white_box clearfix">
                  <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="q_cabinets__elements__wrap" width="70%" valign="top">
                        <?php
                        echo '<div class="q_cabinets__elements">';
                        foreach ($specialty as $item_key => $item)
                        {
                          echo '<label class="q_cabinets__item__label ' . ($item_key < 14 ? 'q_cabinets__item__visible' : '') . '"><input ' . ($specialty_visible_id ? ($specialty_visible_id == $item['id'] ? 'checked="checked"' : '') : '') . ' type="radio" name="question[specialty_id]" value="' . $item['id'] . '" class="n_cust q_cabinets__item_inp" /><span ' . ($item['description'] != '' ? 'title="' . $item['description'] . '"' : '') . ' class="q_cabinets__item q_cabinets__item_active" ' . ($item['description'] ? $item['description'] : '') . '>' . $item['title'] . '</span></label>';
                        }
                        echo '<label class="q_cabinets__item__label"><input type="radio" name="question[specialty_id]" value="undefined" class="n_cust q_cabinets__item_inp" /><span class="q_cabinets__item q_cabinets__item_blue">Затрудняюсь выбрать</span></label>';
                        echo '<div class="q_cabinets__elements__open">ВСЕ КАБИНЕТЫ</div>';
                        echo '</div>';
                        ?>
                      </td>
                      <td align="right" valign="top" width="33%">
                        <div class="q_cabinets__search">
                          <div class="q_cabinets__search__message">Пожалуйста, заполните поле</div>
                          <input type="text" placeholder="Поиск по кабинетам" onkeyup="if(event.keyCode != 13){$('.q_cabinets__search__message').hide();searchWord($('.q_cabinets__search__input').val(), false);};" class="q_cabinets__search__input">
                          <div class="q_cabinets__search__btn" onclick="searchWord($('.q_cabinets__search__input').val(), 'go');">НАЙТИ</div>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <h3 <?php echo count($specialists) == 0 ? 'style="display:none;"' : '';?> class="ask_no_specialist h3_ask_no_specialist">Или врача</h3>
                <div <?php echo count($specialists) == 0 ? 'style="display:none;"' : '';?> class="sorting ask_no_specialist">
                  <a href="desc" class="sorting__link sorting__link_active sorting_desc rating_sort"><span>по рейтингу</span></a>
                  <a href="desc" class="sorting__link sorting_asc symbol_sort"><span>по алфавиту</span></a>
                </div>
                <div style="position:relative;max-width:925px;">
                  <img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
                  <div class="specialist_page <?php echo count($specialists) == 0 ? 'specialist_page_hidden' : '';?> ask_no_specialist white_box clearfix" style="display: flex;flex-flow: row wrap;visibility: hidden;">
                    <?php
                    $specialist_count = count($specialists);
                    $cookie_select_specialist_name = 'question[specialist_id]';
                    $cookie_select_specialist_value = '51';
                    if($specialist_count > 1)
                    {
                      $cookie_select_specialist = true;
                      $rand_arr = array();
                      foreach ($specialists as $specialist)
                      {
                        $rand_arr[] = $specialist['id'];
                      }
                      $cookie_select_specialist_value = $rand_arr[array_rand($rand_arr)];
                    }
                    if(!$specialist_cookie_sort || $cookie_select_specialist)
                    {
                    ?>
                      <label class="specialist_page__item fl_l">
                        <?php
                        echo '<input type="radio" ' . ($cookie_select_specialist ? 'checked="checked"' : '') . ' class="n_cust rating_doctors__item_inp" name="' . $cookie_select_specialist_name . '" value="' . ($no_specialty_id ? 51 : $cookie_select_specialist_value) . '" />';
                        ?>
                        <div class="rating_doctors__item rating_doctors__item_any_doctor">
                          <div class="rating_doctors__item__any_name">Любой врач</div>
                          <div class="rating_doctors__item__any_name_about">Мы сами выберем какому специалисту адресовать Ваш вопрос</div>
                        </div>
                      </label>
                    <?php
                    }
                    ?>
                    <?php
                    foreach ($specialists as $specialist)
                    {
                      $specialist_checked = '';
                      if($sf_request->getParameter('id') && $sf_request->getParameter('id') == $specialist['id'])
                      {
                        $specialist_checked = 'checked="checked"';
                      }
                      ?>
                      <label class="specialist_page__item fl_l">
                        <?php
                        echo '<input type="radio" ' . ($specialist_count == 1 ? 'checked="checked"' : '') . ' class="n_cust rating_doctors__item_inp" name="question[specialist_id]" ' . $specialist_checked . ' value="' . $specialist['id'] . '" />';
                        $time_difference = strtotime(date('Y-m-d' . ' ' . 'H:i:s')) - strtotime($specialist['Specialist_online'][0]['date']);
                        $doctor_status = $time_difference < 3000 ? 'rating_doctors_online' : '';
                        ?>
                        <div class="rating_doctors__item <?php echo $doctor_status;?>">
                        <?php
                          $photo = ($specialist['User']['photo'] ? Page::replaceImageSize($specialist['User']['photo'],'S') : '');
                          echo '<div class="rating_doctors__item__photo" ' . ($photo ? 'style="background-image: url(\'/u/i/' . $photo . '\');" data-photo_url="' . $photo . '"' : '') . '></div>';
                          echo '<a class="rating_doctors__item__link" href="' . url_for('@specialist_index') . $specialist['title_url'] . '/">' . $specialist['User']['first_name'] . ' ' . $specialist['User']['second_name'] . '</a>';
                          ?>
                          <i class="br5"></i>
                          <div class="rating_doctors__item__pos"><?php echo $specialist['about'];?></div>
                          <table cellpadding="0" cellspacing="0" class="rating_doctors__item__num">
                            <tr valign="top">
                              <td class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;"><span class="fs_20"><?php echo number_format($specialist['rating'], 1, ',', '');?></span><br/><span class="fs_13">рейтинг</span></td>
                              <td class="tcolor_red" style="padding-left: 10px;"><span class="fs_20"><?php echo number_format($specialist['answers_count'], 0, ',', ' ');?></span><br/><span class="fs_13">консультаций</span></td>
                            </tr>
                          </table>
                        </div>
                      </label>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="question_cont_tab" data-tab="1">
              <h3>Опишите симптомы</h3>
              <div class="white_box clearfix ask_quest_page__section">
                Вопрос касается вас или члена вашей семьи?
                <i class="br10"></i>
                <label style="margin-right: 40px;"><input type="radio" name="who_does" onchange="$('.who_does_family').slideUp(200);" checked="checked" value="me" />Меня</label>
                <label><input type="radio" name="who_does" onchange="$('.who_does_family').slideDown(200);" value="family" />Члена семьи</label>
                <div class="who_does_family" style="display: none;">
                  <?php
                  echo '<i class="br20"></i>';
                  if(count($attached_family_users) > 0)
                  {
                    foreach ($attached_family_users as $af_user)
                    {
                      ?>
                      <label>
                        <?php
                        echo '<input name="user_about[user_about_id]" onclick="whoDoesFamilyAdd(\'closed\');" value="' . $af_user['user_about_id'] . '" class="n_cust q_cabinets__item__family_select_inp" type="radio">';
                        echo '<span class="q_cabinets__item__family_select">' . $af_user->getUserAbout()->getUsername() . '</span>';
                        ?>
                      </label>
                      <?php
                    }
                  }
                  else
                  {
                    $style_block = 'style="display:block;"';
                    $style_none = 'display:none;';
                  }
                  ?>
                  <div <?php echo $style_block;?> class="who_does_family__add">
                    <input name="user_about[second_name]" type="text" placeholder="Фамилия" />
                    <input name="user_about[first_name]" type="text" placeholder="Имя" />
                    <input name="user_about[middle_name]" type="text" placeholder="Отчество" />
                    <i class="br5"></i>
                    <select name="user_about[gender]" id="user_gender">
                      <option value="0">м</option>
                      <option value="1">ж</option>
                    </select>
                    <input class="ask_question_form__birth_date" name="user_about[birth_date]" type="text" placeholder="Дата рождения" />
                  </div>
                  <?php
                  echo '<i class="br1"></i>';
                  echo '<a href="#" class="btn_all green_btn who_does_family_add_btn" style="border-radius: 2px;' . $style_none . '" onclick="whoDoesFamilyAdd();return false;">Добавить ещё</a>';
                  ?>
                </div>
                <i class="br10"></i>
                <textarea id="ask_question_symptoms_textarea" onkeyup="askQuestionTipAjax($(this).val());" name="question[body]" rows="10" style="width:100%;" placeholder="..."></textarea>
                <div class="aq__q_tip">
                  <div class="aq__q_tip__body"></div>
                </div>
              </div>
            </div>
            <div class="question_cont_tab" data-tab="2">
              <h3>Ответьте на вопросы</h3>
              <div class="q_sheet_history white_box clearfix ask_quest_page__section">
                <img class="q_sheet_history_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
                Уважаемые пациенты и гости сервиса. С целю обеспечения вас квалифицированной консультацией, быстроты ответа на поставленные вопросы и дальнейшего исполнения вами наших рекомендаций, мы решили разработать для вас перечень вопросов, которые облегчат нашу работу и наше с вами общение
                <i class="br30"></i>
                <ol class="q_sheet_history__ol">
                  <?php
                  include_partial('sheet_history', array('sheet_history' => $sheet_history, 'no_change' => true));
                  ?>
                </ol>
              </div>
            </div>
            <div class="ask_q_page__bottom">
              <button class="btn_all ask_q_page__bottom__btns ask_q_page__bottom__back fl_l" onclick="vrb.askQuestion.prevTabs(event);return false;"><div class="ask_q_page__bottom__btns_icon"></div>Выбор специалиста или кабинета</button>
              <button id="ask_question_form_submit_btn" class="btn_all ask_q_page__bottom__btns ask_q_page__bottom__forward fl_r" onclick="vrb.askQuestion.go();vrb.askQuestion.nextTabs(event);sheetHistoryUpdate();return false;">Описание симптомов<div class="ask_q_page__bottom__btns_icon"></div></button>
            </div>
            <label id="question_is_anonymous"><input type="checkbox" name="question[is_anonymous]" value="1" />&nbsp;Задать анонимно</label>
          </form>
          <div class="question_cont_tab" data-tab="3">
            <h3>Личные данные</h3>
            <div class="login_page">
              <div class="white_box clearfix ask_quest_page__section aqp__section3">
                <?php
                if($sf_user->isAuthenticated())
                {
                ?>
                  <div class="ask_quest_page__section_personal_data">
                    <div class="aqp_s_personal_data__name">Вы вошли на сайт как: <b><?php echo $sf_user->getAccount()->getFirstName() . ($sf_user->getAccount()->getSecondName() ? ' ' . $sf_user->getAccount()->getSecondName() : '');?></b></div>
                    Вашу фамилию и имя будет видеть только лечащий врач и участники консилиума.
                  </div>
                <?php
                }
                else
                {
                  ?>
                  <table class="question_cont_tab__auth" width="100%" cellpadding="0" cellspacing="0">
                    <tr id="login_item">
                      <td valign="top" class="auth_form__wrap" style="padding-right: 20px;border-right: 1px solid #d8dfe0;" width="50%" valign="top">
                        <?php include_component('user', 'login', array('ask_question' => true));?>
                      </td>
                      <td valign="top" width="50%" class="registration_form__wrap" style="padding-left: 20px;">
                        <?php include_component('user', 'register', array('ask_question' => true));?>
                      </td>
                    </tr>
                  </table>
                  <?php
                }
                ?>
                <span id="question_final_description">Нажимая кнопку «Отправить»... я подтверждаю и понимаю, что рекомендации консультантов носят предварительно-информативный характер и не могут заменить очную консультацию специалиста.</span>
              </div>
            </div>
          </div>
          <div class="question_cont_tab" data-tab="4"></div>
        </div>
      </td>
      <td width="1" style="padding-left: 30px;">
        <div style="width: 285px;"></div>
        <div class="white_box ask_question__history_right_box">
          <div class="ask_question__history_right_box__all_info">
            <p>Все рекомендации носят информационный или консультативный характер, обязательно нужен личный прием у врача и согласование с ним вашего лечения.</p>
            <p>Если вы разместили информацию о своей болезни в открытом доступе, то вы тем самым подтверждаете свое согласие на нахождение информации в открытом доступе сети интернет и вся ответственность по ее размещению ложиться на самого пациента, который разместил ее.</p>
            <p>Ждем Вас и очень рады, что вы уже пользуетесь нашим сервисом «ВРАЧ РБ - консультация онлайн».</p>
          </div>
          <div class="aqh_rb_tab_1_header">Вы задаёте вопрос</div>
          <div class="aqh_rb_tab_1">
            <div class="aqh_rb_tab_1__name">
              <span class="aqh_rb_tab_1__name__yes">
                <span class="aqh_rb_tab_1__name__link">Любой врач</span>
                <div class="aqh_rb_tab_1__name__specialty">Врач не выбран. Мы изучим вопрос и адресуем специалисту</div>
                <div class="aqh_rb_tab_1__name__photo"></div>
              </span>
              <span class="aqh_rb_tab_1__name__no"></span>
            </div>
          </div>
          <div class="aqh_rb_tab_2_header">Ваши симптомы</div>
          <div class="aqh_rb_tab_2">
            Уважаемые пациенты и гости сервиса. С целю обеспечения вас квалифицированной консультацией, быстроты ответа на поставленные вопросы и дальнейшего исполнения вами наших рекомендаций, мы решили разработать для вас перечень вопросов, которые облегчат нашу работу и наше с вами
          </div>
          <div class="aqh_rb_tab_3_header">Лист анамнеза</div>
          <div class="aqh_rb_tab_3 aqh_rb_tab_3__sheet_history"></div>
          <div class="aqh_right_box__btn_wrap">
            <div class="btn_all aqh_right_box__btn aqh_right_box__btn_disabled" onclick="$('.ask_q_page__bottom__forward').click();">
              <div class="aqh_right_box__btn__item">ОПИСАНИЕ СИМПТОМОВ</div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>
<?php
/*</form>*/
?>
<script type="text/javascript">
  var searchWord = function (letters, type) {
    var searchMess = $('.q_cabinets__search__message');
    if(letters != ''){
      searchMess.hide();
      var c = 0;
      $('.q_cabinets__item').each(function (idx, _this) {
       var
         cabinetTitle = typeof $(_this).prop('title') != 'undefined' ? $(_this).prop('title') : '',
         _thisElem = $(_this);
        if((_thisElem.text().toLowerCase().indexOf(letters.toLowerCase()) != -1 || cabinetTitle.toLowerCase().indexOf(letters.toLowerCase()) != -1) && c < 13){
          c++;
          if(c == 1){
            $('.q_cabinets').addClass('q_cabinets__search_wrap');
            $('.search_active').removeClass('search_active');
          }
          _thisElem.closest('.q_cabinets__item__label').addClass('search_active');
        }
      });
      if(c == 0){
        searchMess.text('Результаты не найдены');
        searchMess.show();
      };
      $('.q_cabinets__elements__visible').removeClass('q_cabinets__elements__visible');
    }else if(type == 'go'){
      searchMess.text('Пожалуйста, заполните поле');
      searchMess.show();
    }
  };
  
  var tipTimeout;
  var aqQTipBody = $('.aq__q_tip__body'), aqQTip = $('.aq__q_tip');
  var askQuestionTipAjax = function(_str){
    if(_str != ''){
      if(tipTimeout){
        clearTimeout(tipTimeout);
      };
      tipTimeout = setTimeout(function(){
        $.post('/ask-question/search-tip/', 'q=' + _str, function(data){
          if(data != '' && data != 'no_result'){
            if(aqQTipBody.is(':visible')){
              aqQTip.css('opacity', 0);
              setTimeout(function(){
                aqQTipBody.html('<div class="aq__q_tip__h">Похожие вопросы c ответами:</div>' + data);
                aqQTip.css('opacity', 1);
              },200);
            }else{
              aqQTipBody.html('<div class="aq__q_tip__h">Похожие вопросы c ответами:</div>' + data);
              aqQTip.slideDown(200);
            };
          }else{
            aqQTip.slideUp(400);
          }
        });
      }, 2000);
    };
  };

  $(document).ready(function(){

    var body = $('body');
    var rightBox = $('.ask_question__history_right_box');
    var rightBoxTop = parseInt(rightBox.offset().top) - 20;
    var fixedClass = 'ask_question__history_right_box_fixed_';
    var footerHeightPlus = $('.footer').outerHeight() + 40;
    var w = $(window);

    w.scroll(function(){
      var wScrollTop = w.scrollTop();
      var htmlHeightNoFooter = $('.body_wrapper').height() - footerHeightPlus - 20;
      var wScrollTopPlus = wScrollTop + rightBox.outerHeight();

      if(wScrollTop > rightBoxTop){
        rightBox.addClass(fixedClass + 'top');
        rightBox.removeClass(fixedClass + 'bottom');
        if(wScrollTopPlus > htmlHeightNoFooter){
          rightBox.removeClass(fixedClass + 'top');
          rightBox.addClass(fixedClass + 'bottom');
        }
      }else{
        rightBox.removeClass(fixedClass + 'top ' + fixedClass + 'bottom');
      }
    });

    var
      specItem = $('.specialist_page__item'),
      ratingItem = $('.rating_doctors__item'),
      elementsOpen = $('.q_cabinets__elements__open'),
      qcsInput = $('.q_cabinets__search__input'),
      qcsBtn = $('.q_cabinets__search__btn'),
      aqsTextarea = $('#ask_question_symptoms_textarea'),
      specialistPage = $('.specialist_page');

    aqsTextarea.val('');

    $('.question_tabs__item').click(function(){
      if($(this).hasClass('qti_elem_text_available')){
        sheetHistoryUpdate($(this));
        vrb.askQuestion.goTabsItem($(this).index());
      };
    });

    qcsInput.keydown(function(event){
      if(event.keyCode == 13){
        qcsBtn.click();      
      };
    });

    $('.q_cabinets__search__input').val('');

    var cabinetsOpen = function () {
      var cabinetElements = $('.q_cabinets__elements');
      if($('.q_cabinets__search_wrap').length > 0){
        $('.q_cabinets__search__input').val('');
        $('.q_cabinets__search_wrap').removeClass('q_cabinets__search_wrap');
        $('.search_active').removeClass('search_active');
        cabinetElements.addClass('q_cabinets__elements__visible');
      }else{
        if(cabinetElements.hasClass('q_cabinets__elements__visible')){
          cabinetElements.removeClass('q_cabinets__elements__visible');
        }else{
          cabinetElements.addClass('q_cabinets__elements__visible');
        }
      };
    };
    elementsOpen.click(cabinetsOpen);
    $('.yes_input').each(function (idx, _this) {
      if($(_this).is(':checked')){
        $(_this).parent().parent().find('.sheet_field__display').show()
      };
    });

    $('.upload_input').each(function (idx, uploadInput) {
      $(uploadInput).val('');
    });

    $('.ask_question_form__birth_date').spinpicker({lang:"ru"});

    vrb.askQuestion.init();

    $(window).resize(resizeSpecialistHeight).resize();

    $('.sorting__link, .q_cabinets__item_inp').click(function () {

      var
        paramSortType,
        paramSortValue,
        paramSpecialtyId,
        href,
        send,
        sortLink = $('.sorting__link');
      var _this = $(this);
      var qCItem =  $(this).next();

      if(_this.hasClass('sorting__link')){
        sortLink.removeClass('sorting__link_active');
        href = _this.attr('href') == 'asc' ? 'desc' : 'asc';

        _this
          .attr('href', href)
          .removeClass('sorting_desc sorting_asc')
          .addClass('sorting_' + href + ' sorting__link_active');
      }

      $('.q_cabinets__item_inp').each(function (idx, elemSpecialty) {
        if($(elemSpecialty).is(':checked')){
          paramSpecialtyId = $(elemSpecialty).val();
        }
      });

      paramSortType = $('.sorting__link_active').hasClass('rating_sort') ? 'rating' : 'symbol';
      paramSortValue = $('.sorting__link_active').attr('href');

      qCItem.addClass('q_cabinets__item_preloader');

      send = 'param=' + paramSortType + ':' + paramSortValue + '&select_specialty_id=' + paramSpecialtyId;

      $.post('<?php echo url_for('@ask_question_specialist_filter');?>', send , function (data) {
        if(data == 0){
          $('.ask_no_specialist').hide();
          specialistPage.html('');
        }else{
          $('.ask_no_specialist').show();
          $('.specialist_page')
            .removeClass('specialist_page_hidden')
            .html(data);
        }
        qCItem.removeClass('q_cabinets__item_preloader');
        resizeSpecialistHeight();
      });

      if(_this.hasClass('sorting__link')){
        return false;
      }
    });
  });

  var whoDoesFamilyAdd = function (elem) {
    var whoDoesFamilyAdd = $('.who_does_family__add');
    if(elem == 'closed') {
      if (!whoDoesFamilyAdd.is(':hidden')) {
        whoDoesFamilyAdd.slideUp(200);
        $('.who_does_family_add_btn').show();
      }
    }else{
      $('.who_does_family__add').slideDown(200);
      $('.who_does_family_add_btn').hide();
      $('.q_cabinets__item__family_select_inp').removeAttr('checked');
    }
  };

  vrb.askQuestion.go = function(){
    if($('.ask-question-form-wrap').find('.ask_quest_page__section_personal_data').length > 0 && $('.question_cont_tab').eq(3).is(':visible')){
      $('#ask-question-form').submit();
      return false;
    };
  }

  var sheetHistorySpId = null;
  var sheetHistorySId = '';
  var qshPreloader = $('.q_sheet_history_preloader');

  var sheetHistoryUpdate = function (_this) {
    var specialtyId = '';
    var specialistId = '';
    var sheetHistoryWrap = $('.q_sheet_history__ol');

    specialtyId = $('.q_cabinets__item_inp:checked').val();
    if($('.rating_doctors__item_inp:checked')){
      specialistId = $('.rating_doctors__item_inp:checked').val();
    };
    if(($(_this).hasClass('question_tabs__item') || $('.question_cont_tab').eq(1).is(':visible')) && (sheetHistoryWrap.data('specialty_id') != specialtyId || sheetHistoryWrap.data('specialist_id') != specialistId))
    {
      if(sheetHistorySpId != specialtyId || sheetHistorySId != specialistId){
        qshPreloader.show();
        sheetHistoryWrap.css('opacity', 0.3);
        $.post('<?php echo url_for('@sheet_history_update');?>', 'specialty_id=' + specialtyId + '&specialist_id=' + specialistId, function (data) {
          sheetHistoryWrap.html(data);
          qshPreloader.hide();
          sheetHistoryWrap.css('opacity', 1);
        });
      };
      sheetHistorySpId = specialtyId;
      sheetHistorySId = specialistId;
    };
  };
</script>