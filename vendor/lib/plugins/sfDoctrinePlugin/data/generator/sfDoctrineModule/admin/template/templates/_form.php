[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]

<div id="lui_scroller" class="lui__scroller_class">
<div class="lui__scroller_wrapper" style="position:relative;z-index:102;padding-left:10px;">
    [?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?]
      [?php include_partial('<?php echo $this->getModuleName() ?>/form_fieldset', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?]
    [?php endforeach; ?]
<span class="br30"></span>
</div>
</div>
[?php
$return = url_for('<?php echo $this->getModuleName() ?>/index');
if($sf_request->hasParameter('return'))
{
  $return = $sf_request->getParameter('return');
}
elseif($sf_request->getReferer() != '' && strpos($sf_request->getReferer(), $sf_request->getUri()) === false)
{
  $return = $sf_request->getReferer();
}
?]
<input type="hidden" name="return" value="[?php echo $return;?]" />
</form>

