<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormChoice represents a choice widget.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormChoice.class.php 23994 2009-11-15 22:55:24Z bschussek $
 */
class sfWidgetFormChoice extends sfWidgetFormChoiceBase
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * choices:          An array of possible choices (required)
   *  * multiple:         true if the select tag must allow multiple selections
   *  * expanded:         true to display an expanded widget
   *                        if expanded is false, then the widget will be a select
   *                        if expanded is true and multiple is false, then the widget will be a list of radio
   *                        if expanded is true and multiple is true, then the widget will be a list of checkbox
   *  * renderer_class:   The class to use instead of the default ones
   *  * renderer_options: The options to pass to the renderer constructor
   *  * renderer:         A renderer widget (overrides the expanded and renderer_options options)
   *                      The choices option must be: new sfCallable($thisWidgetInstance, 'getChoices')
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('multiple', false);
    $this->addOption('expanded', false);
    $this->addOption('renderer_class', false);
    $this->addOption('renderer_options', array());
    $this->addOption('renderer', false);
    $this->addOption('child_rel', false);
    $this->addOption('child_map', array());
  }

  /**
   * Sets the format for HTML id attributes. This is made avaiable to the renderer,
   * as this widget does not render itself, but delegates to the renderer instead.
   *
   * @param string $format  The format string (must contain a %s for the id placeholder)
   *
   * @see sfWidgetForm
   */
  public function setIdFormat($format)
  {
    $this->options['renderer_options']['id_format'] = $format;
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('multiple'))
    {
      $attributes['is_multiple'] = 'multiple';

      if ('[]' != substr($name, -2))
      {
        $name .= '[]';
      }
    }

    if (!$this->getOption('renderer') && !$this->getOption('renderer_class') && $this->getOption('expanded'))
    {
      unset($attributes['multiple']);
    }
    
    $s = $this->getRenderer()->render($name, $value, $attributes, $errors);
    
    
    if($this->getOption('child_rel') && count($this->getOption('child_map')) > 0)
    {
      $modulename = explode('[', $name);
      $modulename = $modulename[0];
      $varname = str_replace(array('[', ']'), array('_', ''), $name);
      $_fn_map = json_encode($this->getOption('child_map'));
$s .= <<<SCRIPT
<script>
$(document).ready(function(){
var {$varname}_child_rel_options = {};
var {$varname}_fn_map = JSON.parse('{$_fn_map}');
var {$varname}_fn = function(select){
  var select_val = {$varname}_child_rel.val();

  {$varname}_child_rel.html('');
  var options = {$varname}_fn_map[$(select).val()] ? ({$varname}_fn_map[$(select).val()]).split(';') : [];
  if(options.length > 0){
    {$varname}_child_rel.append('<option value=""' + (select_val == '' ? ' selected="selected"' : '') + '></option>');
  }
  $.each(options, function(k, option_id){
    var option = {$varname}_child_rel_options[option_id];
    if(option){
      {$varname}_child_rel.append('<option value="' + option_id + '"' + (select_val == option_id ? ' selected="selected"' : '') + '>' + option + '</option>');
    }
  });
  cl();
  {$varname}_child_rel.css('display', options.length > 0 ? 'inline-block' : 'none');
};


var {$varname}_child_rel = $('#{$modulename}_{$this->getOption('child_rel')}');
if({$varname}_child_rel.length){
  {$varname}_child_rel.find('option').each(function(k, option){
    {$varname}_child_rel_options[$(option).val()] = $(option).html();
  });
  $('#{$varname}').change(function(){
    {$varname}_fn($(this));
  }).change();

}

});

</script>
SCRIPT;

    }
   
    
    return $s;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return $this->getRenderer()->getStylesheets();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return $this->getRenderer()->getJavaScripts();
  }

  public function getRenderer()
  {
    if ($this->getOption('renderer'))
    {
      return $this->getOption('renderer');
    }

    if (!$class = $this->getOption('renderer_class'))
    {
      $type = !$this->getOption('expanded') ? '' : ($this->getOption('multiple') ? 'checkbox' : 'radio');
      $class = sprintf('sfWidgetFormSelect%s', ucfirst($type));
    }

    return new $class(array_merge(array('choices' => new sfCallable(array($this, 'getChoices'))), $this->options['renderer_options']), $this->getAttributes());
  }
}
