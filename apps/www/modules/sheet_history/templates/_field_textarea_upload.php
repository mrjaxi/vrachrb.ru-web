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
  echo '<i class="br5"></i><input data-option="file:type" type="file" disabled="true" />';
}
else
{
  echo '<textarea style="resize:vertical;min-height:120px;" name="sheet_field[' . $field->getId() . '][val]" id="" cols="60" rows="5"></textarea>';
  echo '<i class="br10"></i>';
  echo '<div class="upload_input__wrap">';
    echo '<div class="pc_chat__item__click_uploader" onclick="emulationUploaderClick(this, \'click\');return false;">Выберите файл</div>';
    echo '<input class="upload_input" type="hidden" name="sheet_field[' . $field->getId() . '][file]" value="" />';
    echo '<div class="textarea_upload_image"></div>';
  echo '</div>';
}
?>