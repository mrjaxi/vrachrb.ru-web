<div class="overlay overflow_certificate" style="overflow: hidden;" onclick="$(this).hide();overflowHiddenScroll();">
  <div class="overlay__close">×</div>
  <table width="100%" height="100%" onclick="event.stopPropagation();" style="position: relative;top: -100%;">
    <tr>
      <td valign="middle" align="center">
        <div class="fotorama" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-min-width="300" data-max-width="90%" data-min-height="300" data-max-height="90%" data-hash="false">
          <?php
          $result = '';
          $photo = '';
          if($certificates)
          {
            $certificate = explode(';', $certificates);
            foreach ($certificate as $c_key => $c)
            {
              $certificates_exp = explode(':', $c);
              if($c_key == 0)
              {
                $result .= $certificates_exp[0] ? '<b>Сертификаты</b><i class="br10"></i>' : '';
              }
              $result .= '<div class="certificates_image">';
              $result .= '<img class="certificates_image__img" src="/u/i/' . Page::replaceImageSize($certificates_exp[0], 'S') . '" onclick="showPhoto.onClick(this, event);">';
              $result .= '</div>';
              $photo .= ($c_key != 0 ? ',' : '')  . '\'<img src="/u/i/' . Page::replaceImageSize($certificates_exp[0],'M') . '" />\'';
            }
          }
          ?>
        </div>
      </td>
    </tr>
  </table>
</div>

<div class="certificates_wrap">
  <?php
  echo $result;
  ?>
</div>

<script type="text/javascript">
  var photoArr = [<?php echo $photo;?>];
  var showPhoto = {
    item: null,
    onClick: function (currElem, event) {
      var html = '';
      var photo;

      html += '<div class="overlay__close">×</div>';
      html += '<table width="100%" height="100%" onclick="event.stopPropagation();" style="position: relative;top: -100%;">';
      html += '<tr><td valign="middle" align="center">';
      html += '<div class="fotorama" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-min-width="300" data-max-width="90%" data-min-height="300" data-max-height="90%" data-hash="false">';
      for(var i = 0; i < photoArr.length; i++){
        if(i == 0){
          photo += photoArr[$(currElem).index('.certificates_image__img')];
        }
        if($(currElem).index('.certificates_image__img') != i){
          photo += photoArr[i];
        }
      }
      html += photo;
      html += '</div>';
      html += '</td></tr></table>';

      $('.overflow_certificate').html(html);
      overflowHiddenScroll($('.overflow_certificate'));
      $('.fotorama').fotorama();
    },

    init: function(){
      var _this = this;
      _this.item = $('.certificates_img');
    }
  };
  $(document).ready(function(){
    showPhoto.init();
  });
</script>