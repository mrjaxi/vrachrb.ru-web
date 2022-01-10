<?php
include sfConfig::get('sf_root_dir') . '/apps/www/templates/mail_header.php';
?>
<h2>Восстановление пароля на сайте <?php echo $sf_request->getHost() ?></h2>
<br/>
<div>
  Добрый день.<br />
  Для восстановления пароля пройдите по <a href="<?php echo 'http://' . $sf_request->getHost() . url_for('recover_password_active', array('email_sha' => $param['email_sha'], 'password_check' => $param['password_check']))  ?>">ссылке</a>.<br />
  Если вы не отправляли запрос то проигнорируйте это письмо.
</div>
<?php
include sfConfig::get('sf_root_dir') . '/apps/www/templates/mail_footer.php';
?>