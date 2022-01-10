<?php
$sort_doc = explode(':', $sf_request->getCookie('sort_doc'));
$sort_doc2 = explode('&amp;', $sort_doc[2]);
$sort_doc3 = array();
foreach ($sort_doc2 as $item)
{
  $sort_doc3[] = str_replace('specialty_array%5B%5D=', '', $item);
}

if(count($result) > 0)
{
  echo $table == 'Specialist' || $table == 'qa' ? '<div class="filter_block white_box"><b>Фильтр по кабинетам  врачей</b><i class="br10"></i><form class="specialist_filter_form"  >' : '';
  $i = 0;
  $active_class_all = 'filter_tags__item_active';
  foreach($result as $result_key => $result_item)
  {
    $i ++;
    foreach($sort_doc3 as $item)
    {
      if($item == $result_key)
      {
        $checked = 'checked';
        break;
      }
      else
      {
        $checked = '';
      }
    }
    if($table == 'Specialist' || $table == 'qa')
    {
      echo $result_item ? '<div class="filter_block__item"><label><input name="specialty_array[]" value="' . $result_key . '" data-specialty_id="' . $result_key . '" type="checkbox" onchange="filterAjax.' . ($advanced == 'qa' ? 'onQACheck' : 'onCheckGo') . '(this, event);"' . $checked . '>' . $result_item . '</label></div>' : '';
    }
    elseif($table == 'general' && $result_item)
    {
      echo '<li>';





      echo '<label class="categories_general_label"><input class="categories_general_checkbox" data-specialty_id="' . $result_key . '" onclick="filterAjax.onCategoriesGeneralFilter(this);" type="checkbox" class=""><' . ($categories_close[$result_key] == 'open' ? 'a href="' . url_for('@categories_transition_filter?id=' . $result_key) . '"' : 'div') . ' data-specialty_id="' . $result_key . '">' . $result_item . '</' . ($categories_close[$result_key] == 'open' ? 'a' : 'div') . '></label>';
      echo '</li>';
    }
    else
    {
      $ajax_fun = $categories == 'y' ? 'filterAjax.onCategoriesSelect(this, event);' : '';
      $active_class = '';
      if($categories == 'y')
      {
        if($sf_request->getParameter('id') == $result_key)
        {
          $active_class = 'filter_tags__item_active';
          $active_class_all = '';
        }
      }
      echo $result_item ? '<a href="" data-specialty_id="' . $result_key . '" class="filter_tags__item ' . $active_class . '" onclick="' . $ajax_fun . ($table == 'Prompt' ? 'filterAjax.onSelect(this, event);' : '' ) . ';return false;">' . $result_item . '</a>' : '';
      if($i == count($result))
      {
        $ajax_fun = str_replace("onQACheck(this, event)", "onQACheckClear('categories')", $ajax_fun);
        echo '<a href="" data-specialty_id="all" class="filter_tags__item ' . $active_class_all .'" onclick="' . ($categories != 'y' ? 'filterAjax.onSelect(this, event);' : '') . $ajax_fun . 'return false;">' . 'Все' . '</a>';
      }
    }
  }
  if($table == 'Specialist' || $table == 'qa')
  {
    echo '<i class="br20"></i><a href="" class="btn_all blue_btn enter_filter" style="width:100%;" onclick="filterAjax.' . ($advanced == 'qa' ? 'onQACheck()' : 'onCheckGo(this, event)') . ';return false;">Применить</a>';
    echo '<i class="br5"></i><a href="" class="btn_all b_blue_btn clear_filter" style="width:100%;" onclick="filterAjax.' . ($advanced == 'qa' ? 'onQACheckClear' : 'onCheckClear') . '();return false;">Сбросить</a></div></form>';
  }

  isset($_GET['year']) ? $year = $_GET['year'] : $year = false;
}

?>
<script type="text/javascript">
  var filterAjax = {

    item: null,

    onQACheck: function (currElem, event) {
      var _this = this;
      var $currElem = $(currElem);
      var param = $('.specialist_filter_form').length == 1 ? $('.specialist_filter_form').eq(0).serialize() : 'specialty_array%5B%5D=' + $currElem.data('specialty_id');
      $('.specialist_preloader').show();

      if($currElem.data('specialty_id') == null){
        param = '';
      }

      $.post('<?php echo url_for('@question_answer_filter');?>', param, function (data) {
        if($('.quest_page').length == 1){
          $('.quest_page').html(data);
        }else{
          $('.categories_qa_html').html(data);
        }

        $('.specialist_preloader').hide();
        $('.pagination_page').remove();
      })
    },

    onQACheckClear: function (elem) {
      if(elem != 'categories'){
        $('.custom_input').each(function(){
          $(this).removeProp('checked');
        });
      }
      filterAjax.onQACheck();
    },

    onCategoriesSelect: function (currElem, event) {
      var _this = this;
      var $currElem = $(currElem);
      var specialtyId = $currElem.data('specialty_id');

      $('.categories_page').css('opacity', 0.6);

      if(!$currElem.hasClass('filter_tags__item_active')){
        _this.item.removeClass('filter_tags__item_active');
        $currElem.addClass('filter_tags__item_active');
      };

      $.ajax({
        url: '/categories_filter/',
        type: 'POST',
        data: {
          sc: specialtyId
        },
        success: function (data) {
          var ajaxSpecialistAndArticle = data.split(':delimiter:');
          $('.categories__rating_doctors_anchor').html(ajaxSpecialistAndArticle[0]);
          $('.rating_doctors').css('padding-bottom', '0px');
          $('.categories__article_anchor').html(ajaxSpecialistAndArticle[1]);
          var moreStr = (ajaxSpecialistAndArticle[2].indexOf('Нет вопросов к выбранному специалисту') == -1 ? '<div class="categories_page__q_more"><span class="categories_page__q_more__item" onclick="filterAjax.onCategoriesMore(' + specialtyId + ');">Показать все</span></div>' : '');
          $('.categories_qa_html').html(ajaxSpecialistAndArticle[2] + moreStr);
          $('.tips_page_wrap').html(ajaxSpecialistAndArticle[3]);
          $('.categories_page').css('opacity', 1);
          $('.tips_page_wrap').masonry('destroy');
          $('.tips_page_wrap').masonry({
            itemSelector: '.tips_page__item',
            percentPosition: true
          });
          resizeSpecialistHeight('c');
        }
      });
    },

    onCategoriesMore: function(_id){
      $.post('/categories_filter/', 'sc=' + _id + '&limit=no', function(data){
        var ajaxSpecialistAndArticle = data.split(':delimiter:');
        $('.categories_qa_html').html(ajaxSpecialistAndArticle[2]);
      });
    },

    onSelect: function(currElem, event){
      var _this = this;
      var $currElem = $(currElem);
      var specialtyId = $currElem.data('specialty_id');

      if(!$currElem.hasClass('filter_tags__item_active')){
        _this.item.removeClass('filter_tags__item_active');
        $currElem.addClass('filter_tags__item_active');

        $.ajax({
          url: '/specialty_filter/',
          type: 'POST',
          data: {
            sc: specialtyId
          },
          success: function (data) {
            $('.tips_page_wrap').html(data);
            if(specialtyId != 'all')
            {
              $('.pagination_page').remove();
            }
          }
        });
      }
    },

    onCheckGo: function (currElem, event) {
      var _this = this;
      var param = $('.specialist_filter_form').eq(0).serialize();

      var sortParam = $('.sorting__link_active').attr('href').replace('/specialist/?','').split('&');

      var param1 = sortParam[0].split('=');
      var param2 = sortParam[1].split('=');

      if(param2[1] == 'asc'){
        param2[1] = 'desc';
      }else{
        param2[1] = 'asc';
      }

      if(param == '' || currElem == 'clear'){
        param = 'clear';
      }

      var res_param = {
        sort_name: param1[1],
        sort_type: param2[1],
        param_ajax: "y",
        sp_arr: param
      };

      $('.specialist_preloader').show();
      $.ajax({
        url: '/specialist_filter/',
        type: 'POST',
        data: res_param,
        success: function (data) {
          $('.specialist_ajax').html(data);
          $(data).load(function () {
            $('.specialist_preloader').hide();
          });
          $(window).resize(function(){
            resizeSpecialistHeight();
          }).resize();
        }
      });
      specialistSize();
    },

    onCheckClear: function () {
      $('.custom_input').each(function(){
        $(this).removeProp('checked');
      });
      filterAjax.onCheckGo('clear');
    },

    onCategoriesGeneralFilter: function(){
      gfArr = [];

      clearTimeout(liveBandTime);

      cgSpecialtyId = '';
      var c = 0;
      $('.categories_general_checkbox').each(function (_idx, _elem) {
        if($(_elem).is(':checked')){
          var sfSpecialtyId = $(_elem).data('specialty_id');
          cgSpecialtyId += (c > 0 ? 'and' : '') + sfSpecialtyId;
          gfArr.push(sfSpecialtyId);
          c ++;
        }
      });

      liveBandOpacity(true);
      liveBandUpdate();
      doctorGeneralSort(gfArr, 'doctor');
    },

    init: function(){
      var _this = this;
      _this.item = $('.filter_tags__item');
      gfArr = [];
    }
  };

  $(document).ready(function(){
    filterAjax.init();
  })
</script>