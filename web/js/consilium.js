var
  pcUserPageW = $('.pc_user_page_wrap'),
  str = 'Заполните это поле...',
  preloader = $('.specialist_preloader');

var consiliumCheckTextArea = function (_this) {
  if($(_this).val() == str){
    $(_this).val('');
  }
};

var consiliumDetails = function () {
  if($('.overlay__white_box').find('.quest_n_ans_page').size() == 0){
    $('.pc_chat__item__details').find('.specialist_preloader').show();
    $.post('/question-answer/', 'question=' + questionId, function (data) {
      $('.overlay__white_box').html($(data).find('.quest_n_ans_page'));
      overflowHiddenScroll($('.dc_overlay_consilium_details'));
      $('.pc_chat__item__details').find('.specialist_preloader').hide();
    });
  }else{
    overflowHiddenScroll($('.dc_overlay_consilium_details'));
  }
};

var consiliumAnswer = function (elem) {
  var caBody = $('#consilium_answer_body');
  var send, el, s;
  pcUserPageW = $('.pc_user_page_wrap');

  s = $('.pc_chat_add_mess').serialize();
  el = elem.split('_');

  if(el[0] == 'close'){
    send = (caBody.val() != '' && caBody.val() != str) ? s + '&' + 'close=' + el[1] : 'close=' + el[1];
  }else if(el[0] == 'add'){
    if(caBody.val() == '' || caBody.val() == str){
      caBody
        .val(str)
        .css('border', '1px solid #FF5555');
      return false;
    }
    send = s;
  }else if(el[0] == 'open'){
    send = 'open=' + el[1];
  }

  pcUserPageW.css('opacity', 0.4);

  $.post('/doctor-account/consilium-answer/', send, function (data) {
    pcUserPageW.html(data);
    caBody.val('');
    pcUserPageW.css('opacity', 1);
  });
};

var consiliumSpecialistDelete = function (_this, consIdAndspId) {
  $.post('/doctor-account/consilium/', 'cons_id_and_sp_id_delete=' + consIdAndspId, function (data) {
    if(data == 'ok'){
      _this.closest('.dc_members_consilium__item').remove();
    }else{
      alert('Ошибка удаления. Попробуйте перезагрузить страницу.');
    }
  });
};

var consiliumSpecialistAddInfo = function (_this, consIdAndspId) {
  $('.consilium_specialist_add_btn_wrap').find('.specialist_preloader').show();

  var exceptionSpId = '', split, jsn;
  $('.members_of_council_link').each(function () {
    split = $(this).attr('href').split('/specialist/');
    exceptionSpId += split[1];
  });

  if(!exceptionSpId){
    exceptionSpId = 'all';
  }

  $.post('/doctor-account/consilium/', 'add_info=' + exceptionSpId, function (data) {
    jsn = JSON.parse(data);
    var addStr = '<div class="fs_18" style="padding: 0 20px;">Добавьте врачей в консилиум</div><div style="padding-bottom: 0;" class="ta_l overlay__white_box__body"><div class="dc_call_meeting">';
    var i = 0;
    for(i; i < 100; i ++){
      if($(jsn[i]).length == 0){
        break;
      }
      addStr += '<div class="dc_call_meeting__item"><div class="dc_call_meeting__item__t">' + jsn[i]['title'] + '</div><div class="dc_call_meeting__item__b">';
      for(var idx = 0; idx < 100; idx ++){
        if($(jsn[i][idx]).length == 0){
          break;
        }
        addStr += '<label class="confirmation_checkbox custom_input_label"><input class="custom_input" name="specialist_id[]" type="checkbox" value="' + jsn[i][idx]['id'] + '" /><span class="custom_input custom_input_checkbox"></span>' + jsn[i][idx]['name'] + '<br/><span class="fs_11">' + jsn[i][idx]['about'] + '</span></label><i class="br10"></i>';
      }
      addStr += '</div></div>';
    }

    addStr += '</div></div><div class="overlay__white_box__dialog clearfix"><button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;" onclick="consiliumSpecialistAdd();return false;">Добавить в консилиум</button></div>';

    $('.consilium_specialist_add_form').html(addStr);

    $('.dc_call_meeting__item__t').click(function (event) {
      event.stopPropagation();
      $(this).next().slideToggle(200);$(this).closest('.dc_call_meeting__item').toggleClass('dc_call_meeting__item_active');
    });
    $('.consilium_specialist_add_btn_wrap').find('.specialist_preloader').hide();
    overflowHiddenScroll($('.dc_overlay_call_meeting'));
  });
};

var consiliumSpecialistAdd = function () {
  var s = $('.consilium_specialist_add_form').serialize();
  if(s){
    var addStr = '';
    var send = 'specialist_id=' + s + '&consilium_id=' + consiliumId;
    $.post('/doctor-account/consilium/', s + '&consilium_id=' + consiliumId, function (data) {
      if(data){
        var jsn = JSON.parse(data);
        var jsnLength = $(jsn).length;

        for(var i = 0; i < jsnLength; i ++){
          addStr += '<div class="dc_members_consilium__item"><a class="members_of_council_link" href="/specialist/' + jsn[i]['specialist_id'] + '/">' + jsn[i]['name'] + '</a>';
          addStr += '<i class="br1"></i><span class="fs_12">' + jsn[i]['about'] + '</span>';
          addStr += '<span class="dc_consilium_page_icon dc_members_concilium__delete" onclick="consiliumSpecialistDelete(this,\'' + jsn[i]['consilium_id'] + '_' + jsn[i]['specialist_id'] + '\');" title="Удалить из консилиума"></span></div>';
        }
        $('.dc_members_concilium_ajax_wrap').html(addStr);
        $('.consilium_specialist_add_form').html('<b class="fs_16">Врачи успешно добавлены</b><i class="br30"></i>');
      }
    });
  }
};

$(document).ready(function () {
  $('#consilium_answer_body').val('');
});