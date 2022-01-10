<?php

require_once dirname(__FILE__).'/../lib/questionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/questionGeneratorHelper.class.php';

/**
 * question actions.
 *
 * @package    sf
 * @subpackage question
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class questionActions extends autoQuestionActions
{
  public function executeEdit(sfWebRequest $request)
  {
    $request->setParameter('return', $_SERVER['PATH_PREFIX'] . '/question');

    $this->question = Doctrine::getTable('Question')
      ->createQuery('q')
      ->leftJoin('q.Answer a')
      ->leftJoin('a.User u')
      ->where('q.id = ?', $request->getParameter('id'))
      ->fetchOne()
    ;
    $this->form = $this->configuration->getForm($this->question);
  }

  protected function isValidSortColumn($column)
  {
    return true;
  }

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Question')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Question')->$tableMethod($query);
    }

    $query->leftJoin('r.User u');
    $query->leftJoin('r.UserAbout ua');
    $query->leftJoin('r.UserClosed uc');
    $query->leftJoin('r.Specialists s');
    $query->leftJoin('s.User su');
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
}
