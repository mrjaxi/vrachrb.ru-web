<?php
if($user)
{
	if($user->getId())
	{
		echo '<div style="display: inline-block;border:1px solid rgba(0,0,0,0.6);border-radius: 4px;padding: 5px 15px;margin-top: 10px;" class="message_error__user_advanced_info">';
	    echo '<b>Доп. информация:</b><br>';
	    echo '<div style="padding: 3px 0;"><i>username: </i>' . $user->getUsername() . '</div>';
	    echo '<i>email: </i>' . $user->getEmail();
	    echo '<div style="padding: 20px 0 10px 0;"><a target="_blank" href="/arm/question?q=' . $user->getUsername() . '">Вопросы пользователя</a></div>';
	  echo '</div>';
	}
}