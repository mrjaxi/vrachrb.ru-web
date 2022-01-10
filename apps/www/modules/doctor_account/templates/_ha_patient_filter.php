<?php
if(count($patients) > 0)
{
?>
  <form class="white_box filter_block ha_filter_form" style="width:300px;">
    <b>Фильтр по пациентам</b>
    <i class="br20"></i>
    <?php

    $repeat_check = array();
    foreach ($patients as $patient)
    {
      $user = $patient['Question']['User'];
      if(!in_array($user['id'], $repeat_check))
      {
        if($patient['Question']['is_anonymous'] != 1)
        {
          $name = $user['first_name'] ? $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['second_name'] : $user['username'];
        }
        else
        {
          $age = date('Y') - substr($user['birth_date'], 0, 4);
          $name = 'Анонимно, ' . mb_strtolower(Page::nameAge($age, $user['gender'])) . ' ' . ($age > 0 ? $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '');
        }
        echo '<div class="filter_block__item"><label class="confirmation_checkbox"><input type="checkbox" name="patient[' . $user['id'] . ']" onchange="HaReviewFilter.onFilter();" />' . $name . '</label></div>';
      }
      $repeat_check[] = $user['id'];
    }
    ?>
    <i class="br20"></i>
    <a href="" class="btn_all blue_btn enter_filter" style="width:100%;" onclick="HaReviewFilter.onFilter();return false;">Применить</a>
    <i class="br5"></i>
    <a href="" class="btn_all b_blue_btn clear_filter" style="width:100%;" onclick="HaReviewFilter.onClear();return false;">Сбросить</a>
  </form>
<?php
}
else
{
  echo '<div class="null_element" style="width:300px"></div>';
}
?>
<script type="text/javascript">
  var HaReviewFilter = {

    item: null,

    onFilter: function(elem){
      var el, preloader, elBox,
        sLink = $('.sorting__link');
      preloader =  $('.specialist_preloader');

      el = sLink.hasClass('sorting_desc') ? 'asc' : 'desc';
      if(elem == 'date'){
        sLink
          .removeClass('sorting_desc sorting_asc')
          .addClass('sorting_' + el);
      }else{
        el = el == 'asc' ? 'desc' : 'asc';
      }

      elBox = $('.ha_filter_form').eq(0).serialize();

      HaReviewFilter.onResult(el, elBox);
    },

    onClear: function () {
      $('.ha_filter_form').find('.custom_input[type="checkbox"]').each(function () {
        $(this).removeProp('checked');
      });
      HaReviewFilter.onFilter();
    },

    onResult: function (el, elBox) {
      var
        send,
        preloader = $('.specialist_preloader');

      send = 'sort=' + el + '&' + elBox;

      preloader.show();
      $.post('<?php echo url_for('@doctor_account_history_appeal_filter');?>', send, function(data){
        $('.pc_history').html(data);
        vrb.starsPlug.init();
        preloader.hide();
      });
    },
  };
</script>
