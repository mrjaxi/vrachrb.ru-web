<?php
$user = $review->getSpecialist()->getUser();
echo $user->getFirstName() . ' ' . $user->getSecondName();