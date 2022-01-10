<table cellpadding="0" cellspacing="0" width="100%" class="pc_tabs">
  <tr>
    <?php

    foreach($items as $url => $item)
    {
      $gen_url = url_for($url);
      $current = $sf_request->getPathInfo() == $gen_url;
      $cur_tag = $current ? 'span' : 'a';

      $sum_count = '';
      if($gen_url == url_for('@personal_account_index'))
      {
        $sum_count = 'data-count="' . $question_count . '"';
      }
      elseif($gen_url == url_for('@personal_account_patient_card'))
      {
        $sum_count = 'data-count="' . $patient_card_count->getPatientCardCount() . '"';
      }

      echo '<td><'. $cur_tag .' ' . $sum_count . ' href="' . $gen_url . '" class="pc_tabs__link' . ($cur_tag == 'a' ? '' : ' pc_tabs__link_active') . '">' . $item['title'] . '</'. $cur_tag .'></td>';
    }
    ?>
  </tr>
</table>