var init_imageuploader = function(id, options, JSdone, JSinit, name){
  var width = options.width;
  var height = options.height;
  var parent = $('#' + id + 'fileupload').parent();
  var hidden = parent.find('input[type="hidden"]');
  var overlay = false;
  if(hidden.val() != ''){
    parent.removeClass('no_image');
    parent.css('background-image', 'url(/u/' + options.key + '/' + hidden.val().replace('.', '-S.') + ')');
    parent.find('.uploader_messages').html('');
  }
  $('#' + id + 'fileupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
      overlay.remove();
      parent.removeClass('uploading');
      if(data.result.success == true){
        parent.removeClass('no_image');
        parent.css('background-image', 'url(/u/' + options.key + '/' + data.result.filename.replace('.', '-S.') + ')');
        parent.find('.uploader_messages').html('');
        hidden.val(data.result.filename);
      } else {
        parent.find('.uploader_messages').html('<span style="color:#FF0000">' + data.result.error + '</span>');
      }
      JSdone(data, id);
    },
    progressall: function (e, data) {
      JSinit(id);
    },
    add: function(e, data){
      $.each(data.files, function (index, file) {
        if(!(/(\.|\/)(png|gif|jpg|jpeg)$/i.test(file.name))){
          var ext = file.name.substr(file.name.lastIndexOf('.'));
          alert('Недопустимое расширение файла: ' + ext + '. Допустимые расширения: .png, .gif, .jpg, .jpeg');
        } else {
          parent.addClass('uploading');
          parent.find('.uploader_messages').html('Загрузка');
          overlay = sOverlay(parent, 'sOverlay_upload', parent);
          data.submit();
        }
      });
    }
  });
};