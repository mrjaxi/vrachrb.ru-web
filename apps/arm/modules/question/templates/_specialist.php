<?php
$specialist = $question->getSpecialists();
$user = $specialist[0]->getUser();
echo $user->getFirstName() . ' ' . $user->getSecondName();