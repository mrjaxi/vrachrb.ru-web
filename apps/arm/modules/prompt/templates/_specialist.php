<?php
$name = $prompt->getSpecialist()->getUser();
echo $name->getFirstName() . ' ' . $name->getSecondName();