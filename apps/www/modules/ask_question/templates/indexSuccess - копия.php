<?php
  slot('title', 'Задать вопрос');
  use_javascript('//ulogin.ru/js/ulogin.js');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>

<?php
//$a = Doctrine_Query::create()
//  ->select('s.*,sf.*')
//  ->from('SheetHistory s')
//  ->innerJoin('s.SheetHistoryField sf')
//  ->fetchArray();
//
//print_r($a);
?>


<h2>Задать вопрос</h2>
<div class="ask_q_page">
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="100%">
        <table cellpadding="0" cellspacing="0" width="100%" class="question_tabs">
          <tr>
            <td class="question_tabs__item" data-tab="0">1.&nbsp;Выбор специалиста или кабинета врача</td>
            <td class="question_tabs__item" data-tab="1">2.&nbsp;Описание симптомов</td>
            <td class="question_tabs__item" data-tab="2">3.&nbsp;Лист анамнеза</td>
            <td class="question_tabs__item" data-tab="3">4.&nbsp;Личные данныe</td>
          </tr>
        </table>
        <div class="question_cont_tab" data-tab="0">
          <div class="ask_quest_page__section">
            <h3>Выберите кабинет</h3>
            <div class="q_cabinets white_box clearfix">
              <?php
              foreach ($specialty as $item)
              {
                echo '<label><input type="radio" name="q_cabinet" class="n_cust q_cabinets__item_inp" /><span class="q_cabinets__item q_cabinets__item_active">' . $item['title'] . '</span></label>';
              }
              ?>
              <label><input type="radio" name="q_cabinet" class="n_cust q_cabinets__item_inp" /><span class="q_cabinets__item q_cabinets__item_blue">Затрудняюсь выбрать</span></label>
            </div>
            <h3>Или врача</h3>
            <div class="sorting">
              <a href="desc" class="sorting__link sorting__link_active sorting_desc rating_sort"><span>по рейтингу</span></a>
              <a href="desc" class="sorting__link sorting_asc symbol_sort"><span>по алфавиту</span></a>
            </div>


            <div style="position:relative;">
              <img class="specialist_preloader" src="/i/preloader.GIF" alt="" height="40" width="40">
              <div class="specialist_page white_box clearfix" style="display: flex;flex-flow: row wrap;visibility: hidden;">
                <?php
                foreach ($specialists as $specialist)
                {
                  ?>
                  <label class="specialist_page__item fl_l">
                    <input type="radio" class="n_cust rating_doctors__item_inp" name="q_specialist" />
                    <div class="rating_doctors__item rating_doctors_online">
                      <div class="rating_doctors__item__photo" <?php echo ($specialist['User']['photo'] ? 'style="background-image: url(/u/i/' . Page::replaceImageSize($specialist['User']['photo'],'S') . ');"' : '');?>></div>
                      <a href=""><?php echo $specialist['User']['first_name'] . ' ' . $specialist['User']['second_name'];?></a>
                      <i class="br5"></i>
                      <div class="rating_doctors__item__pos"><?php echo $specialist['about'];?></div>
                      <table cellpadding="0" cellspacing="0" class="rating_doctors__item__num">
                        <tr valign="top">
                          <td class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;"><span class="fs_20"><?php echo number_format($specialist['rating'], 1, ',', '');?></span><br/><span class="fs_13">рейтинг</span></td>
                          <td class="tcolor_red" style="padding-left: 10px;"><span class="fs_20"><?php echo number_format($specialist['answers_count'], 0, ',', ' ');?></span><br/><span class="fs_13">консультаций</span></td>
                        </tr>
                      </table>
                    </div>
                  </label>
                  <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>


        <div class="question_cont_tab" data-tab="1">

          <h3>Опишите симптомы</h3>
          <div class="white_box clearfix ask_quest_page__section">
            Вопрос касается вас или члена вашей семьи?
            <i class="br10"></i>
            <label style="margin-right: 40px;"><input type="radio" name="who_does" onchange="$('.who_does_family').slideUp(200);" checked="checked" />Меня</label>
            <label><input type="radio" name="who_does" onchange="$('.who_does_family').slideDown(200);" />Члена семьи</label>
            <div class="who_does_family" style="display: none;">
              <i class="br10"></i>
              <input type="text" placeholder="Фамилия" />
              <input type="text" placeholder="Имя" />
              <input type="text" placeholder="Отчество" />
              <i class="br5"></i>
              <input type="text" placeholder="Дата рождения" />
            </div>
            <i class="br20"></i>
            <textarea name="" id="" rows="10" style="width:100%;" placeholder="..."></textarea>
          </div>

        </div>


        <div class="question_cont_tab" data-tab="2">

          <h3>Ответьте на вопросы</h3>
          <div class="q_sheet_history white_box clearfix ask_quest_page__section">
            Уважаемые пациенты и гости сервиса. С целю обеспечения вас квалифицированной консультацией, быстроты ответа на поставленные вопросы и дальнейшего исполнения вами наших рекомендаций, мы решили разработать для вас перечень вопросов, которые облегчат нашу работу и наше с вами общение
            <i class="br30"></i>
            <ol class="q_sheet_history__ol">
              <li>
                Сколько продолжается болезнь?
                <i class="br10"></i>
                <input type="text" size="5" />
              </li>
              <li>
                Есть ли аллергические реакции?
                <i class="br10"></i>
                <label style="margin-right: 40px;"><input type="radio" name="allergic_reactions" onchange="$('.allergic_reactions_y').slideUp(200);" checked="checked" />Нет</label>
                <label><input type="radio" name="allergic_reactions" onchange="$('.allergic_reactions_y').slideDown(200);" />Да</label>
                <div class="allergic_reactions_y" style="display: none;">
                  <i class="br10"></i>
                  <input type="text" placeholder="На что аллергия?" size="60" />
                </div>
              </li>
              <li>
                Какая сейчас температура?
                <i class="br10"></i>
                <input type="text" size="5" />&nbsp;°C
              </li>
              <li>
                Есть ли боли?
                <i class="br10"></i>
                <label style="margin-right: 40px;"><input type="radio" name="there_pain" onchange="$('.there_pain_y').slideUp(200);" checked="checked" />Нет</label>
                <label><input type="radio" name="there_pain" onchange="$('.there_pain_y').slideDown(200);" />Да</label>
                <div class="there_pain_y" style="display: none;">
                  <i class="br10"></i>
                  <input type="text" placeholder="В какой области?" size="60" />
                  <i class="br10"></i>
                  <select style="width: auto;" name="nature_pain">
                    <option value="">Характер болей</option>
                    <option value="1">Острая</option>
                    <option value="2">Тупая</option>
                    <option value="3">Тянущая</option>
                  </select>
                </div>
              </li>
              <li>
                Были ли травмы, операции у пациента?
                <i class="br10"></i>
                <label style="margin-right: 40px;"><input type="radio" name="trauma_patient" onchange="$('.trauma_patient_y').slideUp(200);" checked="checked" />Нет</label>
                <label><input type="radio" name="trauma_patient" onchange="$('.trauma_patient_y').slideDown(200);" />Да</label>
                <div class="trauma_patient_y" style="display: none;">
                  <i class="br10"></i>
                  <textarea name="description_injuries" placeholder="Описание травм или операций" cols="60" rows="5"></textarea>
                </div>
              </li>
              <li>
                Есть ли жалобы со стороны костной, сердечно-сосудистой, дыхательной, мочеполовой, эндокринной, пищеварительной, кровеносной систем? (боли, выделения, одышка, кашель, покалывание, тремер, раздраженность, изжога, отеки и т.д.)
                <i class="br10"></i>
                <textarea id="" cols="60" rows="5"></textarea>
              </li>
              <li>
                Какой вес и рост пациента?
                <i class="br10"></i>
                <input type="text" placeholder="Рост" size="5" />&nbsp;см&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Вес" size="5" />&nbsp;кг
              </li>
              <li>
                Какого числа вы были у врача последний раз
                <i class="br10"></i>
                <input type="text" placeholder="дд.мм.гггг" size="10" />
              </li>
              <li>
                Какие анализы сдавали? (если сдавали, то приложить)
                <i class="br10"></i>
                <textarea name="" id="" cols="60" rows="5"></textarea>
                <i class="br10"></i>
                <div>
                  <b>Прикрепите фотографии анализов</b>
                  <i class="br5"></i>
                  <input type="file">
                </div>
              </li>
              <li>
                Какие методы диагностики прошли? (если прошли, то нужно приложить)
                <i class="br10"></i>
                <textarea name="" id="" cols="60" rows="5"></textarea>
                <i class="br10"></i>
                <div>
                  <b>Прикрепите фотографии анализов</b>
                  <i class="br5"></i>
                  <input type="file">
                </div>
              </li>
              <li>
                Какое лечение получаете, и кто его назначил? (врач или самостоятельно)
                <i class="br10"></i>
                <textarea name="" id="" cols="60" rows="5"></textarea>
              </li>
              <li>
                Какой результат от консультации хотите получить?
                <i class="br10"></i>
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr valign="top">
                    <td width="50%">
                      <label><input type="radio" name="consult_result" checked="checked" />Просто информацию</label>
                      <i class="br10"></i>
                      <label><input type="radio" name="consult_result" />Узнать методы обследования при вашем заболевании</label>
                      <i class="br10"></i>
                      <label><input type="radio" name="consult_result" />Узнать, как оказать первую помощь</label>
                    </td>
                    <td width="50%">
                      <label><input type="radio" name="consult_result" />Получить рекомендации и в последующем обратиться к врачу на очный прием</label>
                      <i class="br10"></i>
                      <label><input type="radio" name="consult_result" />Получить совет, так как вам некуда обратиться и вы не знаете что делать</label>
                      <i class="br10"></i>
                      <label><input type="radio" name="consult_result" />Вы сомневаетесь в назначенном вам лечении у вашего врача и решили убедиться правильное ли оно</label>
                    </td>
                  </tr>
                </table>


              </li>
            </ol>
          </div>

        </div>

        <div class="question_cont_tab" data-tab="3">

          <h3>Личные данные</h3>
          <div class="login_page">
            <div class="white_box clearfix ask_quest_page__section">
              <table class="question_cont_tab__auth registration_form__wrap" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top" style="padding-right: 20px;border-right: 1px solid #d8dfe0;" width="50%">
                    <?php include_component('user', 'login');?>
                  </td>
                  <td valign="top" class="auth_form__wrap" width="50%" style="padding-left: 20px;">
                    <?php include_component('user', 'register');?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          Вашу фамилию и имя будет видеть только лечащий врач и участники консилиума.
          <i class="br10"></i>

          Нажимая кнопку «Отправить»... я подтверждаю и понимаю, что рекомендации консультантов носят предварительно-информативный характер и не могут заменить очную консультацию специалиста.
          <i class="br20"></i>
          <label><input type="checkbox" />&nbsp;Задать анонимно</label>
          <i class="br20"></i>
        </div>















        <div style="display: none;" class="question_cont_tab test_tab" data-tab="4">
            <div class="white_box clearfix ask_quest_page__section">
              <table class="question_cont_tab__auth registration_form__wrap" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top" style="padding-right: 20px;border-right: 1px solid #d8dfe0;" width="100%">
                    <b class="fs_16">Ваш вопрос получен<i class="br10"></i>Специалисты ответят в кратчайшие сроки!</b>
                  </td>
                </tr>
              </table>
            </div>

          <i class="br20"></i>
        </div>




















        <div class="ask_q_page__bottom clearfix">
          <button class="btn_all blue_btn ask_q_page__bottom__btns ask_q_page__bottom__back fl_l" onclick="vrb.askQuestion.prevTabs(event);">Назад</button>
          <button class="btn_all blue_btn ask_q_page__bottom__btns ask_q_page__bottom__forward fl_r" onclick="vrb.askQuestion.nextTabs(event);test();">Далее</button>
        </div>
      </td>
      <td width="1" style="padding-left: 30px;">
        <div class="white_box" style="width:245px;font-size: 13px;">
          <p>Все рекомендации носят информационный или консультативный характер, обязательно нужен личный прием у врача и согласование с ним вашего лечения.</p>
          <p>Если вы разместили информацию о своей болезни в открытом доступе, то вы тем самым подтверждаете свое согласие на нахождение информации в открытом доступе сети интернет и вся ответственность по ее размещению ложиться на самого пациента, который разместил ее.</p>
          <p>Ждем Вас и очень рады, что вы уже пользуетесь нашим сервисом "ВРАЧ РБ - консультация онлайн".</p>
        </div>
      </td>
    </tr>
  </table>
</div>


<script type="text/javascript">
  $(document).ready(function(){
    var specItem = $('.specialist_page__item');
    var ratingItem = $('.rating_doctors__item');

    vrb.askQuestion.init();

    $(window).resize(function(){
      $('.rating_doctors__item').css('height', 'auto');
      specItem.each(function(i, elem){
        var elem = $(elem);
        var elemH = elem.height();
        elem.find('.rating_doctors__item').outerHeight(elemH);
      });
      $('.specialist_page').css('visibility', 'visible');

    }).resize();

    $('.sorting__link').click(function () {
      var param1, param2, href;
      var _this = $(this);
      var preloader =  $('.specialist_preloader');

      if(_this.hasClass('rating_sort')) {
        param1 = 'rating';
      }else{
        param1 = 'symbol';
      }

      $('.sorting__link').removeClass('sorting__link_active');
      href = _this.attr('href') == 'asc' ? 'desc' : 'asc';

      _this
        .attr('href', href)
        .removeClass('sorting_desc sorting_asc')
        .addClass('sorting_' + href + ' sorting__link_active');

      param2 = _this.attr('href');

      preloader.show();
      $.post('<?php echo url_for('@ask_question_specialist_filter');?>', 'param=' + param1 + ':' + param2 , function (data) {
        $('.specialist_page').html(data);
        preloader.hide();
      });
      return false;
    });


  })



  var test = function(){
    if($('.question_cont_tab').eq(3).is(':visible')){
      $('.ask_q_page__bottom__forward')
        .addClass('test')
        .removeAttr('onclick')
        .attr('onclick', '$(".question_cont_tab").hide();$(".ask_q_page__bottom").hide();$(".test_tab").show();console.log("asdasd");return false;');
    }
  };


</script>