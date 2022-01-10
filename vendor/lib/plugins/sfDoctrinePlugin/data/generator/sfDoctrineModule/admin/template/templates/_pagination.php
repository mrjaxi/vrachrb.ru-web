[?php
$amp = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '?q=' . $sf_request->getParameter('q') . '&' : '?');
if($sf_request->hasParameter('checked'))
{
  $amp .= '&checked=' . $sf_request->getParameter('checked');
}
?]
[?php if ($pager->getPage() > 1): ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=1">[?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/first.png', array('alt' => __('First page', array(), 'sf_admin'), 'title' => __('First page', array(), 'sf_admin'))) ?]</a><a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getPreviousPage() ?]">[?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/previous.png', array('alt' => __('Previous page', array(), 'sf_admin'), 'title' => __('Previous page', array(), 'sf_admin'))) ?]</a>[?php endif; ?][?php foreach ($pager->getLinks() as $page): ?][?php if ($page == $pager->getPage()): ?]<span>[?php echo $page ?]</span>[?php else: ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $page ?]">[?php echo $page ?]</a>[?php endif; ?][?php endforeach; ?][?php if ($pager->getPage() < $pager->getLastPage() ): ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getNextPage() ?]">[?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/next.png', array('alt' => __('Next page', array(), 'sf_admin'), 'title' => __('Next page', array(), 'sf_admin'))) ?]</a><a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getLastPage() ?]">[?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/last.png', array('alt' => __('Last page', array(), 'sf_admin'), 'title' => __('Last page', array(), 'sf_admin'))) ?]</a>[?php endif; ?]
<script type="text/javascript">
$(document).ready(function(){
 $('.lui_pager a').click(function(){
    is_not_good_style_of_code($(this).prop('url'));
  });
});
</script>