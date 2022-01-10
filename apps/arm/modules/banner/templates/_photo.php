<?php
if($banner->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $banner->getPhoto()))
{
  echo '<img src="/u/i/' . Page::replaceImageSize($banner->getPhoto(), 'S') . '" height="75" />';
}