<?php

/**
 * Agreement form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AgreementForm extends BaseAgreementForm
{
  public function configure()
  {
    $this->useFields(array('title', 'description', 'body', 'in_documentation', 'created_at'));

    $this->widgetSchema['title'] = new sfWidgetFormInputText(array(), array('size' => 64));
    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array('cols' => 62));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 20, 'cols' => 120));
//    $this->widgetSchema['file'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'), array());
  }
}
