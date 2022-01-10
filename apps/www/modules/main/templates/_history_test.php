<?php
slot('title', 'История анализов');
use_javascript('fotorama.js');
use_stylesheet('fotorama.css');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>

  <?php
  $prefix = 'doctor';
  if($profile == 'u')
  {
    $prefix = 'personal';
  }
  $add_link = '';
  if($location == 'show')
  {
    $add_link = '<a href="' . url_for('@account_history_test?user_id=' . $sf_request->getParameter('user_id')) . '">История анализов</a>';
  }
  echo '<a href="' . url_for('@' . $prefix .'_account_index') . '">Личный кабинет</a>';
  echo $add_link;
  ?>

</div>
<h2>История анализов</h2>

<?php
$open_analysis_photo = Page::replaceImageSize($analysis_type[0]['Analysis'][0]['photo'], 'M');

$analysis_result = '<div class="pc_history__menu fl_l">';
$analysis_elements = '';
foreach ($analysis_type as $at_key => $at)
{
  $active_class = '';
  $active_drop_class = '';
  if($at_key == 0 && !$sf_request->getParameter('id'))
  {
    $active_class = 'pc_history__menu__link_active';
    $active_drop_class = 'visible_drop';
  }
  $analysis_result .= '<a onclick="historyTestClick(this);return false;" data-analysis_type_id="' . $at['id'] . '" class="analysis_replace_class_' . $at['id'] . ' analysis_type_elem pc_history__menu__link ' . $active_class . '" ' . ($at['description'] ? 'title="' . $at['description'] . '"' : '') . '>' . $at['title'] . '</a>';
  $analysis_elements .= '<div data-analysis_type_id="' . $at['id'] . '" class="' . $active_drop_class . ' at_id_' . $at['id'] . ' pc_history__menu_drop fl_l">';
  foreach ($at['Analysis'] as $analysis_key => $analysis)
  {
    $analysis_active_class = '';
    if(($at_key == 0 && $analysis_key == 0 && !$sf_request->getParameter('id')) || $analysis['id'] == $sf_request->getParameter('id'))
    {
      $analysis_active_class = 'pc_history__menu_drop__link_active';
      $analysis_type_active_id = $analysis['analysis_type_id'];

      $open_analysis_photo = Page::replaceImageSize($analysis['photo'], 'M');
    }
    $analysis_elements .= '<a data-analysis_photo="' . Page::replaceImageSize($analysis['photo'], 'M') . '" onclick="historyTestClick(this);" class="pc_history__menu_drop__link ' . $analysis_active_class . '">' . Page::rusDate($analysis['created_at']) . '</a>';
  }
  $analysis_elements .= '</div>';
}
$analysis_result .= '</div>';
?>

<div class="overlay overlay_photo" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();">
  <div class="overlay__close">×</div>
  <table width="100%" height="100%" onclick="event.stopPropagation();" style="position: relative;top: -100%;">
    <tr>
      <td valign="middle" align="center">
        <div class="fotorama" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-min-width="300" data-max-width="90%" data-min-height="300" data-max-height="90%" data-hash="false">
          <?php
          echo '<img src="/u/i/' . $open_analysis_photo . '" />'
          ?>
        </div>
      </td>
    </tr>
  </table>
</div>

<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      include_component($prefix . '_account', 'menu');
      ?>
    </td>
    <td width="1" style="padding-left: 20px;">
      <img src="/i/n.gif" width="300" height="0" />
    </td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td style="padding-right: 40px;">
      <b id="open_analysis_date"><?php echo Page::rusDate($analysis_type[0]['Analysis'][0]['created_at']);?></b>
      <i class="br10"></i>
      <img onclick="showPhotoAnalysis.onClick(this, event, 'history_test')" data-analysis_photo="<?php echo $open_analysis_photo;?>" class="open_analysis_photo" src="/i/n.gif" width="100%" height="320" style="background: url('/u/i/<?php echo $open_analysis_photo;?>') no-repeat 50% 50%;" />
      <i class="br20"></i>
    </td>
    <td width="1">
      <b>История анализов</b>
      <i class="br10"></i>
      <div class="pc_history__menu_wrap clearfix">

        <?php
        echo $sf_request->getParameter('id') ? str_replace('analysis_replace_class_' . $analysis_type_active_id, 'pc_history__menu__link_active', $analysis_result) : $analysis_result;
        echo $sf_request->getParameter('id') ? str_replace('at_id_' . $analysis_type_active_id, 'visible_drop', $analysis_elements) : $analysis_elements;
        ?>

      </div>
    </td>

  </tr>
</table>