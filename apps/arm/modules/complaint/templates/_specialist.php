<?php
$name = $complaint->getSpecialist()->getUser();
echo $name->getFirstName() . ' ' . $name->getSecondName();