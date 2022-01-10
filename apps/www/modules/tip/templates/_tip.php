<?php
if(!$specialty_id && !$specialist_page && !$categories)
{
  slot('title', 'Советы');
  use_javascript('masonry.pkgd.min.js');
?>
  <script type="text/javascript">
    var masonryFunc = function () {
      $('.tips_page_wrap').masonry('destroy');
      $('.tips_page_wrap').masonry({
        itemSelector: '.tips_page__item',
        percentPosition: true
      });
    };
    $(document).ready(function(){
      masonryFunc();
    });
    $(document).ajaxSuccess(masonryFunc);
  </script>
  <div class="breadcrumbs">
    <a href="/">Главная</a>
  </div>
  <h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Советы');?></h1>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td>
        <div class="tips_page white_box clearfix" style="min-height:120px;">
          <div style="height: 100%;" class="tips_page_wrap">
<?php
}
          if(!$categories)
          {
            $img_w = 270;
            $img_h = 140;
          }
          else
          {
            $img_w = 200;
            $img_h = 100;
          }
          $count_tip = 0;

          foreach ($prompts as $prompt_key => $prompt)
          {
            $specialty_id &&  $specialty_id != 'all' ? $specialty_value = $specialty_id : $specialty_value = $prompt['Specialist']['specialty_id'];

            $specialist_link = '<div>Врач РБ</div>';
            $specialist_about = 'Сервис медицинской консультации';

            if($prompt['Specialist']['id'] != 51)
            {
              $specialist_link = '<a href="' . url_for('@specialist_index') . $prompt['Specialist']['title_url'] . '/">' . $prompt['Specialist']['User']['first_name'] . ' ' . $prompt['Specialist']['User']['middle_name'] . ' ' . $prompt['Specialist']['User']['second_name'] . '</a>';
              $specialist_about = $prompt['Specialist']['about'];
            }

            if($prompt['Specialist']['specialty_id'] == $specialty_value && $count_tip < 4)
            {
              $prompts_one = true;
            ?>
            <div class="tips_page__item fl_l">
              <div class="live_band__item live_band__item_tips">
                <div class="live_band__item__tags clearfix">
                  <?php echo $categories ? '<a href="' . url_for('@tip_index') . '" class="b_blue_btn tags__link fl_l">советы</a>' : '';?>
                </div>
                <i class="live_band__item__date"><?php echo Page::rusDate($prompt['created_at']);?></i>
                <i class="br5"></i>
                <div class="all_link_item">
                  <a href="<?php echo url_for('@tip_index') . $prompt['title_url'];?>/" class="live_band__item__link">
                    <b class="live_band__item__link__border"><?php echo $prompt['title'];?></b>
                  </a>
                  <i class="br10"></i>
                  <?php
                  if($prompt['photo'])
                  {
                    echo '<img src="/i/n.gif" width="' . $img_w . '" height="' . $img_h . '" style="background:url(/u/i/' . Page::replaceImageSize($prompt['photo'],'S') . ') no-repeat center;" class="imgs_grey_shd" />';
                    echo '<i class="br10"></i>';
                  }
                  ?>
                  <?php echo Page::endPointReplace($prompt['description']);?>
                </div>
                <div class="live_band__item__author">
                  <?php
                  echo $specialist_link;
                  echo '<i class="br1"></i>';
                  echo $specialist_about;
                  ?>
                </div>
              </div>
            </div>
            <?php
            }
            $categories ? $count_tip ++ : '';
          }
          if(!$prompts_one)
          {
            echo '<style type="text/css">.tips_page{display: none;}</style>';
          }

          if(!$specialty_id && !$specialist_page && !$categories)
          {
          ?>
          </div>
        </div>
      </td>
      <td width="1" style="padding-left: 20px;">
        <div class="filter_block filter_tags white_box clearfix">
          <b>Фильтр</b>
          <i class="br10"></i>
          <?php
          include_component('main','specialty',array('table' => 'Prompt'));
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td align="center">
        <?php
        echo Page::tipCountPage($show_el, $page, 'Prompt');
        ?>
      </td>
      <td></td>
    </tr>
  </table>
          <?php
          }
          ?>