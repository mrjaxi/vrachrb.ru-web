<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 18.02.2016
 * Time: 11:55
 */
?>

<div class="wrap_comments">
  <?php
  foreach($comments as $k_comment => $comment)
  {
    ?>
    <div class="comment_item">
      <table cellspacing="0" cellpadding="">
        <tr valign="top" align="left">
          <td style="padding-right: 20px" width="600">
            <div class="comment_item_field">
              <b>Пользователь: </b><?php echo $comment->getUser()->getSecondName() . ' ' . $comment->getUser()->getFirstName() . ' ' . $comment->getUser()->getMiddleName() ?>
            </div>
            <div class="comment_item_field">
              <b>Коментарий: </b><?php echo $comment->getBody() ?>
            </div>
            <div class="comment_item_field">
              <b>Дата: </b><?php echo Page::rusDate($comment->getCreatedAt(), true) ?>
            </div>
          </td>
          <td width="200">
            <a href="<?php echo url_for('comment_edit', array('id' => $comment->getId())) ?>">Редактировать</a>
            <i class="br3"></i>
            <a href="<?php echo url_for('comment') . '/' . $comment->getId() . '/delete' ?>" onclick="comment.remove($(this), event)" class="red_link">Удалить</a>
          </td>
        </tr>
      </table>
    </div>
  <?php
  }
  ?>
</div>
<i class="br5"></i>