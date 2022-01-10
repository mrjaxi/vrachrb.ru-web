<?php

/**
 * Certificate form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CertificateForm extends BaseCertificateForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'name', 'photo'));

    $specialists = Doctrine::getTable('Specialist')
      ->createQuery('s')
      ->innerJoin('s.User u')
      ->orderBy('u.second_name ASC')
      ->execute();

    $arr = array();

    foreach($specialists as $specialist)
    {
      $arr[$specialist->getId()] = $specialist->getUser()->getSecondName() . ' ' . $specialist->getUser()->getFirstName();
    }

    $this->widgetSchema['specialist_id'] = new sfWidgetFormChoice(array('choices' => $arr), array('class' => 'chosen'));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=certificate', 'multiple' => false), array('required' => true));

    $this->widgetSchema['name'] = new sfWidgetFormInputText(array(), array('required' => true, 'size' => 100));
  }
}
