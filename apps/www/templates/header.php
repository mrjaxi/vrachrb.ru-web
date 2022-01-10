<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include_http_metas();?>
  <?php include_metas();?>
  <?php
  if($sf_user->getAttribute('seoD'))
  {
    echo '<meta name="description" content="' . $sf_user->getAttribute('seoD') . '" />';
  }
  if($sf_user->getAttribute('seoT'))
  {
    echo '<title>' . $sf_user->getAttribute('seoT') . '</title>';
  }
  else
  {
    ?>
    <title><?php include_slot('title', __(sfConfig::get('app_www_title')));?></title>
    <?php
  }
  ?>
  <?php include_stylesheets();?>
  <?php include_slot('redactor_css');?>
  <?php
  use_javascript('jquery.liteuploader.js');
  use_javascript('atmaUI.js');
  ?>

  <script type="text/javascript">
    <?php
    echo 'var sf_app = "www";';
    echo 'var sf_prefix = "";';
    echo 'var sf_user = "";';
    echo 'var sf_ws_pub = "";';
    ?>
  </script>
  <?php include_javascripts();?>
  <?php
  if(sfConfig::get('app_prod'))
  {
  ?>  
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter36726625 = new Ya.Metrika({
                    id:36726625,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/36726625" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
  <?php
  }
  ?>
</head>
<body>