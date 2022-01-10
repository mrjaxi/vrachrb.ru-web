<?php

require_once dirname(__FILE__).'/../lib/promptGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/promptGeneratorHelper.class.php';

/**
 * prompt actions.
 *
 * @package    sf
 * @subpackage prompt
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promptActions extends autoPromptActions
{
  public function executeEdit(sfWebRequest $request)
  {
    $request->setParameter('return', $_SERVER['PATH_PREFIX'] . '/prompt');
    $this->prompt = Doctrine::getTable('Prompt')
      ->createQuery('p')
      ->leftJoin('p.Comment c')
      ->leftJoin('c.User u')
      ->where('p.id = ?', $request->getParameter('id'))
      ->fetchOne()
    ;
    $this->form = $this->configuration->getForm($this->prompt);
  }

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Prompt')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Prompt')->$tableMethod($query);
    }

    $query->leftJoin('r.Specialist s');
    $query->leftJoin('s.User u');

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);



    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }



  protected function isValidSortColumn($column)
  {
    return true;
  }
}
