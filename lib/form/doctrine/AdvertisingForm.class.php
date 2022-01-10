<?php

/**
 * Advertising form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AdvertisingForm extends BaseAdvertisingForm
{
  public function configure()
  {
    $this->useFields(array('title', 'link', 'photo', 'is_activated'));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=advertising', 'multiple' => false), array('required' => true));
  }
}
