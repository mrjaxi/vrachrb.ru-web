<?php
  if($analysis->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $analysis->getPhoto()))
  {
    $replace_png = str_replace('.png','-S.png',$analysis->getPhoto());
    $replace_jpg = str_replace('.jpg','-S.jpg',$replace_png);

    echo '<img src="/u/i/' . $replace_jpg . '" height="75">';
  }