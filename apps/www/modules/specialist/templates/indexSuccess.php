<?php
if(!$param_ajax)
{
slot('title', 'Специалисты');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
  <h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Специалисты');?></h1>
  <div class="sorting">
  <?php
  $sort_doc = array();

  $valid_sort = array(
    'rating' => 'по рейтингу',
    'first_name' => 'по алфавиту'
  );

  if ($sf_request->hasParameter('sort_name') && $sf_request->hasParameter('sort_type')) {
    $c_sort_name = $sf_request->getParameter('sort_name');
    $c_sort_type = $sf_request->getParameter('sort_type');
  } elseif ($sf_request->getCookie('sort_doc')) {
    $sort_doc = explode(':', $sf_request->getCookie('sort_doc'));
    $c_sort_name = $sort_doc[0];
    $c_sort_type = $sort_doc[1];
  } else {
    $c_sort_name = 'rating';
    $c_sort_type = 'desc';
  }
  foreach ($valid_sort as $key => $valid_sort__item) {
    echo '<a onclick="sortAjax.onSort(this, event);return false;" href="' . url_for('@specialist_index') . '?sort_name=' . $key . '&sort_type=' . ($c_sort_type == 'asc' ? 'desc' : 'asc') . '" class="sorting__link ' . ($key == $c_sort_name ? 'sorting__link_active' : '') . ' sorting_' . $c_sort_type . '"><span>' . $valid_sort__item . '</span></a>';
  }
  ?>

</div>
<script type="text/javascript">

  var sortAjax = {
    item: null,

    onSort: function (currElem, event) {
      var _this = this;
      var $currElem = $(currElem);
      var param = $currElem.attr('href').replace('/specialist/?','').split('&');

      var param1 = param[0].split('=');
      var param2 = param[1].split('=');

      var res_param = {
        sort_name : param1[1],
        sort_type : param2[1],
        param_ajax: 'y'
      };

      var sType, sTypeR, sTypeE, sHrefE;
      if($currElem.index() == 0){
        sType = 'rating';
      }else{
        sType = 'first_name';
      }
      if($currElem.hasClass('sorting_desc')) {
        sTypeR = 'sorting_desc';
        sTypeE = 'sorting_asc';
        sHrefE = '/specialist/?sort_name=' + sType + '&sort_type=desc';
      }else{
        sTypeR = 'sorting_asc';
        sTypeE = 'sorting_desc';
        sHrefE = '/specialist/?sort_name=' + sType + '&sort_type=asc';
      }

      $currElem
        .removeClass(sTypeR)
        .addClass(sTypeE)
        .attr('href', sHrefE);

      if(!$currElem.hasClass('sorting__link_active')) {
        $('.sorting__link').removeClass('sorting__link_active');
        $currElem.addClass('sorting__link_active');
      }

      var sp_f = $('.specialist_filter_form').eq(0).serialize();

      if(sp_f){
        res_param.sp_arr = sp_f;
      }

      $('.specialist_preloader').show();

      if(!$(this).hasClass('.sorting__link_active')){
        $.ajax({
          url: '/specialist_filter/',
          type: 'GET',
          data: res_param,
          success: function(data){
            $('.specialist_ajax').html(data);
            $(data).load(function () {
              $('.specialist_preloader').hide();
            });
            resizeSpecialistHeight();
          }
        });
      }
    },

    init: function(){
      var _this = this;
    }
  }

  $(document).ready(function(){
    sortAjax.init();
  })
</script>
<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td class="specialist_ajax" width="100%">
<?php
}
      include_component('specialist', 'specialist', array('view' => 'horizontall', 'sort_doc' => $sort_doc, 'param_ajax' => $param_ajax));
if(!$param_ajax)
{
?>
    </td>
    <td width="1" style="padding-left: 20px;">
      <?php
      include_component('main', 'specialty', array('table' => 'Specialist'));
      ?>
    </td>
  </tr>
</table>
<?php
}
?>
<script type="text/javascript">
  var specialistSize = function () {
    $(window).resize(function(){
      resizeSpecialistHeight();
    }).resize();
  };
  $(document).ready(function () {
    specialistSize();
  });
</script>