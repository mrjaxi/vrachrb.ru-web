<style>
  .donate_wrap{
    display: none;
    margin: 20px 0;
  }
  .donate_wrap td{
    padding: 1px 0 1px 10px;
    border-bottom: 1px solid rgba(0,0,0,0.2);
  }
  .donate_wrap td:first-child{
    padding-right: 40px;
    border-right: 1px solid rgba(0,0,0,0.2);
  }
</style>
<?php
if(!$form->isNew())
{
  $json = json_decode($form->getObject()->getJson(), true);
  echo '<table class="donate_wrap" cellspacing="0" cellpadding="0">';
  foreach ($json as $j_key => $j)
  {
    echo '<tr>';
      echo '<td width="1"><b>' . $j_key . '</b></td>';
      echo '<td>' . $j . '</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '<a onclick="$(\'.donate_wrap\').fadeToggle();" class="lui_pseudo">Подробности платежа</a>';
}
?>