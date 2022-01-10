<?php
$account = $sf_user->getAccount();
if(!$sf_user->isAuthenticated())
{
  ?>
  <div class="auth_form_hide">
    <b class="auth__h">Войти с помощью:</b>
    <i class="br20"></i>
    <?php
    $auth_form = '<form action="' . url_for('@signin') . '" class="auth_form" method="post" onsubmit="loginFun.onSignin();return false;">';
    $auth_form_last_tag = 'form';
    if($ask_question)
    {
      $auth_form = '<div class="auth_form">';
      $auth_form_last_tag = 'div';
    }
    echo $auth_form;
    ?>
      <div class="fl_l" style="width:80px;padding-top:7px;">Эл.почта</div>
      <div class="fl_l" style="width:80%;">
        <?php echo $signin_form['username'] . $signin_form['username']->renderError();?>
      </div>
      <i class="br10"></i>
      <div class="fl_l" style="width:80px;padding-top:7px;">Пароль</div>
      <div class="fl_l" style="width:80%;">
        <?php echo $signin_form['password'] . $signin_form['password']->renderError();?>
      </div>
      <i class="br10"></i>
      <div class="fl_l" style="width:80px;">&nbsp;</div>
      <div class="fl_l" style="width: 220px;">
        <button id="login_btn" style="width:100%;" type="submit" class="btn_all blue_btn" data-xp="enabled_on_completed: true" onclick="loginFun.onSignin('alternative');return false;">Войти</button>
      </div>
    <?php
    echo '</' . $auth_form_last_tag . '>';
    ?>
    <div class="auth_form__recover_password">
      <a class="auth_form__recover_password__link" href="<?php echo url_for('@recover_password');?>">Восстановить пароль</a>
    </div>
    <i class="br20"></i>
    <b>Или войти через соц. сети:</b><i class="br20"></i>
    <div id="uLogin" title="Войти с помощью Вконтакте" data-ulogin="display=buttons;fields=first_name,last_name,photo,sex,email;providers=vkontakte;hidden=;callback=check_token">
      <img width="40" height="40" src="/i/vk.png" data-uloginbutton="vkontakte"/>
    </div>
  </div>
<?php
}
else
{
?>
  <style type="text/css">
    .register_table,.register_h{
      display: none;
    }
  </style>
  <b>Вход выполнен</b><i class="br30"></i>Вы вошли как:<i class="br5"></i>
  <div id="login_true"></div>
<?php
  echo '<b class="login_true_fio">' . (!$account->getFirstName() && !$account->getSecondName() ? $account->getUsername() : $account->getFirstName() . ' ' . $account->getSecondName()) . '</b>';
  if(!$ask_question)
  {
    echo '<div class="login_info"><i class="br30"></i>Перейти в <a href="' . url_for('@' . Page::whoIsDoctor($sf_user->getAccount()->getId(), 'url') . '_account_index') . '">Личный кабинет</a><i class="br10"></i><a href="' . url_for('@signout') . '">Выйти</a></div>';
  }
}
?>