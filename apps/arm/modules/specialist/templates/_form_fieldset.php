<fieldset id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
  <?php echo $form->renderHiddenFields(false) ?>
  <?php if ('NONE' != $fieldset): ?>
    <h2><?php echo __($fieldset, array(), 'messages') ?></h2>
  <?php endif; ?>

  <?php foreach ($fields as $name => $field): ?>
    <?php
    if ($name == 'user')
    {
      continue;
    }
    ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
    <?php include_partial('specialist/form_field', array(
      'name'       => $name,
      'attributes' => $field->getConfig('attributes', array()),
      'label'      => $field->getConfig('label'),
      'help'       => $field->getConfig('help'),
      'form'       => $form,
      'field'      => $field,
      'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
    )) ?>
  <?php endforeach; ?>

  <?php

  $form_user = $form['user'];
  foreach($form_user as $name => $field)
  {
    if ($field->isHidden())
    {
      echo $field->render();
      continue;
    }
    $opts = $field->getWidget()->getOptions();
    $type = isset($opts['format']) ? 'date' : $opts['type'];
    $class = 'sf_admin_form_row sf_admin_' . $type . ' sf_admin_form_field_'.$name;
    ?>
    <div class="<?php echo $class ?><?php $field->hasError() and print ' errors' ?>">
      <div>
        <?php if ($opts['type'] == 'checkbox'): ?>
          <div class="content"><label><?php echo $field->render(); ?><?php echo $opts['label']; ?></label></div>
        <?php else: ?>
          <span class="inline-label"><?php echo $field->renderLabel() ?></span>
          <div class="content">
            <?php echo $field->render() ?>
            <?php echo $field->renderError() ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php
  }

  ?>

  <?php include_partial('specialist/form_fieldset_footer', array('form' => $form)) ?>
</fieldset>










<?php
if ($form->isNew() == false)
{
  ?>
  <i class="br20"></i>
  <div class="fs_18">Место работы</div>


  <i class="br10"></i>
  <?php
  $works = Doctrine::getTable('Specialist_work_place')->findBySpecialistId($form->getObject()->getId());
  ?>
  <div class="wrap_comments">

      <div class="comment_item">
        <table cellspacing="0" cellpadding="">
          <tr>
            <td></td>
            <td align="center">
                <b>Скрыто в профиле:</b>
            </td>
            <td></td>
          </tr>

          <?php
          foreach($works as $k_work => $work)
          {
          ?>
            <tr valign="top" align="left">
              <td style="padding-right: 20px;border-bottom:1px solid rgba(0,0,0,0.1);" width="400">
                <div class="comment_item_field">
                  <i class="br10"></i>
                  <?php echo $work->getTitle();?>
                </div>
              </td>
              <td align="center" valign="middle" style="border-bottom:1px solid rgba(0,0,0,0.1);">
                <div class="comment_item_field">
                  <?php echo $work->getHidden() ? '<img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">' : '';?>
                </div>
              </td>
              <td style="border-bottom:1px solid rgba(0,0,0,0.1);padding: 0 20px;">
                <a href="<?php echo url_for('specialist_work_place_edit', array('id' => $work->getId())) ?>">Редактировать</a>
                <i class="br3"></i>
                <a href="<?php echo url_for('specialist_work_place') . '/' . $work->getId() . '/delete' ?>" onclick="work_location.remove($(this), event)" class="red_link">Удалить</a>
                <i class="br10"></i>
              </td>
            </tr>
            <?php
          }
          ?>

        </table>
      </div>

  </div>
  <i class="br5"></i>
  <a class="lui_pseudo" href="<?php echo url_for('specialist_work_place_new') ?>?id=<?php echo $form->getObject()->getId() ?>">Добавить место работы</a>
  <?php
}
?>