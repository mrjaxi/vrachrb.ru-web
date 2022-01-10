  protected function getPager(sfWebRequest $request)
  {
    $pager = $this->configuration->getPager('<?php echo $this->getModelClass() ?>');
    $pager->setQuery($this->buildQuery());
    $pager->setPage($this->getPage());
    if($request->hasParameter('export'))
    {
      $pager->setMaxPerPage(100000);
    }
    $pager->init();
    


    return $pager;
  }

  protected function setPage($page)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.page', $page, 'admin_module');
  }

  protected function getPage()
  {
    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.page', 1, 'admin_module');
  }
<?php if ($this->configuration->hasTabs()): ?>
  protected function setTab($page)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.tab', $page, 'admin_module');
  }

  protected function getTab()
  {
    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.tab', 0, 'admin_module');
  }
<?php endif; ?>
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
<?php if ($this->configuration->hasFilterForm()): ?>
    if (null === $this->filters)
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $this->filters->setTableMethod($tableMethod);

    $query = $this->filters->buildQuery($this->getFilters());
<?php else: ?>
    $query = Doctrine::getTable('<?php echo $this->getModelClass() ?>')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->$tableMethod($query);
    }
<?php endif; ?>

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);

<?php if ($this->configuration->hasSearch()): ?>
    
    $this->addSearchQuery($query);
    
<?php endif; ?>

<?php if ($this->configuration->hasTabs()): ?>
    
    $this->tabs = $this->configuration->getTabs();
    $this->current_tab = $this->getTab();
    $this->addTabQuery($query);
    
<?php endif; ?>

    $this->addCheckedQuery($query);
    
    $query = $event->getReturnValue();


    return $query;
  }
