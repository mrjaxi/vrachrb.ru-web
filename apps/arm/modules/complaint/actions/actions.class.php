<?php

require_once dirname(__FILE__).'/../lib/complaintGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/complaintGeneratorHelper.class.php';

/**
 * complaint actions.
 *
 * @package    sf
 * @subpackage complaint
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class complaintActions extends autoComplaintActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable("Complaint")
      ->createQuery("c");

    if ($tableMethod)
    {
      $query = Doctrine::getTable("Complaint")->$tableMethod($query);
    }

    $query->leftJoin("c.Question q");
    $query->leftJoin('c.Specialist s');
    $query->leftJoin('s.User u');
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
}
