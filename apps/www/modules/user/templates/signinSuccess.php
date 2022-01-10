<?php
if($agreement)
{
  echo '<td colspan="2">';
  echo '<form id="agreement__form" action="' . url_for('@agreement_index') . '" method="post">';
  include_component('agreement', 'agreement', array('ajax' => true, 'btn' => true, 'login' => true));
  echo '</form>';
  echo '</td>';
}
else
{
  echo '<td class="auth_form__wrap" width="50%">';
  include_partial('login', array('signin_form' => $signin_form));
  echo '</td>';
  echo '<td class="registration_form__wrap" width="50%">';
  include_component('user', 'register', array('ajax' => true));
  echo '</td>';
}
if($sf_user->isAuthenticated())
{
?>
  <script type="text/javascript">
    <?php
    if($agreement)
    {
    ?>
      var url = decodeURI(document.location);
      if(url.indexOf('login') != -1){
        $('.white_box').before('<h2>Для пользования порталом вам необходимо прочесть и принять соглашения:</h2>');
      }
    <?php
    }
    ?>
    $('.profile_link__wrap').html('<a class="profile_link" href="<?php echo url_for('@' . Page::whoIsDoctor($sf_user->getAccount()->getId(), 'url') . '_account_index');?>"><?php echo Page::authorizedUserName($sf_user->getAccount());?></a><a class="profile_exit_link" href="/signout/"><img src="/i/n.gif" style="background: url(/i/consilium_sprite.png) -28px 0;" height="19" width="18"></a>');
  </script>
<?php
}
?>

