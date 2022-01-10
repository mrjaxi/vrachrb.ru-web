<?php
if($message_error->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') .  '/i/' . $message_error->getPhoto()))
{
  echo '<img src="/u/i/' . Page::replaceImageSize($message_error->getPhoto(), 'S') . '" height="75" />';
}