[?php
$checked = $sf_request->hasParameter('checked') ? in_array($<?php echo $this->getSingularName() ?>->getPrimaryKey(), explode(':', $sf_request->getParameter('checked'))) : false;
?]
<td class="sf_admin_batch_checkbox__td">
  <input type="checkbox" name="ids[]"[?php echo $checked ? ' checked="checked"' : '';?] value="[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]" class="sf_admin_batch_checkbox" />
</td>
