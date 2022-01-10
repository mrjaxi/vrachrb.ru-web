<?php
slot('title', $page->getTitle());
slot('logo_h', $page->getTitle());
echo '<div class="ftext">';
echo Page::findImages($page->getBody(ESC_RAW));
echo '</div>';
?>
