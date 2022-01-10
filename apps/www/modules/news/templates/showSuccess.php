<?php
slot('title', 'Новости');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a><a href="/news/">Новости</a>
</div>
<?php
$h1 = ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $news->getTitle());
echo '<h1>' . $h1 . '</h1>';
?>
<i class="br1"></i>
<i class="news__item__date"><?php echo Page::rusDate($news->getCreatedAt());?></i>
<i class="br10"></i>
<div class="ftext">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <?php
        if($news['video'] && $news['video'] != '')
        {
          echo '<iframe width="700" height="400" src="https://www.youtube.com/embed/' . $news['video'] . '" frameborder="0" allowfullscreen></iframe>';
        }
        else
        {
          $img = '/u/i/' . Page::replaceImageSize($news->getPhoto(), 'M');
          echo '<img src="' . $img . '" width="700" height="400" alt="' . $news->getTitle() . '" class="imgs_grey_shd" />';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <i class="br20"></i>
        <?php
        echo $text = $news->getBody(ESC_RAW);
        ?>
        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
        <br /><br />
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" <?php echo ($img ? 'data-image="' . sfConfig::get('app_protocol') . '://' . $sf_request->getHost() . $img . '"' : '') . ' data-title="' . $news->getTitle() . '"';?>></div>
        <div class="vk_like_wrap">
          <div id="vk_like"></div>
        </div>
        <div id="vk_comments"></div>
      </td>
    </tr>
  </table>
</div>

<?php include_component('main', 'similar_post', array(
  'module' => 'News',
  'url' => 'news_show',
  'cut' => array('title' => 180, 'body' => 200),
  'element_id' => $news->getId(),
  'fields' => array('title' => 'title', 'link' => 'title_url', 'photo' => 'photo', 'body' => 'body'),
  'style' => 'margin-bottom:20px;'));?>