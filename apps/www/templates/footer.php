<script type="text/javascript">
  function listener(event) {
    var size = {};
    var elem = $('.body_wrapper');
    if (event.origin != 'http://atmadev.ru') {
      return;
    }
    size.h = elem.outerHeight();
    size.w = elem.outerWidth();
    event.source.postMessage(JSON.stringify(size), event.origin);
    // console.log( "vrb принял: " + event.data );
  }

  if (window.addEventListener) {
    window.addEventListener("message", listener);
  } else {
    window.attachEvent("onmessage", listener);
  }
</script>
<?php
if(sfConfig::get('sf_environment') === 'prod'){
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-123892196-12"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-123892196-12');
    </script>
    <?php
}
?>

</body>
</html>