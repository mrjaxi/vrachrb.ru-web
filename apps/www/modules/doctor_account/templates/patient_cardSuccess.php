<?php
slot('title', 'Амбулаторная карта пациента')
?>

<div class="breadcrumbs">
  <a href="/">Главная</a>
  <a href="<?php echo url_for('@doctor_account_index');?>">Личный кабинет</a>
</div>
<h2>Амбулаторная карта пациента</h2>

<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">

      <div class="white_box pc_user_page patient_card_page">
        <b>История обращений</b>
        <div class="pc_history">
          <?php
          include_partial('personal_account/patient_card_item', array('patient_card' => $patient_card, 'doctor_account' => true));
          ?>
        </div>
      </div>

    </td>
    <td>
      <div style="min-width:300px;"></div>
      <?php
      include_component('main', 'now_analysis', array('user_id' => $sf_request->getParameter('id'), 'specialist' => true));
      ?>
    </td>
  </tr>
</table>