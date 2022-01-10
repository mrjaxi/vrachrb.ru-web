<style type="text/css">
  .rating_doctors{
    height: 0;
  }
</style>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td colspan="2" width="100%" class="main_columns">
      <div class="help_block white_box">
        <div class="help_block__middle">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td><span class="fs_25 ff_roboto_l">Вы можете в режиме онлайн задать вопросы квалифицированным врачам разных специальностей</span></td>
              <td><a onclick="yaCounter36726625.reachGoal('BANNERASK');" href="<?php echo url_for('@ask_question'); ?>" class="btn_all new_btn_all green_btn">Задать вопрос</a></td>
            </tr>
          </table>
        </div>
      </div>
    </td>
    <td rowspan="2" width="1" class="main_columns main_cont__height" style="padding-left: 20px;">
      <?php
      /*<td width="1" class="main_columns" style="padding-left: 20px;" rowspan="2">*/
      ?>

      <a href="<?php echo url_for('@specialist_index');?>" class="h_link anb">Врачи</a>
      <?php
      include_component('specialist', 'specialist', array('view' => 'vertical', 'type' => 'general'));
      ?>
      <?php
      /*
      echo '<a href="/news/" class="h_link anb">Новости</a>';
      include_component('news', 'news', array('general' => 'y'));
      */
      ?>
    </td>
  </tr>
  <tr valign="top">
    <td width="100%">
      <span class="h_link anb"><b>Живая лента</b></span>
      <div class="live_band white_box">
        <div onclick="liveBandUpdate('update');" class="live_band__update_btn">Обновить живую ленту</div>
        <div class="live_band__opacity">
          <?php include_component('main', 'live_band', array('type' => 'general'));?>
        </div>
        <div class="new_live_band__full_link_wrap">
          <a href="<?php echo url_for('@question_answer_index');?>" class="new_live_band__full_link">ВСЕ ВОПРОСЫ И ОТВЕТЫ</a><div class="new_live_band__full_link_after new_live_band__full_link_after_first"></div>
          <a href="<?php echo url_for('@tip_index');?>" class="new_live_band__full_link">ВСЕ СОВЕТЫ</a><div class="new_live_band__full_link_after"></div>
        </div>
      </div>
    </td>
    <td width="1" class="main_columns" style="padding-left: 20px;">
      <a href="<?php echo url_for('@categories_index');?>" class="h_link anb">Рубрикатор</a>
      <ul class="menu_doctors white_box">
        <?php include_component('main','specialty',array('table' => 'general'));?>
      </ul>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <?php include_component('article', 'article', array('year' => $year, 'general' => 'y'));?>
    </td>
    <td style="padding-left: 20px;padding-bottom: 20px;">
      <a href="//atma.company/ru/works/" target="_blank" class="anb"><img src="/i/n.gif" height="290" width="100%" class="poster_img" style="background-image: url(/i/poster_atma.png);" /></a>
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <a href="/partner/" class="h_link anb">Партнёры</a>
      <table cellspacing="0" cellpadding="0" width="100%" class="partners_table">
        <tr valign="top">
          <?php
          foreach ($partner as $key => $partner_item)
          {
            if($key != 0 && $key % 4 == 0)
            {
              echo '</tr>';
              echo '<tr><td colspan="4"><i class="br20"></i></td></tr>';
              echo '<tr>';
            }
            ?>
            <td align="center">
              <img src="/i/n.gif" width="250" height="85" style="background: url(/u/i/<?php echo Page::replaceImageSize($partner_item['logo'], 'S');?>) no-repeat center;" class="partners__item" />
              <div class="partners__item_name"><?php echo $partner_item['title'];?></div>
            </td>
            <?php
          }
          ?>
        </tr>
      </table>
      <i class="br40"></i>
    </td>
  </tr>
</table>