<?php
$article = $comment->getArticle();
$photo = Page::replaceImageSize($article->getPhoto(), 'S');
if($article && file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $photo) && $photo)
{
  echo '<img style="max-width: 70px;max-height: 70px;" src="/u/i/' . $photo . '">';
}