<fieldset id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
  <?php echo $form->renderHiddenFields(false) ?>
  <?php if ('NONE' != $fieldset): ?>
    <h2><?php echo __($fieldset, array(), 'messages') ?></h2>
  <?php endif; ?>

  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
    <?php include_partial('lpu/form_field', array(
      'name'       => $name,
      'attributes' => $field->getConfig('attributes', array()),
      'label'      => $field->getConfig('label'),
      'help'       => $field->getConfig('help'),
      'form'       => $form,
      'field'      => $field,
      'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
    )) ?>
  <?php endforeach; ?>
  <?php include_partial('lpu/form_fieldset_footer', array('form' => $form)) ?>
</fieldset>



<?php
if ($form->isNew() == false)
{
  ?>
  <style type="text/css">
    .comment_item{
      border-bottom:1px solid rgba(0,0,0,0.1);
    }
    .comment_item_field{
      display: inline-block;
      margin-bottom: 10px;
    }
    .field_1{
      min-width: 300px;
    }
    .field_2{
      min-width: 200px;
    }
    .add_list{
      width: auto;
      display: none;
      padding: 20px;
      border-radius: 4px;
      border:1px solid rgba(0,0,0,0.1);
    }
    .add_list label{
      display: block;
      margin-bottom: 20px;
    }
  </style>
  <i class="br20"></i>
  <div class="fs_18">Специалисты</div>
  <i class="br20"></i>
  <?php
  $lpu_specialists = $form->getObject()->getSpecialist();
  ?>
  <div class="wrap_comments">
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <div class="comment_item">
            <div class="comment_item_field field_1">
              <b>ФИО</b>
              <i class="br10"></i>
            </div>
            <div class="comment_item_field field_1">
              <b>Специальность</b>
              <i class="br10"></i>
            </div>
          </div>
        </td>
      </tr>
      <tr valign="top" align="left">
        <td valign="middle">
        <?php
        foreach($lpu_specialists as $k_lpu_specialist => $lpu_specialist)
        {
        ?>
          <div class="comment_item">
            <div class="comment_item_field field_1">
              <?php echo $lpu_specialist->getUser()->getFirstName() . ' ' . $lpu_specialist->getUser()->getSecondName();?>
            </div>
            <div class="comment_item_field field_2">
              <?php echo $lpu_specialist->getSpecialty();?>
            </div>
            <div class="comment_item_field">
              <a href="<?php echo url_for('@event_elem');?>" onclick="eventElem.remove($(this), event, 'LpuSpecialist__delete__<?php echo $form->getObject()->getId() . '__' . $lpu_specialist->getId();?>')" class="red_link">Удалить</a>
            </div>
          </div>
        <?php
        }
        ?>
        </td>
      </tr>
    </table>
  </div>
  <i class="br5"></i>
  <a class="lui_pseudo" href="" onclick="$('.add_list').slideDown(200);$(this).hide();return false;">Добавить специалиста</a>
  <i class="br20"></i>
  <div class="add_list">
    <?php
    $specialist_list = Doctrine_Query::create()
      ->select("s.*, u.*")
      ->from("Specialist s")
      ->innerJoin("s.User u")
      ->fetchArray()
    ;
    foreach ($specialist_list as $specialist)
    {
      echo '<label>';
        echo '<input class="lpu_specialist_add" type="radio" name="specialist_id" value="' . $specialist['id'] . '" />';
        echo $specialist['User']['second_name'] . ' ' . $specialist['User']['first_name'];
      echo '</label>';
    }
    ?>
    <a class="lui_pseudo" href="" onclick="eventElem.add($(this), event, 'LpuSpecialist__add__<?php echo $form->getObject()->getId() . '__' . 0;?>')">Добавить специалиста</a>
  </div>
  <?php
}
?>