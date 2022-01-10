<?php
$arr = Page::strCut($feedback->getBody(), 300);
echo $arr[0];