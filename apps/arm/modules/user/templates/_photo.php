<?php
if($user->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $user->getPhoto()))
{
  echo '<img height="75" src="/u/i/' . Page::replaceImageSize($user->getPhoto(),'S') . '">';
}