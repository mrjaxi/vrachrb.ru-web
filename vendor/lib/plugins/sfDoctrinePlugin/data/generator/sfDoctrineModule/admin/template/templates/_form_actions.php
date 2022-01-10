<ul class="sf_admin_actions sf_admin_actions__pseudo">
    <?php foreach (array('new', 'edit') as $action): ?>
        <?php if ('new' == $action): ?>
            [?php
            if($sf_user->hasCredential('<?php echo $this->getModuleName() . '_' . ($action == 'edit' ? 'update' : 'create'); ?>'))
            {
            ?]
            [?php if ($form->isNew()): ?]
        <?php else: ?>
            [?php else: ?]
        <?php endif; ?>
        <?php foreach ($this->configuration->getValue($action . '.actions') as $name => $params): ?>
            <?php if ('_delete' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToDelete($form->getObject(), ' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php elseif ('_list' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToList(' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php elseif ('_save' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToSave($form->getObject(), ' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php elseif ('_save_and_add' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToSaveAndAdd($form->getObject(), ' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php elseif ('_save_and_list' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToSaveAndList($form->getObject(), ' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php else: ?>
                <li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
                    [?php if (method_exists($helper, 'linkTo<?php echo $method = ucfirst(sfInflector::camelize($name)) ?>')): ?]
                    <?php echo $this->addCredentialCondition('[?php echo $helper->linkTo' . $method . '($form->getObject(), ' . $this->asPhp($params) . ') ?]', $params) ?>

                    [?php else: ?]
                    <?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, true), $params) ?>

                    [?php endif; ?]
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
    [?php endif; ?]
    [?php
    }
    ?]
    <li style="visibility:hidden;"><input type="submit"></li>
</ul>
[?php if (!$sf_request->isXmlHttpRequest()): ?]
<script type="text/javascript">
    $(document).ready(function () {
        $('.sf_admin_action_save input').click(function () {
            $(this).closest('.lui_form_layer').find('form').submit();
        });
        $('.sf_admin_action_save_and_list input').click(function () {
            var form = $(this).closest('.lui_form_layer').find('form');
            form.append('<input type="hidden" name="_save_and_list" value="1" />');
            form.submit();
        });
    });
</script>
[?php endif; ?]
