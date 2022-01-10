(function ($) {
  $.fn.customizeFormWww = function () {
    this.each(function () {
      var input = $(this);
      if (!input.data('initialized')) {
        do_customInput(input, false);
      }
    });
    return this;
  };
})(jQuery);

var do_customInput = function (input, param) {
  if(!input.hasClass('n_cust')){
    if(input.is('input')){
      var type = input.attr('type');
      if(type == 'radio' || type == 'checkbox'){
        input.parent().addClass('custom_input_label');
        input.addClass('custom_input');
        input.after($('<span class="custom_input custom_input_' + type + '"></span>'));
      }else if(param == 'file' && type == 'file'){
        input.wrap('<label class="custom_upload_input"></label>');
        input.before('Выберите файл');
      }
    }else if(input.is('select')){
      if(input.is(':visible')){
        var arrow = $('<span class="custom_select__arrow"></span>');
        var inputW = input.outerWidth();
        input.wrap('<span class="custom_select" style="width:' + input.outerWidth() + 'px;" />');
        input.width(inputW + 20);
        input.after(arrow);
        // input.change();
      }
    }
    input.data('initialized', '1');
  }
}







var  answerRepeatCheckArr = [];
var answerRepeatCheck = function (str) {
  var repeat = false;
  if(answerRepeatCheckArr.length > 0){
    for(var i = 0; i < answerRepeatCheckArr.length; i++){
      if(answerRepeatCheckArr[i] == str){
        repeat = true;
        break;
      }
    }
  }
  if(!repeat){
    answerRepeatCheckArr.push(str);
  }
  debug(answerRepeatCheckArr);
  return repeat;
};

var resizeSpecialistHeight = function (type) {
  if(type == 'c'){
    var ratingDoc = $('.rating_doctors');
    var ratingDocTop = vrb.getDifferenceOff('.categories_left', ratingDoc);
    var height;
    var rdiHeight;
    ratingDoc.hide();
    height = $('.categories_height').outerHeight() - ratingDocTop;
    ratingDoc.outerHeight((height >= 290 ? height : 290) - 20);
    ratingDoc.show();
    rdiHeight = $('.rating_doctors__item ').outerHeight() + 62;
    if(height < rdiHeight){
      ratingDoc.outerHeight(rdiHeight, true);
    }
    $('.rating_doctors').perfectScrollbar({
      wheelSpeed: 2
    });
  }else{
    var specItem = $('.specialist_page__item');
    var ratingItem = $('.rating_doctors__item');
    $('.rating_doctors__item').css('height', 'auto');
    specItem.each(function(i, elem){
      var elem = $(elem);
      var elemH = elem.height();
      elem.find('.rating_doctors__item').outerHeight(elemH);
    });
    $('.specialist_page').css('visibility', 'visible');
  }
};

var overflowHiddenScroll = function(elem){
  var mw = window.innerWidth - document.body.clientWidth;

  if($('body').hasClass('scrollbarHidden')){
    $('body')
        .css({
          'overflow-y': 'scroll',
          'margin-left': '0'
        })
        .removeClass('scrollbarHidden');
    $(elem).hide();
  }else{
    $('body')
        .css({
          'overflow-y': 'hidden',
          'margin-left': -mw
        })
        .addClass('scrollbarHidden');
    $(elem).show();
  }
};

var agreementCheck = function (_this, type) {
  var check = $('.agreement__check'), checkCount = 0, agreementBtn = $('.agreement_btn');
  check.each(function (idx, _this) {
    var thisAgreement = $(_this).closest('.agreement');
    if($(_this).is(':checked')){
      checkCount ++;
      thisAgreement.removeClass('agreement__error');
    }else if(type == 'submit' || type == 'submitAjax'){
      thisAgreement.addClass('agreement__error');
    }
  });
  if(checkCount == check.size()){
    agreementBtn.removeClass('disabled_btn');
    if(type == 'submit'){
      $(_this).closest('form').submit();
    }else if(type == 'submitAjax'){
      var s = $(_this).closest('form').serialize();
      $.post('/agreement/', s, function(data){
        $('.authorization_window').hide();
        overflowHiddenScroll();
      });
    }
  }else{
    agreementBtn.addClass('disabled_btn');
  }
};

var loginFun = {

  item: null,

  onRegister: function (type) {
    var s = '';
    var aCheck;
    var location = decodeURI(document.location);

    if(type == 'alternative'){
      $('.registration_form').find('input').each(function (idx, elem) {
        if(idx != 0){
          s += '&';
        }
        s += $(elem).attr('name') + '=' + $(elem).val();
      });
    }else{
      s = $('.registration_form').serialize();
    }
    var agCheck = $('.agreement__check'), agCheckCount = 0;
    agCheck.each(function(idx, ag){
      if($(ag).is(':checked')){
        agCheckCount ++;
        $(ag).closest('label').removeClass('agreement__error');
      }else{
        $(ag).closest('label').addClass('agreement__error');
      }
    });
    if(agCheck.size() == agCheckCount){
      $.post('/register/', s, function (data) {
        $('.registration_form__wrap').html(data);
        aCheck = ($.trim(data)).slice(0, 6);

        if(data.indexOf('Для завершения регистрации необходимо') != -1 && location.indexOf('/login/') != -1){
          yaCounter36726625.reachGoal('REG');
        }

        addComment.onRestart(aCheck, 'r');
      });
    }
  },

  onSignin: function (type) {
    var s = null;
    var aCheck;
    var param1;
    var url = decodeURI(document.location);

    if(type == 'alternative'){
      s = 'signin[username]=' + $('#signin_username').val() + '&signin[password]=' + $('#signin_password').val();
    }else{
      s = $('.auth_form').serialize();
    };
    $.post('/signin/', s, function (data) {
      var agData = data;
      if(url.indexOf('article') != -1 || url.indexOf('tip') != -1){
        agData = data.replace('agreementCheck(this, \'submit\')', 'agreementCheck(this, \'submitAjax\')');
      };
      aCheck = false;
      if(data.indexOf('<b>Вход выполнен</b>') != -1){
        aCheck = '<b>Вход';
      };
      if(aCheck == '<b>Вход' && url.indexOf('/ask-question/') != -1){
        $('.ask_q_page').removeClass('ask_q_page__no_authenticated');
        agData = '<td valign="top">';
        agData += '<div class="ask_quest_page__section_personal_data">';
        agData += '<div class="aqp_s_personal_data__name">Вы вошли на сайт как: <b>' + $(data).find('.login_true_fio').text() + '</b></div>';
        agData += 'Вашу фамилию и имя будет видеть только лечащий врач и участники консилиума.';
        agData += '</div>';
        agData += '</td>';
      };
      $('#login_item').html(agData);
      addComment.onRestart(aCheck, 'a');
    });
    return false;
  },

  onChange: function () {
    var s = null;

    s = $('.registration_form').serialize();
    $.post('/change/', s, function (data) {
      $('.registration_form__wrap').html(data);
      debug(data);
    });
  },

  onChangePassword: function () {
    var _this = this;
    var s = null;

    s = $('.change_password_form').serialize();
    $.post('/change_password/', s, function (data) {
      $('.change_password_form_wrap').html(data);
    });
  },

  init: function(){
    var _this = this;
  }
};

var debug = function (data) {
  console.log(data);
};

var attachmentUpload = function () {
  $('#answer_attachment___uploader').click();
};

var check_token = function (token) {
  var location = decodeURI(document.location.pathname);
  $.post('/check_token/', 'token=' + token, function (data) {
    var dlpSplit = (location).split('/');
    if(dlpSplit[1] == 'tip' || dlpSplit[1] == 'article'){
      $('.authorization_window').html(data);
      addComment.onRestart('s', 's');
    }else{
      var aCheck = false;
      if(data.indexOf('<b>Вход выполнен</b>') != -1){
        aCheck = '<b>Вход';
      };
      agData = data;
      if(aCheck == '<b>Вход' && location.indexOf('/ask-question/') != -1){
        $('.ask_q_page').removeClass('ask_q_page__no_authenticated');
        var agData = '<td valign="top">';
        agData += '<div class="ask_quest_page__section_personal_data">';
        agData += '<div class="aqp_s_personal_data__name">Вы вошли на сайт как: <b>' + $(data).find('.login_true_fio').text() + '</b></div>';
        agData += 'Вашу фамилию и имя будет видеть только лечащий врач и участники консилиума.';
        agData += '</div>';
        agData += '</td>';
        $('#login_item').html(agData);
      }else{
        $('.login_page').html(agData);
      };
    };
  });
};

var questionAnswerQuickOpen = {
  init: function(){
    var _this = this;
    _this.liveBandOpacity = $('.live_band__opacity');
    _this.location = decodeURI(document.location.pathname);
  },
  open: function (_elem, _question_id) {
    var _this = $(_elem);
    if(_question_id && !_this.hasClass('quick_open_link_active')){
      _this.addClass('quick_open_link_active preloader_before');

      questionAnswerQuickOpen.close();

      setTimeout(function () {
        $.post('/question-answer/' + _question_id + '/', 'quick_open=1', function (data) {
          if(data != ''){
            var item = _this.closest('.live_band__item');
            item.addClass('quick_open__first');

            item.find('.live_band__item__author__div').outerHeight(0);

            var quickOpenMt0 = '';
            if(questionAnswerQuickOpen.location == '/'){
              if(item.index('.live_band__item') == 0){
                quickOpenMt0 = ' quick_open__mt0';
              }
            }
            
            item.wrap('<div class="quick_open' + quickOpenMt0 + '"></div>');
            var quickOpen = $('.quick_open');
            quickOpen.append('<div class="quick_open__all">' + data + '</div>');

            $('.quick_open__all').outerHeight($('.quick_open__block').outerHeight() + 10);
            quickOpen.css('opacity', 1);

            $('.preloader_before').removeClass('preloader_before');

            if(questionAnswerQuickOpen.location == '/'){
              setTimeout(function(){
                questionAnswerQuickOpen.liveBandOpacity.perfectScrollbar('update');
              }, 300);
            }
          }
        });
      }, 300);

    }
  },
  close: function (param) {
    var quickOpen = $('.quick_open');

    if(quickOpen.length > 0){
      var quickOpenFirst = $('.quick_open__first');
      var quickOpenAll = $('.quick_open__all');

      quickOpen
          .css('opacity', 0.3)
          .addClass('quick_open_close');

      quickOpenAll.css('height', '0');

      var quickOpenFirstAuthorTable = quickOpenFirst.find('.live_band__item__author__div__table');
      quickOpenFirstAuthorTable.parent().outerHeight(quickOpenFirstAuthorTable.outerHeight());

      setTimeout(function(){
        quickOpenAll.remove();

        quickOpenFirst.unwrap();
        quickOpenFirst.removeClass('quick_open__first');
        $('.quick_open_link_active').removeClass('quick_open_link_active');

        if(questionAnswerQuickOpen.location == '/'){

/*          questionAnswerQuickOpen.liveBandOpacity.css('height', 'auto');
          questionAnswerQuickOpen.liveBandOpacity.css('height', questionAnswerQuickOpen.liveBandOpacity.outerHeight());*/

          $('.live_band__opacity').perfectScrollbar('update');
        }
      }, 250);
    }
  },
  more: function () {
    $('.quick_open').addClass('quick_open_more');
  },
  full: function () {
    $('.quick_open').addClass('quick_open__full');
    if(questionAnswerQuickOpen.location == '/'){

      /*questionAnswerQuickOpen.liveBandOpacity.css('height', 'auto');
      questionAnswerQuickOpen.liveBandOpacity.css('height', questionAnswerQuickOpen.liveBandOpacity.outerHeight());*/

      questionAnswerQuickOpen.liveBandOpacity.perfectScrollbar('update');
    }
  }
};

function getFilter(account_type, filter_type) {
  if ( (account_type == 'doctor' || account_type == 'personal') && (filter_type == 'all' || filter_type == 'open'|| filter_type == 'close') ) {
    var url = "/" + account_type + "-account/filter/";
    var preloader = $(".filter_preloader");
    preloader.css('display', 'inline-block');
    $.ajax({
      type: "post",
      data: {filter: filter_type},
      url: url,
      success: function (msg) {

        console.log(msg, $('.pc_user_page_wrap'));

        /*
         if (msg.indexOf('pc_chat__item') == -1) {
         msg = '<div class="white_box pc_user_page" style="padding-top: 0;"><div class="pc_not_dialog"><div class="pc_not_dialog__inner">Нет вопросов с таким статусом</div></div></div>';
         }
         */
        $('.pc_user_page_wrap').html(msg);
        preloader.css('display', 'none');
      }
    });
  }
}

var messageErrorAdd = function (_this, type) {
  if(type == 'add'){
    var s = $(_this).serialize();
    $.post('/message-error-add/', s, function (data) {
      if(data == 'ok'){
        $('.message_error__item__please').hide();
        $('.message_error__item__result')
          .html('<b class="fs_18">Спасибо!<br>Ваше сообщение доставлено</b>')
          .show();
        $('#message_error_body').val('');
      }else{
        $('.message_error__item__result').html('<b class="fs_18">Ошибка отправки.<br>Попробуйте обновить страницу</b>');
      }
    });
  }else if(type == 'clear'){
    $('.message_error__item').slideToggle(200);
    setTimeout(function () {
      $('.message_error__item__please').show();
      $('.message_error__item__result').hide();
    },200);
  }
};

var historyTestClick = function (_this) {
  if($(_this).hasClass('analysis_type_elem')){
    if(!$(_this).hasClass('.pc_history__menu_drop__link_active') && !$(this).hasClass('.pc_history__menu__link_active')){
      $('.analysis_type_elem').removeClass('pc_history__menu__link_active');
      $(_this).addClass('pc_history__menu__link_active');
      $('.pc_history__menu_drop').hide();

      $('.pc_history__menu_drop').each(function (idx, dropElem) {
        if($(dropElem).data('analysis_type_id') == $(_this).data('analysis_type_id')){
          $(dropElem).show();
        };
      });
    }
  }else{
    $('#open_analysis_date').text($(_this).text());
    $('.open_analysis_photo').css('background', 'url(\'/u/i/' + $(_this).data('analysis_photo') + '\') no-repeat 50% 50%');
    $('.fotorama').find('img').attr('src', '/u/i/' + $(_this).data('analysis_photo'));

    $('.pc_history__menu_drop__link').removeClass('pc_history__menu_drop__link_active');
    $(_this).addClass('pc_history__menu_drop__link_active');
  }
};

var sheetHistoryDetails = function (elem) {
  var param = '';
  if(elem == 'consilium'){
    param = '&in_consilium=1';
  }

  var historyDetails = $('.dc_overlay_shhet_history_details');
  if(historyDetails.find('#question_sheet_history_true').length == 0)
  {
    $.post('/doctor-account/sheet-history/', 'sheet_history_q_id=' + questionId + param, function (data) {
      historyDetails.find('.overlay__white_box').html(data);
      overflowHiddenScroll(historyDetails);
    });
  }else{
    overflowHiddenScroll(historyDetails);
  }
};

var patientCardDetails = function (userId) {
  var send = 'user_id=' + userId;
  debug(userId);
  $.post('/doctor-account/patient-card/', send, function (data) {

    //$('.dc_overlay_patient_card_details').html();

    debug(data);
  });
};

var uploaderImagesObj = {};
var titleArr = [];
var emulationUploaderClick = function (elem, type) {
  var
      imageValue = null,
      check,
      location = decodeURI(document.location),
      conditionActive = $('.condition_active');

  if(type == 'click' && location.indexOf('/ask-question/') == -1){
    $('.pc_chat__item__tests').removeClass('.uploader_form_active');
    $(elem).closest('.pc_chat__item__tests').addClass('uploader_form_active');
    titleArr = [];
    $(elem).closest('.pc_chat__item__tests').find('.pc_chat__item__click_uploader').each(function (idx, titleElem){
      titleArr.push($(titleElem).data('title'));
    });
  }

  if(location.indexOf('/ask-question/') == -1){
    for(var i = 0; i < titleArr.length; i++){
      if(uploaderImagesObj[titleArr[i]] == 'active'){
        if(type == 'write'){
          imageValue = elem;
          $('.pc_chat__item__checkbox_result').each(function (chIdx, checkbox) {
            titleFirstArr = titleArr[i].split(':');
            if($(checkbox).data('value') == titleFirstArr[0]){
              $(checkbox).prop('checked', 'checked');

              //var size = $(checkbox).closest('.custom_input_label').find('.custom_input_checkbox').size();
              //if(size > 1){
              //  $(checkbox).closest('custom_input_label').find('.custom_input_checkbox').eq(0).remove();
              //}
            };
          });
        }else if(type == 'click'){
          imageValue = null;
        }
        uploaderImagesObj[titleArr[i]] = imageValue;
      }
    }
  }

  if(type == 'click'){
    $('.analysis_photo_wrap').find('.pseudo_button_file').click();
    if(location.indexOf('/ask-question/') == -1){
      imageValue = 'active';
      uploaderImagesObj[$(elem).data('title')] = imageValue;
    }else{
      conditionActive.removeClass('condition_active');
      $(elem).next('input[type="hidden"]').addClass('condition_active');
    }
  }

  if(type == 'write'){
    if(location.indexOf('/ask-question/') == -1){
      for(var i = 0; i < titleArr.length; i++){
        if(uploaderImagesObj[titleArr[i]] == 'active' || uploaderImagesObj[titleArr[i]] == null){
          check = true;
          break;
        }
      }
      if(!check){
        $('.uploader_form_active').find('.give_analysis_btn').removeAttr('disabled');
      }else{
        $('.uploader_form_active').find('.give_analysis_btn').attr('disabled', 'disabled');
      }
    }else{
      conditionActive.val(conditionActive.val() + (conditionActive.val() != '' ? ';' : '') + elem);

      var cSplit = conditionActive.val().split(';');
      var cStr = '';

      for(var i = 0; i < cSplit.length; i ++){
        cStr += '<div class="upload_image"><img src="/u/i/' + cSplit[i].replace('.', '-S.') + '" /><span class="uploader_preview__item_close" onclick="uploadImageRemove(this);">×</span></div>';
      }
      conditionActive.next('.textarea_upload_image').html(cStr);
    }
  }
};

var uploadImageRemove = function (_this) {
  var uploadInput, split;
  var src = $(_this).prev('img').attr('src').replace('-S.', '.');
  var str = '';
  var count = 0;
  uploadInput = $(_this).closest('.upload_input__wrap').find('.upload_input');
  split = uploadInput.val().split(';');
  for(var i = 0; i < split.length; i ++){
    if(src.indexOf(split[i]) == -1){
      str += (count != 0 ? ';' : '') + split[i];
      count ++;
    }
  }
  uploadInput.val(str);
  $(_this).parent().remove();
};

var sheetHistoryPhoto = function(_this, type){
  var img = $(_this).find('img');

  $('.sheet_history_photo__show').removeClass('sheet_history_photo__show');
  if(type != 'close'){
    $(_this).addClass('sheet_history_photo__show');
    img.attr('src', img.attr('src').replace('-S.', '-M.'));
  }
};

var showPhotoAnalysis = {

  item: null,

  onClick: function (currElem, event, type) {
    var _this = this;
    var $currElem = $(currElem);
    var analysisId = $currElem.data('analysis_id');
    var fotoramaItem = '';

    if(type == 'history_test'){
      //if($('.fotorama__img').length == 1){

        debug($currElem.data('analysis_photo'));

      //$('.fotorama__img').attr('src', '/u/i/' + $currElem.data('analysis_photo'));
      //}
      overflowHiddenScroll($('.overlay_photo'));
    }else{
      $.ajax({
        url: '/photo/',
        type: 'GET',
        data: {
          dataTable: 'Analysis',
          dataValue: $currElem.data('answer_id'),
          dataId: analysisId
        },
        success: function (data) {
          $('.overlay_photo')
            .show()
            .html(data);
          $('.overlay_photo').find('.fotorama').trigger('click');
          $('.fotorama').fotorama();
          overflowHiddenScroll();
        }
      });
    }
  },

  init: function(){
    var _this = this;
    _this.item = $('.show_photo_analysis');
  }
};

var showAttachment = function (_this, type) {
  if(type == 'image'){
    var photoArr = [], html = '', photo = '', _index = '<img src="' + $(_this).attr('src').replace('-S.', '-M.') + '">', src, imageM;
    $(_this).closest('.attachment__files').find('.attachment__photo').each(function (idx, photo) {
      src = $(photo).attr('src');
      imageM = src.replace('-S.', '-M.');
      photoArr.push('<img src="' + imageM + '">');
    });
    html += '<div class="overlay__close">×</div>';
    html += '<table width="100%" height="100%" onclick="event.stopPropagation();" style="position: relative;top: -100%;">';
    html += '<tr><td valign="middle" align="center">';
    html += '<div class="fotorama" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-min-width="300" data-max-width="90%" data-min-height="300" data-max-height="90%" data-hash="false">';
    if(photoArr.length > 1){
      for(var i = 0; i < photoArr.length; i++){
        if(i == 0){
          photo += _index;
        }
        if(photoArr[i] != _index){
          photo += photoArr[i];
        }
      }
    }else if(photoArr.length == 1){
      photo = photoArr[0];
    }
    html += photo;
    html += '</div>';
    html += '</td></tr></table>';
    $('.overflow_attachment').html(html);
    overflowHiddenScroll($('.overflow_attachment'));
    $('.fotorama').fotorama();
  }
};

var noticeDelete = function (_this, noticeId) {
  if($('.pc_curr_dialogues__item').size() > 1){
    _this.closest('.pc_curr_dialogues__item').remove();
  }else{
    $('.pc_curr_dialogues').remove();
  }
  $.post('/notice-update/', 'notice_delete=' + noticeId, function (data) {});
};

var noteShowUser = function (_this) {
  var elem = $(_this).find('.pc_chat__item__note__show_user__item');
  if(elem.is(':hidden')){
    elem.slideDown(200);
  }else{
    elem.slideUp(200);
  };
};

var vrb = {

  inpFile: {

    hasFile: function(inp, event){
      var val = inp.val();
      console.log(val);
    },

    init: function(){
      $('input[type="file"]').each(function(i, inp){
        var $inp = $(inp);

        //$inp.attr('onchange', 'vrb.inpFile.hasFile($(this), event);return false;');
      });
    }
  },

  browserName: function(){
    var nav = navigator;
    var navUs = nav.userAgent.toLowerCase();
    var bs = ['edge', 'firefox', 'trident', 'chrome'];
    var name = '';
    var state = 1;
    bs.forEach(function(item, i){
      if(state && navUs.indexOf(item) > 0){
        state = 0;
        name = item;
      }
    });
    return name;
  },

  formInpClone: {
    add: function(cloned){
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
      $(clone).data('work_place_id', 'none');

      //if(cloned == '.certificate_elem'){
      //  clone.find('img').remove();
      //  clone.find('.br20').remove();
      //  clone.append('<input type="file" /><i class="br20"></i>');
      //}
      //
      //debug(cloned);

      $cloned.last().after(clone);



      if($('.dc_invation_inp input[type="text"]').length > 0){
        $('.dc_invation_inp input[type="text"]').spinpicker({lang:"ru"});
      }
      _this.indexed(cloned);
    },

    indexed: function(elems){
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
    }
  },



  starsPlug: {

    stepImgConst: 24,

    onMouseMove: function(score, event){
      var _this = this;
      var x = event.offsetX;
      var percent = score.find('.review_average_score__percent');
      var _x = Math.ceil(x / _this.stepImgConst);
      percent.css('width', _x * _this.stepImgConst);
    },

    onMouseOut: function(score, event){
      var _this = this;
      var inp = score.prev();
      var percent = score.find('.review_average_score__percent');
      percent.css('width', inp.val() * _this.stepImgConst);
    },

    onClick: function(score, event){
      var _this = this;
      var x = event.offsetX;
      var inp = score.prev();
      var _x = Math.ceil(x / _this.stepImgConst);
      inp.val(_x);
    },

    returnTemp: function(inp){
      var _this = this;
      var percW = inp.val() * _this.stepImgConst;
      var events = ' onmousemove="vrb.starsPlug.onMouseMove($(this), event);" ' +
        'onclick="vrb.starsPlug.onClick($(this), event);" ' +
        'onmouseout="vrb.starsPlug.onMouseOut($(this), event);" ' +
        'style="cursor:pointer;" ';
      var temp = '<div class="review_average_score"' + (inp.data('click') == '1' ? events : '') + '><div class="review_average_score__percent" style="width:' + percW + 'px;"></div></div>';
      return temp;
    },

    init: function(){
      var _this = this;
      _this.inps = $('input.stars_plugin');
      if(_this.inps.length > 0){
        _this.inps.each(function(i, inp){
          var $inp = $(inp);
          if(typeof $inp.data('init') == 'undefined'){
            $inp.val($inp.val() > '5' ? '5' : $inp.val());
            $inp.hide();
            $inp.after(_this.returnTemp($inp));
            $inp.data('init', '1');
          }
        });
      }

    }
    
  },
  

  setLocation: function(curLoc){
    try {
      history.pushState(null, null, curLoc);
      return;
    } catch(e) {}
    location.hash = '#' + curLoc;
  },


  tabsPlug: {

    onClick: function(el, id){
      var $el = $(el);
      var wrap = $el.closest('.tabs_wrap');
      var menu = $el.closest('.tabs_menu');
      var currTabsCont = wrap.find('.tabs_content[data-tab="' + id + '"]');



      console.log(currTabsCont);


      menu.find('li').removeClass('inner_page__r__menu__active');
      $el.addClass('inner_page__r__menu__active');

      wrap.find('.tabs_content').removeClass('inner_page__r__menu__active').hide();
      currTabsCont.show();
      window.location.hash = id;
      if(typeof currTabsCont.data('init') != 'undefined'){
        eval(currTabsCont.data('init'));
      }
    },

    init: function(){
      var tabsWrap = $('.tabs_wrap');
      var hash = window.location.hash.replace('#', '');
      hash = hash == '' ? 0 : hash;

      tabsWrap.find('.tabs_menu').each(function(i, tabMenu){
        var $tabMenu = $(tabMenu);
        $tabMenu.find('li').each(function(j, tab){
          var $tab = $(tab);
          var id = $tab.data('tab');
          $tab.attr('onclick', 'vrb.tabsPlug.onClick(this, ' + id + ');');
        });

      });

      tabsWrap.find('.tabs_menu li[data-tab="' + hash + '"]').click();
    }

  },

  askQuestion: {
    checkArray: {},
    qTabsItem: null,
    qContTab: null,
    init: function(){
      var _this = this;
      _this.qTabsItem = $('.question_tabs__item');
      _this.qContTab = $('.question_cont_tab');
      _this.qBtnBottom = $('.ask_q_page__bottom');
      _this.ashRBox = $('.ask_question__history_right_box');
      _this.aqpBtnBack = $('.ask_q_page__bottom__back');
      _this.aqpBtnForward = $('.ask_q_page__bottom__forward');
      _this.qtItem = $('.question_tabs__item');
      _this.qtiElemText = $('.question_tabs__item__elem__text');
      _this.aqhRBBtnItem = $('.aqh_right_box__btn__item');
      _this.aqhQPage = $('.ask_q_page');

      _this.topBtns = {
        0:true,
        1:false,
        2:false,
        3:false
      };

      _this.qTabsItem.each(function(i, qTab){
        var $qTab = $(qTab);
        var qTabIdx = $qTab.data('tab');
      });

      _this.qContTab.each(function(i, qCont){
        var $qCont = $(qCont);
        var qContIdx = $qCont.data('tab');
        var onFunc = 'vrb.askQuestion.checkArr.tab_' + qContIdx + '();';
        $qCont.attr('onclick', onFunc);
        $qCont.attr('onkeyup', onFunc);
        if(i != 3){
          $qCont.attr('onchange', onFunc);
        }
      });

      _this.goTabsItem(0);
    },

    rightHistory: function(){

      var aqhRbTab_1_nameYes = $('.aqh_rb_tab_1__name__yes');

      var rightHistoryItem = function(name, description, photo, online, href){
        var specialistDescription = '';
        var aqhRbTab_1_nameYesHtml = '';
        if(name != ''){
          aqhRbTab_1_nameYesHtml += '<a target="_blank" class="aqh_rb_tab_1__name__link ' + (online != 0 ? 'aqh_rb_tab_1__name__link_online' : '') + '" href="' + href + '">' + name + '</a>';
          specialistDescription = description;
        }else{
          aqhRbTab_1_nameYesHtml += '<span class="aqh_rb_tab_1__name__link aqh_rb_tab_1__name__link_any">Любой врач</span>';
          specialistDescription = 'Врач не выбран. Мы изучим вопрос и адресуем специалисту';
        }
        aqhRbTab_1_nameYesHtml += '<div class="aqh_rb_tab_1__name__specialty">' + specialistDescription + '</div>';
        aqhRbTab_1_nameYesHtml += '<div class="aqh_rb_tab_1__name__photo" ' + (photo ? ' style="background:url(/u/i/' + photo + ');background-size:cover;"' : '') + '></div>';
        aqhRbTab_1_nameYes.html(aqhRbTab_1_nameYesHtml);
      };

      var checkDoctor = false;

      $('.rating_doctors__item_inp').each(function (idx, _elem) {
        if($(_elem).is(':checked') && $(_elem).closest('.specialist_page__item').find('.rating_doctors__item__any_name').length == 0){
          checkDoctor = true;

          var closestElem = $(_elem).closest('.specialist_page__item');
          var findLink = closestElem.find('.rating_doctors__item__link');
          var specialistDescription = null;
          var photoLink = closestElem.find('.rating_doctors__item__photo').data('photo_url');

          rightHistoryItem(findLink.text(), closestElem.find('.rating_doctors__item__pos').text(), photoLink, closestElem.find('.rating_doctors_online').length, findLink.attr('href'));
        };
      });

      if(!checkDoctor){
        $('.q_cabinets__item_inp').each(function(_cab_idx, _cab_elem){
          if($(_cab_elem).is(':checked')){
            var cabName = $(_cab_elem).next().text();
            if(cabName != 'Затрудняюсь выбрать'){
              $('.aqh_rb_tab_1__name__no').html(cabName);            
              vrb.askQuestion.ashRBox.addClass('aqh_rb_tab_1__no');
            }else{
              vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_1__no');
              rightHistoryItem('', false, false, false, false);
            };
          };
        });
      };
    },

    checkArr: {
      tab_0: function(){
        var $qContSections = vrb.askQuestion.qContTab.eq(0).find('.ask_quest_page__section');
        var inpStates = false;

        vrb.askQuestion.aqpBtnForward.html('<div class="ask_q_page__bottom__btns_icon"></div>ОПИСАНИЕ СИМПТОМОВ');
        vrb.askQuestion.aqhRBBtnItem.html('ОПИСАТЬ СИМПТОМЫ<div class="ask_q_page__bottom__btns_icon"></div>');

        vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_3_wrap aqh_rb_tab_2_wrap aqh_rb_tab_1__no');
        vrb.askQuestion.qtItem.removeClass('qti_elem_text_complete');
        vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_1_wrap');
        vrb.askQuestion.qBtnBottom.hide();

        $qContSections.each(function(i, section){
          var $section = $(section);
          var inpState = false;

          $section.find('input[type="radio"]').each(function(j, inp){
            var $inp = $(inp);
            if($inp.is(':checked')){
              inpState = true;
              return;
            }
          });
          inpStates = inpState;
        });

        if(inpStates){
          $qContSections.find('.white_box').removeClass('no_valid');
        }else{
          $qContSections.find('.white_box').addClass('no_valid');
        }

        vrb.askQuestion.visBottomBtns({
          vis: 'hidden',
          dis: true
        }, {
          vis: 'visible',
          dis: inpStates
        });

        vrb.askQuestion.visTopBtns(0, inpStates);

        $('#question_is_anonymous').hide();
        vrb.askQuestion.aqhQPage.removeClass('ask_q_page__no_authenticated');
      },
      tab_1: function(){
        var $qContSections = vrb.askQuestion.qContTab.eq(1).find('.ask_quest_page__section');
        $qContSections.find('input, textarea, select').each(function(i, elem){
          var $elem = $(elem);
          var tagName = $elem[0].tagName.toLowerCase();

          if($elem.is(':visible')){
            if(tagName == 'textarea'){
              if($elem.val().trim() == ''){
                $elem.addClass('no_valid');
              }else{
                $elem.removeClass('no_valid');
              }
            }
            if(tagName == 'input'){
              switch($elem.attr('type')){
                case 'email':
                //case 'file':
                case 'text':
                  if($elem.val().trim() == ''){
                    $elem.addClass('no_valid');
                  }else{
                    $elem.removeClass('no_valid');
                  }
                  break;
              }
            }
          }
        });

        var inpStates = $qContSections.find('.no_valid:visible').length > 0 ? false : true;

        vrb.askQuestion.visBottomBtns({
          vis: 'visible',
          dis: true
        }, {
          vis: 'visible',
          dis: inpStates
        });

        vrb.askQuestion.rightHistory();

        vrb.askQuestion.ashRBox.addClass('aqh_rb_tab_1_wrap');
        vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_2_wrap aqh_rb_tab_3_wrap');
        vrb.askQuestion.aqpBtnForward.html('ЗАПОЛНИТЬ ЛИСТ АНАМНЕЗА<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.aqhRBBtnItem.html('ЗАПОЛНИТЬ ЛИСТ АНАМНЕЗА<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.qtItem.removeClass('qti_elem_text_complete');
        vrb.askQuestion.qtItem.eq(0).addClass('qti_elem_text_complete');
        vrb.askQuestion.qBtnBottom.show();      
        
        vrb.askQuestion.visTopBtns(1, inpStates);

        $('#question_is_anonymous').hide();
        vrb.askQuestion.aqhQPage.removeClass('ask_q_page__no_authenticated');
      },
      tab_2: function(){
        var $qContSections = vrb.askQuestion.qContTab.eq(2).find('.ask_quest_page__section');
        $qContSections.find('input, textarea, select').each(function(i, elem){
          var $elem = $(elem);
          if($elem.closest('li').hasClass('is_required')){
            var tagName = $elem[0].tagName.toLowerCase();
            if($elem.is(':visible')){
              if(tagName == 'textarea' || tagName == 'select'){
                if($elem.val().trim() == ''){
                  $elem.addClass('no_valid');
                }else{
                  $elem.removeClass('no_valid');
                }
              }
              if(tagName == 'input'){
                switch($elem.attr('type')){
                  case 'email':
                  //case 'file':
                  case 'text':
                    if($elem.val().trim() == ''){
                      $elem.addClass('no_valid');
                    }else{
                      $elem.removeClass('no_valid');
                    }
                    break;
                }
              }
            }
          }
        });

        $('.is_required').each(function(idx, _elem){
          var checkboxItem = $(_elem).find('input[type="checkbox"], input[type="radio"]');
          if(checkboxItem.length > 0){
            var checkCount = 0;
            checkboxItem.each(function (_idxx, _elemm) {
              if($(_elemm).is(':checked')){
                checkCount ++;
              };
            });
            if(checkCount > 0){
              $(_elem).removeClass('no_valid');
            }else{
              $(_elem).addClass('no_valid');
            };
          };
        });



        var inpStates = $qContSections.find('.no_valid:visible').length > 0 ? false : true;

        vrb.askQuestion.visBottomBtns({
          vis: 'visible',
          dis: true
        }, {
          vis: 'visible',
          dis: inpStates
        });

        vrb.askQuestion.aqpBtnForward.html('АВТОРИЗОВАТЬСЯ<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.aqhRBBtnItem.html('АВТОРИЗОВАТЬСЯ<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.aqpBtnBack.html('<div class="ask_q_page__bottom__btns_icon"></div>ОПИСАТЬ СИМПТОМЫ');

        vrb.askQuestion.qtItem.removeClass('qti_elem_text_complete');
        vrb.askQuestion.qtItem.slice(0, 2).addClass('qti_elem_text_complete');
        vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_1_wrap aqh_rb_tab_3_wrap');
        vrb.askQuestion.ashRBox.addClass('aqh_rb_tab_2_wrap');
        $('.aqh_rb_tab_2').text($('#ask_question_symptoms_textarea').val());

        vrb.askQuestion.visTopBtns(2, inpStates);

        $('#question_is_anonymous').hide();

        vrb.askQuestion.qBtnBottom.show();
        vrb.askQuestion.aqhQPage.removeClass('ask_q_page__no_authenticated');
      },
      tab_3: function(){
        $('#question_is_anonymous').show();
        console.log('tab3');

        vrb.askQuestion.qtItem.removeClass('qti_elem_text_complete');
        vrb.askQuestion.qtItem.slice(0, 3).addClass('qti_elem_text_complete');
        vrb.askQuestion.aqpBtnForward.html('ОТПРАВИТЬ ВОПРОС<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.aqhRBBtnItem.html('ОТПРАВИТЬ ВОПРОС<div class="ask_q_page__bottom__btns_icon"></div>');
        vrb.askQuestion.aqpBtnBack.html('<div class="ask_q_page__bottom__btns_icon"></div>ЗАПОЛНИТЬ ЛИСТ АНАМНЕЗА');

        if($('.ask_quest_page__section_personal_data').length != 0){
          vrb.askQuestion.aqhQPage.removeClass('ask_q_page__no_authenticated');
        }else{
          vrb.askQuestion.aqhQPage.addClass('ask_q_page__no_authenticated');
        };

        var sHistoryStr = '';
        var sHistoryCount = 0;        

        $('.sheet_history__wrap').each(function(idx, _elem){
          var thisElem = $(_elem).find('textarea').length != 0 ? $(_elem).find('textarea') : ($(_elem).find('input[type="text"]').length != 0 ? $(_elem).find('input[type="text"]') : false);
          if(thisElem){
            if(thisElem.val() != ''){
              sHistoryStr += '<div class="aqh_rb_tab_3__title ' + (sHistoryCount == 0 ? 'aqh_rb_tab_3__title_first' : '') + '">' + $(_elem).find('.sheet_history__title').text() + ':</div>' + thisElem.val();
              sHistoryCount ++;
            };
          }else if($(_elem).find('.choices_inner_div').size() > 0){
            var choicesInner = '';
            var choicesInnerCount = 0;
            $(_elem).find('.choices_inner_div').each(function(_inner_idx, _inner_elem){
              if($(_inner_elem).find('input').is(':checked')){
                choicesInner += '<div class="aqh_rb_tab_3__list">' + ' - ' + $(_inner_elem).find('.choices_inner_div__body').text() + '</div>';
                // (choicesInnerCount != 0 ? ';<br>' : '')
                choicesInnerCount ++;
              };
            });

            if(choicesInner != ''){
              sHistoryStr += '<div class="aqh_rb_tab_3__title ' + (sHistoryCount == 0 ? 'aqh_rb_tab_3__title_first' : '') + '">' + $(_elem).find('.sheet_history__title').text() + ':</div>' + choicesInner;
            };

            sHistoryCount ++;
          };
        });

        var sHistoryFileStr = '';

        $('.upload_image').each(function(idx, _elem){
          var uImg = $(_elem).find('img');
          if(uImg.length != 0){
            sHistoryFileStr += (uImg.attr('src') != '' ? '<div class="aqh_rb_tab_3__img" data-file_type="Изображение"><div class="aqh_rb_tab_3__img__item" style="background-image:url(\'' + uImg.attr('src') + '\')"></div></div>' : '');
          };
        });
        
        $('.aqh_rb_tab_3__sheet_history').html(sHistoryStr);
        if(sHistoryFileStr != ''){
          $('.aqh_rb_tab_3_header').remove();
          $('.aqh_rb_tab_3__sheet_history_file').remove();
          $('.aqh_rb_tab_3__sheet_history').after('<div class="aqh_rb_tab_3_header">Ваши файлы</div><div class="aqh_rb_tab_3 aqh_rb_tab_3__sheet_history_file">' + sHistoryFileStr + '</div>');
        };

        vrb.askQuestion.ashRBox.removeClass('aqh_rb_tab_2_wrap aqh_rb_tab_4_wrap');
        vrb.askQuestion.ashRBox.addClass('aqh_rb_tab_3_wrap');
      }
    },

    visBottomBtns: function(prevBtnProp, nextBtnProp){
      var _this = this;
      var prevBtn = _this.qBtnBottom.find('.ask_q_page__bottom__back');
      var nextBtn = _this.qBtnBottom.find('.ask_q_page__bottom__forward');

      prevBtn.css('visibility', prevBtnProp.vis);
      nextBtn.css('visibility', nextBtnProp.vis);

      nextBtn.data('title', nextBtnProp.title);

      if(!prevBtnProp.dis){
        prevBtn.addClass('disabled_btn');
      }else{
        prevBtn.removeClass('disabled_btn');
      }

      if(!nextBtnProp.dis){
        nextBtn.addClass('disabled_btn');
        $('.aqh_right_box__btn').addClass('aqh_right_box__btn_disabled');
      }else{
        nextBtn.removeClass('disabled_btn');
        $('.aqh_right_box__btn').removeClass('aqh_right_box__btn_disabled');
      };
    },

    visTopBtns: function(_tab, _type){
      vrb.askQuestion.topBtns[_tab + 1] = _type;
      for(var i = 0;i < 4;i++){
        if(vrb.askQuestion.topBtns[i]){
          vrb.askQuestion.qTabsItem.eq(i).addClass('qti_elem_text_available');
        }else{
          vrb.askQuestion.qTabsItem.eq(i).removeClass('qti_elem_text_available');
        };
      };
    },

    checkRadio: function(_this){
      $(_this).closest('.sheet_history__wrap').find('.custom_input').removeProp('checked');
      $(_this).find('.custom_input').prop('checked', 'checked');
    },

    goTabsItem: function(idx){
      var _this = this;

      _this.qTabsItem.removeClass('question_tabs__item_active');
      _this.qContTab.hide();

      _this.qTabsItem.eq(idx).addClass('question_tabs__item_active');
      _this.qContTab.eq(idx).show();

      _this.checkArr['tab_' + idx]();

      $(window).resize();
    },
    
    nextTabs: function(event){
      var _this = this;
      var $active = $('.question_tabs__item_active');
      var idx = $active.data('tab');
      var nextIdx = $active.next().data('tab');

      _this.checkArr['tab_' + idx]();

      if($(event.target).hasClass('disabled_btn')){
        _this.qContTab.eq(idx).addClass('show_no_valid');
        $('.no_valid:visible').eq(0).focus();
        return false;
      }else{
        _this.qContTab.eq(idx).removeClass('show_no_valid');
      }
      if(nextIdx){
        _this.goTabsItem(nextIdx);
      }
    },

    prevTabs: function(event){
      var _this = this;
      var $active = $('.question_tabs__item_active');
      var idx = $active.data('tab');
      var prevIdx = null;

      if(idx != 0){
        prevIdx = $active.prev().data('tab');
      }else{
        return false;
      }
      _this.goTabsItem(prevIdx);
    }
  },


  elemRes: {
    menuTop: function(){
      var menuWrap = $('.menu_wrap');
      var menuLinks = menuWrap.find('.menu__link');
      var menuTable = menuWrap.find('.menu_table');
      var p = 0;

      menuTable.hide();
      menuLinks.css('padding-left', p);
      menuLinks.css('padding-right', p);
      menuTable.show();
      p = ((menuWrap.width() - (menuTable.outerWidth() + 2)) / (menuLinks.length-1)) / 2;
      menuLinks.each(function(i, menuLink){
        var menuLink = $(menuLink);
        if(i == 0){
          menuLink.css('padding-right', p);
        }else if(i == menuLinks.length - 1){
          menuLink.css('padding-left', p);
        }else{
          menuLink.css('padding-left', p);
          menuLink.css('padding-right', p);
        }

      });
      menuTable.css('visibility','visible');
    },

    mainHeightRes: function(){
      var mainBlock = $('.main_cont__height');
      var ratingDoc = $('.rating_doctors');
      var ratingDocWrap = $('.rating_doctors__item_wrap');
      var ratingDocItem = $('.rating_doctors__item');
      var mainBlockH = 0;
      var liveBand = null;
      var ratingDocHeight = null;

      if(window.location.pathname == '/' || window.location.pathname == '/frame/'){
        liveBand = $('.live_band');
        if($(window).width() < 1280){
          liveBand.find('.live_band__item').last().hide();
        }else{
          liveBand.find('.live_band__item').last().show();
        }

        ratingDocItem.eq(ratingDocItem.length - 1).css('margin-bottom', 0);

        ratingDoc.outerHeight(0);
        mainBlockH = mainBlock.outerHeight(true);

        ratingDocHeight = mainBlockH - vrb.getDifferenceOff('.main_columns', ratingDoc);

        ratingDoc.outerHeight(ratingDocHeight, true);

        ratingDocWrap.outerHeight(ratingDocHeight - 40 - ratingDocWrap.position().top);
      }
    },

    all: function(){
      this.menuTop();
      this.mainHeightRes();
    }
  },

  getDifferenceOff : function(parClass, elClass){
    var elOffTop = elClass.offset().top;
    var parent = elClass.closest(parClass);
    var parOffTop = parent.offset().top;
    var elH = elOffTop - parOffTop;

    return elH;
  },

  plugin: {
    init: function(){
      // $('.rating_doctors').perfectScrollbar({
      //   wheelSpeed: 2
      // });
      vrb.tabsPlug.init();
      vrb.starsPlug.init();
      vrb.inpFile.init();
    }
  },

  
  init: function(){
    var _this = this;
    $(window).resize(function(){
      _this.onWindowRes();
    }).resize();

    _this.plugin.init();
  },
  
  onWindowRes: function(){
    var _this = this;
    _this.elemRes.all();
  }
  
};

$(document).ready(function(){
  $('input, select').customizeFormWww();
  messageErrorAdd('clear');
  $('.ready_flash').css('opacity', 1);
});

$(window).load(function(){
  vrb.init();
});
