<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 22.07.2015
 * Time: 15:05
 */

class cwWidgetFormPhotoUpload extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('size', array('width' => 200, 'height' => 200));
    $this->addOption('multiple', false);
    $this->addOption('pseudo_text', 'Изображение');
    $this->addOption('url', '/arm/page/noname_upload');
    $this->addOption('key', '');
    $this->addOption('JSdone', 'function(){}');
    $this->addOption('JSinit', 'function(){}');
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $name_str = str_replace(array('[', ']'), '_', $name);
    $html = '';
    $html .= '<div class="lite_uploader_photos_wrap">';
      $html .= '<div class="pseudo_button lui_pseudo pseudo_button_file_wrapper only_root_button" data-url="' . $this->getOption('url') . '?key=' . $this->getOption('key') . '" onclick="photo.upload($(this), event);" style="position:relative;z-index:1000;vertical-align:top;">';
      $html .= '<input accept="application/pdf,image/png,image/jpeg" type="file" class="pseudo_button_file cw_lite_uploader" name="file[]" id="sevent_poster___uploader"><b>Загрузить</b>';
      $html .= '</div>';
      $html .= '<div class="my_delete_btn_arm  my_delete_btn_arm_visible" onclick="photo.remove($(this), event);">Удалить</div>';
      $html .= '<div class="lite_uploader__preloader"></div>';

      $html .= '<div class="lite_uploader_photos" data-name="'  . $name . '">';

        $html .= '<div class="lite_uploader_photo lite_uploader_photo_partners">';
          $html .= '<img src="'. ($value ? '/u/i/' . str_replace('.', '-S.', $value) : '/i/n.gif') . '" >';
          $html .= '<input type="hidden" name="'  . $name . '" value="' . $value . '">';
        $html .= '</div>';

      $html .= '</div>';
    $html .= '</div>';
    return $html;
  }
}
?>

