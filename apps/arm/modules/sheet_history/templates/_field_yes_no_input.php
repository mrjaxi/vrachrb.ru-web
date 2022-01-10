<?php
$options = unserialize($field->getFieldOptions(ESC_RAW));
if(isset($edit))
{
  echo '<label><input type="radio" disabled="true" />Нет</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" disabled="true" checked="checked" />Да</label><i class="br5"></i>';
  echo '<input data-option="size:size;placeholder:val()"' .
    (isset($options['size']) && $options['size'] != '' ? ' size="' . $options['size'] . '"' : '') . 
    (isset($options['placeholder']) && $options['placeholder'] != '' ? ' value="' . $options['placeholder'] . '"' : '') .
    ' type="text" class="sf_sheet_history_resizeable" />&nbsp;';
  echo '<input data-option="unit:val()"' . (isset($options['unit']) && $options['unit'] != '' ? ' value="' . $options['unit'] . '"' : '') . ' type="text" size="10" placeholder="Ед. измерения" />';
}
else
{
  echo '<label><input class="yes_no_input" onclick="if($(this).is(\':checked\')){$(this).parent().parent().find(\'.sheet_field__display\').hide();}" type="radio" checked="checked" value="Нет" name="sheet_field[' . $field->getId() . '][bool]" />Нет</label>';
  echo '&nbsp;&nbsp;&nbsp;';
  echo '<label><input class="yes_input" onclick="if($(this).is(\':checked\')){$(this).parent().parent().find(\'.sheet_field__display\').show();}" type="radio" name="sheet_field[' . $field->getId() . '][bool]" value="Да" />Да</label>';
  echo '<div style="display:none" class="sheet_field__display">';
  echo '<i class="br5"></i>';
  echo '<input name="sheet_field[' . $field->getId() . '][val]"' . 
    (isset($options['size']) && $options['size'] != '' ? ' size="' . $options['size'] . '"' : '') . 
    (isset($options['placeholder']) && $options['placeholder'] != '' ? ' placeholder="' . $options['placeholder'] . '"' : '') .
    ' type="text" />';
  if(isset($options['unit']))
  {
    echo '&nbsp;' . $options['unit'];
  }
  echo '</div>';
}
?>