[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]

<div id="lui_scroller" class="lui__scroller_class">
<div class="lui__scroller_wrapper" style="position:relative;z-index:102;">
    [?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?]
      [?php include_partial('<?php echo $this->getModuleName() ?>/form_fieldset', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?]
    [?php endforeach; ?]
<span class="br30"></span>
<table cellpadding="0" cellspacing="0" width="100%" class="cm_save_n_del_btns">
  <tr>
    <td width="50%"><button class="cm_buttons cm_green_btn cm_save_btn fs_16">Сохранить</button></td>
    [?php 
    if(!$form->isNew())
    {
    ?]
    <td width="50%" align="right"><button class="cm_buttons cm_redb_btn cm_del_btn fs_16">Удалить</button></td>
    [?php 
    }
    ?]
  </tr>
</table>
<span class="br30"></span>
</div>
</div>

</form>

