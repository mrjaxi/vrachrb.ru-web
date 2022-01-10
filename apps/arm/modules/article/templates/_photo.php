<?php
if($article->getPhoto() && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $article->getPhoto()))
{
  echo '<img height="75" src="/u/i/' . Page::replaceImageSize($article->getPhoto(),'S') . '">';
}