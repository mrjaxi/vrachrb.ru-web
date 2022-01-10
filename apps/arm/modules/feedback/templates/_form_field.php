<?php if ($field->isPartial()): ?>
  <?php include_partial('feedback/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('feedback', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
  <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
    <div>
      <?php $opts = $form[$name]->getWidget()->getOptions(); ?>
      <?php if ($opts['type'] == 'checkbox'): ?>
        <div class="content"><label><?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes); ?><?php echo $label; ?></label></div>
      <?php else: ?>
        <span class="inline-label"><?php echo $form[$name]->renderLabel($label) ?></span>
        <div class="content">
          <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
          <?php echo $form[$name]->renderError() ?>
          <?php if ($help): ?><span class="help"><?php echo __($help, array(), 'messages') ?></span><?php elseif ($help = $form[$name]->renderHelp()): ?><span class="help"><?php echo strip_tags($help, '<a><i>') ?></span><?php endif; ?>
        </div>

        <?php
        if($form->isNew() == false && $name == 'user_id')
        {
          include_partial('message_error/advanced_user_info', array('user' => $form->getObject()->getUser()));
        }
        ?>

      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
