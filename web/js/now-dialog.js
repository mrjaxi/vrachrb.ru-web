var
  str = 'Заполните это поле...',
  aBody = $('#answer_body');

var dialogCheckTextArea = function (_this, type) {
  aBody = $('#answer_body');
  if(type == 'submit'){
    if(aBody.val() == '' || aBody.val() == str){
      aBody
        .val(str)
        .css('border', '1px solid #FF5555');
      return false;
    }else{
      return true;
    }
  }else if(type == 'click'){
    if($(_this).val() == str){
      $(_this).val('');
    }
  }
};

var nowDialogAnswerEdit = function(type, _this){
  var next, textarea, btn, text;
  if(type == 'open'){
    next = $(_this).next();
    btn = next.find('.pc_chat__item__edit__btn');
    textarea = next.find('.pc_chat__item__edit__textarea');
    text = next.find('.pc_chat__item__edit_text').text();
    if(next.hasClass('active_edit')){
      next.removeClass('active_edit');
    }else{
      $('.active_edit').removeClass('active_edit');
      next.addClass('active_edit');
      textarea.val(next.find('.pc_chat__item__edit_text').text());
    }
  }else if(type == 'submit'){
    var message = 'Ответ сохранён';
    var active = $('.active_edit');
    var body = active.find('.pc_chat__item__edit__textarea').val();
    var send = 'type=answer&id=' + _this + '&body=' + body;
    if(body !=  active.find('.pc_chat__item__edit_text').text() && body != ''){
      $.post('/doctor-account/now-dialog-edit/', send, function (data) {
        if(data == 'ok'){
          active.find('.pc_chat__item__edit_text').text(body);
          active.removeClass('active_edit');
        }else{
          message = 'Ошибка сохранения';
        }
        active.append('<div class="save_true_message pc_chat__item__edit__message">' + message + '</div>');
        setTimeout(function () {
          $('.pc_chat__item__edit__message').remove();
        }, 2000);
      });
    }
  }
};

var nowDialogAnswerAdd = function () {
  if(dialogCheckTextArea($('#answer_body'),'submit')){
    if(!answerRepeatCheck($('#answer_body').val())){
      var s = $('#now_dialog_form').serialize() + '&question_id=' + questionId;
      nowDialogAnswerSubmit(s);
    }
  }
};

var nowDialogPleaseAnalysis = function (type) {
  var s;
  if(type == 'clear'){
    overflowHiddenScroll($('.dc_overlay_write_directions'));
    $('.please_analysis_form__item').show();
    $('.please_analysis_form__thx').hide();

    $('#please_analysis_form').find('.q_sheet_history__ol_inp').each(function (idx, _this) {
      if(idx != 0){
        $(_this).remove();
      }
    });
  }

  if(!type){
    s = $('#now_dialog_form').serialize() + '&' + $('#please_analysis_form').serialize() + '&please_analysis=1';
    nowDialogAnswerSubmit(s);

    debug(s);
  }
};

var nowDialogAnswerSubmit = function (send) {
  var pcChat = $('.pc_chat'), jsn, jsnLength, addStr, sendSplit;
  sendSplit = send.split('=')[0];

  if(sendSplit != 'consilium_specialist' && sendSplit != 'live_reception_info' && sendSplit != 'live_reception'){
    pcChat.css('opacity', 0.4);
  }

  $.post('/doctor-account/now-dialog-answer/', send, function (data) {
    if(sendSplit == 'consilium_specialist'){
      jsn = JSON.parse(data);
      addStr = '<div class="fs_18" style="padding: 0 20px;">Добавьте врачей в консилиум</div><div style="padding-bottom: 0;" class="ta_l overlay__white_box__body"><div class="dc_call_meeting">';
      var i = 0;
      for(i; i < 100; i ++){
        if($(jsn[i]).length == 0){
          break;
        }
        addStr += '<div class="dc_call_meeting__item"><div class="dc_call_meeting__item__t" onclick="event.stopPropagation();$(this).next().slideToggle(200);$(this).closest(\'.dc_call_meeting__item\').toggleClass(\'dc_call_meeting__item_active\');">' + jsn[i]['title'] + '</div><div class="dc_call_meeting__item__b">';
        for(var idx = 0; idx < 100; idx ++){
          if($(jsn[i][idx]).length == 0){
            break;
          }
          addStr += '<label class="confirmation_checkbox custom_input_label"><input class="custom_input" name="specialist_id[]" type="checkbox" value="' + jsn[i][idx]['id'] + '" /><span class="custom_input custom_input_checkbox"></span>' + jsn[i][idx]['name'] + '<br/><span class="fs_11">' + jsn[i][idx]['about'] + '</span></label><i class="br10"></i>';
        }
        addStr += '</div></div>';
      }
      addStr += '</div></div><div class="overlay__white_box__dialog clearfix"><button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;">Добавить в консилиум</button></div>';

      $('.consilium_specialist_add_form').html(addStr);
      $('.preloader_before').removeClass('preloader_before');
      overflowHiddenScroll($('.dc_overlay_call_meeting'));
    }else if(sendSplit == 'consilium'){
      if(data)
      {
        $('.consilium_specialist_add_form').html('<b class="fs_16">Консилиум успешно создан</b><i class="br10"></i><a href="/doctor-account/consilium/' + data + '/">Перейти в консилиум</a><i class="br30"></i>');
        $('.da_consilium_add_btn_wrap').html('<a href="/doctor-account/consilium/' + data + '/" class="btn_all blue_btn fl_l" >Перейти в консилиум</a>');
        pcChat.css('opacity', 1);
      }else{
        alert('Ошибка создания. Попробуйте перезагрузить страницу');
      }
    }else if(sendSplit == 'live_reception_info'){

      if(data){
        jsn = JSON.parse(data);
        jsnLength = jsn.length;
        addStr = '';
        if(jsnLength > 0){
          addStr += '<b>Место работы:</b><i class="br5"></i>';
          addStr += '<table cellspacing="0" cellpadding="0" width="100%">';
          for(i = 0; i < jsnLength; i ++){
            if((i % 2) == 0 && i != 0){
              addStr += '<tr><td colspan="3"><i class="br20"></i></td></tr>';
            }

            if((i % 2) == 0) {
              addStr += '<tr valign="top">';
            }
            addStr += '<td width="50%">';
            addStr += '<label class="custom_input_label"><input class="custom_input" ' + (i == 0 ? 'checked="checked"' : '') +  ' value="' + jsn[i]['id'] + '" name="work_place[]" type="radio"><span class="custom_input custom_input_radio"></span>' + jsn[i]['title'] + '</label>';
            //addStr += '<label class="confirmation_checkbox custom_input_label"><input class="custom_input" value="' + jsn[i]['id'] + '" name="work_place[]" type="checkbox"><span class="custom_input custom_input_checkbox"></span>' + jsn[i]['title'] + '</label>';
            addStr += '</td>';

            if((i % 2) == 0) {
              addStr += '<td width="1"><img src="/i/n.gif" height="0" width="20"></td>';
            }

            if(((i+1) % 2) == 0) {
              addStr += '</tr>';
            }
            if((i - 1) == jsnLength && (jsnLength % 2) != 0) {
              addStr += '<td width="50%"></td>';
            }
          }
          if((jsnLength % 2) != 0 ){
            addStr += '</tr>';
          }
          addStr += '</table>';
          $('#now_dialog_work_place_wrap').html(addStr);
          overflowHiddenScroll($('.dc_overlay_invation'));
        }
      }else{
        alert('Для отправки приглашений заполните "Место работы" на странице "Личные данные"');
      }

    }else if(sendSplit == 'live_reception'){
      $('.pc_user_page_wrap').html(data);
      $('.now_dialog_invite_live_reception_form_item').hide();
      $('.live_reception_message').show();
      $('.dc_overlay_invation').attr('onclick', 'overflowHiddenScroll($(this));$(\'.live_reception_message\').hide();$(\'.now_dialog_invite_live_reception_form_item\').show();');
    }else{
      if(sendSplit == 'close'){
        overflowHiddenScroll($('.overlay_alert'));
      }
      if(send.indexOf('please_analysis')){
        $('.please_analysis_form__item').hide();
        $('.please_analysis_form__thx').show();
      }

      debug(data);

      $('.pc_user_page_wrap').html(data);
      $('#answer_body').val('');
      pcChat.css('opacity', 1);
    }
  });
};

var nowDialogAnswerOpen = function () {
  var send = 'open=' + questionId;
  nowDialogAnswerSubmit(send);
};

var nowDialogAnswerClose = function (type) {
  if(type == 'check'){
    overflowHiddenScroll($('.overlay_alert'));
  }else if(type == 'submit'){
    var s = $('#now_dialog_form').serialize();
    var send = 'close=' + questionId;
    if($('#answer_body').val() != '' && $('#answer_body').val() != str){
      send += '&' + s;
    };
    nowDialogAnswerSubmit(send);
  }
};

var nowDialogAddConsiliumInfo = function (_this) {
  var send = 'consilium_specialist=' + questionId;
  $(_this).addClass('preloader_before');
  nowDialogAnswerSubmit(send);
};

var nowDialogAddConsilium = function () {
  var
    s = $('.consilium_specialist_add_form').serialize(),
    send;
  send = 'consilium=' + questionId + (s ? '&' + s : '') ;
  nowDialogAnswerSubmit(send);
};

var nowDialogRedirectAdmin = function () {
  var form = $('#now_dialog_form');
  form
    .removeAttr('onsubmit')
    .append('<input type="hidden" value="' + questionId + '" name="redirect_admin[question_id]">');
};

var nowDialogEmailReminder = function () {
  var s = $('#now_dialog_form').serialize();
  var send = s + '&email=' + questionId;
  nowDialogAnswerSubmit(send);
};

var nowDialogInviteLiveReceptionInfo = function () {
  var send = 'live_reception_info=' + questionId + '&question_id=' + questionId;
  nowDialogAnswerSubmit(send);
};

var nowDialogInviteLiveReception = function () {
  if(nowDialogCheck('reception') == 'ok'){
    var s = $('#now_dialog_invite_live_reception_form').serialize();
    var send;
    send = 'live_reception=' + questionId + '&' + s + '&question_id=' + questionId;
    nowDialogAnswerSubmit(send);
  }
};

var nowDialogCheck = function (elem) {
  var
      form = $('#now_dialog_invite_live_reception_form'),
      checkLocation, checkPrice, checkDate = true;
  if(elem == 'reception'){
    $('.reception_form_error').removeClass('reception_form_error');
    form.find('input[type="radio"]').each(function () {
      if($(this).is(':checked')){
        checkLocation = true;
        return false;
      }
    });

    if(!checkLocation){
      $('#now_dialog_work_place_wrap').addClass('reception_form_error');
    }

    if($('.dc_price_admission_charge_wrap').find('input[type="radio"]').eq(1).is(':checked')){
      if($('#price_admission_price_institutions').is(':checked')){
        checkPrice = true;
      }else{
        if($('#price_admission_money_number').val() == '') {
          $('.dc_price_admission_charge_wrap').addClass('reception_form_error');
        }else{
          checkPrice = true;
        }
      }
    }else if($('.dc_price_admission_charge_wrap').find('input[type="radio"]').eq(0).is(':checked')){
      checkPrice = true;
    }

    $('.now_dialog_datetime_wrap').find('input[type="text"]').each(function () {
      if($(this).val() == ''){
        checkDate = false;
        return false;
      }
    });
    $('.now_dialog_datetime_wrap').find('select').each(function () {
      if($(this).val() == ''){
        checkDate = false;
        return false;
      }
    });
    if(!checkDate){
      $('.now_dialog_datetime_wrap').addClass('reception_form_error');
    }
  }

  if(checkLocation && checkPrice && checkDate){
    return 'ok';
  }
};

$(document).ready(function () {
  $('#answer_body').val('');

  $('#complaint_body').keyup(function () {
    if($('#complaint_body').val() != ''){
      $('.now_dialog_complaint_form').find('button').removeAttr('disabled');
    }else{
      $('.now_dialog_complaint_form').find('button').attr('disabled', 'true');
    }
  });
});
