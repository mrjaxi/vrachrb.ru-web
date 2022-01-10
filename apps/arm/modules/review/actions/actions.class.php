<?php

require_once dirname(__FILE__).'/../lib/reviewGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/reviewGeneratorHelper.class.php';

/**
 * review actions.
 *
 * @package    sf
 * @subpackage review
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reviewActions extends autoReviewActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Review')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Review')->$tableMethod($query);
    }

    $query->leftJoin('r.Specialist s')
      ->leftJoin('s.User u')
      ->leftJoin('r.Question q')
    ;

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
