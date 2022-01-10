<?php
if ($form->isNew() == false)
{
  ?>
  <i class="br20"></i>
  <div class="fs_18">Комментарии</div>
  <i class="br10"></i>
  <?php
  $comments = $form->getObject()->getComment();
  ?>
  <?php echo include_partial('prompt/comments', array('comments' => $comments)); ?>
  <a class="lui_pseudo" href="<?php echo url_for('comment_new') ?>?type=prompt&id=<?php echo $form->getObject()->getId() ?>">Добавить комментарий</a>
  <?php
}
?>
