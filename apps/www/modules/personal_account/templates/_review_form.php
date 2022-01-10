<div class="overlay pc_overlay_review" onclick="overflowHiddenScroll($(this));">
  <div class="overlay__close">×</div>
  <form id="pc_overlay_review_form" method="post" action="<?php echo url_for('@personal_account_now_dialog_answer');?>" onsubmit="paNowDialogReview();return false;" class="overlay__white_box" onclick="event.stopPropagation();">

    <div class="review_form__item">
      <div class="fs_18">Оставьте отзыв</div>
      <div class="ta_l overlay__white_box__body">
        <?php
        echo $review_form->renderGlobalErrors();
        echo $review_form->renderHiddenFields();
        echo $review_form['body'] . $review_form['body']->renderError();
        ?>
        <i class="br20"></i>
        <div class="review_i_c__wrap">
          Информативность
          <i class="br5"></i>
          <input type="text" id="review_informative" name="review[informative]" value="0" class="stars_plugin review_i_c" data-click="1" />
        </div>
        <i class="br10"></i>
        <div class="review_i_c__wrap">
          Вежливость
          <i class="br5"></i>
          <input type="text" id="review_courtesy" name="review[courtesy]" value="0" class="stars_plugin review_i_c" data-click="1" />
        </div>

        <?php
        /*
  <i class="br10"></i>
        Рассказать о нашем проекте в соц. сетях?<br />Ваш отзыв не будет виден.
        <i class="br5"></i>
        <label style="margin: 0 20px 5px 0;"><input type="radio" name="vis_review" />Да</label>
        <label style="margin: 0 20px 5px 0;"><input type="radio" name="vis_review" />Нет</label>
         */
        ?>

      </div>
      <div class="overlay__white_box__dialog clearfix">
        <button class="btn_all overlay__white_box__dialog__btn blue_btn" style="width:100%;">Отправить отзыв</button>
      </div>
    </div>
    <div class="review_form__thx">
      <b style="padding: 0px 30px 30px;display: block;" class="fs_18 ta_c">Отзыв успешно добавлен</b>
    </div>

  </form>
</div>