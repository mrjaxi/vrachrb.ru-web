<?php

/**
 * Specialist form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SpecialistForm extends BaseSpecialistForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'specialty_id', 'live_reception', 'about', 'education', 'certificate'));
    
    $this->widgetSchema['about'] = new sfWidgetFormTextarea(array(), array('cols' => 70, 'rows' => 5, 'style' => 'resize:vertical;min-height:100px;'));
    $this->widgetSchema['education'] = new sfWidgetFormTextarea(array(), array('cols' => 70, 'rows' => 10, 'style' => 'resize:vertical;min-height:100px;', 'class' => 'rich'));
    $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['specialty_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'Specialty'), array('required' => true, 'class' => 'managed', 'data-managed_module' => 'specialty'));
    $this->widgetSchema['certificate'] = new sfWidgetFormInputFileUpload(array('allowedFileTypes' => 'image/png,image/jpeg', 'script' => '/uploader?key=certificate', 'multiple' => true,'with_text' => true), array('required' => true));

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorDoctrineUnique(array('model'=> 'Specialist','column'=> 'title_url')),
    )));

    $user_form = new UserSpecialistForm();
    if ($this->isNew() == false && $this->getObject()->getUserId() != '')
    {
      $user = Doctrine::getTable('User')->find($this->getObject()->getUserId());
      $user_form = new UserSpecialistForm($user);
    }

    $this->validatorSchema['user_id'] = new sfValidatorPass();

    $this->embedForm('user', $user_form);
  }
  protected function doSave($con = null)
  {
    $this->updateObjectEmbeddedForms($this->values);
    $this->saveEmbeddedForms();
    $user = $this->getEmbeddedForm('user')->getObject();

    $this->values['user_id'] = $user->getId();
    $this->values['user']['id'] = $user->getId();
    parent::doSave($con);
  }
}
