<?php

require_once dirname(__FILE__).'/../lib/articleGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/articleGeneratorHelper.class.php';

/**
 * article actions.
 *
 * @package    sf
 * @subpackage article
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends autoArticleActions
{


  public function executeEdit(sfWebRequest $request)
  {
    $request->setParameter('return', $_SERVER['PATH_PREFIX'] . '/article');
    $this->article = Doctrine::getTable('Article')
      ->createQuery('a')
      ->leftJoin('a.Comment c')
      ->leftJoin('c.User u')
      ->where('a.id = ?', $request->getParameter('id'))
      ->fetchOne();
    $this->form = $this->configuration->getForm($this->article);
  }

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Article')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Article')->$tableMethod($query);
    }

    $query->leftJoin('r.Specialist s');
    $query->leftJoin('s.User u');

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);

    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
  protected function isValidSortColumn($column)
  {
    return true;
  }
}
