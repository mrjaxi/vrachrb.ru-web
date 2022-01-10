<?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_question">
  <?php if ('q.body' == $sort[0]): ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@consilium', array('query_string' => 'sort=q.body&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@consilium', array('query_string' => 'sort=q.body&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_closed">
  <?php if ('closed' == $sort[0]): ?>
    <?php echo link_to(__('Консилиум завершен', array(), 'messages'), '@consilium', array('query_string' => 'sort=closed&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Консилиум завершен', array(), 'messages'), '@consilium', array('query_string' => 'sort=closed&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_closing_date">
  <?php if ('closing_date' == $sort[0]): ?>
    <?php echo link_to(__('Дата закрытия', array(), 'messages'), '@consilium', array('query_string' => 'sort=closing_date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Дата закрытия', array(), 'messages'), '@consilium', array('query_string' => 'sort=closing_date&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_created_at">
  <?php if ('created_at' == $sort[0]): ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@consilium', array('query_string' => 'sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@consilium', array('query_string' => 'sort=created_at&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>