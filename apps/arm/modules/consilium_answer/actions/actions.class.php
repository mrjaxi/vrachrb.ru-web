<?php

require_once dirname(__FILE__).'/../lib/consilium_answerGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/consilium_answerGeneratorHelper.class.php';

/**
 * consilium_answer actions.
 *
 * @package    sf
 * @subpackage consilium_answer
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class consilium_answerActions extends autoConsilium_answerActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();

    if ($request->getParameter('id'))
    {
      $this->form->setDefault('consilium_id', $request->getParameter('id'));
    }

    $this->consilium_answer = $this->form->getObject();
  }

}
