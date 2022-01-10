<?php
slot('title', __('О проекте'));
?>

  <div class="breadcrumbs">
    <a href="/">Главная</a>
  </div>
<?php
if($pages->getIsActivated() == 1)
{
  echo '<h1>' . ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $pages->getTitle()) . '</h1>';
  ?>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td>
        <div class="ftext" style="max-width:800px;">
          <?php echo $pages->getBody(ESC_RAW);?>
        </div>
      </td>
    </tr>
  </table>
  <?php
}
?>