<?php
  slot('title', 'Личные данные');
  use_javascript('expromptum.js');
  use_javascript('mur.d.js');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>

<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">

      <?php
      echo '<div class="da_menu_wrap">';
        include_component('personal_account', 'menu');
      echo '</div>';
      !$sf_user->isAuthenticated() ? $auth = true : '';
      $account = $sf_user->getAccount();
      ?>

      <div class="white_box pc_user_page pc_data_page">
        <?php
        include_partial('user/register', array('register_form' => $form, 'change_profile' => 'y', 'change_password_form' => $change_password_form));
        ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 'user'));?>
      </div>
      <div style="width:300px;"></div>
    </td>
  </tr>
</table>