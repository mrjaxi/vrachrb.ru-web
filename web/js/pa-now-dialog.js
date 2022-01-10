var
  str = 'Заполните это поле...',
  aBody = $('#answer_body');

var paNowDialogAnswerAdd = function () {
  if(dialogCheckTextArea($('#answer_body'),'submit')){
    if(!answerRepeatCheck($('#answer_body').val())){
      var s = $('#pa_now_dialog_form').serialize() + '&question_id=' + questionId;
      paNowDialogAnswerSubmit(s, 'param');
    }
  }
};

var paNowDialogReceptionAdd = function () {
  var
      s = $('#pa_now_dialog_form').serialize(),
      send;
  send = 'user_reception=user_reception&' + s + '&question_id=' + questionId;
  paNowDialogAnswerSubmit(send, 'param');
};

var paNowDialogMyReceptionInfo = function (receptionId) {
  $('.pc_now_dialog_my_reception_form__item').show();
  $('.live_reception_message, #pc_overlay_invation__receive_reject_reason').hide();
  paNowDialogAnswerSubmit('reception_info=' + receptionId + '&question_id=' + questionId, 'param');
};

var questionIdForReview = function (_this, q_id) {
  $('#review_body').val('');
  $('.review_form__item').show();
  $('.review_form__thx').hide();
  if(q_id){
    $('.review_add_btn').removeClass('review_add_btn__active');
    $('#pc_overlay_review_form').attr('onsubmit', 'paNowDialogReview(\'' + q_id + '\');return false;');
    $(_this).addClass('review_add_btn__active');
  }
};

var paNowDialogReview = function (q_id) {
  var
    send = 'review_add=review&' + $('#pc_overlay_review_form').serialize() + '&question_id=' + (q_id ? q_id : questionId);
  $('.review_i_c__wrap').removeClass('form_error');
  $('.review_i_c').each(function (idx, elem) {
    if($(elem).val() == '' || $(elem).val() == 0){
      $(elem).closest('.review_i_c__wrap').addClass('form_error');
    }
  });
  if(!$('.review_i_c__wrap').hasClass('form_error')){
    paNowDialogAnswerSubmit(send, 'param');
  }
};

var paNowDialogMyReceptionAnswer = function (receptionAnswer) {
  var pcReceiveRejectReason = $('#pc_overlay_invation__receive_reject_reason');
  var send = 'reception_answer=' + receptionAnswer + '&' + $('#pc_now_dialog_my_reception_form').serialize() + '&question_id=' + questionId;

  if(receptionAnswer == 'n')
  {
    if(!pcReceiveRejectReason.is(':hidden')){
      if(pcReceiveRejectReason.find('textarea').val() != ''){
        paNowDialogAnswerSubmit(send, 'r_answer_' + receptionAnswer);
      }else{
        $('#pc_overlay_invation__receive_reject_reason').find('textarea').addClass('reception_form_error');
      }
    }else{
      $('#pc_overlay_invation__receive_reject_reason').find('textarea').val('');
      $('#pc_overlay_invation__receive_reject_reason').slideDown();
    }
  }else if(receptionAnswer == 'y'){
    paNowDialogAnswerSubmit(send, 'r_answer_' + receptionAnswer);
  }
};

var dialogCheckTextArea = function (_this, type) {
  aBody = _this;
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

var patientCardSpecialtyFilter = function (what) {
  var pcHistory = $('.pc_history');
  var send = 'patient_card_filter=ok&' +  $('#p_card_sp_filter').serialize();

  pcHistory.css('opacity', 0.1);

  if(what == 'all' || what == 'update'){
    $('.p_card_sp_filter_input').removeAttr('checked');
    send = 'patient_card_filter=' + what;
  }

  $.post('/personal-account/patient-card/', send, function (data) {
    if(what == 'update'){
      pcHistory.html($(data).find('.patient_card_left_block'));
      $('.patient_card_right_block').html($(data).find('.filter_block'));
      $('.patient_card_right_block').append($(data).find('.pc_history_test'));
    }else{
      $('.pc_history').html(data);
    }
    pcHistory.css('opacity', 1);
  });
};

var paNowDialogGiveAnalysis = function (_this, a_id) {
  var send = $('#pa_now_dialog_form').serialize() + '&answer_please_id=' + a_id + '&question_id=' + questionId;
  var string = '';
  for(var i = 0; i < titleArr.length; i++){
    string += '&analysis[]=' + titleArr[i] + ':' + uploaderImagesObj[titleArr[i]];
  }
  send += string;

  paNowDialogAnswerSubmit(send, 'give_analysis');
};

var paNowDialogAnswerSubmit = function (send, param) {
  var pcChat = $('.pc_chat'),
    jsn,
    jsnLength,
    jsnPrice,
    addStr = '',
    sendSplit,
    sendSplit = send.split('=');
  if(sendSplit[0] != 'reception_info' && sendSplit[0] != 'review_add'){
    pcChat.css('opacity', 0.4);
  }

  $.post('/personal-account/now-dialog-answer/', send, function (data) {
    if(sendSplit[0] == 'reception_info'){
      jsn = JSON.parse(data);
      if(jsn.length != 0){
        $('#pc_overlay_invation__receive_location').html(jsn[0]['Location'][0]['title'] + '<input type="hidden" value="' + jsn[0]['id'] + '" name="reception_contract_id">');

        jsnPrice = jsn[0]['price'];
        if(jsn[0]['price'] == 0) {
          jsnPrice = 'Бесплатно';
        }else if(jsn[0]['price'] == 1){
          jsnPrice = 'Согласно утвержденному прайсу учреждения';
        }
        $('#pc_overlay_invation__receive_price').html(jsnPrice);

        jsnLength = jsn[0]['Receive_datetime'].length;

        for(var i = 0; i < jsnLength; i ++){
          addStr += '<label class="custom_input_label" style="margin: 0 20px 5px 0;"><input ' + (i == 0 ? 'checked="checked"' : '') + ' class="custom_input" value="' + jsn[0]['Receive_datetime'][i]['id'] + '" name="time_n_date" type="radio"><span class="custom_input custom_input_radio"></span>' + jsn[0]['Receive_datetime'][i]['datetime'] + '</label>';
        }
        $('#pc_overlay_invation__receive_datetime').html(addStr);
        overflowHiddenScroll($('.pc_overlay_invation'));
      }
    }else if(sendSplit[0] == 'review_add'){
      if(data == 'ok') {
        $('.review_form__item').hide();
        $('.review_form__thx').show();
        if($('.review_add_btn').size() > 1){
          $('.review_add_btn__active').remove();
        }else{
          $('.review_add_btn').remove();
        }
      }
    }else{
      if(sendSplit[0] == 'reception_answer') {
        if(param == 'r_answer_y'){
          $('.pc_now_dialog_my_reception_form__item').hide();
          $('.live_reception_message').show();
          $('.live_reception_message').find('b').html('Согласие на прием отправлено.<i class="br5"></i>Врач примет вас в указанное время.');
        }else if(param == 'r_answer_n'){
          $('.pc_now_dialog_my_reception_form__item').hide();
          $('.live_reception_message').show();
          $('.live_reception_message').find('b').html('Отказ отправлен.');
        }
      }
      
     
      if(param == 'give_analysis'){
        console.log('good');
      }
      
      
      
      $('.pc_user_page_wrap').html(data);
      $('#answer_body').val('');
      pcChat.css('opacity', 1);
    }
  });
};

$(document).ready(function () {
  $('#answer_body').val('');
});
