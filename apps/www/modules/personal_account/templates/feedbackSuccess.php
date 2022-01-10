<?php
  slot('title', 'Обратная связь');
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
      ?>

      <div class="white_box pc_user_page">
        <b>Обратная связь</b>
        <i class="br20"></i>

        <?php
        if(!$message_true)
        {
          echo 'Вы можете отправить вопросы и предложения по работе сервиса';
        }
        ?>
        <i class="br20"></i>
        <?php
        if(!$message_true)
        {
          echo '<form action="' . url_for('@personal_account_feedback') . '" method="post" >';
            echo $form->renderGlobalErrors();
            echo $form->renderHiddenFields();
            echo $form['body'] . $form['body']->renderError();
            echo '<i class="br20"></i>';
            echo '<button class="btn_all blue_btn">Отправить</button>';
          echo '</form>';
        }
        else
        {
          echo '<b>Ваше сообщение отправлено!</b>';
        }
        ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 'u'));?>
      </div>
      <div style="width: 300px;"></div>
    </td>
  </tr>
</table>