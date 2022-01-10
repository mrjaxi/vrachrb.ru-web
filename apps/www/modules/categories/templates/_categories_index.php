<table class="categories_page" cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td width="100%" class="categories_height">

      <div class="quest_n_ans_page white_box">
        <a href="<?php echo url_for('@question_answer_index');?>" class="h_link anb" style="margin-bottom: 0;"><b>Вопросы и ответы</b></a>
        <div class="categories_qa_html">
          <?php
          include_component('question_answer', 'question_index', array('specialty_id' => $sf_request->getParameter('id'), 'categories' => 'y'));
          if($sf_request->getParameter('id'))
          {
            echo '<div class="categories_page__q_more"><span class="categories_page__q_more__item" onclick="filterAjax.onCategoriesMore(' . $sf_request->getParameter('id') . ');">Показать все</span></div>';
          }
          ?>
        </div>
      </div>

      <div class="tips_page white_box clearfix" style="min-height: 120px;;">
        <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">
        <a href="<?php echo url_for('@tip_index');?>" class="h_link anb" style="padding: 5px 0 0 10px;"><b>Советы</b></a>
        <div class="tips_page_wrap">
          <?php include_component('tip', 'tip', array('categories' => 'y', 'specialty_id' => $sf_request->getParameter('id')));?>
        </div>
      </div>

      <div class="categories_element">
        <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">
        <div class="categories__article_anchor">
          <?php include_component('article', 'article', array('categories' => 'y', 'categories_id' => $sf_request->getParameter('id')));?>
        </div>
      </div>

    </td>
    <td width="1" class="categories_left" style="padding-left: 20px;">

      <form class="filter_block filter_tags white_box clearfix">
        <b>Фильтр</b>
        <i class="br10"></i>
        <?php include_component('main','specialty',array('table' => 'Prompt', 'categories' => 'y', 'specialty_id' => $sf_request->getParameter('id')));?>
      </form>

      <a href="<?php echo url_for('@specialist_index'); ?>" class="h_link anb">Рейтинги врачей</a>
      <div class="categories_element categories__rating_doctors_anchor">
        <?php include_component('specialist', 'specialist', array('view' => 'vertical' , 'style' => 'width:auto;', 'categories_id' => $sf_request->getParameter('id')));?>
      </div>

    </td>
  </tr>
</table>