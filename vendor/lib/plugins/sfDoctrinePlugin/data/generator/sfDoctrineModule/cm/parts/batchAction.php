  public function executeBatch(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();

    if (!$ids = $request->getParameter('ids'))
    {
      $this->getUser()->setFlash('error', 'You must at least select one item.');

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }

    if (!$action = $request->getParameter('batch_action'))
    {
      $this->getUser()->setFlash('error', 'You must select an action to execute on the selected items.');

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }

    if (!method_exists($this, $method = 'execute'.ucfirst($action)))
    {
      throw new InvalidArgumentException(sprintf('You must create a "%s" method for action "%s"', $method, $action));
    }

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($action)))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $validator = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $this->getModelClass() ?>'));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // execute batch
      $ret = $this->$method($request);
      if($ret === false)
      {
        return sfView::NONE;
      }
    }
    catch (sfValidatorError $e)
    {
      $this->getUser()->setFlash('error', 'A problem occurs when deleting the selected items as some items do not exist anymore.');
    }

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }

  protected function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $records = Doctrine_Query::create()
      ->from('<?php echo $this->getModelClass() ?>')
      ->whereIn('<?php echo $this->getPrimaryKeys(true) ?>', $ids)
      ->execute();

    foreach ($records as $record)
    {
      $record->delete();
    }

    $this->getUser()->setFlash('notice', 'The selected items have been deleted successfully.');
    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
  
  protected function executeBatchMerge(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
    
    $primary = Doctrine::getTable(<?php echo $this->getModelClass() ?>)->find($request->getParameter('batch_primary_id'));
    
    $records = Doctrine_Query::create()
      ->from('<?php echo $this->getModelClass() ?>')
      ->whereIn('<?php echo $this->getPrimaryKeys(true) ?>', $ids)
      ->execute();
    
    foreach ($records as $record)
    {
      if($primary->getPrimaryKey() == $record->getPrimaryKey())
      {
        continue;
      }
      foreach($record->getTable()->getRelations() as $relation_name => $relation)
      {
        if($relation['type'] == 1)
        {
          $method = 'get' . $relation_name;
          foreach($record->$method() as $child)
          {
            $child_method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $relation['foreign'])));
            $child->$child_method($primary->getPrimaryKey());
            $child->save();
          }
        }
      }
      $record->delete();
    }


    
    $this->getUser()->setFlash('notice', 'Выделенные объекты успешно объеденены.');
    $this->redirect($request->getReferer());
  }
