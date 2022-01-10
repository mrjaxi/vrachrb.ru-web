<?php
  if($documentation->getFile() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $documentation->getFile()))
  {
    echo '<div data-val="' . $documentation->getFile() . '" class="document_file_wrap_icon"><div class="document_file_small_icon"></div></div>';
  }