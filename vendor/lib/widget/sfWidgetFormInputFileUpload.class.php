<?php
class sfWidgetFormInputFileUpload extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('allowedFileTypes', '');
    $this->addOption('customInputFile', false);
    $this->addOption('script', '/uploader');
    $this->addOption('size', 1024 * 1024 * 120);
    $this->addOption('multiple', false);
    $this->addOption('emulateUpload', false);
    $this->addOption('jcrop', false);
    $this->addOption('with_text', false);
  }


  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = str_replace(array('[', ']'), '_', $name);
    $size = $this->getOption('size');
    $allowedFileTypes = $this->getOption('allowedFileTypes');
    $customInputFile = $this->getOption('customInputFile');
    $script = $this->getOption('script');
    $multiple = $this->getOption('multiple');
    $with_text = $this->getOption('with_text');

    if($this->getOption('emulateUpload'))
    {
      $emulate_script = 'emulationUploaderClick($(\'.uploader_preview__item\').data(\'val\'), \'write\');';
      if($this->getOption('multiple'))
      {
        $emulate_script .= '$(\'.uploader_preview__item\').remove()';
      }
    }

    if($this->getOption('jcrop'))
    {
      $jcrop_key_exp = explode('=', $script);
      $jcrop_key = $jcrop_key_exp[1];

      $min_size_arr = sfConfig::get('app_' . $jcrop_key . '_sizes');
      $min_width = $min_size_arr['min']['width'];
      $min_height = $min_size_arr['min']['height'];
    }

    $add_js = '';

    $jMethod = $multiple ? 'append' : 'html';
    $jInput = $with_text ? '<i class="br5"></i><input onkeyup="liteUploader_updateVal_' . $id . '();" size="10" style="width:100%;margin:0;" type="text" value="" />' : '';

    if(!is_null($value) && $value != '')
    {
      foreach(explode(';', $value) as $v)
      {
        $file = '';
        $input = '';
        if($with_text)
        {
          $ex = explode(':', $v);
          $file = $ex[0];
          $input = '<i class="br5"></i><input onkeyup="liteUploader_updateVal_' . $id . '();" size="10" style="width:100%;margin:0;" type="text" value="' . (count($ex) == 2 ? $ex[1] : '') . '" />';
        }
        else
        {
          $file = $v;
        }
        $file_type_img = explode('.', $file);
        $format = $file_type_img[count($file_type_img) - 1];
        $preview = ($format != 'png' && $format != 'jpg' && $format != 'jpeg') ? '<div class="document_file_icon"></div>' : '<img class="imgs_grey_shd" src="/u/i/' . str_replace('.', '-S.', $file) . '" width="" height="100" />';
        $html = '<div class="uploader_preview__item" data-val="' . $v . '"><span title="Удалить файл" class="uploader_preview__item_close" onclick="$(this).parent().remove();liteUploader_updateVal_' . $id . '();">×</span>' . $preview . '</div>';
        $add_js .= "$('#{$id}__uploader_preview').{$jMethod}('{$html}');";
      }
    }

    $html = '<div class="progress_bar_and_file_ct progress_bar_and_file_ct__' . $id . '">';
    $html .= '<div class="progress_bar_ct"><div class="progress_bar_ct_bar_info">Загрузка</div><div class="progress_bar_ct_bar_percent">20%</div>';
    $html .= '<div class="progress_bar_ct_bar"><div class="progress_bar_ct_bar_w"></div></div><div class="progress_bar_ct_button"></div></div>';
    $html .= '<div class="pseudo_button lui_pseudo pseudo_button_file_wrapper only_root_button" style="position:relative;z-index:1000;">';

    $html .= ($customInputFile ? '<label class="custom_upload_input">Выберите файл' : '');
    $html .= '<input accept="' . $allowedFileTypes . '" type="file" class="pseudo_button_file" name="file' . ($multiple ? '" multiple="multiple' : '') . '" id="' . $id . '__uploader" /><b>Загрузить</b>';
    $html .= ($customInputFile ? '</label>' : '');

    $html .= '</div>&nbsp;<input style="font-weight:bold;position:relative;top:1px;border-color:transparent;visibility:hidden;" size="50" readonly="true" type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" /><i class="br5"></i><div id="' . $id . '__uploader_preview"></div></div>';

    $onUpload = '';
    if(!$multiple){
      $onUpload = "$('#{$id}').val(response.filename);";
    }

    $js = <<<END

<style>
.uploader_preview__item{
  display:inline-block;
  margin:5px 10px 5px 0;
  position:relative;
  vertical-align: top;
  border: none;
}
.uploader_preview__item_close{
  font-size: 31px;
  position: absolute;
  top: 0px;
  right: 0px;
  cursor: pointer;
  padding: 5px;
  color: #FF0000;
  background-color: rgba(255,255,255,0.7);
  display: none;
}
.uploader_preview__item:hover .uploader_preview__item_close{
  display: inline-block;
}
</style>
<script type="text/javascript">

  var jcropKey = '{$jcrop_key}';

var liteUploader_updateVal_{$id} = function(){

  {$emulate_script}

  var val = [];

  $('#{$id}__uploader_preview').find('.uploader_preview__item').each(function(k, v){
    var input_text = $(v).find('input');
    val.push($(v).data('val') + (input_text.length ? ':' + input_text.val() : ''));
  });

  $('#{$id}').val(val.join(';'));
};
$(document).ready(function() {
  $(function() {

  if(decodeURI(document.location).indexOf('/arm/') != -1){
    $( "#{$id}__uploader_preview" ).sortable();
    $( "#{$id}__uploader_preview" ).disableSelection();
  }

  });
  $("#project_image___uploader_preview").on( "sortstop", function( event, ui ) {
    liteUploader_updateVal_{$id}();
  } );
  var liteUploader_overlay = null;
  $('#{$id}__uploader').liteUploader({
    singleFileUploads: true,
    script: sf_prefix + '{$script}',
    rules: {
      allowedFileTypes: '{$allowedFileTypes}',
      maxSize: {$size}
    }
  })
  .on('lu:errors', function (e, errors) {
    var isErrors = false;
    var errors_a = [];
    $.each(errors, function (i, error) {
      if (errors.errors.length > 0) {
        isErrors = true;
        $.each(errors.errors, function (i, errorInfo) {
          if(errorInfo.type == 'type')
          errors_a.push('Неверный формат файла');
        });
      }
    });
    if (isErrors) {
      alert('Ошибка: ' + errors_a.join('\\n'));
    }
  })
  .on('lu:before', function (e, files) {
    $('.progress_bar_and_file_ct__{$id} .progress_bar_and_file_ct').addClass('progress_bar_and_file_ct_progress_bar');
  })
  .on('lu:progress', function (e, percentage) {
    $('.progress_bar_and_file_ct__{$id} .progress_bar_ct_bar_info').html('Загрузка');
    $('.progress_bar_and_file_ct__{$id} .progress_bar_ct_bar_w').css('width', percentage + '%');
    $('.progress_bar_and_file_ct__{$id} .progress_bar_ct_bar_percent').html(percentage + '%');
  })
  .on('lu:success', function (e, response) {
    var response = $.parseJSON(response);
    if(response.state == 'success'){

      if(response.preview64){
        response.preview = '<img style="border:1px solid #E5E5E5;" src="data:image/jpeg;base64,' + response.preview64 + '" />';
      }
      if(response.filename){
        var fileTypeCheck = response.filename.substr(-4);
        if(fileTypeCheck != '.jpg' && fileTypeCheck != '.png'){
          var div = '<div class="uploader_preview__item test_item" data-val="' + response.filename + '"><span title="Удалить файл" class="uploader_preview__item_close" onclick="$(this).parent().remove();liteUploader_updateVal_{$id}(event);">×</span><div class="document_file_icon"></div></div>';
        }else{
          if(response.preview){

END;

    if($this->getOption('jcrop'))
    {
      $js .= "jCrop.crop(response.filename, " . $min_width . ", " . $min_height . ");";
    }

    $js .= <<<END

            var div = '<div class="uploader_preview__item test_item" data-val="' + response.filename + '"><span title="Удалить файл" class="uploader_preview__item_close" onclick="$(this).parent().remove();liteUploader_updateVal_{$id}(event);">×</span>' + response.preview + '{$jInput}</div>';
          }
        }
        $('#{$id}__uploader_preview').{$jMethod}(div);
      }
      liteUploader_updateVal_{$id}($('.test_item'));
      $('fieldset input').each(function(){
        if($(this).prop('name').indexOf('[title]') != -1 && $(this).val() == ''){
          $(this).val(response.name);
          return false;
        }
      });
      $('.progress_bar_and_file_ct__{$id} .progress_bar_and_file_ct').removeClass('progress_bar_and_file_ct_progress_bar');
    }
    else {
      alert('Ошибка: ' + response.errorTxt);
    }
  });
  $('#{$id}__uploader').change(function () {
    $(this).data('liteUploader').startUpload();
  });
  {$add_js}
});
</script>
END;

    return $html . $js;
  }
}
