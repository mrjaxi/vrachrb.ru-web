<?php

if($show_location == 'bottom')
{
  $permit = 2;
  $mr = 'margin-right:25px;';
}
else
{
  $permit = count($banners);
  $mr = '';
}

foreach ($banners as $banner_key => $banner)
{
  $show_location == 'bottom' ? $active = 1 : $active = $banner['is_activated'];

  echo ($banner_key == 0 && $show_location == 'bottom') ? '<i class="br40"></i>' : '';
  if($banner_key < $permit && $banner['is_activated'] == $active)
  {
    if($sf_request->getPathInfo() != url_for('@documentation_index') || $show_location == 'document')
    {
      echo '<div class="public_institution" style="' . $mr . 'background: url(/u/i/' . Page::replaceImageSize($banner['photo'], 'S') . ') no-repeat 0% 50%,#fff;">';
      echo $banner['title'];
      echo '<i class="br5"></i>';
      if($banner['link'])
      {
        echo '<a target="_blank" href="http://' . $banner['link'] . '" class="public_institution__link">http://' . $banner['link'] . '</a>';
      }
      echo '</div>';
    }
  }
}