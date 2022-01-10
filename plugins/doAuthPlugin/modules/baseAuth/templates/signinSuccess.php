<?php slot('title', 'Авторизация');?>
<?php use_helper('I18N') ?>
<?php 
//use_javascript('deployJava.js');
?>
<style>
input::-webkit-input-placeholder {
  color:rgba(255,255,255,0.5);
}
.signin_chosen__div .chosen-single{
  background-color: rgba(183, 203, 206, 0.5);
  color: #fff;
  text-align: left;
  border: none !important;
  padding: 8px 12px;
  font-weight: bold;
}
.chosen-results{
  text-align: left;
}
.signin_chosen__div .chosen-single div b{
  background-position: 0 17px;
}
</style>
<table width="100%" class="login_form_table">
<tr><td align="center">
<form method="post" id="login_form" class="form" action="<?php echo url_for('@signin');?>">
<div>
<table cellpadding="0">
<tr align="center"><td>
<div class="signin_chosen__div">
<!--  <select name="conn" class="chosen signin_chosen" style="width:100%;">-->
<!--    <option value="doctrine">ООО «Башэнергонефть»</option>-->
<!--    <option value="bnp">ООО «Башнефть–Полюс»</option>-->
<!--  </select>-->
</div><span class="br10"></span>
<?php
echo $form;
?>
  </td></tr>
  <tr align="center"><td>
  <input type="submit" id="submit" name="submit" data-title_normal="Войти" data-title_progress="Вход..." data-title_error="Ошибка" value="Войти" />
  </td>
</tr>
</table>
<i class="br40"></i>
<div class="invert" id="put_card">
<!--<i>~&nbsp;или&nbsp;~</i>-->
<i class="br40"></i>
<!--Положите карту на ридер-->
<i class="br20"></i>
<!--<img src="/i/arm/login_reader.png" width="250" height="60" />-->
</div>
</div>
</form>
</td></tr></table>
<div class="bg_changer" style="background-image:url(/i/arm/login_bg_2.jpg);background-color:#467585;"></div>
<div class="bg_changer" style="background-image:url(/i/arm/login_bg_1.jpg);background-color:#6f95c2;"></div>

<?php
//<div class="bg_changer" style="background-image:url(/i/arm/login_bg_3.jpg);background-color:#cbdad3;"></div>
?>
<script type="text/javascript">
var bg_changer = $('.bg_changer');
var bg_changer_index = bg_changer.length - 1;
var bg_changer_rec = function(){
  $(bg_changer[bg_changer_index]).animate({
    opacity: 0,
  }, 20000, function() {
    var zi = $(this).css('z-index');
    $(this).css({
      'z-index': zi - bg_changer.length,
      'opacity': 1
    })
    bg_changer_rec();
  });
  bg_changer_index--;
  if(bg_changer_index < 0){
    bg_changer_index = bg_changer.length - 1;
  }
};
$(document).ready(function() {
  //bg_changer_rec();
  
  //$('#signin_username').val('root');
  //$('#signin_password').val('');
});

</script>





<script type="text/javascript">
/*
var parameters = { fontSize: 16, device: 'cardreader.0', readType: 'READ_ALL' };
var version = '1.6';

if(deployJava.isPluginInstalled()){
  deployJava.runApplet({
    id: 'MifareReader',
    code: 'ru.peaksystems.cm2.MifareReader.class',
    archive: '/jar/uos-mifare-applet-1.0.jar',
    width: 0,
    height: 0
  }, parameters, version);
}
*/
var stop_applet = false;
$(document).idle({
  onHide: function(){
    stop_applet = true;
  },
  onShow: function(){
    stop_applet = false;
  }
});

function cardReaded(cardNumber, allData) {
  if(stop_applet){
    return;
  }
  var cur_mifare = $('body').data('mifare');
  if(cur_mifare == cardNumber){
    return;
  }
  if(cardNumber != ''){
    $('body').data('mifare', cardNumber);
  } 
  $.get('/ui_by_num/' + cardNumber + '/', function(resp){
    var json = $.parseJSON(resp);
    var submit = $('#submit');
    var _this = $('form');
    
    if(json.arm === true){
      $('#signin_username').val(cardNumber);
      arm.remove_error();
      $(submit).removeClass('error');
      $(submit).val($(submit).data('title_normal'));
      $('#signin_password').prop('placeholder', 'Пин-код');
      $('#signin_password').focus();
      $('#put_card').animate({
        'opacity': 0
      });
      
    } else {
      $('#signin_username').val('');
      $('#put_card').animate({
        'opacity': 1
      });
      arm.create_error('Доступ по данной карте запрещен');
      $(submit).addClass('error');
      
      if($(submit).data('title_error')){
        $(submit).val($(submit).data('title_error'));
      }
      
      $(_this).find('input[type=password],input[type=text]').addClass('shake_eff');
      arm.timeouts[0] = setTimeout(function(){
        $(submit).removeClass('error');
        $(submit).val($(submit).data('title_normal'));
        $(_this).find('input[type=password],input[type=text]').removeClass('shake_eff');
      }, 1500);

      arm.timeouts[1] = setTimeout(function(){
        arm.remove_error();
      }, 5000);
      $('body').data('mifare', '');
    }
  });

}
</script>






<?php
/*
use_javascript('/jar/js/deployJava.js');
?>
<div id="cardReaderApplet"></div>
<script type="text/javascript">
function loadCardReaderApplet() {
  var parameters = {java_status_events: 'true', permissions: 'sandbox' };
  var version = '1.6';
  deployJava.runApplet({
    id: 'CardReader',
    jnlp_href:'/jar/CardReader.jnlp',
    width: 1,
    height: 1
  }, parameters, version,'cardReaderApplet');
  isAppletLoad();
}
loadCardReaderApplet();
</script>
<?php
*/
?>