[?php use_helper('I18N', 'Date') ?]
[?php if (!$sf_request->isXmlHttpRequest()): ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<span class="lui__h1">[?php echo <?php echo $this->getI18NString('list.title') ?> ?]</span><sup class="lui__list_count">&nbsp;[?php echo ($sf_request->getParameter('q') ? 'найдено:&nbsp;' : '');?][?php echo $pager->getNbResults(); ?]&nbsp;</sup>

[?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

<?php if ($this->configuration->hasTabs()): ?>
<div class="sf_admin_tabs">
[?php
foreach($tabs as $tab_key => $tab_val)
{
  echo '<a href="?tab=' . $tab_key . '" class="sf_admin_tab' . ($tab_key == $current_tab ? ' sf_admin_tab__active' : '') . '">' . $tab_val['label'] . '</a>';
}
?]
</div>
<?php endif; ?>
<span class="br15"></span>
<div class="batch_form__class">
[?php endif; ?]
<div class="lui__list_actions__wrapper" data-count="[?php echo $pager->getNbResults();?]">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
[?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
<?php if ($this->configuration->hasSearch()): ?>
<td style="padding-right: 10px"><input style="background-color:#fff;width:360px;" type="text" autocomplete="off" value="[?php echo $sf_request->getParameter('q'); ?]" class="type_search input_with_erase" id="<?php echo $this->getModuleName() ?>_live_search" placeholder="Найти" />
</td>
<?php endif; ?>
<?php if ($this->configuration->hasFilterForm()): ?>
<td style="padding-right: 10px">
[?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration)) ?]
</td>
<?php endif; ?>
<?php
if($this->configuration->getListExport() !== false)
{
?>
[?php
if($pager->getNbResults() > 0)
{
?]
<td>
<a href="[?php echo url_for('<?php echo $this->getModuleName();?>/index');?]?[?php echo ($sf_request->hasParameter('q') ? 'q=' . $sf_request->getParameter('q') . '&' : '');?]export" class="export_excel pseudo_button lui_pseudo anywhere_icon__excel">Excel</a>
</td>
[?php
}
?]
<?php
}
?>
[?php if ($pager->haveToPaginate()): ?]
<td align="right" width="100%">
  <div class="lui_pager">
     [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]&nbsp;
     <b style="position:relative;top:2px">[?php echo __('%%page%%/%%nb_pages%%', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?]</b>
  </div>
</td>
[?php endif; ?]
</tr>
</table>




</div>


<form id="batch_form" action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post">


<div id="lui_scroller" class="lui_scroller"><div class="lui__scroller_wrapper">[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]</div></div>


<input type="hidden" name="batch_action" id="batch_action_id" />
[?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?]
  <input type="hidden" name="[?php echo $form->getCSRFFieldName() ?]" value="[?php echo $form->getCSRFToken() ?]" />
[?php endif; ?]

</form>

[?php if (!$sf_request->isXmlHttpRequest()): ?]
</div>



<?php if ($this->configuration->hasSearch()): ?>
<script type="text/javascript">
var jqxhr = false;
var jqxhr_o = false;
var jqxhr_old = $('#<?php echo $this->getModuleName() ?>_live_search').val().trim();
var jqxhr_last_result = $('#<?php echo $this->getModuleName() ?>_live_search').val().trim();

var is_not_good_style_of_code = function(url){
  
  var state = {
    title: $('title').html(),
    url: window.location.pathname + url
  }

  
  history.pushState(state, state.title, state.url);
  
  jqxhr_o = sOverlay($('.lui_scroller'));
  jqxhr = $.ajax(url).done(function(html){

    var new_html = $(html);
    

    $('.lui_pager').html(new_html.find('.lui_pager').length > 0 ? new_html.find('.lui_pager').html() : '');
    $('.lui__scroller_wrapper').html(new_html.find('.lui__scroller_wrapper').html());
    
    create_custom_table_head();
    $('.lui__list_count').html('&nbsp;' + (jqxhr_old == '' ? '' : 'найдено:&nbsp;') + new_html.data('count'));
    $('.lui__list_table__sourse').removeHighlight().highlight(jqxhr_old);
    $('.export_excel').prop('href', url + '&export');
    if(new_html.data('count') == '0'){
      $('.export_excel').hide();
    } else {
      $('.export_excel').show();
    }
    
    jqxhr_last_result = $('#<?php echo $this->getModuleName() ?>_live_search').val().trim();
    
    jqxhr_o.remove();
  });
}

$(document).ready(function(){
  $('#<?php echo $this->getModuleName() ?>_live_search').keyup(function(event){
    
    if(jqxhr_last_result == $(this).val().trim()){
      return;
    }
    
    var checked = [];
    $('.sf_admin_batch_checkbox:checked').each(function(k, v){
      checked.push($(v).val());
    });
    jqxhr_old = $(this).val().trim();
    try{
      jqxhr.abort();
      jqxhr_o.remove();
    } catch(e){}
    
    if($(this).val().trim() != '' && event.which == 13){
      is_not_good_style_of_code('?q=' + jqxhr_old + (checked.length > 0 ? '&checked=' + checked.join(':') : ''));
    } else if( $(this).val().trim() == ''){
      is_not_good_style_of_code(checked.length > 0 ? '?checked=' + checked.join(':') : '');
    }
    

  });
  if(jqxhr_old != ''){
    cl(jqxhr_old);
    $('.lui__list_table__sourse').removeHighlight().highlight(jqxhr_old);
  }
});
</script>
<?php endif; ?>
[?php endif; ?]