var lp_vrb_onmessage = function(text, id, channel){
  var location = decodeURI(document.location);
  var splitShowId = location.split('/');

  text['location'] = '';
  text['full_location'] = location;

  if(/\/doctor\-account\/now-dialog\/[0-9]*\//.test(location) || /\/personal\-account\/now\-dialog\/[0-9]*\//.test(location)){
    text['location'] = 'dialog_show';
    text['show_id'] = splitShowId[splitShowId.length - 2];
  }else if(/\/doctor\-account\/consilium\/[0-9]*\//.test(location)){
    text['location'] = 'consilium_show';
    text['show_id'] = splitShowId[splitShowId.length - 2];
  }else if(/\/doctor\-account\/consilium\//.test(location)){
    text['location'] = 'consilium_index';
  }else if(/\/doctor\-account\/history\-appeal\//.test(location)){
    text['location'] = 'review';
  }else if(/\/personal\-account\/patient\-card\//.test(location)){
    text['location'] = 'patient_card';
    if(text['event'] == 'closed' || text['event'] == 'resume'){
      patientCardSpecialtyFilter('update');
    }
  }else if(/\/doctor\-account\//.test(location) || /\/personal\-account\//.test(location)){
    text['location'] = 'dialog_index';
  }

  if(text['location']){
    updateNotice(text);
  }
};

var updateNotice = function (arr) {
  var noticeSplit;
  var validMenuUpdate;

  $.post('/notice-update/', 'notice[type]=' + arr['type'] + '&notice[inner_id]=' + arr['inner_id'] + '&notice[event]=' + arr['event'] + '&notice[profile]=' + arr['profile'] + '&notice[location]=' + arr['location'] + '&notice[full_location]=' + arr['full_location'] + '&notice[show_id]=' + arr['show_id'], function (data) {
    if(arr['location'] == 'dialog_index' || arr['location'] == 'consilium_index'){
      if(arr['event'] == 'question' || arr['event'] == 'closed' || arr['event'] == 'resume' || arr['event'] == 'consilium_specialist_add' || arr['event'] == 'consilium_specialist_delete' || arr['event'] == 'consilium_closed' || arr['event'] == 'consilium_resume'){
        if(arr['location'] == 'consilium_index'){
          $('.pc_user_page_wrap').html($(data).find('.dialog_list_notice'));
        }else if(!/consilium/.test(arr['event'])){
          $('.pc_user_page_wrap').html($(data).find('.pc_user_page'));
        }
      }
      $('.notice_wrap').html($(data).find('.pc_curr_dialogues'));
    }else if(arr['location'] == 'dialog_show' || arr['location'] == 'consilium_show'){
      if(arr['show_id'] == arr['inner_id']){
        $('.pc_user_page_wrap').html($(data).find('.dialog_list_notice'));
      }

      if(arr['location'] == 'consilium_show' && arr['show_id'] == arr['inner_id'] && arr['event'] == 'consilium_specialist_delete'){
        $('.dc_members_concilium').remove();
      }

      if(arr['show_id'] != arr['inner_id']){
        $('.notice_wrap').html($(data).find('.pc_curr_dialogues'));
      }
    }else if(arr['location'] == 'review'){
      if(arr['event'] != 'review'){
        $('.notice_wrap').html($(data).find('.pc_curr_dialogues'));
      }else{
        $('.review_wrap').html($(data).find('.dialog_list_notice'));
        $('.ha_patient_filter_wrap').html($(data).find('.advanced_list_notice'));
        vrb.starsPlug.init();
      }
    }else if(arr['location'] == 'patient_card'){
      $('.notice_wrap').html($(data).find('.pc_curr_dialogues'));
    }

    validMenuUpdate = {
      review: true,
      consilium_specialist_add: true,
      consilium_specialist_delete: true,
      question: true,
      closed: true,
      resume: true
    };

    if((/\/doctor\-account\//.test(arr['full_location']) || /\/personal\-account\//.test(arr['full_location'])) && validMenuUpdate[arr['event']]){
      $('.da_menu_wrap').html($(data).find('.da_menu_notice'));
    }
  });
};

var pushstream = null;
var lp_vrb = {
  init: function(channel){
    PushStream.LOG_LEVEL = 'debug';
    pushstream = new PushStream({
      host: window.location.hostname,
      port: window.location.port,
      modes: "longpolling"
    });
    pushstream.onmessage = lp_vrb_onmessage;
    pushstream.removeAllChannels();
    try {
      pushstream.addChannel('private-' + channel);
      pushstream.connect();
    } catch(e) {
      console.log(e)
    };
  },
  onmessage: function(message){
    alert(message);
  }
};