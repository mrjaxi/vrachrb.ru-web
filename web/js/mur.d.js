var openProfile = function (el) {
  var window = $('.profile_link_window');

  if(el != 'show'){
    window.hide();
  }else{
    if(window.is(':visible')){
      window.hide();
    }else{
      window.show();
    }
  }
};

$(document).click(openProfile);

var addComment = {

  item: null,

  onAdd: function () {
    var s = null;
    s = $('.add_comment').serialize();
    $.post('/comment/', s, function (data) {
      $('.comment_all').html(data);
      $('#comment_body').val(' ');
    })
  },

  onRestart: function (aCheck, rA) {

    var str, param1;
    if(rA == 'r'){
      str = '<h2>Вы';
    }else if(rA == 'a'){
      str = '<b>Вход';
    }else if(rA == 's'){
      str = 's';
    }

    var dlpSplit = (decodeURI(document.location.pathname)).split('/');
    if(dlpSplit[1] == 'tip' || dlpSplit[1] == 'article'){
      if(aCheck == str){
        if(dlpSplit[1] == 'tip'){
          param1 = 'Prompt'
        }else if(dlpSplit[1] == 'article'){
          param1 = 'Article'
        }
        $('.authorization_window').hide();
        overflowHiddenScroll();
        $('.show_prev').remove();
        $.post('/comment-restart/', 'restart[restart]=1&restart[type]=' + param1 + '&restart[id]=' + dlpSplit[2], function (data) {
          $('.comment_wrap').html(data);
        });
      }
    }
  },

  onShowPrev: function () {
    var s = null, splitSize;
    var visibleComment = $('.comment_all').find('.comment_body').size();
    var cTypeId = $('.comment_type_id').val();

    $.post('/comment/', 'show_prev=' + visibleComment + '&comment_type_id=' + cTypeId, function (data) {
      $('.comment_all').append(data);

      splitSize = (data.split('comment_body')).length;
      if(splitSize < 4){
        $('.show_prev_btn').remove();
      }
    });
  },

  init: function(){
    var _this = this;
  }
};