<?php
class sfWidgetFormPhotosUpload extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('url', '/arm/page/noname_upload');
    $this->addOption('key', '');
    $this->addOption('files_name', 'projects');
    $this->addOption('JSdone', 'function(){}');
    $this->addOption('JSinit', 'function(){}');
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = str_replace(array('[', ']'), '_', $name);
    $size = $this->getOption('size');
    $html = '';
    $html .= '<i class="br3"></i>';
    $html .= '<div class="pseudo_button lui_pseudo pseudo_button_file_wrapper only_root_button" style="position:relative;overflow:hidden">';
    $html .= '<input id="' . $id . 'fileupload" data-url="' . $this->getOption('url') . '" type="file" multiple="true" name="file" style="cursor:pointer;opacity:0;float:right;z-index:11;position:absolute;height:100px;font-size:100px;padding:0;margin:0;left:-10px;top:-10px;"><span class="pseudo_button__txt">Загрузить файлы</span>';
    $html .= '</div>';
    $html .= '&nbsp;&nbsp;&nbsp;<i class="pseudo_button__filename" style="position:relative;top:1px">&nbsp;</i>';
    $html .= '<i class="br10"></i>';
    $html .= '<div class="lui_photos">';
    $html .= '</div>';
    $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $id . '" />';
    $js = '<script type="text/javascript">';
    $js .= '$(document).ready(function(){init_imagesuploader(\'' . $id . '\', {url: \'' . $this->getOption('url')  . '\', key: \'' . $this->getOption('key')  . '\'}, ' . $this->getOption('JSdone') . ', ' . $this->getOption('JSinit') . ', \''. $this->getOption('files_name') . '\');';
    $js .= '';
    $js .= '});</script>';
    return $html . $js;
  }
  public function getJavaScripts()
  {
    return array(
      'jquery.ui.widget.js',
      'jquery.iframe-transport.js',
      'jquery.fileupload.js',
      'imagesuploader_logic.js',
      'arm/jquery-ui-1.10.3.custom.min.js',
    );
  }
}
