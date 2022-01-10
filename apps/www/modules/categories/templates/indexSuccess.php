<?php
slot('title', 'Рубрикатор');
use_javascript('masonry.pkgd.min.js');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Рубрикатор');?></h1>
<?php include_partial('categories_index');?>
<script type="text/javascript">
  var masonry = function () {
    $('.tips_page_wrap').masonry({
      itemSelector: '.tips_page__item',
      percentPosition: true
    });
  };
  masonry();

  $(document).ready(function(){
    var scId = '<?php echo $sc_id;?>'
    if(scId != ''){

      $('.filter_tags__item').each(function (i, elem) {
        if($(elem).data('specialty_id') == scId){
          $(elem).click();
        }
      });
    };

    $(window).resize(function(){
      resizeSpecialistHeight('c');
    }).resize();
  });
</script>