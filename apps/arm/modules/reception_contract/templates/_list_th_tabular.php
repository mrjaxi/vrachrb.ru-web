<?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_user">
  <?php if ('u.second_name' == $sort[0]): ?>
    <?php echo link_to(__('Кто', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=u.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Кто', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=u.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_specialist">
  <?php if ('su.second_name' == $sort[0]): ?>
    <?php echo link_to(__('К кому', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=su.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('К кому', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=su.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_question">
  <?php if ('q.body' == $sort[0]): ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=q.body&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=q.body&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_price">
  <?php if ('price' == $sort[0]): ?>
    <?php echo link_to(__('Цена', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=price&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Цена', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=price&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_is_activated">
  <?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Подтвержден', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Подтвержден', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=is_activated&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_boolean sf_admin_list_th_is_reject">
    <?php if ('is_reject' == $sort[0]): ?>
      <?php echo link_to(__('Отказ от приема', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=is_reject&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
      <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
    <?php else: ?>
      <?php echo link_to(__('Отказ от приема', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=is_reject&sort_type=desc' . $q)) ?>
    <?php endif; ?>
  </th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_created_at">
  <?php if ('created_at' == $sort[0]): ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@reception_contract', array('query_string' => 'sort=created_at&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>