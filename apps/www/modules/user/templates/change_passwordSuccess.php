<?php
echo $save_true ? '<div class="save_true_message">Пароль изменен</div>' : '';
include_partial('change_password', array('change_password_form' => $change_password_form));