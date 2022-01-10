<div class="menu_wrap">
  <table class="menu_table" cellspacing="0" cellpadding="0">
    <tr>
      <?php
      foreach($items as $url => $item)
      {
        $gen_url = url_for($url);
        $ex_path = explode('/', $sf_request->getPathInfo());

        $page_arr = array('tip', 'news', 'article', 'categories');
        if(in_array($ex_path[1], $page_arr) && $ex_path[2] == 'page')
        {
          $current = $sf_request->getPathInfo() == $gen_url || ('/' . $ex_path[1] . '/') == $gen_url;
        }
        else
        {
          $current = $sf_request->getPathInfo() == $gen_url;
        }
        $cur_tag = $current ? 'span' : 'a';
        echo '<td><' . $cur_tag . ' href="' . $gen_url . '" class="menu__link"><span>' . $item['title'] . '</span></'. $cur_tag .'></td>';
      }
      ?>
    </tr>
  </table>
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
  echo '<i class="br20"></i>';
}
?>