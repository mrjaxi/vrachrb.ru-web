<?php
$attachment_exp = explode(';', $files);
echo '<div class="attachment__files">';
foreach ($attachment_exp as $att)
{
  $att_exp = explode('.', $att);
  $att_format = $att_exp[count($att_exp) - 1];
  if($att_format != 'png' && $att_format != 'jpg' && $att_format != 'jpeg')
  {
    echo '<a ' . ($att_format == 'pdf' ? 'target="_blank"' : '') . ' href="/u/i/' . $att . '" class="uploader_preview__item ui-sortable-handle" data-val="' . $att . '"><div class="attachment document_file_icon"></div></a>';
  }
  else
  {
    echo '<img onclick="showAttachment(this, \'image\');" class="attachment attachment__photo uploader_preview__item" src="/u/i/' . Page::replaceImageSize($att, 'S') . '">';
  }
}
echo '</div>';