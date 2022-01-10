<?php
if($advertising->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $advertising->getPhoto()))
{
  echo '<img src="/u/i/' . Page::replaceImageSize($advertising->getPhoto(), 'S') . '" style="max-width:100px;max-height:75px;" />';
}