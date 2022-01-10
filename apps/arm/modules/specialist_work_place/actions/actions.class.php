<?php

require_once dirname(__FILE__).'/../lib/specialist_work_placeGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/specialist_work_placeGeneratorHelper.class.php';

/**
 * specialist_work_place actions.
 *
 * @package    sf
 * @subpackage specialist_work_place
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class specialist_work_placeActions extends autoSpecialist_work_placeActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable("Specialist_work_place")
      ->createQuery("swp");

    if ($tableMethod)
    {
      $query = Doctrine::getTable("Specialist_work_place")->$tableMethod($query);
    }

    $query->leftJoin("swp.Specialist s");
    $query->leftJoin("s.User u");
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();

    return $query;
  }
}
