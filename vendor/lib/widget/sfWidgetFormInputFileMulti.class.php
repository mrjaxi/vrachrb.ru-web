<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormInputFileMulti represents an upload HTML input tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormInputFile.class.php 9046 2008-05-19 08:13:51Z FabianLange $
 */
class sfWidgetFormInputFileMulti extends sfWidgetFormInput
{
  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('type', 'file');
    $this->setOption('needs_multipart', true);
    $this->addOption('path', '');
    $this->addOption('name', '');
    $this->addOption('is_new', true);
    $this->addOption('is_new_text', '');
    $this->addOption('collection', '');
    $this->addOption('url', '');
    $this->addOption('container', '');
    $this->addOption('type_filter', "'Изображения (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png'");
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if($this->getOption('is_new'))
    {
      return '<div class="multiUpload">' . $this->getOption('is_new_text') . '</div>';
    }
    $add =  $this->getOption('collection') != '' ? '<div class="sfWidgetFormInputFileMultiCollection">' . $this->getOption('collection') . '</div>' : '';
    return '<div id="' . $name . '-multiupload-status" container="' . $this->getOption('container') . '" url="' . $this->getOption('url') . '" path="' . $this->getOption('path') . '" name="' . $this->getOption('name') . '" class="multiUpload" type_filter="' . $this->getOption('type_filter') . '">
      ' . $add . '
      <p>
        <a href="" class="devNull" id="' . $name . '-multiupload-browse">Выбрать файлы для загрузки</a> |
        <a href="" class="devNull" id="' . $name . '-multiupload-upload">Начать загрузку</a>
      </p>
      <div>
        <strong class="overall-title"></strong><br />
        <img src="/images/assets/progress-bar/bar.gif" class="progress overall-progress" />
      </div>
      <div>
        <strong class="current-title"></strong><br />
        <img src="/images/assets/progress-bar/bar.gif" class="progress current-progress" />
      </div>
      <div class="current-text"></div>
      <ul style="padding-top:5px" id="' . $name . '-multiupload-list" class="list"></ul><div><a href="" class="devNull" id="' . $name . '-multiupload-clear">Очистить список</a></div></div>';
  }
}
