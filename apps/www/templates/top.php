<?php
/*
if(!$sf_user->isAuthenticated())
{
  $ad = Doctrine_Query::create()
    ->select('a.*')
    ->from('Advertising a')
//    ->where('a.is_activated = 1')
    ->fetchArray()
  ;
  foreach ($ad as $ad_key => $item)
  {
    if($ad_key == 0)
    {
    ?>
      <div class="lower_block">
        <div class="max_width">
          <div class="lower_block__close" onclick="$(this).closest('.lower_block').slideUp(200);">Скрыть рекламу</div>
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td width="1">
                <img <?php echo $item['photo'] ? 'style="background:url(/u/i/' . Page::replaceImageSize($item['photo'], 'S') . ') no-repeat 50% 50%;background-size:cover;"' : '';?> src="/i/n.gif" width="260" height="140" />
              </td>
              <td width="100%" class="lower_block__r">
                <i class="br10"></i>
                <?php echo $item['title'];?>
              </td>
            </tr>
          </table>
          <a href="#" class="anb lower_block__link"></a>
        </div>
      </div>
    <?php
    }
  }
}
*/
?>
<div style="display:block;background:#fff;padding:20px;color:#444;">
  <div class="max_width">
    Внимание! Сервис запущен в режиме beta-тестирования. Мы будем рады вашим предложениям по улучшению сервиса и просим отнестись с пониманием к возможным ошибкам и недочетам. Форма обратной связи с разработчиками в правом нижнем углу.
  </div>
</div>
<div class="header_wrap" style="box-shadow: none;">
  <div class="max_width">
    <div class="header">
      <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="" width="1">
            <?php
            if($sf_request->getPathInfo() == '/')
            {
              echo '<img src="/i/logo.png" width="188" height="64" />';
            }
            else
            {
              echo '<a href="/" class="anb"><img src="/i/logo.png" width="188" height="64" /></a>';
            }
            ?>
          </td>
          <td style="padding-left: 10px;"><b class="header__logo_title">Сервис медицинской<br/>консультации</b></td>
          <td width="1" style="padding-right: 20px;">
            <div class="lpu_select_btn_clear_lpu <?php echo (!$sf_user->getAttribute('lpu_title') || $sf_user->getAttribute('lpu_title') == 'Все ЛПУ' ? '' : 'lpu_active');?>">
              <a href="?token=null" title="Сбросить фильтр">×</a>
            </div>
            <button class="lpu_select btn_all" style="height:38px;background-color:rgba(255,255,255,0.5);" onclick="overflowHiddenScroll($('.lpu_select_overlay'));"><span><?php echo ($sf_user->getAttribute('lpu_title') ? $sf_user->getAttribute('lpu_title') : 'Все ЛПУ');?></span></button>
          </td>
          <td width="1">
            <div class="profile_link__wrap">
            <?php
              if(!$sf_user->isAuthenticated())
              {
                echo $sf_request->getPathInfo() == url_for('@login_index') ? 
                  '<div style="padding: 12px 25px 0 25px;height:38px;opacity: 0.5;background-color: #18C21C !important;" class="btn_all green_btn header__sign_out disabled_btn">Войти</div>' : 
                  '<a href="' . url_for('@login_index') . '" class="btn_all green_btn header__sign_out" style="padding: 12px 25px 0 25px;height:38px;">Войти</a>';
              }
              else
              {
                echo '<a href="' . url_for('@' . Page::whoIsDoctor($sf_user->getAccount()->getId(), 'url') . '_account_index') . '" class="profile_link">' . Page::authorizedUserName($sf_user->getAccount()) . '</a>';
                echo '<a class="profile_exit_link" href="' . url_for('@signout') . '"><img src="/i/n.gif" style="background: url(/i/consilium_sprite.png) -28px 0;" width="18" height="19" /></a>';
                include_partial('user/lp');
              }
            ?>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <?php
      include_component('main', 'menu');
    ?>
  </div>
</div>
<div class="lpu_select_overlay" onclick="overflowHiddenScroll($(this));">
  <span class="overlay__close">×</span>
  <?php
  $lpus = Doctrine_Query::create()
    ->select("l.*")
    ->from("Lpu l")
    ->orderBy("l.id ASC")
    ->fetchArray()
  ;
  ?>
  <table cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
      <td height="100%" align="center">
        <div class="max_width">
          <ul class="lpu_select_overlay__list" onclick="event.stopPropagation();">
            <li>
              <h2>Уфа</h2>
              <i class="br1"></i>
              <?php
              foreach ($lpus as $lpu_key => $lpu)
              {
                echo '<a href="?token=' . $lpu['token'] . '">' . $lpu['title'] .'</a>';
                echo $lpu_key + 1 != count($lpus) ? '<i class="br5"></i>' : '';
              }
              ?>
            </li>
            <li>
              <h2>Екатеринбург</h2>
              <i class="br1"></i>
              <span href="">ГКБ № 1</span>
              <i class="br5"></i>
              <span href="">ГКБ № 8</span>
              <i class="br5"></i>
              <span href="">ГКБ № 10</span>
            </li>
            <li>
              <h2>Казань</h2>
              <i class="br1"></i>
              <span href="">ГКБ № 4</span>
              <i class="br5"></i>
              <span href="">ГКБ № 11</span>
              <i class="br5"></i>
              <span href="">ГКБ № 18</span>
            </li>
            <li>
              <h2>Новосибирск</h2>
              <i class="br1"></i>
              <span href="">ГКБ № 4</span>
              <i class="br5"></i>
              <span href="">ГКБ № 8</span>
            </li>
            <li>
              <h2>Пермь</h2>
              <i class="br1"></i>
              <span href="">ГКБ №5</span>
            </li>
            <li>
              <h2>Владикавказ</h2>
              <i class="br1"></i>
              <span href="">ГКБ №7</span>
            </li>
            <li>
              <h2>Калининград</h2>
              <i class="br1"></i>
              <span href="">ГКБ №1</span>
              <i class="br5"></i>
              <span href="">ГКБ №5</span>
            </li>
            <li>
              <h2><a href="?token=null">Все ЛПУ</a></h2>
            </li>
          </ul>
        </div>
      </td>
    </tr>
  </table>
</div>