<?php
$options = unserialize($field->getFieldOptions(ESC_RAW));
if(isset($edit))
{
  echo '<label>';
  echo '<input type="checkbox" onclick="sf_sheet_choices_switch($(this));" data-option="multiple:checked"' .
   (isset($options['multiple']) && $options['multiple'] != '' ? ' checked="checked"' : '') . 
   ' />';
  echo 'Множественный выбор';
  echo '</label>';
  echo '<i class="br10"></i>';

  
  $choices = isset($options['choices']) && is_array($options['choices']) ? $options['choices'] : array('');
  
  echo '<div data-option="choices:inner"><button onclick="sf_sheet_choices_add($(this));return false;" class="bnt__add" style="position:absolute;left:67%">Добавить</button>';
  foreach($choices as $choice)
  {
    echo '<div class="choices_inner_div">';
    echo '<label class="radio_over_input"><input type="' . (isset($options['multiple']) && $options['multiple'] != '' ? 'checkbox' : 'radio') .  '" disabled="true" /></label><input value="' . $choice . '" type="text" style="width:65%;" />';
    echo '</div>';
  }
  echo '</div>';
}
else
{
  echo '<i class="br10"></i>';

  $choices = isset($options['choices']) && is_array($options['choices']) ? $options['choices'] : array('');

  foreach($choices as $choice_key => $choice)
  {
    echo '<i class="br5"></i>';
    echo '<div class="choices_inner_div">';
      echo '<label class="radio_over_input custom_input_label">';
        echo '<input class="custom_input" value="' . $choice . '" name="sheet_field[' . $field->getId() . '][choices][' . $choice_key . ']" type="' . (isset($options['multiple']) && $options['multiple'] != '' ? 'checkbox' : 'radio') .  '" />';
        echo '<span class="custom_input custom_input_checkbox"></span>' . $choice;
      echo '</label>';
    echo '</div>';
  }
}
?>