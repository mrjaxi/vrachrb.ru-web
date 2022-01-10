<table class="da_menu_table pc_tabs" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <?php
    foreach($items as $url => $item)
    {
      $gen_url = url_for($url);
      $current = $sf_request->getPathInfo() == $gen_url;
      if($sf_request->getPathInfo() ==  url_for('@doctor_account_posting_article') && $gen_url == url_for('@doctor_account_posting'))
      {
        $current = $gen_url;
      }
      $cur_tag = $current ? 'span' : 'a';
      $sum_count = 1;

      $url_arr = explode('_', $url);
      $update_class = 'da_menu_' . $url_arr[count($url_arr) - 1];

      if($gen_url == url_for('@doctor_account_index'))
      {
        $sum_count = $count_arr[0]['questions_count'];
      }
      elseif($gen_url == url_for('@doctor_account_consilium'))
      {
        $sum_count = $count_arr[0]['consiliums_count'];
      }
      elseif($gen_url == url_for('@doctor_account_history_appeal'))
      {
        $sum_count = $count_arr[0]['reviews_count'];
      }
      elseif($gen_url == url_for('@doctor_account_posting_article') || $gen_url == url_for('@doctor_account_posting'))
      {
        $sum_count = $count_arr[0]['prompts_count'] + $count_arr[0]['articles_count'];
      }
      $data_count = ' data-count="' . $sum_count . '"';

      if($gen_url == url_for('@doctor_account_data'))
      {
        $data_count = '';
      }

      echo '<td><'. $cur_tag .' ' . $data_count . ' href="' . $gen_url . '" class="pc_tabs__link' . ($cur_tag == 'a' ? '' : ' pc_tabs__link_active') . ' ' . $update_class . '">' . $item['title'] . '</'. $cur_tag .'></td>';
    }
    ?>
  </tr>
</table>