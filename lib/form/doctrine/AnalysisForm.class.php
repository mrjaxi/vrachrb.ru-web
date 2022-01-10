<?php

/**
 * Analysis form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AnalysisForm extends BaseAnalysisForm
{
  public function configure()
  {
    $this->useFields(array('answer_id', 'user_id', 'analysis_type_id', 'photo', 'created_at'));

    $q_user = Doctrine::getTable('User')
      ->createQuery('u')
      ->leftJoin('u.Specialist s')
      ->groupBy('u.id')
      ->having('COUNT(s.id) = 0')
      ->orderBy('u.second_name ASC')
    ;

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'User', 'query' => $q_user, 'method' => 'getSFM'), array('class' => 'chosen', 'required' => true));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=analysis', 'multiple' => false), array('required' => true));
  }
}
