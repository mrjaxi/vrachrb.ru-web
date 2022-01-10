<?php
if(!$r)
{
  include_partial('register', array('register_form' => $form, 'ajax' => true));
}
else
{
  ?>
  <div>
    Для завершения регистрации необходимо подтвердить электронный адрес почты.
    <i class="br10"></i>
    На указанный эл. адрес отправлено письмо для подтверждения регистрации.
  </div>
  <?php
  if(false)
  {
  ?>
    <h2>Вы успешно зарегистрировались и авторизовались!</h2>
    <i class="br20"></i>Перейти в <a href="<?php echo url_for('@personal_account_index');?>">Личный кабинет</a><i class="br20"></i>
    <script type="text/javascript">
      $('.auth_form_hide').remove();
      $('.profile_link__wrap').html('<div class="profile_link_window" onclick="event.stopPropagation();"><a href="<?php echo url_for('@' . Page::whoIsDoctor($sf_user->getAccount()->getId(), 'url') . '_account_index');?>">Личный кабинет</a><i class="br10"></i><a href="<?php echo url_for('@signout');?>">Выйти</a></div><span class="profile_link"><?php echo Page::authorizedUserName($sf_user->getAccount());?></span>');
    </script>
  <?php
  }
}
?>