<?php
class sfWidgetFormFilterTab extends sfWidgetFormChoiceBase
{

  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('sf_filter_tab', true);
    parent::configure($options, $attributes);
    
  }

  public function setIdFormat($format)
  {
    $this->options['renderer_options']['id_format'] = $format;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return '<div class="sf_filter_tab"></div>';
  }

  public function getRenderer()
  {
    if ($this->getOption('renderer'))
    {
      return $this->getOption('renderer');
    }

    //return new sfWidgetFormSelectRadio(array_merge(array('choices' => new sfCallable(array($this, 'getChoices'))), $this->options['renderer_options']), array('class' => 'sf_filter_tab'));
  }
}
