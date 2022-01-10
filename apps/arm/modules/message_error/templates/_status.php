<?php
$status = $message_error->getStatus();
if($status_name = Page::messageErrorStatus($status, 'name'))
{
	echo '<div class="message_error_status message_error_status_' . $status . '">' . $status_name . '</div>';
}