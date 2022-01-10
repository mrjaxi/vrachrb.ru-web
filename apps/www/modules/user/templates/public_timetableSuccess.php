<?php
slot('title', 'Расписание');
?>
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr valign="top">
<?php
/*
<td width="250" class="other__cont__menu">
include_component('main', 'menu');
</td>
*/
?>
<td class="other__cont" style="padding-right: 80px;padding-top:0;">
<?php
$wget = file_get_contents('http://ufaucheba.ru/ru/education/infomat2/' . ($sf_request->hasParameter('date') ? '?date=' . $sf_request->getParameter('date') : ''));
echo $wget;
?>
</td>
</tr>
</table>