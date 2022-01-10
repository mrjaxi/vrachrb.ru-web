<?php
  use_javascript('//ulogin.ru/js/ulogin.js');
if(!$ajax)
{
  echo '<div class="overlay authorization_window" onclick="$(this).hide();overflowHiddenScroll();"></div>';
  echo '<div class="comment_wrap">';
}
  if(!$ajax)
  {
  ?>
    <form action="/comment/" class="add_comment" method="post" onsubmit="addComment.onAdd();return false;">
      <div class="comment_top">Комментарии</div>
      <div class="comment_body">
        <?php
        echo '<input class="comment_type_id" type="hidden" name="comment_type_id" value="' . $type . ':' . $id . '" />';
        echo $form_comment->renderGlobalErrors();
        echo $form_comment->renderHiddenFields();
        echo $form_comment['body'];
        ?>
        <i class="br20"></i><button class="btn_all blue_btn">Отправить</button>
      </div>
    </form>
    <div class="comment_all">
  <?php
  }

  foreach ($comments as $comment_key => $comment)
  {
    if($comment_key != 0 || $ajax)
    {
      echo '<i class="br1" style="margin:10px 0;"></i>';
    }
    echo '<div class="comment_body">';
      echo '<b>' . $comment['User']['first_name'] .'</b>';
      echo '<span class="comment_date">' . Page::rusDate($comment['created_at']) . ',&nbsp;' . substr($comment['created_at'], 11, 5) . '</span>';
      echo '<div class="comment_item">' . $comment['body'] . '</div>';
    echo '</div>';
  }

if(!$ajax)
{
    echo '</div>';
  echo '</div>';
  if($comments_count > $comment_limit)
  {
    echo '<div class="show_prev_btn" onclick="addComment.onShowPrev();return false;"><a href="">Показать предыдущие</a><span>&#9660;</span></div>';
  }
?>
<script type="text/javascript">
  $(document).ready(function() {
    var str = 'Ваш комментарий...';
    var cb = $('#comment_body');
    cb.val(str);
    cb.click(function() {

      $.post('/signin-check/', 'signin_check=1', function(data) {
        if(data != 'y'){
          $('.authorization_window').html($(data).find('.white_box'));
          $('.authorization_window').find('.white_box').attr('onclick', 'event.stopPropagation();');
          if($('.agreement').size() > 0){
            $('.agreement').addClass('custom_input_label');
            $('.agreement__check').addClass('custom_input');
            $('.agreement__check').after('<span class="custom_input custom_input_checkbox"></span>');
          }
          overflowHiddenScroll($('.authorization_window'));
        }
      });
      if($(this).val() == str){
        $(this).val('');
      }
    });

    cb.blur(function() {
      if($(this).val() == ''){
        $(this).val('Ваш комментарий...');
      }
    });
  });
</script>
<?php
}
?>