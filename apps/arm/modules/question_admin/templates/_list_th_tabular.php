<?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_user">
  <?php if ('u.second_name' == $sort[0]): ?>
    <?php echo link_to(__('От кого', array(), 'messages'), '@question_admin', array('query_string' => 'sort=u.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('От кого', array(), 'messages'), '@question_admin', array('query_string' => 'sort=u.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_body">
  <?php if ('body' == $sort[0]): ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@question_admin', array('query_string' => 'sort=body&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@question_admin', array('query_string' => 'sort=body&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_is_anonymous">
  <?php if ('is_anonymous' == $sort[0]): ?>
    <?php echo link_to(__('Анонимно', array(), 'messages'), '@question_admin', array('query_string' => 'sort=is_anonymous&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Анонимно', array(), 'messages'), '@question_admin', array('query_string' => 'sort=is_anonymous&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<th class="sf_admin_boolean sf_admin_list_th_is_anonymous">
  <?php if ('approved' == $sort[0]): ?>
    <?php echo link_to(__('Одобрено', array(), 'messages'), '@question_admin', array('query_string' => 'sort=approved&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Одобрено', array(), 'messages'), '@question_admin', array('query_string' => 'sort=approved&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_foreignkey sf_admin_list_th_closed_by">
  <?php if ('uc.second_name' == $sort[0]): ?>
    <?php echo link_to(__('Закрыто пользователем', array(), 'messages'), '@question_admin', array('query_string' => 'sort=uc.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Закрыто пользователем', array(), 'messages'), '@question_admin', array('query_string' => 'sort=uc.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_date sf_admin_list_th_created_at">
    <?php if ('comment_id' == $sort[0]): ?>
      <?php echo link_to(__('id Вк', array(), 'messages'), '@question_admin', array('query_string' => 'sort=comment_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
      <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
    <?php else: ?>
      <?php echo link_to(__('id Вк', array(), 'messages'), '@question_admin', array('query_string' => 'sort=comment_id&sort_type=desc' . $q)) ?>
    <?php endif; ?>
  </th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_created_at">
  <?php if ('created_at' == $sort[0]): ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@question_admin', array('query_string' => 'sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@question_admin', array('query_string' => 'sort=created_at&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>