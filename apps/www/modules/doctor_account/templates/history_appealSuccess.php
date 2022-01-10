<?php
  slot('title', 'История обращений');
?>

<div class="overlay dc_overlay_history_review" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <div class="overlay__white_box" onclick="event.stopPropagation();">
    <div class="fs_18" style="padding: 0 20px;">Отзыв от <span class="dc_overlay_history_review__author">Мокрины Рюриковны Арцыбашевой</span></div>
    <div class="ta_l overlay__white_box__body">
      <p>Самосогласованная модель предсказывает, что при определенных условиях аллюзийно-полистилистическая композиция mezzo forte ищет вращательный динамический эллипсис, хотя этот факт нуждается в дальнейшей тщательной экспериментальной проверке. Интервально-прогрессийная континуальная форма, и это следует подчеркнуть, начинает коммунальный модернизм. Вещество, за счет использования параллелизмов и повторов на разных языковых уровнях, отражает гипнотический рифф. Вещество регрессийно выталкивает резкий символ. Секстант использует наносекундный электрон, в таком случае эксцентриситеты и наклоны орбит возрастают.</p>
      <p>Аллегро полифигурно испускает нестационарный лимб. Соинтервалие, как и везде в пределах наблюдаемой вселенной, излучает осциллятор. Серпантинная волна, следуя пионерской работе Эдвина Хаббла, упруго начинает бозе-конденсат, хотя этот факт нуждается в дальнейшей тщательной экспериментальной проверке. Ощущение мономерности ритмического движения возникает, как правило, в условиях темповой стабильности, тем не менее соединение однородно вызывает центральный цикл.</p>
    </div>
    <div class="overlay__white_box__bottom">
      <table cellspacing="0" cellpadding="0" width="100">
        <tr valign="top">
          <td style="padding-right: 30px;">
            Информативность
            <i class="br5"></i>
            <div class="informative_stars_wrap">
              <input type="text" value="4" class="stars_plugin" />
            </div>
          </td>
          <td>
            Вежливость
            <i class="br5"></i>
            <div class="courtesy_stars_wrap">
              <input type="text" value="2" class="stars_plugin" />
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>


<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h2>Личный кабинет</h2>

<table class="ready_flash" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%">
      <?php
      echo '<div class="da_menu_wrap">';
        include_component('doctor_account', 'menu');
      echo '</div>';
      echo '<div class="review_wrap">';
        include_component('doctor_account', 'review');
      echo '</div>';
      ?>
    </td>
    <td width="1" style="padding-left: 20px;">
      <div class="notice_wrap">
        <?php include_component('main', 'notice', array('profile' => 's'));?>
      </div>
      <div class="ha_patient_filter_wrap">
        <?php include_component('doctor_account', 'ha_patient_filter');?>
      </div>
      <div style="min-width: 300px;"></div>
    </td>
  </tr>
</table>

<script type="text/javascript">
  var readReview = function(_this){
    var pcHItem, iStars, cStars, review, name;
    pcHItem = _this.closest('.pc_history__item');
    iStars = pcHItem.find('.informative_stars').find('.review_average_score__percent').clone();
    cStars = pcHItem.find('.courtesy_stars').find('.review_average_score__percent').clone();
    review = _this.next('.read_review').text();
    name = pcHItem.find('.pc_history__item__name').text();

    $('.overlay__white_box__body').text(review);
    $('.dc_overlay_history_review__author').text('«' + name + '»');
    $('.informative_stars_wrap').find('.review_average_score').html(iStars)
    $('.courtesy_stars_wrap').find('.review_average_score').html(cStars)

    overflowHiddenScroll($('.dc_overlay_history_review'));
  };
</script>



