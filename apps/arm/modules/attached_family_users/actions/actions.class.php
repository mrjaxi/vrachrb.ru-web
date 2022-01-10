<?php

require_once dirname(__FILE__).'/../lib/attached_family_usersGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/attached_family_usersGeneratorHelper.class.php';

/**
 * attached_family_users actions.
 *
 * @package    sf
 * @subpackage attached_family_users
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class attached_family_usersActions extends autoAttached_family_usersActions
{
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable("Attached_family_users")
      ->createQuery("afu");

    if ($tableMethod)
    {
      $query = Doctrine::getTable("Attached_family_users")->$tableMethod($query);
    }

    $query->leftJoin("afu.User u");
    $query->leftJoin("afu.UserAbout ua");
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

    $this->addSearchQuery($query);


    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();

    return $query;
  }
}
