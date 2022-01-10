
if(typeof arm.worktype == 'undefined') {
  arm.worktype = {};
}
arm.worktype.design_mode = function(t, target, division_id, profession_id){
  if($(t).hasClass('anywhere_icon__edit')){
    $(t).removeClass('anywhere_icon__edit');
    $(t).addClass('anywhere_icon__save');
    $(target).addClass('epe-arm-design_mode_off_on');
  } else {
    var checkboxes = [];
    $.each($(target).find('input:checked'), function(k, v){
      checkboxes.push($(v).val());
    });
    var sov = sOverlay($(target), 'more_index', $(target));
    $.post('/' + sf_app + '/work_type_division/save_all?division_id=' + division_id + '&profession_id=' + profession_id, {'work_type_division': checkboxes.join(';')}, function(){
      
      
      //sov.remove();
      $(t).addClass('anywhere_icon__edit');
      $(t).removeClass('anywhere_icon__save');
      $(target).removeClass('epe-arm-design_mode_off_on');
      callback_add_row_stuped('', sov);
    });
    
  }
}


if(typeof arm.worker == 'undefined') {
  arm.worker = {};
}


arm.worker.worker_only_course = function(type){
  var sov = sOverlay($('.for_calc_scroll'), 'more_index', $('.for_calc_scroll'));
  var _this = this;
  var is_new = type == 'new';
  $.get('/' + sf_app + '/worker_only_course/' + type, function(html){
    sov.addClass('no-bg');
    var ml = arm.modal_layer($('.ierch__layer__worker'), (type == 'new' ? 'Назначение курса' : 'Редактирование назначенного курса'), sov);
    $(ml).find('.ierch__layer__modal__wrapper').html($(html).find('.lui__scroller_wrapper'));
    $(ml).find('.lui__scroller_wrapper fieldset').after($(html).find('.sf_admin_actions'));
    
    
    var worker_id = $(ml).find('#worker_only_course_worker_id');
    worker_id.val($('.ierch__layer__worker').data('worker'));
    


    $(worker_id).css({
      'position': 'absolute',
      'visibility': 'hidden'
    });
    
    $(ml).css({
      'margin-left': -1 * Math.round($(ml).outerWidth() / 2) + 'px',
      'margin-top': -1 * Math.round($(ml).outerHeight() / 2) + ($('.ierch__layer__worker').scrollTop()) + 'px'
    });
    $(ml).find('.sf_admin_action_delete a').prop('onclick', '');
    $(ml).find('.sf_admin_action_delete a').click(function(event){
      event.preventDefault();
      if(confirm('Вы действительно хотитие отменить назначение?')){
        sov.removeClass('no-bg');
        $(ml).hide();
        $.ajax({
          url: $(this).attr('href'),
          type: 'POST',
          data: {'sf_method': 'delete'}
        }).done(function(resp){
          
          $('.ierch__layer__item__opened').last().click();
          
        });
      }
      return false;
    });
    
    $(ml).find('.sf_admin_action_save').click(function(){
      
      sov.removeClass('no-bg');

      var pseudo_form = $('<form action="/' + sf_app + '/worker_only_course' + (is_new ? '">' : '/' + type.replace('/edit', '') + '"><input type="hidden" name="sf_method" value="put" />') + '<input type="hidden" /></form>');
      pseudo_form.append($(ml));


      $.ajax({
        url: pseudo_form.prop('action'),
        type: 'POST',
        data: pseudo_form.serialize()
      }).done(function(resp){
         
         var erlis = $(resp).find('.error_list');
         if(erlis.length > 0){
           //TODO
         } else {
           sov.remove();
           $('.ierch__layer__item__opened').last().click();
         }
        
      });

      return false;
    });
    
    
    
  });
};

arm.worker.division_course = function(type){
  
  
  var sov = sOverlay($('.for_calc_scroll'), 'more_index', $('.for_calc_scroll'));
  var _this = this;
  var is_new = type == 'new';
  $.get('/' + sf_app + '/division_course/' + type, function(html){
    sov.addClass('no-bg');
    var ml = arm.modal_layer($('.ierch__layer__worker'), (type == 'new' ? 'Назначение курса' : 'Редактирование назначенного курса'), sov);
    $(ml).find('.ierch__layer__modal__wrapper').html($(html).find('.lui__scroller_wrapper'));
    
    var worker_id = $(ml).find('#worker_only_course_worker_id');
    worker_id.val($('.ierch__layer__worker').data('worker'));
    


    $(worker_id).css({
      'position': 'absolute',
      'visibility': 'hidden'
    });
    
    $(ml).css({
      'margin-left': -1 * Math.round($(ml).outerWidth() / 2) + 'px',
      'margin-top': -1 * Math.round($(ml).outerHeight() / 2) + ($('.ierch__layer__worker').scrollTop()) + 'px'
    });
    $(ml).find('.sf_admin_action_delete a').prop('onclick', '');
    $(ml).find('.sf_admin_action_delete a').click(function(event){
      event.preventDefault();
      if(confirm('Вы действительно хотитие отменить назначение?')){
        sov.removeClass('no-bg');
        $(ml).hide();
        $.ajax({
          url: $(this).attr('href'),
          type: 'POST',
          data: {'sf_method': 'delete'}
        }).done(function(resp){
          
          $('.ierch__layer__item__opened').last().click();
          
        });
      }
      return false;
    });
    
    $(ml).find('.sf_admin_action_save').click(function(){
      
      sov.removeClass('no-bg');

      var pseudo_form = $('<form action="/' + sf_app + '/worker_only_course' + (is_new ? '">' : '/' + type.replace('/edit', '') + '"><input type="hidden" name="sf_method" value="put" />') + '<input type="hidden" /></form>');
      pseudo_form.append($(ml));


      $.ajax({
        url: pseudo_form.prop('action'),
        type: 'POST',
        data: pseudo_form.serialize()
      }).done(function(resp){
         
         var erlis = $(resp).find('.error_list');
         if(erlis.length > 0){
           //TODO
         } else {
           sov.remove();
           $('.ierch__layer__item__opened').last().click();
         }
        
      });

      return false;
    });
    
    
    
  });
};



if(typeof arm.bid == 'undefined') {
  arm.bid = {};
}

arm.bid.fiterByDivision = function(el){
  var els_ct = el.closest('tbody');
  var els = els_ct.find('.arm_bid_fiter_by_division');
  if(el.hasClass('arm_bid_fiter_by_division__active')){
    els.removeClass('arm_bid_fiter_by_division__active');
    els_ct.find('tr').show();
  } else {
    els.removeClass('arm_bid_fiter_by_division__active');
    el.addClass('arm_bid_fiter_by_division__active');
    els.each(function(trk, trv){
      var show = false;
      var tr = $(trv).closest('tr');
      if($(trv).data('id') == el.data('id')){
        $(trv).addClass('arm_bid_fiter_by_division__active');
        show = true;
      }
      if(tr.find('input:checked').length){
        show = true;
      }
      if(show){
        tr.show();
      } else {
        tr.hide();
      }
    });
  }
}


arm.bid.check_chbid = function(el, id){
  $(el).toggleClass('arm_bid_bid_grouper__closed');
  if($(el).hasClass('arm_bid_bid_grouper__closed')){
    $('.sf_admin_row__chbid_' + id).hide();
    $.cookie('arm_bid_check_chbid_closed_' + id, '1');
  } else {
    $('.sf_admin_row__chbid_' + id).show();
    $.cookie('arm_bid_check_chbid_closed_' + id, '0');
  }
}

arm.bid.check_virtual_worker = function(select){
  var oldval = $(select).parent().parent().find('input').val();
  $(select).parent().parent().find('input').val($(select).val());
  var classname = $(select).attr('class');
  var val = $(select).val();
  var disabled = false;
  if($(select).val() == '0'){
    $(select).parent().parent().find('input').removeProp('checked');
  } else {
    disabled = true;
    $(select).parent().parent().find('input').prop('checked', 'checked')
  };
  
  $('.' + classname).each(function(k, rselect){
    $(rselect).find('option[value="' + oldval + '"]').removeAttr('disabled');;
    if(select.data('id') != $(rselect).data('id')){
      $(rselect).find('option[value="' + val + '"]').attr('disabled', true);
    }
  });
  
  arm.bid.check_workers();
}
arm.bid.check_virtual = function(checkbox){
  if($(checkbox).is(':checked')){
    $('#' + $(checkbox).val()).removeAttr('disabled');
  } else {
    $('#' + $(checkbox).val()).attr('disabled', 'disabled');
  }
}
arm.bid.change_date = function(ref, course_type){
  var url = $.url($('.lui_form_layer form').data('r'));
  var getvars = {};

  $.each($('.arm-widget_date_range select,.arm-widget_date_range input[type="text"],.arm-widget_date_range input[type="hidden"],.arm-widget_date_range input:checked'), function(k, v){
    getvars[$(v).attr('name')] = $(v).val();
  });
  

  
  if(ref == 'p'){
    var typess = '';
    
    $.each(getvars, function(k, v){
      typess += '&' + k + '=' + v;
    });
    
    $('.arm_p2_right_panel__b__cont').html('');
    $('.arm_p2_right_panel__b__cont').css({
      'background-image' : 'url(/i/arm/282-p.gif)',
      'background-repeat' : 'no-repeat',
      'background-position' : 'center center'
    });
    $.get(sf_prefix + '/report/learning/' + current_division + '?ref=p' + typess, function(html){
      $('.arm_p2_right_panel__b__cont').css({'background-image' : 'url(/i/n.gif)'});
      $('.arm_p2_right_panel__b__cont').html(html);
    });
  } else {
    var o = sOverlay($('.lui__desktop_right'), 'more_index2');
    
    $.post(url.attr('path'), getvars, function(html){
      
      $('.overflower').html($(html).find('.overflower').html());
      $('#bid_create_div input[type="text"],#bid_create_div select').val('');
      $('#bid_create_div select.chosen').trigger('chosen:updated');
      $('#bid_create_div select').change();
      o.remove();
      arm.bid.check_workers();
      
    });
  }

}

arm.bid.fixed_thead = function(){
  var master_thead = $('#standart__table__master_thead');
  var master_table = $('#standart__table__master').parent().parent();
  if(master_thead.length){
    var master_thead_clone = master_thead.clone();
    var clone = $('.standart__table__cloned');
    if(clone.length == 0){
      clone = $('<table cellpadding="0" cellspacing="0" class="standart__table standart__table__cloned" style="position:fixed;background:#fff;z-index:10000;"></table>');
      clone.append(master_thead_clone);
      $('#standart__table__master').parent().append(clone);
      clone.css({
        'top': master_table.offset().top + 1,
        'left': master_table.offset().left,
        //'width' : master_thead.width()
      });
    }
    
    
    var s_master_thead_ths = master_thead.find('th');
    var master_thead_ths = clone.find('th');
    master_thead_ths.each(function(i, th){
      var sth = s_master_thead_ths[i];
      
      
      $(th).width($(sth).width());
      
    });
    //clone.outerWidth(master_thead.outerWidth());
   
  }
}

arm.bid.check_bid_form = function(){
  var valid = false;
  var error_txt = false;
  if($('#bid_date_end').val() == ''){
    $('#bid_date_end').val($('#bid_date_start').val());
  }
  if($('#bid_date_start').val() != '' && $('#bid_date_end').val() != ''){
    var bid_date_start = new Date($('#bid_date_start').val().split('.').reverse().join('/'));
    var bid_date_end = new Date($('#bid_date_end').val().split('.').reverse().join('/'));
    
    if(bid_date_start < bid_date_end){
      valid = true;
    }
    if($('#training_center_id').val() == ''){
      valid = false;
    }
  }
  if(valid){
    $('.bid_create_button').removeAttr('disabled');
  } else {
    $('.bid_create_button').attr('disabled', true);
  }
};

arm.bid.check_checkboxes = function(){
  var items = $('.epe-arm-bid_create_item');
  items.each(function(i, item){
    var enabled_ch = $(item).find('.worker_checkbox:enabled').length;
    var checked_ch = $(item).find('.worker_checkbox:checked').length;
    var course_ch = $(item).find('.course_checkbox');
    course_ch.removeClass('not_all_checked');
    if(checked_ch > 0){
      course_ch.prop('checked', true);
      if(checked_ch < enabled_ch){
        course_ch.addClass('not_all_checked');
      }
    } else {
      course_ch.removeProp('checked');
    }
  });
}
arm.bid.check_workers = function(){
  arm.bid.calc_selected();
  arm.bid.check_checkboxes();
  var form = $('.lui_form_layer form');
  var url = $.url(form.attr('action'));
  var l = $('#lui__list_table_donor input:checked').length;
  $('#bid_create_div .sOverlay_white').remove();
  var checkBatchOverlay = $('#bid_create_div .sOverlay_white');
  if(checkBatchOverlay.length == 0){
    checkBatchOverlay = sOverlay($('#bid_create_div'), 'sOverlay_white', $('#bid_create_div'));
  }
  checkBatchOverlay.css('visibility', 'hidden');
  
  var checkBatchOverlayWait = sOverlay($('#bid_create_div'), 'more_index2', $('#bid_create_div'));
  
  $.post(sf_prefix + '/bid/check_workers?date_start=' + $('#bid_date_start').val() + '&date_end=' + $('#bid_date_end').val(), form.serialize(), function(response){
    checkBatchOverlayWait.remove();
    if($('.worker_checkbox:checked, .course_checkbox:checked').length > 0){
      checkBatchOverlay.css('visibility', 'hidden');
    }else{
      checkBatchOverlay.css('visibility', 'visible');
    }
    arm.bid.fixed_thead();
    arm.bid.check_bid_form();
  });
};

arm.bid.make = function(){
  var form = $('.lui_form_layer form');
  var url = $.url(form.attr('action'));
  var checkBatchOverlayWait = sOverlay($('#bid_create_div'), 'more_index2', $('#bid_create_div'));
  $.post(url.attr('path') + 
    '?date_start=' + $('#bid_date_start').val() + 
    '&date_end=' + $('#bid_date_end').val() + 
    '&training_center_id=' + $('#training_center_id').val() +
    '&training_center_branche_id=' + $('#training_center_branche_id').val()
    , form.serialize(), function(html){
    checkBatchOverlayWait.remove();
    arm.bid.change_date('', '');
  });
};

arm.bid.calc_selected = function(){
  var summ = 0;
  $('.worker_checkbox:checked').each(function(k, checkbox){
    if(!isNaN(parseInt($(checkbox).data('price')))){
      summ += parseInt($(checkbox).data('price'));
    }
    
  })
  $('.epe-arm-bid_create_item__cost__val_selected').text(number_format(summ, 0, '', ' '));
  
}
arm.bid.calc_summ_total = function(){
  var summ = 0;
  $('.epe-arm-bid_create_item__cost__val').each(function(k, v){
    if($(v).text() != '?'){
      summ += parseInt($(v).text().replace(/ /g, ''));
    }
    if($(v).text() == '0'){
      $(v).parent().addClass('hidden_element_width_pseudo');
    }
  });
  if(summ == 0){
    $('.epe-arm-bid_create_item__cost__val_total').parent().addClass('hidden_element_width_pseudo');
  }

  $('.epe-arm-bid_create_item__cost__val_total').text(number_format(summ, 0, '', ' '));
 
}



function check(elem, s){
  if($(elem).length > 0){
    $(elem).prop('checked' , s);
  }
  var l = $('#lui__list_table_donor input:checked').length;
  var checkBatchOverlay = $('#bid_create_div .sOverlay_white');
  if(checkBatchOverlay.length == 0){
    checkBatchOverlay = sOverlay($('#bid_create_div'), 'sOverlay_white', $('#bid_create_div'));
  }

  
  if($('.worker_checkbox:checked, .course_checkbox:checked').length > 0){
    checkBatchOverlay.css('visibility', 'hidden');
  }else{
    checkBatchOverlay.css('visibility', 'visible');
  }
  arm.bid.calc_selected();
};

function check_worker(worker){
  var wo = $(worker);
  $('.worker_checkbox__' + wo.data('wid')).each(function(k, v){
    if($(v).prop('id') != wo.prop('id')){
      if(wo.is(':checked') && $(v).data('deadline') != wo.data('deadline')){
        $(v).prop('disabled', true);
      } else {
        $(v).prop('disabled', false);
      }
    }
  });
}


$(document).on('click', '.course_checkbox', function(){
  var tr = $(this).closest('tr');
  var items = tr.find('.worker_checkbox');

  if($(this).is(':checked')){
    items.prop('checked', true);
  } else {
    if($(this).hasClass('not_all_checked')){
      items.prop('checked', true);
      $(this).prop('checked', true);
      $(this).removeClass('not_all_checked')
    } else {
      items.removeProp('checked');
    }
  }
  arm.bid.check_workers();
});
$(document).on('click', '.worker_checkbox:enabled', function(){
  arm.bid.check_workers();
  /*
  
  check_worker($(this));
  var state = false,
      p = $(this).parents('.standart__table');

  state = $(this).parents('.standart__table_tr').find('.worker_checkbox:enabled').is(':checked') ? 'true' : false;
  check($(this).parents('.standart__table_tr').find('.course_checkbox'), state);
  check(p.find('.course_all_checkbox'), p.find('.course_checkbox:checked').length ? 'true' : false);
  */
});
