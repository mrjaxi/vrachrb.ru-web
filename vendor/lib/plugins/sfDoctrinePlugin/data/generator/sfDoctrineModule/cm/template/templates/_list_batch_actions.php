<?php
if ($listActions = $this->configuration->getValue('list.batch_actions'))
{
  echo '<td><div class="batch_actions_wrapper"><ul class="lui__list_actions">';
  echo '<b class="batch_action" id="list_checked"></b>';
  foreach ((array) $listActions as $action => $params)
  {
?>
[?php if ($sf_user->hasCredential('<?php echo $this->getModuleName() . '_' . $action;?>')): ?]
<?php
    echo '<li class="batch_action sf_admin_action_' . $params['class_suffix'] . '">';
    $script = $script_f = "\$('#batch_action_id').val('" . $action . "');\$('#batch_form').submit();";
    
    $script = $action == 'batchDelete' ? "if(confirm('Подтверждаете удаление?')){" . $script . "};" : $script;
    
    
    if($action == 'batchMerge')
    {
?>
<script>
var sf_batchMergeChecked = false;
var sf_batchMergeCheckRows = function(){
  $('.sf_admin_row').each(function(k, row){
    var chb = $(row).find('.batch_primary_id');
    if(chb.length > 0){
      if(chb.is(':checked')){
        $(row).css('text-decoration', 'none');
      } else {
        $(row).css('text-decoration', 'line-through');
      }
    }
  });
};
var sf_batchMerge = function(){
  if(sf_batchMergeChecked){
    <?php echo $script_f;?>
    return;
  }
  $('.sf_admin_row').each(function(k, row){
    var chb = $(row).find('.sf_admin_batch_checkbox');
    if(chb.is(':checked')){
      chb.css('visibility', 'hidden');
      chb.parent().find('.custom_input_checkbox').css('visibility', 'hidden');
      chb.parent().append('<label class="custom_input_label" style="position:absolute;left:9px"><input onclick="sf_batchMergeCheckRows()" class="batch_primary_id custom_input"' + (!sf_batchMergeChecked ? ' checked="checked"' : '') + ' type="radio" name="batch_primary_id" value="' + chb.val() + '" /><span class="custom_input custom_input_radio"></span></label>');
      sf_batchMergeChecked = true;
    } else {
      $(row).css('opacity', '0.2');
    }
  });
  sf_batchMergeCheckRows();
};
</script>
<?php
      $script = 'sf_batchMerge();';
    }
    
    echo '<span class="lui_pseudo" onclick="' . $script . '">[?php echo __(\''.$params['label'].'\', array(), \'sf_admin\') ?]</span>';
    echo '</li>';
?>
[?php endif; ?]
<?php
}

  echo '</ul></div></td>';
}
?>