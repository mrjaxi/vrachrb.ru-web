<?php

require_once dirname(__FILE__).'/../lib/feedbackGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/feedbackGeneratorHelper.class.php';

/**
 * feedback actions.
 *
 * @package    sf
 * @subpackage feedback
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class feedbackActions extends autoFeedbackActions
{

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Feedback')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Feedback')->$tableMethod($query);
    }

    $query->leftJoin('r.User u');

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
