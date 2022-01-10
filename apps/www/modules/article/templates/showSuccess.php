<?php
  slot('title', $article->getTitle());
?>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">
      <div class="breadcrumbs">
        <a href="/">Главная</a><a href="/article/">Статьи</a>
      </div>
      <h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $article->getTitle());?></h1>
      <i class="br1"></i>
      <i class="news__item__date"><?php echo Page::rusDate($article->getCreatedAt());?></i>
      <i class="br10"></i>
    </td>
  </tr>
  <tr>
    <td width="1">
      <div class="ftext">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <?php
              if($article->getVideo() && $article->getVideo() != '')
              {
                echo '<iframe width="700" height="400" src="https://www.youtube.com/embed/' . $article->getVideo() . '" frameborder="0" allowfullscreen></iframe>';
              }
              else
              {
                $img = '/u/i/' . Page::replaceImageSize($article->getPhoto(), 'M');
                echo '<img src="/i/n.gif" style="border-radius:4px;background: url(\'' . $img . '\') no-repeat center" width="700" height="400" alt="' . $article->getTitle() . '">';
              }
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <i class="br20"></i>
              <?php echo $article->getBody(ESC_RAW);?>
            </td>
          </tr>
        </table>
        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
        <br /><br />
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" <?php echo 'data-title="' . $article->getTitle() . '"' . ($img ? 'data-image="' . sfConfig::get('app_protocol') . '://' . $sf_request->getHost() . $img . '"' : '');?> ></div>
        <div class="vk_like_wrap">
          <div id="vk_like"></div>
        </div>
        <div id="vk_comments"></div>
      </div>
    </td>
    <td valign="top" align="left">
      <div class="article_author">
        <strong>Автор:</strong>
        <i class="br10"></i>
        <?php
        $specialist_link = '<div>Врач РБ</div>';
        $specialist_about = 'Сервис медицинской консультации';
        if($specialist->getId() != 51)
        {
          $specialist_link = '<a href="' . url_for('@specialist_index') . $specialist->getTitleUrl() . '/">' . $user->getFirstName() . ' ' . $user->getMiddleName() . '<br>' . $user->getSecondName() . '</a>';
        }
        echo $specialist_link;
        echo '<i class="br5"></i>';
        echo '<div class="rating_doctors__item__pos">' . $specialist_about . '</div>';
        ?>
      </div>
    </td>
  </tr>
</table>

<?php
include_component('main', 'similar_post', array(
  'module' => 'Article',
  'url' => 'article_show',
  'cut' => array('title' => 300, 'body' => 200),
  'element_id' => $article->getId(),
  'fields' => array('title' => 'title', 'link' => 'title_url', 'photo' => 'photo', 'body' => 'description'),
  'style' => 'margin-bottom:20px;'));

$type_id = $sf_request->getParameter('id');
if($type_id)
{
  include_component('main', 'comment', array('type' => 'Article', 'id' => $sf_request->getParameter('id')));
}
?>