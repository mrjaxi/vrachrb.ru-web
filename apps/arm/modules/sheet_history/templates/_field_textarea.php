<?php
$options = unserialize($field->getFieldOptions(ESC_RAW));
if(isset($edit))
{
  echo '<textarea style="width:70%;resize:none;" data-option="rows:rows;placeholder:val()"' . 
    (isset($options['rows']) && $options['rows'] != '' ? ' rows="' . $options['rows'] . '"' : '') . 
    ' class="sf_sheet_history_resizeable">' .
    (isset($options['placeholder']) && $options['placeholder'] != '' ? $options['placeholder'] : '') . 
    '</textarea>'
    ;
}
else
{
  echo '<textarea name="sheet_field[' . $field->getId() . '][val]" style="width:70%;resize:vertical;min-height:70px;"' .
    (isset($options['rows']) && $options['rows'] != '' ? ' rows="' . $options['rows'] . '"' : '') .
    ' class="sf_sheet_history_resizeable">' .
    (isset($options['placeholder']) && $options['placeholder'] != '' ? $options['placeholder'] : '') .
    '</textarea>'
  ;
}
?>