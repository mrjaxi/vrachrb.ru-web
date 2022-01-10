<?php
if ($form->isNew() == false)
{
  ?>
  <i class="br20"></i>
  <div class="fs_18">Ответы</div>
  <i class="br10"></i>
  <?php
  $consilium_answers = $form->getObject()->getConsilium_answer();
  ?>
  <div class="wrap_comments">
    <?php
    foreach($consilium_answers as $k_answer => $consilium_answer)
    {
      ?>
      <div class="comment_item">
        <table cellspacing="0" cellpadding="">
          <tr valign="top" align="left">
            <td style="padding-right: 20px" width="600">
              <div class="comment_item_field">
                <b>Специалист: </b><?php echo $consilium_answer->getSpecialist()->getSFM() ?>
              </div>
              <div class="comment_item_field">
                <b>Текст: </b><?php echo $consilium_answer->getBody() ?>
              </div>
              <div class="comment_item_field">
                <b>Дата: </b><?php echo Page::rusDate($consilium_answer->getCreatedAt(), true) ?>
              </div>
            </td>
            <td width="200">
              <a href="<?php echo url_for('consilium_answer_edit', array('id' => $consilium_answer->getId())) ?>">Редактировать</a>
              <i class="br3"></i>
              <a href="<?php echo url_for('consilium_answer') . '/' . $consilium_answer->getId() . '/delete' ?>" onclick="comment.remove($(this), event)" class="red_link">Удалить</a>
            </td>
          </tr>
        </table>
      </div>
    <?php
    }
    ?>
  </div>
  <i class="br5"></i>
  <a class="lui_pseudo" href="<?php echo url_for('consilium_answer_new') ?>?id=<?php echo $form->getObject()->getId() ?>">Добавить ответ</a>
<?php
}
?>