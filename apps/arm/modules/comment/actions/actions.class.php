<?php

require_once dirname(__FILE__).'/../lib/commentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/commentGeneratorHelper.class.php';

/**
 * comment actions.
 *
 * @package    sf
 * @subpackage comment
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commentActions extends autoCommentActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();

    if ($request->getParameter('type') == 'prompt')
    {
      $this->form->setDefault('prompt_id', $request->getParameter('id'));
    }
    else
    {
      $this->form->setDefault('article_id', $request->getParameter('id'));
    }

    $this->comment = $this->form->getObject();
  }
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable("Comment")
      ->createQuery("c");

    if ($tableMethod)
    {
      $query = Doctrine::getTable("Comment")->$tableMethod($query);
    }
    $query->leftJoin('c.User u');
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
}
