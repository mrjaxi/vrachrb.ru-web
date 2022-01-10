<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDate represents a date widget.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormDate.class.php 24605 2009-11-30 21:20:19Z FabianLange $
 */
class sfWidgetFormDate extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * format:       The date format string (%month%/%day%/%year% by default)
   *  * years:        An array of years for the year select tag (optional)
   *                  Be careful that the keys must be the years, and the values what will be displayed to the user
   *  * months:       An array of months for the month select tag (optional)
   *  * days:         An array of days for the day select tag (optional)
   *  * can_be_empty: Whether the widget accept an empty value (true by default)
   *  * empty_values: An array of values to use for the empty value (empty string for year, month, and day by default)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $rusM = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'); 
    $this->addOption('format', '%day%%month%%year%');
    $this->addOption('days', parent::generateTwoCharsRange(1, 31));
    $this->addOption('months', $rusM);
    $years = range(date('Y') - 5, date('Y') + 5);
    $this->addOption('years', array_combine($years, $years));

    $this->addOption('can_be_empty', true);
    $this->addOption('empty_values', array('year' => '', 'month' => '', 'day' => ''));
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // convert value to an array
    $default = array('year' => null, 'month' => null, 'day' => null);
    if($value == null)
    {
      $value = false;
    }
    if (is_array($value))
    {
      $value = array_merge($default, $value);
    }
    else
    {
      $value = (string) $value == (string) (integer) $value ? (integer) $value : strtotime($value);
      if (false === $value)
      {
        $value = $default;
      }
      else
      {
        $value = array('year' => date('Y', $value), 'month' => date('n', $value), 'day' => date('j', $value));
      }
    }

    $date = array();
    $emptyValues = $this->getOption('empty_values');
    /*
    $date['%day%'] = $this->renderDayWidget($name.'[day]', $value['day'], array('choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['days']) + $this->getOption('days') : $this->getOption('days')), array_merge($this->attributes, $attributes));
    */
    $widget = new sfWidgetFormInputText(array(), array('maxlength' => 2, 'size' => '1', 'style' => 'text-align:center', 'placeholder' => 'дд', 'class' => 'required', 'data-error_before' => true, 'data-error_text' => 'Пожалуйста, правильно укажите день.')); 
    $date['%day%'] = $widget->render($name.'[day]', $value['day']);
    
    
    
    $date['%month%'] = $this->renderMonthWidget($name.'[month]', $value['month'], array('choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['month']) + $this->getOption('months') : $this->getOption('months')), array_merge($this->attributes, $attributes));
    
    /*
     $widget = new sfWidgetFormInputText(array(), array('maxlength' => 2, 'size' => '2', 'style' => 'text-align:center', 'placeholder' => 'мм', 'class' => 'required', 'data-error_before' => true, 'data-error_text' => 'Пожалуйста, правильно укажите месяц.')); 
    $date['%month%'] = $widget->render($name.'[month]', $value['month']);
    */
    
    
    /*
    $date['%year%'] = $this->renderYearWidget($name.'[year]', $value['year'], array('choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['year']) + $this->getOption('years') : $this->getOption('years')), array_merge($this->attributes, $attributes));
    */
    $widget = new sfWidgetFormInputText(array(), array('maxlength' => 4, 'size' => '2', 'style' => 'text-align:center', 'placeholder' => 'гггг', 'class' => 'required', 'data-error_text' => 'Пожалуйста, правильно укажите год.')); 
    $date['%year%'] = $widget->render($name.'[year]', $value['year']); 
  
    
    //$widget = new sfWidgetFormInputHidden(); 
    //$date['%month%'] = $widget->render($name.'[month]', $value['month']);
    
    //$widget = new sfWidgetFormInputHidden(); 
    //$date['%year%'] = $widget->render($name.'[year]', $value['year']); 
    
    $s = $value['month'];
    //$widget = new sfWidgetFormInput(array(), array('class' => 'calendarRender', 'readonly' => 'true'));
    //$date['%date_str%'] = $widget->render($name . '_', $value['day'] . ' ' . $rusM[$s - 1] . ' ' . $value['year']);

    return strtr($this->getOption('format'), $date);
  }

  /**
   * @param string $name
   * @param string $value
   * @param array $options
   * @param array $attributes
   * @return string rendered widget
   */
  protected function renderDayWidget($name, $value, $options, $attributes)
  {
    $widget = new sfWidgetFormInputText($options, $attributes);
    return $widget->render($name, $value);
  }

  /**
   * @param string $name
   * @param string $value
   * @param array $options
   * @param array $attributes
   * @return string rendered widget
   */
  protected function renderMonthWidget($name, $value, $options, $attributes)
  {
    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }

  /**
   * @param string $name
   * @param string $value
   * @param array $options
   * @param array $attributes
   * @return string rendered widget
   */
  protected function renderYearWidget($name, $value, $options, $attributes)
  {
    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }
}
