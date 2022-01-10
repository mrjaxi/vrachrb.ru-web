[?php
$amp = ($sf_request->hasParameter('q') && $sf_request->getParameter('q') != '' ? '?q=' . $sf_request->getParameter('q') . '&' : '?');
if($sf_request->hasParameter('checked'))
{
//$amp .= 'checked=' . $sf_request->getParameter('checked') . '&';
}
?]
[?php if ($pager->getPage() > 1): ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=1"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a><a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getPreviousPage() ?]"><i class="fa fa-angle-left" aria-hidden="true"></i></a>[?php endif; ?][?php foreach ($pager->getLinks() as $page): ?][?php if ($page == $pager->getPage()): ?]<span>[?php echo $page ?]</span>[?php else: ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $page ?]">[?php echo $page ?]</a>[?php endif; ?][?php endforeach; ?][?php if ($pager->getPage() < $pager->getLastPage() ): ?]<a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getNextPage() ?]"><i class="fa fa-angle-right" aria-hidden="true"></i></a><a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?][?php echo $amp;?]page=[?php echo $pager->getLastPage() ?]"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>[?php endif; ?]
<script type="text/javascript">
$(document).ready(function () {
$('.lui_pager a').click(function () {
    var split = $(this).prop('href').split("?");

    /*
    var params = [];
    $.each(split.split("&"), function (k, v) {
        if (v.indexOf("checked") == -1) {
            params.push(v);
        }
    });
    */

    var checked = [];
    $('.sf_admin_batch_checkbox:checked').each(function (k, v) {
        checked.push($(v).val());
    });
    is_not_good_style_of_code("?" + split[1] + (checked.length > 0 ? '&checked=' + checked.join(':') : ''));
    return false;
});
});
</script>
