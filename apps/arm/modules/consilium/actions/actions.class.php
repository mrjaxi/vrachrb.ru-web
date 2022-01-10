<?php

require_once dirname(__FILE__).'/../lib/consiliumGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/consiliumGeneratorHelper.class.php';

/**
 * consilium actions.
 *
 * @package    sf
 * @subpackage consilium
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class consiliumActions extends autoConsiliumActions
{
  public function executeEdit(sfWebRequest $request)
  {
    $request->setParameter('return', $_SERVER['PATH_PREFIX'] . '/consilium');
    $this->consilium = Doctrine::getTable('Consilium')
      ->createQuery('c')
      ->leftJoin('c.Consilium_answer ca')
      ->leftJoin('ca.Specialist s')
      ->leftJoin('s.User u')
      ->where('c.id = ?', $request->getParameter('id'))
      ->fetchOne()
    ;
    $this->form = $this->configuration->getForm($this->consilium);
  }


  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Consilium')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Consilium')->$tableMethod($query);
    }

    $query->leftJoin('r.Question q');

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
