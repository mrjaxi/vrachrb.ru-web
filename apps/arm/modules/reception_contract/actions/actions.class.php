<?php

require_once dirname(__FILE__).'/../lib/reception_contractGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/reception_contractGeneratorHelper.class.php';

/**
 * reception_contract actions.
 *
 * @package    sf
 * @subpackage reception_contract
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reception_contractActions extends autoReception_contractActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Reception_contract')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Reception_contract')->$tableMethod($query);
    }

    $query->leftJoin('r.User u')
      ->leftJoin('r.Specialist s')
      ->leftJoin('s.User su')
      ->leftJoin('r.Question q')
    ;


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
