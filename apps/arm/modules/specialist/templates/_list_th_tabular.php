<?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_user">
  <?php if ('u.username' == $sort[0]): ?>
    <?php echo link_to(__('Пользователь', array(), 'messages'), '@specialist', array('query_string' => 'sort=u.username&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Пользователь', array(), 'messages'), '@specialist', array('query_string' => 'sort=u.username&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_sfm">
  <?php if ('u.second_name' == $sort[0]): ?>
    <?php echo link_to(__('ФИО', array(), 'messages'), '@specialist', array('query_string' => 'sort=u.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('ФИО', array(), 'messages'), '@specialist', array('query_string' => 'sort=u.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_specialty">
  <?php if ('s.title' == $sort[0]): ?>
    <?php echo link_to(__('Специальность', array(), 'messages'), '@specialist', array('query_string' => 'sort=s.title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Специальность', array(), 'messages'), '@specialist', array('query_string' => 'sort=s.title&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_work_place">
  <?php if ('work_place' == $sort[0]): ?>
    <?php echo link_to(__('Место работы', array(), 'messages'), '@specialist', array('query_string' => 'sort=work_place&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Место работы', array(), 'messages'), '@specialist', array('query_string' => 'sort=work_place&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_rating">
  <?php if ('rating' == $sort[0]): ?>
    <?php echo link_to(__('Рейтинг', array(), 'messages'), '@specialist', array('query_string' => 'sort=rating&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Рейтинг', array(), 'messages'), '@specialist', array('query_string' => 'sort=rating&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_foreignkey sf_admin_list_th_answers_count">
  <?php if ('answers_count' == $sort[0]): ?>
    <?php echo link_to(__('Количество консультаций', array(), 'messages'), '@specialist', array('query_string' => 'sort=answers_count&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Количество консультаций', array(), 'messages'), '@specialist', array('query_string' => 'sort=answers_count&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>