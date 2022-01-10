<?php
echo '<select id="test_cards" onchange="enter_pin($(this).val())" style="position:absolute;top:4px;right:4px;"><option value="">Select card..</option>';
foreach($users as $user)
{
  echo '<option value="' . $user->getUsername() . '">' . $user->getUsername() . '</option>';
}
echo '</select>';
?>