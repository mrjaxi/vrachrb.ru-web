<?php
echo '<h2>' . mb_strtoupper($agreement->getTitle(), 'UTF-8') . '</h2><br>';
echo '<div class="ftext">' . $agreement->getBody(ESC_RAW) . '</div>';
?>