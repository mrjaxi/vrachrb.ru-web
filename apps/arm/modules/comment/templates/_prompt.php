<?php
$prompt = $comment->getPrompt();
$photo = $prompt->getPhoto();
if($prompt)
{
  if(file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $photo) && $photo)
  {
    echo '<img style="max-width: 70px;max-height: 70px;" src="/u/i/' . $photo . '">';
  }
  else
  {
    echo mb_substr($prompt->getBody(), 0, 100, 'utf-8');
  }
}