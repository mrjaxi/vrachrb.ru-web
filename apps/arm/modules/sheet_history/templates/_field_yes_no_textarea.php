<?php
$options = unserialize($field->getFieldOptions(ESC_RAW));
if(isset($edit))
{
  echo '<label><input type="radio" disabled="true" />Нет</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" disabled="true" checked="checked" />Да</label><i class="br5"></i>';
  echo '<textarea style="width:70%;resize:none;" data-option="rows:rows;placeholder:val()"' . 
    (isset($options['rows']) && $options['rows'] != '' ? ' rows="' . $options['rows'] . '"' : '') . 
    ' class="sf_sheet_history_resizeable">' .
    (isset($options['placeholder']) && $options['placeholder'] != '' ? $options['placeholder'] : '') . 
    '</textarea>'
  ;
}
else
{
  echo '<label><input class="yes_no_input" onclick="if($(this).is(\':checked\')){$(this).parent().parent().find(\'.sheet_field__display\').hide();}" type="radio" checked="checked" name="sheet_field[' . $field->getId() . '][bool]" value="Нет" />Нет</label>&nbsp;&nbsp;&nbsp;<label>';
  echo '<input class="yes_input" type="radio" name="sheet_field[' . $field->getId() . '][bool]" onclick="if($(this).is(\':checked\')){$(this).parent().parent().find(\'.sheet_field__display\').show();}" value="Да" />Да</label>';
  echo '<i class="br5"></i>';
  echo '<div class="sheet_field__display" style="display: none;"><i class="br5"></i>';
    echo '<textarea name="sheet_field[' . $field->getId() . '][val]" style="width:70%;resize:vertical;" data-option="rows:rows;placeholder:val()"' .
      (isset($options['rows']) && $options['rows'] != '' ? ' rows="' . $options['rows'] . '"' : '') .
      ' class="sf_sheet_history_resizeable">' .
      (isset($options['placeholder']) && $options['placeholder'] != '' ? $options['placeholder'] : '') .
      '</textarea>'
    ;
  echo '</div>';
}
?>