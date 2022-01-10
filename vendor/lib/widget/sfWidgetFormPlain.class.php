<?php
class sfWidgetFormPlain extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('html');
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return $this->getOption('html');
  }
}
