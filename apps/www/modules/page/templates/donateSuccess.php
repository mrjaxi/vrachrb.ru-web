<?php
slot('title', __('Поддержать проект'));
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<?php
if($pages->getIsActivated() == 1)
{
  echo '<table width="100%" cellspacing="0" cellpadding="0"><tr>';
    echo '<td><h1>' . ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : $pages->getTitle()) . '</h1></td>';
    echo '<td width="320" align="left">';
    if(csSettings::get('donate_amount'))
    {
      echo '<b>Уже собрано:</b> ' . number_format(csSettings::get('donate_amount'), 0, ',', ' ') . ' рубл' . Page::niceRusEnds(csSettings::get('donate_amount'), 'ь', 'я', 'ей');
    }
    echo '</td>';
  echo '</table>';
?>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr valign="top">
      <td>
        <div class="ftext" style="max-width:800px;">
          <?php echo str_replace('consultation_count', number_format($consultation_count, 0, ',', ' '), $pages->getBody(ESC_RAW));?>
          <div id="yandex_money" style="position:relative;left:-2px;">
          <iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/shop.xml?account=410012854680235&fio=on&quickpay=shop&payment-type-choice=on&writer=seller&targets=%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C+%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D1%83+%C2%AB%D0%92%D1%80%D0%B0%D1%87+%D0%A0%D0%91%C2%BB&default-sum=&button-text=01&comment=on&hint=%D0%A2%D0%B0%D0%BA%D0%B6%D0%B5+%D0%B2%D1%8B+%D0%BC%D0%BE%D0%B6%D0%B5%D1%82%D0%B5+%D0%BF%D0%BE%D0%B1%D0%BB%D0%B0%D0%B3%D0%BE%D0%B4%D0%B0%D1%80%D0%B8%D1%82%D1%8C+%D0%B2%D1%80%D0%B0%D1%87%D0%B0%2C+%D1%83%D0%BA%D0%B0%D0%B7%D0%B0%D0%B2+%D0%B5%D0%B3%D0%BE+%D1%84%D0%B0%D0%BC%D0%B8%D0%BB%D0%B8%D1%8E&successURL=" width="450" height="286"></iframe>
          </div>
          <i class="br20"></i>
          <label style="display: inline;" class="custom_input_label" id="donate_sponsor_is_anonymous_wrap">
            <input id="donate_sponsor_is_anonymous" class="custom_input" name="is_anonymous" value="1" type="checkbox">&nbsp;Анонимно
          </label>
          <i class="br20"></i>
        </div>
      </td>
      <?php
      include_component('page', 'sponsor_list', array('donate_sponsors' => $donate_sponsors, 'location' => 'donate'));
      ?>
    </tr>
  </table>
<?php
}
?>
<script type="text/javascript">
  $(document).ready(function () {
    $('#donate_sponsor_is_anonymous').removeProp('checked');
  });
  $('#donate_sponsor_is_anonymous').change(function () {
    var param = '&fio=on';
    if($(this).is(':checked')){
      param = '';
    };
    $('#yandex_money').html('<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/shop.xml?account=410012854680235' + param + '&quickpay=shop&payment-type-choice=on&writer=seller&targets=%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C+%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D1%83+%C2%AB%D0%92%D1%80%D0%B0%D1%87+%D0%A0%D0%91%C2%BB&default-sum=&button-text=01&comment=on&hint=%D0%A2%D0%B0%D0%BA%D0%B6%D0%B5+%D0%B2%D1%8B+%D0%BC%D0%BE%D0%B6%D0%B5%D1%82%D0%B5+%D0%BF%D0%BE%D0%B1%D0%BB%D0%B0%D0%B3%D0%BE%D0%B4%D0%B0%D1%80%D0%B8%D1%82%D1%8C+%D0%B2%D1%80%D0%B0%D1%87%D0%B0%2C+%D1%83%D0%BA%D0%B0%D0%B7%D0%B0%D0%B2+%D0%B5%D0%B3%D0%BE+%D1%84%D0%B0%D0%BC%D0%B8%D0%BB%D0%B8%D1%8E&successURL=" width="450" height="286"></iframe>');
  });
</script>