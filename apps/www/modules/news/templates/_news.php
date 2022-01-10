<?php
if($general != 'y')
{
  $general_n = 'general_news';
  $news_class = 'news_page__item fl_l';
  $div = '</div>';
  slot('title', 'Новости');
  ?>
  <div class="breadcrumbs">
    <a href="/">Главная</a>
  </div>
  <h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Новости');?></h1>
  <div class="pagination_years">
    <?php

    $output_year = '';
    foreach ($newss_years as $ny_key => $news_year)
    {
      $ex_n = explode('-', $news_year['created_at']);
      if ($ny_key == 0) {
        $year_value = $ex_n[0];
        if ($year)
        {
          $year == $year_value ? $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>' : $output_year .= '<a href="' . url_for('@news_page?id=' . $ex_n[0]) . '" class="pagination_years__item">' . $ex_n[0] . '</a>';
        }
        else
        {
          $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>';
        }
      }
      if ($year_value != $ex_n[0])
      {
        $year_value = $ex_n[0];
        if ($year)
        {
          $year == $year_value ? $output_year .= '<span class="pagination_years__item">' . $year_value . '</span>' : $output_year .= '<a href="' . url_for('@news_page?id=' . $ex_n[0]) . '" class="pagination_years__item">' . $ex_n[0] . '</a>';
        }
        else
        {
          $output_year .= '<a href="' . url_for('@news_page?id=' . $ex_n[0]) . '" class="pagination_years__item">' . $ex_n[0] . '</a>';
        }
      }
    }
    echo substr_count($output_year, 'pagination_years__item') > 1 ? $output_year : '';
    ?>
  </div>
  <?php
}
else
{
  $news_class = 'news';
}
echo $general != 'y' ? '<div class="news news_page clearfix ' . $general_n . '">' : '';
foreach ($newss as $news_key => $news)
{
  if($general == 'y')
  {
    $enter_news = $news_key < 5 ? true : false;
  }
  else
  {
    $enter_news = true;
  }
  $ex_n = explode('-', $news['created_at']);
  $news_key == 0 ? $this_year = $ex_n[0] : '';
  if($this_year == $ex_n[0] && $enter_news)
  {
    echo '<div class="' . $news_class . '">';
  ?>
      <div class="news__item">
        <i class="news__item__date"><?php echo Page::rusDate($news['created_at']);?></i>
        <i class="br5"></i>
        <a href="<?php echo url_for('@news_index') . $news['title_url'] . '/';?>" class="news__item__link">
          <img src="/i/n.gif" width="165" style="background:url('/u/i/<?php echo Page::replaceImageSize($news['photo'], 'S');?>')no-repeat center;" height="100" class="news__item__img" />
          <?php
          if($general == 'y')
          {
            echo '<i class="br1"></i>';
          }
          echo mb_substr($news['title'], 0, 150, 'utf-8') . (strlen($news['title']) > 150 ? '...' : '');
          ?>
        </a>
      </div>
    </div>
  <?php
  }
}
$div;
?>
