<?php
slot('title', 'Личный кабинет');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>
<div class="ready_flash filter_selector">
  Статус вопроса: 
  <select autocomplete="off" onchange="getFilter('personal', $(this).val())">
    <option value="all">Все</option>
    <option value="open">Открытые</option>
    <option value="close">Закрытые</option>
  </select>
  <img class="filter_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
</div>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('personal_account', 'menu');
      echo '</div>';
      ?>
      <div class="pc_user_page_wrap">
        <?php
        include_component('personal_account', 'now_dialog_list');
        ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php
        include_component('main', 'notice');
        ?>
      </div>
    </td>
  </tr>
</table>