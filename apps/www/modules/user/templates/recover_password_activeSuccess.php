<div class="white_box recover_password_page">
  <style type="text/css">
    .recover_password_page__form__input_h{
      width: 120px;
    }
    .recover_password_page__form__input_wrap{
      position: relative;
      display: inline-block;
    }
    #recover_password_page__form__input_error{
      display: none;
      position: absolute;
      top: 8px;
      left: 240px;
      color: red;
      font-size: 13px;
      min-width: 300px;
    }
  </style>
  <b>Смена пароля:</b>
<?php
if($change_pass)
{
  echo '<i class="br20"></i>Пароль изменён. Вы можете войти используя новый пароль <a href="' . url_for('@login_index') . '">личный кабинет</a>';
}
else
{
?>
  <i class="br20"></i>
  <form onsubmit="return false;" class="recover_password_page__form" method="post">
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <div class="fl_l recover_password_page__form__input_h">Новый пароль</div>
        </td>
        <td>
          <div class="recover_password_page__form__input_wrap">
            <input type="text" name="new_password" class="recover_password_page__form__input" maxlength="12">
            <div id="recover_password_page__form__input_error"></div>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <i class="br10"></i>
        </td>
      </tr>
      <tr>
        <td>
          <div class="fl_l recover_password_page__form__input_h">Повтор пароля</div>
        </td>
        <td>
          <input type="text" name="repeat_password" class="recover_password_page__form__input" maxlength="12">
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <i class="br10"></i>
          <button type="submit" class="btn_all blue_btn recover_password_page__form__btn">Сменить пароль</button>
        </td>
      </tr>
    </table>
  </form>
<?php
}
?>
</div>
<?php
if(!$change_pass)
{
?>
<script type="text/javascript">
  var newPass = document.getElementsByClassName('recover_password_page__form__input')[0];
  var repeatPass = document.getElementsByClassName('recover_password_page__form__input')[1];
  var errorElem = document.getElementById('recover_password_page__form__input_error');
  document.getElementsByClassName('recover_password_page__form__btn')[0].onclick = function(){
    var error = 'Пароли не совпадают';
    if(newPass.value == repeatPass.value){
      error = 'Требуется от 8 до 12 символов';
      if(newPass.value.length >= 8 && newPass.value.length <= 12){
        error = null;
        document.getElementsByClassName('recover_password_page__form')[0].removeAttribute('onsubmit');
      };
    };
    if(error){
      errorElem.innerText = error;
      errorElem.style.display = 'inline-block';
    }
  };
</script>
<?php
}
?>