<?php
class sfWidgetFormPhotoUpload extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('size', array('width' => 200, 'height' => 200));
    $this->addOption('multiple', false);
    $this->addOption('url', '/arm/page/noname_upload');
    $this->addOption('key', '');
    $this->addOption('JSdone', 'function(){}');
    $this->addOption('JSinit', 'function(){}');
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = str_replace(array('[', ']'), '_', $name);
    $size = $this->getOption('size');
    $html = '<div style="display:inline-block;position:relative;overflow:hidden;width:' . $size['width'] . 'px;height:' . $size['height'] . 'px;" class="widget_form_photo_upload no_image" id="' . $id . 'widget_form_photo_upload">
          <input id="' . $id . 'fileupload" data-url="' . $this->getOption('url') . '" type="file" name="file" style="cursor:pointer;opacity:0;float:right;z-index:11;position:relative;height:' . $size['height'] . 'px;font-size:' . $size['height'] . 'px;padding:0;margin:0;">
          <div class="uploader_messages" style="position:absolute;z-index:10;width:inherit;top:50%;text-align:center;margin-top:-9px;font-size:10px;">Загрузить</div>
          <input type="hidden" name="' . $name . '" value="' . $value . '" />
        </div>';
    $js = '<script type="text/javascript">';
    $js .= '$(document).ready(function(){init_imageuploader(\'' . $id . '\', {width: ' . $size['width'] . ', height: ' . $size['width'] . ', url: \'' . $this->getOption('url')  . '\', key: \'' . $this->getOption('key')  . '\'}, ' . $this->getOption('JSdone') . ', ' . $this->getOption('JSinit') . ');';
    $js .= '});</script>';
    return $html . $js;
  }
  public function getJavaScripts()
  {
    return array(
      'jquery.ui.widget.js',
      'jquery.iframe-transport.js',
      'jquery.fileupload.js',
      'imageuploader_logic.js',
    );
  }
}
