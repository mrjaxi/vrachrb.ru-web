<?php
slot('title', 'Обращение к президенту');
?>
<table class="table_cont" cellspacing="0" cellpadding="0" width="100%" height="100%">
  <tr valign="top">
    <td width="1">
      <?php
      include_partial('form_builder/menu_l');
      ?>
    </td>
    <td height="100%">
      <div class="cont__cont_r">
        <div class="cont__cont_r__pad">
          <div class="fs_30">Обращение к «Президенту»</div>
          <i class="br20"></i>
          <?php
          if($sf_user->hasFlash('notice'))
          {
            echo '<div class="notice">' . $sf_user->getFlash('notice') . '</div>';
          }
          else
          {
          ?>
          <form method="post" action="<?php echo url_for('@appeal');?>">
            <input type="hidden" name="id" value="3" />
            ФИО<i class="br1"></i>
            <table cellpadding="0" cellspacing="0">
            <tr>
            <td>
            <?php
            $user_info = $sf_user->isAuthenticated() ? $sf_user->getAccount()->getUserInfo() : false;
            ?>
            <div style="display:inline-block;position:relative;">
              <button class="button clear_btn"<?php echo $sf_user->isAuthenticated() ? ' style="display:none"' : '';?>></button>
              <input name="fields[ФИО]" data-formlabel="ФИО" size="30" type="text" autocomplete="off" <?php echo $sf_user->isAuthenticated() ? 'value="' . $user_info['LastName'] . ' ' . $user_info['FirstName'] . ' ' . $user_info['MiddleName'] . '" readonly="true"' : 'class="auto_fio"';?> />
            </div>
            </td>
            <td style="padding-left: 10px;">
            <div id="auto_comp__area"<?php echo $sf_user->isAuthenticated() ? ' style="display:none"' : '';?>>
              <div class="auto_comp"><i class="br5"></i>Для автоматического заполнения приложите свою карту</div>
            </div>
            </td>
            </tr>
            </table>
            <i class="br20"></i>
            Текст обращения:<i class="br1"></i>
            <textarea rows="15" cols="30" style="width:100%" name="fields[Текст обращения]"></textarea>
            <i class="br20"></i>
            <i class="br20"></i>
            <button class="read_btn button" type="submit" onclick="return $('#review_body').val().trim().length > 0">Отправить</button>&nbsp;&nbsp;&nbsp;<b class="error"></b>
            
          </form>
          <?php
          }
          ?>
        </div>
      </div>
    </td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
  
});
</script>