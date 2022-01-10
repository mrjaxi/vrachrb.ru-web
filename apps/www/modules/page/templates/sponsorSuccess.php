<?php
slot('title', __('Спонсоры проекта'));
?>
<div class="breadcrumbs">
  <a href="/">Главная</a><a href="<?php echo url_for('@donate');?>">Поддержать проект</a>
</div>
<h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Нам помогли');?></h1>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td>
    <?php
    include_component('page', 'sponsor_list', array('donate_sponsors' => $donate_sponsors, 'location' => 'sponsor'));
    ?>
    </td>
  </tr>
</table>