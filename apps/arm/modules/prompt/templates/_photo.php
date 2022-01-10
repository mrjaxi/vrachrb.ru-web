<?php
if($prompt->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $prompt->getPhoto()))
{
  echo '<img src="/u/i/' . Page::replaceImageSize($prompt->getPhoto(),'S') . '" height="75" />';
}