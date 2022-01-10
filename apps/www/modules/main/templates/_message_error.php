<div class="message_error">
  <div class="message_error__h" onclick="messageErrorAdd(this, 'clear');">Сообщить об ошибке</div>
  <form action="/abc123" onsubmit="messageErrorAdd(this, 'add');return false;" method="post" class="message_error__item">
    <div class="message_error__item__please">
      <i>Добрый день!</i><br>
      <i>Вы находитесь на пробной версии сайта, это значит, что сейчас мы улучшаем и тестируем его.</i><br>
      <i>Пожалуйста, если вы заметили ошибку или у вас возникли пожелания - смело пишите сюда. Также вы можете прикрепить фотографию ошибки.</i><br>
      <i>Спасибо за понимание!</i>
      <i class="br10"></i>
      <?php
      echo $message_error_form->renderGlobalErrors();
      echo $message_error_form->renderHiddenFields();
      echo $message_error_form['body'] . $message_error_form['body']->renderError();
      ?>
      <i class="br10"></i>
      <?php
      if(!$sf_user->isAuthenticated())
      {
        echo '<input id="message_error__item__email" type="text" required="1" placeholder="Эл.почта для обратной связи" name="message_error_email"><i class="br10"></i>';
      }
      echo $message_error_form['photo'] . $message_error_form['photo']->renderError();
      ?>
      <button class="message_error__item__btn btn_all blue_btn">Отправить</button>
    </div>
    <div class="message_error__item__result"></div>
  </form>
</div>
<script type="text/javascript">
  $('#message_error_body').focus(function(){
    $('.message_error__item').attr('action', '<?php echo url_for('@message_error_add');?>');
  });
</script>