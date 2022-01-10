<?php
class sfWidgetFormRTree extends sfWidgetForm
{
  protected $choices = array();
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('add_empty', false);
    $this->addOption('not_empty', false);
  }
  protected function rec($node, $level = 0, $group = false)
  {
    $children = $node->Children->getIterator();
    if($children && $children->count() > 0)
    {
      if($level == 0)
      {
        $group = $node->getTitle();
        $this->choices[$group] = array();
      }
      else
      {
        if($group && is_array($this->choices[$group]))
        {
          $this->choices[$group][$node->getId()] = str_repeat('', $level) . $node->getTitle();
        }
        else
        {
          $this->choices[$node->getId()] = str_repeat('', $level) . $node->getTitle();
        }
      }
      $level++;
      while($child = $children->current())
      {
        $this->rec($child, $level, $group);
        $children->next();
      }
    }
    else
    {
      if($group && is_array($this->choices[$group]))
      {
        $this->choices[$group][$node->getId()] = str_repeat('', $level) . $node->getTitle();
      }
      else
      {
        $this->choices[$node->getId()] = str_repeat('', $level) . $node->getTitle();
      }
    }
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if($this->getOption('add_empty'))
    {
      $this->choices[''] = $this->getOption('add_empty');
    }
    $roots = Doctrine_Query::create()
      ->select("a.*")
      ->from($this->getOption('model') . ' a')
      ->where('a.parent_id IS NULL')
      ->execute();
    foreach($roots as $root)
    {
      $this->rec($root, 0);
    }
    return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value, $this->choices))."\n", array_merge(array('name' => $name), $attributes));
  }
  protected function getOptionsForSelect($value, $choices)
  {
    $mainAttributes = $this->attributes;
    $this->attributes = array();
    if(!is_array($value) && strstr($value, ';') !== false)
    {
      $value = explode(';', $value);
    }
    if (!is_array($value))
    {
      $value = array($value);
    }

    $value = array_map('strval', array_values($value));
    $value_set = array_flip($value);

    $options = array();
    foreach ($choices as $key => $option)
    {
      if (is_array($option))
      {
        $options[] = $this->renderContentTag('optgroup', implode("\n", $this->getOptionsForSelect($value, $option)), array('label' => self::escapeOnce($key)));
      }
      else
      {
        $attributes = array('value' => self::escapeOnce($key));
        if (isset($value_set[strval($key)]))
        {
          $attributes['selected'] = 'selected';
        }

        $options[] = $this->renderContentTag('option', self::escapeOnce($option), $attributes);
      }
    }

    $this->attributes = $mainAttributes;

    return $options;
  }
}
