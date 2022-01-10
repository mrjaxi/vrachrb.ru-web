  protected function addSortQuery($query)
  {
    if (array(null, null) == ($sort = $this->getSort()))
    {
      return;
    }

    if (!in_array(strtolower($sort[1]), array('asc', 'desc')))
    {
      $sort[1] = 'asc';
    }

    $query->addOrderBy($sort[0] . ' ' . $sort[1]);
  }

  protected function getSort()
  {
    if (null !== $sort = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module'))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (null !== $sort[0] && null === $sort[1])
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.sort', $sort, 'admin_module');
  }
  
  protected function addSearchQuery($query)
  {
    if ($search = $this->getSearch())
    {
      $query->andWhere("CONCAT_WS(' ', " . $this->configuration->getSearchFields() . ") LIKE '%" . $search . "%'");
    }

  }
  
  protected function addTabQuery($query)
  {
    $current_tab = $this->tabs[$this->getTab()];
    if(isset($current_tab['where']))
    {
      $query->andWhere($current_tab['where']);
    }
    if(isset($current_tab['inner_join']))
    {
      $query->innerJoin($current_tab['inner_join']);
    }

  }
  
  protected function addCheckedQuery($query)
  {
    if ($checked = $this->getChecked())
    {
      $query->orWhereIn("id", explode(':', $checked));
    }

  }

  protected function setSearch($search)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.search', $search, 'admin_module');
  }
  
  protected function setChecked($checked)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.checked', $checked, 'admin_module');
  }

  protected function getSearch()
  {
    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.search', false, 'admin_module');
  }
  
  protected function getChecked()
  {
    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.checked', false, 'admin_module');
  }
  
  protected function isValidSortColumn($column)
  {
    return Doctrine::getTable('<?php echo $this->getModelClass() ?>')->hasColumn($column);
  }
