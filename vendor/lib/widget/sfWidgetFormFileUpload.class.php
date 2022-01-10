<?php
class sfWidgetFormFileUpload extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('multiple', false);
    $this->addOption('url', '/a/page/upload_file');
    $this->addOption('key', '');
    $this->addOption('exts', 'png|gif|jpg|jpeg');
    $this->addOption('JSdone', 'function(){}');
    $this->addOption('JSinit', 'function(){}');
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = str_replace(array('[', ']'), '_', $name);
    $size = $this->getOption('size');
    $html = '';

    
    $html .= '<i class="br3"></i>';
    $html .= '<div class="pseudo_button" style="position:relative;overflow:hidden">';
    $html .= '<input id="' . $id . 'fileupload" data-url="' . $this->getOption('url') . '" type="file" name="file" style="cursor:pointer;opacity:0;float:right;z-index:11;position:absolute;height:100px;font-size:100px;padding:0;margin:0;left:-10px;top:-10px;"><span class="pseudo_button__txt">Загрузить</span>';
    $html .= '</div>';
    $html .= '&nbsp;&nbsp;&nbsp;<i class="pseudo_button__filename" style="position:relative;top:1px">' . ($value ? $value : '&nbsp;') . '</i>';
    $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $id . '" />';
    
    $js = '<script type="text/javascript">';
    $js .= '$(document).ready(function(){
      init_fileuploader(\'' . $id . '\', {
          url: \'' . $this->getOption('url')  . '\',
        }, 
        \''. $this->getOption('exts') . '\',
        ' . $this->getOption('JSdone') . ', 
        ' . $this->getOption('JSinit') . ');';
    
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
      'fileuploader_logic.js',
    );
  }
}
