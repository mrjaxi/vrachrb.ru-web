<div class="cont__cont padding_l padding_t padding_r">
<b class="fs_20">Для обеспечения конфиденциальности Ваших данных требуется установить PIN-код, для этого введите 4 произвольные цифры в соответствующее поле. Пожалуйста не забывайте его и не передавайте другим людям.</b>
<i class="br40"></i>
<?php
if($sf_user->hasFlash('error'))
{
  echo '<div class="error">' . $sf_user->getFlash('error') . '</div>';
  echo '<i class="br40"></i>';
}
?>
<form method="post">
<b class="fs_20">PIN-код</b><br/>
<input type="password" autocomplete="off" name="pin" style="width:300px;text-align: center;" maxlength="4" /><i class="br25"></i>
<b class="fs_20">Мы должны убедиться, что Вы правильно запомнили PIN-код, для этого введите его еще раз</b><br/>
<input type="password" autocomplete="off" name="pin_check" style="width:300px;text-align: center;" maxlength="4" /><i class="br40"></i>
<button class="read_btn" type="submit">Задать</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/signin/" class="read_btn o_btn">Отмена</a>
</form>
<i class="br40"></i>
</div>