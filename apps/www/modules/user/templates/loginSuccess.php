<?php
use_javascript('//ulogin.ru/js/ulogin.js');
use_javascript('expromptum.js');
use_javascript('mur.d.js');
use_javascript('sexypicker.js');
if($sf_request->getParameter('authorization') != 1)
{
  echo '<div class="login_page">';
}
?>
  <div class="white_box clearfix ask_quest_page__section">
    <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">
    <table cellpadding="0" cellspacing="0" width="100%">
      <tr valign="top" id="login_item">
        <td width="50%" class="auth_form__wrap">
          <?php
          include_component('user', 'login', array('static' => true));
          ?>
        </td>
        <td class="registration_form__wrap" width="50%">
          <?php
          if(!$sf_user->isAuthenticated())
          {
            include_partial('register', array('register_form' => $register_form));
          }
          ?>
        </td>
      </tr>
    </table>
  </div>
<?php
if($sf_request->getParameter('authorization') != 1)
{
  echo '</div>';
}
if($sf_user->isAuthenticated())
{
?>
  <script type="text/javascript">
    $('.profile_link__wrap').html('<div class="profile_link_window" onclick="event.stopPropagation();"><a href="<?php echo url_for('@' . Page::whoIsDoctor($sf_user->getAccount()->getId(), 'url') . '_account_index');?>">Личный кабинет</a><i class="br10"></i><a href="<?php echo url_for('@signout');?>">Выйти</a></div><span class="profile_link"><?php echo Page::authorizedUserName($sf_user->getAccount());?></span>');
  </script>
<?php
}
?>
<script type="text/javascript">
  $(document).ready(function () {
    $('.agreement__check').each(function (i, _this) {
      $(_this).removeProp('checked');
    });
  });
</script>