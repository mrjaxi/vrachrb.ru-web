<?php if ($actions = $this->configuration->getValue('list.actions')): ?>
<td>
<ul class="lui__list_actions">
<?php foreach ($actions as $name => $params): ?>
<?php if ('_new' == $name): ?>
<?php echo $this->addCredentialCondition('[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]', array('class' => 'lui_pseudo', 'credentials' => $this->getModuleName() . '_new'))."\n" ?>
<?php else: ?>
<li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
  <?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, false), array_merge($params, array('credentials' => $this->getModuleName() . '_new')))."\n" ?>
</li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
</td>
<?php endif; ?>
