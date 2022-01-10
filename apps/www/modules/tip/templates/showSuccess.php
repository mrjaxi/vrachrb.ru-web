<?php slot('title', $prompt->getTitle());?>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">
      <div class="breadcrumbs">
        <a href="/">Главная</a><a href="/tip/">Советы</a>
      </div>
      <?php
      $h1 = ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $prompt->getTitle());
      echo '<h1>' . $h1 .'</h1>';
      ?>
      <i class="br1"></i>
      <i class="news__item__date"><?php echo Page::rusDate($prompt->getCreatedAt());?></i>
      <i class="br10"></i>
    </td>
  </tr>
  <tr>
    <td>
      <div class="ftext">
        <table width="700" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <?php
              if($prompt->getVideo() && $prompt->getVideo() != '')
              {
                echo '<iframe width="700" height="400" src="https://www.youtube.com/embed/' . $prompt->getVideo() . '" frameborder="0" allowfullscreen></iframe>';
              }elseif($prompt->getPhoto())
              {
                $img = '/u/i/' . Page::replaceImageSize($prompt->getPhoto(), 'M');
                echo '<img src="' . $img . '" width="700" height="400" alt="' . $prompt->getTitle() . '" class="imgs_grey_shd" />';
                echo '<i class="br20"></i>';
              }
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $prompt->getBody(ESC_RAW);?>
            </td>
          </tr>
        </table>
        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
        <br /><br />
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" <?php echo 'data-title="' . $prompt->getTitle() . '" ' . ($img ? 'data-image="' . sfConfig::get('app_protocol') . '://' . $sf_request->getHost() . $img . '"' : '');?> ></div>
        <div class="vk_like_wrap">
          <div id="vk_like"></div>
        </div>
        <div id="vk_comments"></div>
      </div>

    </td>
    <td valign="top" align="left">
      <div class="prompt_author">
        <strong>Автор:</strong>
        <i class="br10"></i>
        <?php
        $specialist_link = '<div>Врач РБ</div>';
        $specialist_about = 'Сервис медицинской консультации';
        if($specialist->getId() != 51)
        {
          $specialist_link = '<a href="' . url_for('@specialist_index') . $specialist->getTitleUrl() . '/"/>' . $user->getFirstName() . ' ' . $user->getMiddleName() . '<br>' . $user->getSecondName() . '</a>';
          $specialist_about = $specialist->getAbout();
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
  'module' => 'Prompt',
  'url' => 'tip_show',
  'cut' => array('title' => 300, 'body' => 200),
  'element_id' => $prompt->getId(),
  'fields' => array('title' => 'title', 'link' => 'title_url', 'photo' => 'photo', 'body' => 'description'),
  'style' => 'margin-bottom:20px;'));

$type_id = $sf_request->getParameter('id');
if($type_id)
{
  include_component('main', 'comment', array('type' => 'Prompt', 'id' => $sf_request->getParameter('id')));
}
?>