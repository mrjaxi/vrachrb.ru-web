<?php

/**
 * Certificate form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateCertificateForm extends BaseCertificateForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'name', 'photo'));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=certificate', 'multiple' => true), array('required' => true));

    $this->validatorSchema['name'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
