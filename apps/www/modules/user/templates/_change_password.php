<div class="change_password_form_wrap">
  <form class="change_password_form" action="/change_password/" onsubmit="loginFun.onChangePassword();return false;">
    <table id="change_password_table" cellpadding="0" cellspacing="0" class="pc_p_data_table" width="100%">
      <tr>
        <td class="pc_p_data_label"><b>Старый пароль</b></td>
        <td class="pc_p_data_inps">
          <?php
          echo $change_password_form->renderHiddenFields();
          echo $change_password_form->renderGlobalErrors();
          echo $change_password_form['old_password'] . $change_password_form['old_password']->renderError();
          ?>
        </td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Новый пароль</b></td>
        <td class="pc_p_data_inps">
          <?php
          echo $change_password_form['password'] . $change_password_form['password']->renderError();
          ?>
        </td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Повтор пароля</b></td>
        <td class="pc_p_data_inps">
          <?php
          echo $change_password_form['confirm_password'] . $change_password_form['confirm_password']->renderError();
          ?>
        </td>
      </tr>
      <tr>
        <td class="pc_p_data_label">&nbsp;</td>
        <td class="pc_p_data_inps">
          <div style="display:inline-block;"><button type="submit" class="btn_all blue_btn" data-xp="enabled_on_completed: true">Сохранить пароль</button></div>
        </td>
      </tr>
    </table>
  </form>
</div>