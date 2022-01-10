<?php
$name = $specialist_work_place->getSpecialist()->getUser();
echo $name->getFirstName() . ' ' . $name->getSecondName();