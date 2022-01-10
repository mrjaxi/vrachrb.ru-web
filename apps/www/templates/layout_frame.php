<?php include(sfConfig::get('sf_app_template_dir') . '/header.php'); ?>
<link rel="stylesheet" href="/css/frame.css" />
<div class="body_wrapper_wrapper">
  <table class="body_wrapper" width="100%" cellspacing="0" cellpadding="0" height="100%">
    <tr>
      <td height="100%" align="center">
        <table class="root_table" width="100%" height="100%" cellspacing="0" cellpadding="0">
          <tr valign="top">
            <td class="root_table__top" align="center">

              <div class="header_wrap">
                <div class="max_width">
                  <div class="header">
                    <div class="menu_wrap">
                      <div class="search">
                        <form action="<?php echo url_for('search') ?>">
                          <input value="<?php echo $sf_request->getParameter('q') ?>" type="text" name="q" class="search__inp" placeholder="Поиск" required="required" />
                        </form>
                      </div>
                    </div>

                    <?php
                    if($sf_request->getPathInfo() == '/')
                    {
                      include_component('main', 'stat');
                    }
                    else
                    {
                      
                    }
                    ?>
                  </div>
                </div>
              </div>

            </td>
          </tr>
          <tr valign="top">
            <td class="root_table__middle" align="center" height="100%">
              <div class="max_width">
                <?php echo $sf_content; ?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<?php include(sfConfig::get('sf_app_template_dir') . '/footer.php'); ?>