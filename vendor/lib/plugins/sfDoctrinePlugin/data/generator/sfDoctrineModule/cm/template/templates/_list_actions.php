<?php if ($actions = $this->configuration->getValue('list.actions')): ?>

<?php foreach ($actions as $name => $params): ?>
<?php if ('_new' == $name): ?>
<?php echo $this->addCredentialCondition('[?php echo $helper->linkToNew('. $this->asPhp(array_merge($params, array('class' => 'cm_buttons cm_green_btn'))) . ') ?]', array('credentials' => $this->getModuleName() . '_new'))."\n" ?>
<?php else: ?>

<?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, false), array_merge($params, array('credentials' => $this->getModuleName() . '_new')))."\n" ?>

<?php endif; ?>
<?php endforeach; ?>

<?php endif; ?>
