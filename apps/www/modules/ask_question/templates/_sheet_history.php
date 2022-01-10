<?php
if($sheet_history)
{
  foreach($sheet_history->getSheetHistoryField() as $field)
  {
    echo '<li class="sheet_history__wrap ' . ($field['is_required'] ? 'is_required' : '') . '">';
    echo ($field['is_required'] ? '<b class="sheet_history__title">' . $field['title'] . '</b>' : '<span class="sheet_history__title">' . $field['title'] . '</span>');
    echo '<i class="br10"></i>';
    include_partial('sheet_history/field_' . $field['field_type'], array('field' => $field, 'no_change' => $no_change));
    echo '</li>';
  }
}
?>