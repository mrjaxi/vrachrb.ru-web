<?php
$name = $article->getSpecialist()->getUser();
echo $name->getFirstName() . ' ' . $name->getSecondName();