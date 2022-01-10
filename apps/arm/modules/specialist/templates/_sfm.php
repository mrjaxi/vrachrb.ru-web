<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 16.02.2016
 * Time: 15:14
 */
?>
<?php
$user = $specialist->getUser();
echo $user->getSecondName() . ' ' . $user->getFirstName() . ' ' . $user->getMiddleName();
?>