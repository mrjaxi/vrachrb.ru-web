<?php

/**
 * Article form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ArticleForm extends BaseArticleForm
{
  public function configure()
  {
    $this->useFields(array('specialist_id', 'title', 'photo', 'video', 'description', 'body', 'is_activated', 'created_at'));

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
    $this->widgetSchema['video'] = new sfWidgetFormInputText(array('label' => 'Код видео', 'requared' => true), array('size' => '50'));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=article', 'multiple' => false, 'jcrop' => true), array('required' => true));

    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => '6'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => 20, 'class' => 'rich'));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model'=> 'Article','column'=> 'title')),
        new sfValidatorDoctrineUnique(array('model'=> 'Article','column'=> 'title_url')),
    )));
  }
}
