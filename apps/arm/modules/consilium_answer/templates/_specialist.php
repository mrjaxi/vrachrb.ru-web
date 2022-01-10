<?php
$name = $consilium_answer->getSpecialist()->getUser();
echo $name->getFirstName() . ' ' . $name->getSecondName();