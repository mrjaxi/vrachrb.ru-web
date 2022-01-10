<?php
$options = unserialize($field->getFieldOptions(ESC_RAW));
if(isset($edit))
{
  echo '<input data-option="size:size;placeholder:val()"' . 
    (isset($options['size']) && $options['size'] != '' ? ' size="' . $options['size'] . '"' : '') . 
    (isset($options['placeholder']) && $options['placeholder'] != '' ? ' value="' . $options['placeholder'] . '"' : '') . 
    ' type="text" class="sf_sheet_history_resizeable" />&nbsp;';
  echo '<input data-option="unit:val()"' . (isset($options['unit']) && $options['unit'] != '' ? ' value="' . $options['unit'] . '"' : '') . ' type="text" size="10" placeholder="Ед. измерения" />';
}
else
{
  echo '<input name="sheet_field[' . $field->getId() . '][val]"' . (isset($options['size']) && $options['size'] != '' ? ' size="' . $options['size'] . '"' : '') . ' type="text" />';
  if(isset($options['unit']))
  {
    echo '&nbsp;' . $options['unit'];
  }
}
?>