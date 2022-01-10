<?php

/**
 * QuestionSpecialist form base class.
 *
 * @method QuestionSpecialist getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuestionSpecialistForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'question_id'   => new sfWidgetFormInputHidden(array(), array()),
      'specialist_id' => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'question_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'question_id', 'required' => false)),
      'specialist_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'specialist_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('question_specialist[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuestionSpecialist';
  }

}
