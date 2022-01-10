<?php

require_once dirname(__FILE__).'/../lib/answerGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/answerGeneratorHelper.class.php';

/**
 * answer actions.
 *
 * @package    sf
 * @subpackage answer
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class answerActions extends autoAnswerActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();

    if ($request->getParameter('id'))
    {
      $this->form->setDefault('question_id', $request->getParameter('id'));
    }

    $this->answer = $this->form->getObject();
  }
}
