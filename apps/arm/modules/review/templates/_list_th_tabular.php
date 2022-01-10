<?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_question">
  <?php if ('q.body' == $sort[0]): ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@review', array('query_string' => 'sort=q.body&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Вопрос', array(), 'messages'), '@review', array('query_string' => 'sort=q.body&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_specialist_name">
  <?php if ('u.second_name' == $sort[0]): ?>
    <?php echo link_to(__('Специалист', array(), 'messages'), '@review', array('query_string' => 'sort=u.second_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Специалист', array(), 'messages'), '@review', array('query_string' => 'sort=u.second_name&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>


<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_body">
  <?php if ('body' == $sort[0]): ?>
    <?php echo link_to(__('Текст отзыва', array(), 'messages'), '@review', array('query_string' => 'sort=body&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Текст отзыва', array(), 'messages'), '@review', array('query_string' => 'sort=body&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_text sf_admin_list_th_approved">
    <?php if ('approved' == $sort[0]): ?>
      <?php echo link_to(__('Одобрено', array(), 'messages'), '@review', array('query_string' => 'sort=approved&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
      <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
    <?php else: ?>
      <?php echo link_to(__('Одобрено', array(), 'messages'), '@review', array('query_string' => 'sort=approved&sort_type=desc' . $q)) ?>
    <?php endif; ?>
  </th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php $q = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '&q=' . $sf_request->getParameter('q') : ''); ?>
<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_created_at">
  <?php if ('created_at' == $sort[0]): ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@review', array('query_string' => 'sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc') . $q)) ?>
    <?php echo ($sort[1] == 'asc' ? '▼' : '▲');?>
  <?php else: ?>
    <?php echo link_to(__('Дата создания', array(), 'messages'), '@review', array('query_string' => 'sort=created_at&sort_type=desc' . $q)) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>