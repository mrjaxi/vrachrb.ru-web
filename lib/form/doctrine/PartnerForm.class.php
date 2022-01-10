<?php

/**
 * Partner form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PartnerForm extends BasePartnerForm
{
  public function configure()
  {
    $this->useFields(array('title', 'logo', 'body', 'link', 'order_field'));

    $this->widgetSchema['logo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=partner', 'multiple' => false), array('required' => true));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => '6'));
  }
}
