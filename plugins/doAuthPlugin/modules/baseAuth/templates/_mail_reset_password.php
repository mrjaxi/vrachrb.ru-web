Для сброса пароля, откройте ссылку в браузере:

<?php echo url_for('@password?user='.$user->getId().'&code='.$code,array('absolute'=> true)) ?>