<?php

/**
 * News form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class NewsForm extends BaseNewsForm
{
  public function configure()
  {
    $this->useFields(array('title', 'body', 'photo', 'video', 'created_at'));

    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=news', 'multiple' => false, 'jcrop' => true), array('required' => true));
    $this->widgetSchema['video'] = new sfWidgetFormInputText(array('label' => 'Код видео', 'requared' => true), array('size' => '50'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => 20, 'class' => 'rich'));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorDoctrineUnique(array('model'=> 'News','column'=> 'title')),
      new sfValidatorDoctrineUnique(array('model'=> 'News','column'=> 'title_url')),
    )));
  }
}
