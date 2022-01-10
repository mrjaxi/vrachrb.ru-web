<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 16.02.2016
 * Time: 10:04
 */
include sfConfig::get('sf_root_dir') . '/apps/www/templates/mail_header.php';
?>

<h2>Активация аккаунта на сайте <?php echo $sf_request->getHost() ?></h2>
<br/>
<div>
  Добрый день.<br />
  Для активации аккаунта пройдите по <a href="<?php echo 'http://' . $sf_request->getHost() . url_for('user_active', array('username' => base64_encode($user->getUsername()), 'password_check' => $user->getPasswordCheck()))  ?>">ссылке</a>.<br />
  Если вы не отправляли запрос то проигнорируйте это письмо.
</div>
<?php
include sfConfig::get('sf_root_dir') . '/apps/www/templates/mail_footer.php';
?>