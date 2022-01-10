var init_imagesuploader__update_val = function(id){
  var values = [];
  $('.lui_photos__item').each(function(k, el){
    if($(el).find('input').val()){
      values.push($(el).find('input').val() + ':' + $(el).find('img').width() + ':' + $(el).find('img').height());
    }
  });
  $('#' + id).val(values.join(';'));
}
var init_imagesuploader__create_thumb = function(path, w, h, files_name, value, id, update){
  $('.lui_photos').append('<div class="lui_photos__item">' +
    '<ul class="sf_admin_actions">' +
      '<li class="sf_admin_action_delete"><a href="" style="border-bottom-style:dashed" onclick="$(this).parent().parent().parent().remove();init_imagesuploader__update_val(\'' + id + '\');return false;">Удалить</a></li>' +
    '</ul>' +
    '<img src="' + path.replace('.', '-S.') + '" width="' + w + '" height="' + h + '" />' +
    '<input type="hidden" name="' + files_name + '[]" value="' + value + '" />' +
    '</div>');
  $('.lui_photos').sortable({containment: '.lui_photos', stop: function( event, ui ) {init_imagesuploader__update_val(id);}});
  if(update){
    init_imagesuploader__update_val(id);
  }
}
var init_imagesuploader = function(id, options, JSdone, JSinit, files_name){
  var pseudo_button__txt = $('#' + id + 'fileupload').parent().find('.pseudo_button__txt');
  var pseudo_button__filename = $('#' + id + 'fileupload').parent().parent().find('.pseudo_button__filename');
  pseudo_button__txt.data('holder', pseudo_button__txt.html());
  if($('#' + id).val() != ''){
    var ex = $('#' + id).val().split(';');
    $.each(ex, function(k, v){
      var exx = v.split(':');
      init_imagesuploader__create_thumb('/u/i/' + exx[0], exx[1], exx[2], files_name, exx[0], id, false);
    })
  }
  $('#' + id + 'fileupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
      //NProgress.done();
      pseudo_button__txt.html(pseudo_button__txt.data('holder'));
      $('#' + id + 'fileupload').parent().removeClass('pseudo_button__disabled');
      if(data.result.success == true){
        //JSdone.call(data);
        var amazing = JSdone.bind(data);
        amazing();
        init_imagesuploader__create_thumb('/u/i/' + data.result.filename, data.result.sizes.S.width, data.result.sizes.S.height, files_name, data.result.filename, id, true);
        pseudo_button__filename.html('&nbsp;');
      } else {
        alert(data.result.error);
      }
    },
    progressall: function (e, data) {
      //NProgress.set(data.loaded / data.total);
      pseudo_button__filename.html(Math.round(data.loaded / data.total * 100) + '%');
    },
    add: function(e, data){
      $.each(data.files, function (index, file) {
        if(!(/(\.|\/)(png|gif|jpg|jpeg)$/i.test(file.name))){
          var ext = file.name.substr(file.name.lastIndexOf('.'));
          alert('Недопустимое расширение файла: ' + file.name + '. Допустимые расширения: .png, .gif, .jpg, .jpeg');
          return;
        } else {
          
        }
      });
      $('#' + id + 'fileupload').parent().addClass('pseudo_button__disabled');
      pseudo_button__txt.html('Загрузка..');
      //NProgress.start();
      data.submit();
    }
  });
};

var init_imageUploader = function(id, options, JSdone, JSinit, files_name){
  var pseudo_button__txt = $('#' + id + 'fileupload').parent().find('.pseudo_button__txt');
  var pseudo_button__filename = $('#' + id + 'fileupload').parent().parent().find('.pseudo_button__filename');
  pseudo_button__txt.data('holder', pseudo_button__txt.html());

  $('#' + id + 'fileupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
      //NProgress.done();
      pseudo_button__txt.html(pseudo_button__txt.data('holder'));
      $('#' + id + 'fileupload').parent().removeClass('pseudo_button__disabled');
      if(data.result.success == true){
        //JSdone.call(data);
        var amazing = JSdone.bind(data);
        amazing();
        init_imagesuploader__create_thumb('/u/i/' + data.result.filename, data.result.sizes.S.width, data.result.sizes.S.height, files_name, data.result.filename, id, true);
        pseudo_button__filename.html('&nbsp;');
      } else {
        alert(data.result.error);
      }
    },
    progressall: function (e, data) {
      //NProgress.set(data.loaded / data.total);
      pseudo_button__filename.html(Math.round(data.loaded / data.total * 100) + '%');
    },
    add: function(e, data){
      $.each(data.files, function (index, file) {
        if(!(/(\.|\/)(png|gif|jpg|jpeg)$/i.test(file.name))){
          var ext = file.name.substr(file.name.lastIndexOf('.'));
          alert('Недопустимое расширение файла: ' + file.name + '. Допустимые расширения: .png, .gif, .jpg, .jpeg');
          return;
        } else {

        }
      });
      $('#' + id + 'fileupload').parent().addClass('pseudo_button__disabled');
      pseudo_button__txt.html('Загрузка..');
      //NProgress.start();
      data.submit();
    }
  });
};