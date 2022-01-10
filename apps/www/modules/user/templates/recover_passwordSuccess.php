<?php use_javascript('expromptum');?>
<div class="white_box recover_password_page">
  <b>Восстановление пароля:</b>
  <i class="br20"></i>
  <?php
  if($sf_user->hasFlash('recover_message'))
  {
    echo $sf_user->getFlash('recover_message');
    echo '<i class="br40"></i>';
  }
  else
  {
  ?>
    <form class="recover_password_page__form" method="post">
      <table width="300" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <div class="fl_l recover_password_page__form__input_h">Эл. почта</div>
          </td>
          <td>
            <input type="text" name="email" data-xp="type: 'email', required: true"  class="recover_password_page__form__input" placeholder="example@ya.ru">
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <i class="br10"></i>
            <button type="submit" data-xp="enabled_on_completed: true" class="btn_all blue_btn recover_password_page__form__btn">Отправить</button>
          </td>
        </tr>
      </table>
    </form>
  <?php
  }
  ?>
</div>
<?php
if(!$sf_user->hasFlash('recover_message'))
{
?>
  <script type="text/javascript">
    expromptum();
    document.getElementsByClassName('recover_password_page__form__input')[0].onfocus = function(){
      document.getElementsByClassName('recover_password_page__form')[0].setAttribute('action', '/recover-password/');
    };
  </script>
<?php
}
?>