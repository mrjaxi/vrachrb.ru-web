<?php
$email_text = '';
if($sf_user->isAuthenticated())
{
  $email_text = $sf_user->getAccount()->getEmail() ? 'Уведомление так же придет на вашу почту: ' . $sf_user->getAccount()->getEmail() : '';
}
?>
<div class="white_box question_complete_page">
  <div class="qcp__icon"></div>
  <i class="br1"></i>
  <div class="qcp__body">
    Готово! Ваш вопрос отправлен специалисту. Скоро вы получите сообщение с ответом от врача. <?php echo $email_text;?>
    <i class="br25"></i>
    А пока можно посмотреть вопросы от других пользователей&nbsp;&nbsp;<img class="qcp__arrow" src="/i/ask_question_complete.svg" width="16" height="17">&nbsp;&nbsp;<a href="<?php echo url_for('@question_answer_index');?>">Вопросы и ответы</a>
  </div>
</div>