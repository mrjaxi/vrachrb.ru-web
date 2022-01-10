<?php

/**
 * Analysis form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateMultipleAnalysisForm extends BaseAnalysisForm
{
  public function configure()
  {
    $this->disableLocalCSRFProtection();

    $this->useFields(array('answer_id', 'user_id', 'analysis_type_id', 'photo'));
    unset($this['id']);

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=analysis', 'multiple' => true, 'emulateUpload' => true), array('required' => true));

    $this->validatorSchema['analysis_type_id'] = new sfValidatorInteger(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
    $this->validatorSchema['photo'] = new sfValidatorString(array(), array('required' => 'Обязательное поле.', 'invalid' => 'Не верный формат.'));
  }
}
