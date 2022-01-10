<?php
$arr = Page::strCut($question->getBody(), 300);
echo $arr[0];