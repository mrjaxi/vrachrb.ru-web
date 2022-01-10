<?php
if($question->getClosedBy() != '')
{
  $user_closed = $question->getUserClosed();
  echo $user_closed->getSFM();
}
?>