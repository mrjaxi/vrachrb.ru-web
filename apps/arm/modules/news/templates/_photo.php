<?php
if($news->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') .  '/i/' . $news->getPhoto()))
{
  echo '<img src="/u/i/' . Page::replaceImageSize($news->getPhoto(), 'S') . '" height="75" />';
}