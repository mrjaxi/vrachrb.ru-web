<?php include(sfConfig::get('sf_app_template_dir') . '/header.php'); ?>
<div class="body_wrapper_wrapper">
  <table class="body_wrapper" width="100%" cellspacing="0" cellpadding="0" height="100%">
    <tr>
      <td height="100%" align="center">
        <table class="root_table" width="100%" height="100%" cellspacing="0" cellpadding="0">
          <tr valign="top">
            <td class="root_table__top" align="center">

              <?php include(sfConfig::get('sf_app_template_dir') . '/top.php'); ?>

            </td>
          </tr>
          <tr valign="top">
            <td class="root_table__middle" align="center" height="100%">
              <div class="max_width">
                <?php echo $sf_content; ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="root_table__bottom" align="center">

              <?php include(sfConfig::get('sf_app_template_dir') . '/bottom.php'); ?>

            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<?php include(sfConfig::get('sf_app_template_dir') . '/footer.php'); ?>