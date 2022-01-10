[?php use_helper('I18N', 'Date') ?]

<td width="1">
<div class="cm_menu_l_wrap">
  <div class="cm_menu_l__additional">
    <table cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td>
        [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]
        </td>
        <td align="right"><div class="cm_menu_l__additional__sort" [?php echo ' data-sort="' . implode(':',  $sort->getRawValue()) .'"';?]></div></td>
      </tr>
    </table>
  </div>
  [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]
</div>
</td>
<td height="100%" class="cm_cont">
[?php include_partial('<?php echo $this->getModuleName() ?>/form', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
</td>
