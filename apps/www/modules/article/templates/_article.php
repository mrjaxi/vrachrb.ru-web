<?php
if($question_answer_articles && count($articles) > 0 && !$specialists)
{
?>
  <div class="articles_nabs articles_vertical white_box clearfix">
    <a href="<?php echo url_for('@article_index');?>" class="h_link anb"><b>Статьи</b></a>
    <i class="br1"></i>
<?php
}
  if(!$specialist_id && !$general && !$categories && !$answer)
  {
    slot('title', 'Статьи');
?>
  <div class="breadcrumbs">
    <a href="/">Главная</a>
  </div>
  <h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Статьи');?></h1>
  <div class="pagination_years">
    <?php
    $output_year = '';
    foreach ($articles_years as $ar_key => $articles_year)
    {
      $ex_a = explode('-', $articles_year['created_at']);
      if($ar_key == 0)
      {
        $year_value = $ex_a[0];
        if($year)
        {
          $year == $year_value ? $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>' : $output_year .= '<a href="' . url_for('@article_index') . '" class="pagination_years__item">' . $ex_a[0] . '</a>';
        }
        else
        {
          $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>';
        }
      }
      if($year_value != $ex_a[0])
      {
        $year_value = $ex_a[0];
        if($year)
        {
          $year == $year_value ? $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>' : $output_year .= '<a href="' . url_for('@article_page?id=' . $ex_a[0]) . '" class="pagination_years__item">' . $ex_a[0] . '</a>';
        }
        else
        {
          $output_year .= '<a href="' . url_for('@article_page?id=' . $ex_a[0]) . '" class="pagination_years__item">' . $ex_a[0] . '</a>';
        }
      }
    }
    echo substr_count($output_year, 'pagination_years__item') > 1 ? $output_year : '';
    ?>
  </div>
<?php
    $wrap_class = 'articles_page ';
    $link_class = 'articles__item__link__bottom ';
  }
  else
  {
    /*$wrap_class = 'articles_nabs ';*/
    $specialist_id ? $wrap_class = 'articles_page ' : $wrap_class = 'articles_nabs ';
    /*$categories == 'y' || $specialist_id ? $wrap_class = 'articles_page ' : $wrap_class = 'articles_nabs';*/
    $link_class = 'articles__item__link__specialist ';
  }
  $count_article = 0;
  foreach ($articles as $article_key => $article)
  {
    $first_ar = true;
    $ex_a = explode('-', $article['created_at']);
    $article_key == 0 ? $this_year = $ex_a[0] : '';
    if(!$year && !$specialist_id && !$categories && !$answer)
    {
      if($article_key == 0)
      {
        $first_ar = false;
        if(!$general)
        {
          $img = '<img src="/i/n.gif" width="440" height="305" style="background: url(/u/i/' . Page::replaceImageSize($article['photo'],'M') . ') no-repeat center;" class="articles__item__img" />';
        }
        else
        {
          $img = '<img src="/i/n.gif" width="330" height="220" style="width:330px;height:220px;background: url(/u/i/' . Page::replaceImageSize($article['photo'],'S') . ') no-repeat center;" class="articles__item__img" />';
          $general_a = 'general_article';
          $general_a_link = '<a href="/article/" class="h_link anb">Статьи</a>';
        }
      ?>
        <div class="articles articles_new white_box <?php echo $general_a;?>">
          <?php echo $article['is_activated'] == 1 ? '<span class="b_grey_btn btn_all articles_new__tag">Реклама</span>' : ''?>
          <?php echo $general_a_link;?>
          <div class="articles__item">
            <i class="articles__item__date"><?php echo Page::rusDate($article['created_at']);?></i>
            <?php echo $img;?>
            <i class="br5"></i>
            <div class="all_link_item">
              <?php
              echo '<a href="' . url_for('@article_index') . $article['title_url'] . '/" class="articles__item__link articles__item__link__top fs_25">' . $article['title'] . '</a>';
              ?>
              <i class="br10"></i>
              <div class="articles__item__text"><?php echo Page::endPointReplace($article['description']); ?></div>
            </div>
          </div>
        </div>
      <?php
      }
    }





    if(!$general && !$answer && count($articles) > 0)
    {
      if($article_key == 0)
      {
        echo '<div style="padding-bottom: 0;" class="' . $wrap_class . 'white_box clearfix test">';
        echo $categories ? '<a href="' . url_for('@article_index') . '" class="h_link anb"><b>Статьи</b></a><i class="br1"></i>' : '';
      }
      if($first_ar)
      {
        if($this_year == $ex_a[0] && $count_article < 3)
        {
          ?>
          <div class="articles_page__item fl_l">
            <div class="articles__item">
              <i class="articles__item__date"><?php echo Page::rusDate($article['created_at']); ?></i>
              <?php
              if ($article['photo']) {
                ?>
                <img src="/i/n.gif" style="background:url('/u/i/<?php echo Page::replaceImageSize($article['photo'], 'S'); ?>') no-repeat center" class="articles__item__img"/>
                <?php
              }
              ?>
              <i class="br5"></i>
              <div class="all_link_item">
                <a href="<?php echo url_for('@article_index') . $article['title_url']; ?>/"
                   class="articles__item__link <?php echo $link_class; ?> fs_16 ff_rr"><?php echo $article['title']; ?></a>
                <i class="br10"></i>
                <div class="articles__item__text"><?php echo Page::endPointReplace($article['description']); ?></div>
              </div>
            </div>
          </div>
          <?php
          $categories ? $count_article ++ : '';
        }
      }
    }





    if($answer)
    {
    ?>
      <div class="articles_page__item">
        <div class="articles__item">
          <i class="articles__item__date"><?php echo Page::rusDate($article['created_at']);?></i>
          <div class="all_link_item">
            <img <?php echo 'style="background:url(/u/i/' . Page::replaceImageSize($article['photo'], 'S') . ') no-repeat 50% 50%;background-size:cover;"';?> src="/i/n.gif" width="130" height="120" class="articles__item__img" />
            <i class="br5"></i>
            <?php
            echo '<a href="' . url_for('@article_index') . $article['title_url'] . '/" class="articles__item__link">' . $article['title'] . '</a>';
            ?>
            <i class="br5"></i>
            <div class="articles__item__text"><?php echo $article['description'];?></div>
          </div>
        </div>
      </div>
    <?php
    }
  }
  if(!$answer && !$general && !$specialists)
  {
    echo '</div>';
  }
  if($question_answer_articles && count($articles) > 0 && !$specialists)
  {
    echo '</div>';
  }
  if($specialists && count($articles) > 0)
  {
    echo '</div>';
  }
  ?>