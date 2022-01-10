<?php

require_once dirname(__FILE__).'/../lib/noticeGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/noticeGeneratorHelper.class.php';

/**
 * notice actions.
 *
 * @package    sf
 * @subpackage notice
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class noticeActions extends autoNoticeActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Notice')
      ->createQuery('n');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Notice')->$tableMethod($query);
    }

    $query->leftJoin('n.User u');
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
}
