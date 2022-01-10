<?php

require_once dirname(__FILE__).'/../lib/analysisGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/analysisGeneratorHelper.class.php';

/**
 * analysis actions.
 *
 * @package    sf
 * @subpackage analysis
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class analysisActions extends autoAnalysisActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable("Analysis")
      ->createQuery("a");

    if ($tableMethod)
    {
      $query = Doctrine::getTable("Analysis")->$tableMethod($query);
    }

    $query->leftJoin("a.User u");
    $query->leftJoin("a.Analysis_type at");
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();

    return $query;
  }
}
