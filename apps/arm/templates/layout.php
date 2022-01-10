<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php
$path_prefix = isset($_SERVER['PATH_PREFIX']) ? $_SERVER['PATH_PREFIX'] : '';
?>
<script type="text/javascript">
var sf_app = '<?php echo sfConfig::get('sf_app');?>';
var sf_prefix = '<?php echo isset($_SERVER['PATH_PREFIX']) ? $_SERVER['PATH_PREFIX'] : '';?>';
var sf_user = <?php echo $sf_user->isAuthenticated() ? $sf_user->getUserId() : 'false';?>;
var sf_ws_pub = '<?php echo sfConfig::get('app_ws_pub');?>';
</script>
<?php
if($sf_user->isAuthenticated())
{
  if(file_exists(sfConfig::get('sf_web_js_dir_name', 'js') . $path_prefix . '/' . $this->getModuleName() . '.js'))
  {
    use_javascript(mb_substr($path_prefix, 1) . '/' . $this->getModuleName() . '.js');
  }
  if(file_exists(sfConfig::get('sf_web_css_dir_name', 'css') . $path_prefix . '/' . $this->getModuleName() . '.css'))
  {
    use_stylesheet(mb_substr($path_prefix, 1) . '/' . $this->getModuleName() . '.css');
  }
}
?>
<?php
include_http_metas();
include_stylesheets();
include_javascripts();
?>
<title><?php include_slot('title', 'Врач РБ - АРМ');?></title>
</head>
<body>
<div class="jcrop_curtain">
  <table width="100%" height="100%" cellcpacing="0" cellpadding="0">
    <tr>
      <td class="jcrop_curtain__item" align="center" valign="middle"></td>
    </tr>
  </table>
</div>
<div class="lui__root">
<?php
if(!$sf_user->isAuthenticated())
{
  echo $sf_content;
}
else
{
?>
<table class="lui__desktop" border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="top">
  <td class="lui__desktop_left invert" style="padding-top:20px;">
    <div class="lui__desktop_left_hider" title="<?php echo __('Свернуть');?>"></div>
    <div class="lui__desktop_left__wrapper">
      <img class="logo__full" src="/i/logo.png" hspace="20" width="104" height="30" />
      <img class="logo__small" src="/i/n.gif" hspace="10" width="16" height="40" />
      <i class="br20" style="border-bottom:1px solid #393f41"></i>
      <b class="br20"></b>
      <div class="lui__nav__wrapper" style="position:relative;">
      <ul class="lui__nav">
<?php
function menu_cmp($a, $b)
{
  if ($a['order'] == $b['order'])
  {
    return 0;
  }
  return ($a['order'] < $b['order']) ? -1 : 1;
}
$parser = new sfYamlParser();
$modules = glob(sfConfig::get('sf_app_module_dir') . '/*');
$links = array();


// $links[] = array(
//   'm' => 'price_edit?id=1',
//   'title' => 'Цены',
//   'order' => 900,
//   'group' => false,
//   'add' => false,
//   'hr' => false,
// );

// $links[] = array(
//   'm' => 'banner_edit?id=2',
//   'title' => 'Баннер',
//   'order' => 1000,
//   'group' => false,
//   'add' => false,
//   'hr' => false,
// );


/*
$links[] = array(
  'm' => 'cp',
  'title' => 'Контрольная панель',
  'order' => -1000,
  'group' => false,
  'add' => false,
  'hr' => false,
);
*/


foreach($modules as $k => $v)
{
  if(file_exists($v . '/config/generator.yml'))
  {
    $p = $parser->parse(file_get_contents($v . '/config/generator.yml'));
    $module = basename($v);
    
    if(!$sf_user->hasCredential($module . '_index') || $p['generator']['param']['skip'] || $p['generator']['param']['config']['list']['title'] == '')
    {
      continue;
    }
    
    $model = str_replace(' ', '', ucwords(str_replace('_', ' ', $module)));
    
    $count = '';
    
    if(class_exists($model) && method_exists($model, 'getMenuCount'))
    {
      $countc = $module::getMenuCount($sf_user);
      $count = '&nbsp;<span class="lui__nav_menu__count">'  . ($countc == 0 ? '' : $countc) . '</span>';

    }
    
    
    $links[] = array(
      'm' => $module,
      'title' => $p['generator']['param']['config']['list']['title'] . $count,
      'order' => isset($p['generator']['param']['order']) ? $p['generator']['param']['order'] : 0,
      'group' => isset($p['generator']['param']['group']) ? $p['generator']['param']['group'] : false,
      'add' => isset($p['generator']['param']['add']) ? $p['generator']['param']['add'] : true,
      'hr' => isset($p['generator']['param']['hr']),
    );
  }
}
usort($links, 'menu_cmp');
foreach($links as $k => $v)
{
  if($sf_user->hasCredential($v['m'] . '_index'))
  {
    if($v['group'])
    {
      echo '<i class="br10"></i>';
      if($v['group'] != '-')
      {
        echo '<li><b>' . $v['group'] . '</b></li>';
      }
    }
    echo '<li' . ($this->getModuleName() == $v['m'] ? ' class="current"' : '') . '>';
      echo '<a class="lui__nav_a_' . $v['m'] . '" href="' . url_for('@' . $v['m']) . '">' . $v['title'] . '</a>';
    echo '</li>';
    if($v['hr'] && $k != count($links) - 1)
    {
      echo '<i class="hr20" style="margin:10px 0;background:#2d3436;"></i>';
    }
  }
}

echo '</ul>';
echo '</div>';
echo '<div class="fixed_user_menu">';
  //echo '<a href="" class="sprite_link sprite_link_user">' . $sf_user->getAccount()->getFIorUsername() . '</a><i class="br10"></i>';
  
  echo '<div id="cube_recreate_lock" style="visibility:hidden;padding:0 0 10px 24px;background-image:url(/i/arm/3-b.gif);background-repeat:no-repeat;background-position:0px 0px;color:#969696">Пересчёт данных</div>';
  echo '<b class="sprite_link sprite_link_user">' . str_replace(' ', '<br>', $sf_user->getAccount()->getFIorUsername()) . '</b><i class="br20"></i>';
  echo '<a style="display: inline-block;" href="/arm/settings">Настройки</a><i class="br10"></i>';
  echo '<a href="' . url_for('@signout') . '" class="sprite_link sprite_link_logout">Выйти</a>';
echo '</div>'; 
?>
  </div>
  </td>
  <td width="100%" class="lui__desktop_right" height="100%">
  <div class="lui__desktop_right__wrapper <?php echo sfConfig::get('sf_app') . '_' . $this->getModuleName();?>">
  <?php
    echo $sf_content;
  ?>
  </div>
  </td>
</tr>
</table>
<?php
}
?>
</div>
<script type="text/javascript">
$(document).ready(function() {
  var calc_lui__nav__wrapper = function(){
    $('.lui__nav__wrapper').height($(window).height() - 200);
    $('.lui__nav__wrapper').perfectScrollbar('update');
  };
  $('.lui__nav__wrapper').perfectScrollbar();
  $('.lui__nav__wrapper').scroll(function(){
    
    var top = $(this).scrollTop();
    var bottom = $('.lui__nav').outerHeight() - $(this).outerHeight();
    
    
    $(this).removeClass('lui__nav__wrapper__sh_top');
    $(this).removeClass('lui__nav__wrapper__sh_bottom');
    $(this).removeClass('lui__nav__wrapper__sh_bottom_top');
    
    if(bottom < 0){
      return false;
    }
    
    if(top == 0){
      $(this).addClass('lui__nav__wrapper__sh_bottom');
    } else if (top == bottom) {
      $(this).addClass('lui__nav__wrapper__sh_top');
    } else {
      $(this).addClass('lui__nav__wrapper__sh_bottom_top');
    }
  }).scroll();
  
  $(window).resize(function(){
    calc_lui__nav__wrapper();
    $('.lui__nav__wrapper').scroll();
  }).resize();
  /*
  setInterval(function(){
    $.ajax({
      url: '/cube_recreate.lock',
      statusCode: {
        200: function() {
          $('#cube_recreate_lock').css('visibility', 'visible');
        },
        404: function() {
          $('#cube_recreate_lock').css('visibility', 'hidden');
        }
      }
    });
  }, 1000);
  */
});
</script>

</body>
</html>