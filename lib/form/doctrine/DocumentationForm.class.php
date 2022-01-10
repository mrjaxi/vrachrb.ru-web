<?php

/**
 * Documentation form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DocumentationForm extends BaseDocumentationForm
{
  public function configure()
  {
    $this->useFields(array('title', 'file', 'order_field'));

    $this->widgetSchema['file'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'application/x-download,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'), array('required' => true));
  }
}
