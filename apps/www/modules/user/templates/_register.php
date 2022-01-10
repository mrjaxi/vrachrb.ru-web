<?php
if(!$sf_user->isAuthenticated() || $register_form->getObject()->isNew() == false)
{
  if(!$change_answer && $change_profile)
  {
    echo '<div class="registration_form__wrap">';
  }
  echo '<b class="register_h">' . ($register_form->getObject()->isNew() ? 'Регистрация' : 'Личные данные') . '</b>';
  ?>
  <i class="br20"></i>
  <?php
  echo $save_true ? '<div class="save_true_message">Личные данные сохранены</div>' : '';
  ?>
  <style type="text/css">
    .pc_p_data_label{
      width: 140px;
    }
    .pc_p_data_label:before{
      width: 148px;
    }
    .auth_form .fl_l{
      width: 80px;
    }
  </style>
  <?php
  $registration_form = '<form class="registration_form" method="post" onsubmit="' . (!$register_form->getObject()->isNew() ? 'loginFun.onChange' : 'loginFun.onRegister') . '();return false;">';
  $registration_form_last_tag = 'form';
  if(false)
  {
    $registration_form = '<div class="registration_form">';
    $registration_form_last_tag = 'div';
  }
  echo $registration_form;
  echo $register_form->renderHiddenFields();
  echo $register_form->renderGlobalErrors();
  ?>
    <table class="register_table" cellpadding="0" cellspacing="0" class="pc_p_data_table" width="100%">
      <tr>
        <td class="pc_p_data_label"><b>Имя</b></td>
        <td class="pc_p_data_inps"><?php echo $register_form['first_name'] . $register_form['first_name']->renderError();?></td>
      </tr>
      <tr>
        <td class="pc_p_data_label">Фамилия</td>
        <td class="pc_p_data_inps"><?php echo $register_form['second_name'] . $register_form['second_name']->renderError();?></td>
      </tr>
      <tr>
        <td class="pc_p_data_label">Отчество</td>
        <td class="pc_p_data_inps"><?php echo $register_form['middle_name'] . $register_form['middle_name']->renderError();?></td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Пол</b></td>
        <td class="pc_p_data_inps">
          <?php echo $register_form['gender'] . $register_form['gender']->renderError();?>
        </td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Дата рождения</b></td>
        <td class="pc_p_data_inps r_form__birth_date"><?php echo $register_form['birth_date'] . $register_form['birth_date']->renderError();?></td>
      </tr>
      <tr>
        <td class="pc_p_data_label">Номер телефона</td>
        <td class="pc_p_data_inps"><?php echo $register_form['phone'] . $register_form['phone']->renderError();?></td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Электронная почта</b></td>
        <td class="pc_p_data_inps">
          <?php echo $register_form['email'] . $register_form['email']->renderError();?>
        </td>
      </tr>
      <?php
      if($register_form->getObject()->isNew())
      {
      ?>
      <tr>
        <td colspan="2" class="pc_p_data_g_line"><i class="br1" style="background-color: #d8dfe0;"></i></td>
      </tr>
      <tr>
        <td class="pc_p_data_label"><b>Пароль</b></td>
        <td class="pc_p_data_inps">
          <?php echo $register_form['password'] . $register_form['password']->renderError();?>
        </td>
      </tr>
      <tr>
      <tr>
        <td class="pc_p_data_label"><b>Повтор пароля</b></td>
        <td class="pc_p_data_inps">
          <?php
            echo $register_form['repeat_password'] . $register_form['repeat_password']->renderError();
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="pc_p_data_g_line"><i class="br1" style="background-color: #d8dfe0;"></i></td>
      </tr>
      <tr>
        <td colspan="2">
          <?php include_component('agreement', 'agreement', array('ajax' => $ajax));?>
        </td>
      </tr>
      <?php
      }
      ?>
      <tr>
        <td class="pc_p_data_label">&nbsp;</td>
        <td class="pc_p_data_inps" align="left">
          <div style="display: inline-block;">
            <?php
            $register_btn_title = 'Сохранить информацию';
            $register_event = '';
            if($register_form->getObject()->isNew())
            {
              $register_btn_title = 'Регистрация';
              if(false)
              {
                $register_event = 'onclick="loginFun.onRegister(\'alternative\');return false;"';
              }
            }
            echo '<button type="submit" style="width: 220px;" class="btn_all blue_btn" ' . $register_event . '>' . $register_btn_title . '</button>';
            ?>
          </div>
        </td>
      </tr>
      <?php
      if($change_profile == 'y' && $sf_user->getAccount()->getPassword())
      {
      ?>
        <tr>
        <td colspan="2" class="pc_p_data_g_line"><i class="br1" style="background-color: #d8dfe0;"></i></td>
      </tr>
      <tr>
        <td colspan="2"><b>Сменить пароль</b><i class="br20"></i></td>
      </tr>
      <?php
      }
      ?>
    </table>
  <?php
  echo '</' . $registration_form_last_tag . '>';
  if(!$change_answer && $change_profile)
  {
    echo '</div>';
  }
  if($change_password_form && $sf_user->getAccount()->getPassword())
  {
    include_partial('user/change_password', array('change_password_form' => $change_password_form));
  }
  if(isset($change_form))
  {
    echo $change_form;
  }
}
?>