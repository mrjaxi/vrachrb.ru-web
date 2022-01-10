<?php

/**
 * Message_error form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Message_errorForm extends BaseMessage_errorForm
{
  public function configure()
  {
    $this->useFields(array('body', 'photo', 'user_id', 'status', 'created_at'));

    $this->widgetSchema['user_id'] = new sfWidgetFormDoctrineChoice(array('add_empty' => '&mdash;', 'model' => 'User', 'method' => 'getSFM'), array('class' => 'chosen'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('style' => 'resize:vertical;min-height:100px;', 'rows' => 4, 'cols' => 80, 'required' => true));
    $this->widgetSchema['photo'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=message_error', 'multiple' => false), array('required' => false));
  }
}
