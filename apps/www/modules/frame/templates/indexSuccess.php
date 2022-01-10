<h1 class="dn"><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : sfConfig::get('app_www_title'));?></h1>
<div class="main_cont">
  <div class="main_cont__slider cycle-slideshow" data-slides="> div" data-cycle-fx="scrollHorz" data-cycle-pause-on-hover="true" data-cycle-timeout="5000" data-cycle-pager=".main_cont__slider__circle">
    <div class="main_cont__slider__item main_cont__slider__item_tab_3">
      <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
          <td class="main_cont__slider__item__text" width="100%">
            Поддержав проект вы поможете и другим людям в их беде! Все собранные средства пойдут на развитие сервиса.
            <i class="br30"></i>
            <a onclick="yaCounter36726625.reachGoal('MAINDONATE');" href="<?php echo url_for('@donate'); ?>" class="btn_all blue_btn btn_all_middle fs_18" style="border-radius:30px;">Поддержать проект</a>
          </td>
          <td align="left" style="padding-right: 150px;">
            <img src="/i/slider_slide_3.svg" width="237" style="margin-top:-15px;" height="239" />
          </td>
        </tr>
      </table>
    </div>
    <div class="main_cont__slider__item">
      <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
          <td class="main_cont__slider__item__text" width="100%">
            Вы можете в режиме онлайн задать вопросы квалифицированным врачам разных специальностей
            <i class="br20"></i>
            <a onclick="yaCounter36726625.reachGoal('MAINASK');" href="<?php echo url_for('@ask_question'); ?>" class="btn_all green_btn btn_all_middle fs_18" style="border-radius:30px;">Задать вопрос</a>
          </td>
          <td style="padding-right: 60px;">
            <img src="/i/slider_slide_1.png" width="373" height="283" />
          </td>
        </tr>
      </table>
    </div>
    <div class="main_cont__slider__item">
      <table cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
          <td class="main_cont__slider__item__text" width="100%">
            Или обратиться к рубрикатору, чтобы найти информацию в уже существующих ответах, статьях и советах.
            <i class="br30"></i>
            <a href="<?php echo url_for('@categories_index');?>" class="fs_16">Перейти в рубрикатор</a>
          </td>
          <td style="padding-right: 60px;">
            <img src="/i/slider_slide_2.png" width="373" height="283" />
          </td>
        </tr>
      </table>
    </div>
    <span class="main_cont__slider__circle"></span>
  </div>
  <?php include_component('main', 'main');?>
</div>