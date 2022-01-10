<?php
  if($partner->getLogo() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $partner->getLogo()))
  {
    echo '<img style="max-width:200px;max-height:75px;" src="/u/i/' . $partner->getLogo() . '">';
  }