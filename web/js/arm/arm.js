var arm = {
  hl_color: '#C0FFC0',
  timeouts: [],
  create_error: function(message, classname){
    if($('.abs_error_list').length == 0){
      $('body').append('<div class="abs_error_list"></div>');
    }
    $('.abs_error_list').css('top', '15%');
    $('.abs_error_list').html(message);
  },
  remove_error: function(){
    $('.abs_error_list').css('top', '-100px');
  },
  init_sf_admin_date: function(){
    $('.sf_admin_date select').each(function(k, el){
      if(!$(el).data('spinpicker_input_init') && $(el).parent().find('.spinpicker_input_dyn').length == 0){
        var varname = $(el).attr('id').replace('_month', '');
        var day = $(el).parent().find('#' + varname + '_day');
        var month = $(el);
        var year = $(el).parent().find('#' + varname + '_year');
        
        $.each([day, month, year], function(k, el){
          $(el).css({
            'visibility': 'hidden',
            'position': 'absolute'
          });
        });
        $(el).data('spinpicker_input_init', true);
        var spinpicker_input = $('<input type="text" class="spinpicker_input_dyn" value="' + (day.val() != '' && month.val() != '' && year.val() != '' ? ((day.val() < 10 ? '0' + day.val() : day.val()) + '.' + (month.val() < 10 ? '0' + month.val() : month.val()) + '.' + year.val()) : '') + '" size="10" />');
        $(el).parent().append(spinpicker_input);
        $(spinpicker_input).spinpicker({
          lang: 'ru'
        });
        
        spinpicker_input.change(function(){
          var ex = $(this).val().split('.');
          day.val(ex[0]);
          month.val(parseInt(ex[1]));
          year.val(ex[2]);
          month.change();
        });
      }
      
      
      
      
    });
  },
  init: function(){
    this.init_sf_admin_date();

    $('.tool_tip_l').powerTip({
      fadeInTime: 0,
      fadeOutTime: 0,
      placement: 'nw',
      closeDelay: 0
    });
    $('.tool_tip').powerTip({
      fadeInTime: 0,
      fadeOutTime: 0,
      closeDelay: 0
    });
    $('form').each(function(k, el){
      var _this = $(el);
      
      if(_this.data('inited') == '1'){
        return;
      }
      _this.submit(function(){
        _this.data('inited', '1');
        arm.remove_error();
        if(arm.timeouts[0]){
          clearTimeout(arm.timeouts[0]);
        }
        if(arm.timeouts[1]){
          clearTimeout(arm.timeouts[1]);
        }
        var submit = $(this).find('input[type=submit],button[type=submit]');
        
        if(!submit.data('title_progress')){
          return;
        }
        if($(submit).hasClass('progress')){
          return false;
        }
        $(submit).addClass('progress');
        if($(submit).data('title_progress')){
          $(submit).val($(submit).data('title_progress'));
        }
        $.ajax({
          url: _this.attr('action') + '?json',
          type: _this.attr('method') ? _this.attr('method') : 'POST',
          data: _this.serialize()
        }).done(function(html){
          $(submit).removeClass('progress');
          var erlis = $(html).find('.error_list');
          if(erlis.length > 0){
            arm.create_error(erlis.html());
            $(submit).addClass('error');
            
            if($(submit).data('title_error')){
              $(submit).val($(submit).data('title_error'));
            }
            $(_this).find('input[type=password],input[type=text]').addClass('shake_eff');
            arm.timeouts[0] = setTimeout(function(){
              $(submit).removeClass('error');
              $(submit).val($(submit).data('title_normal'));
              $(_this).find('input[type=password],input[type=text]').removeClass('shake_eff');
            }, 1500);
            $(_this).find('input[type=password]').val('');
            $(_this).find('input[type=password]')[0].focus();
            arm.timeouts[1] = setTimeout(function(){
              arm.remove_error();
            }, 5000);
          } else if(html == 'refresh') {
            window.location.reload();
          } else if($(_this).data('callback')) {
            
            var evcode = $(_this).data('callback') + '(\''  + html + '\')';
            //alert(evcode);
            eval(evcode);
          } else {
            $('.lui__desktop_right').html(html);
            arm.init();
          }
        });
        return false;
      });
    });
    $('.ierch__layer__items').perfectScrollbar();
  },
  open_layer_props: false,
  open_layer_spool: [],
  open_layer: function(item, once){
    $('.ierch__layer__shadowed').remove();
    var parent = $(item).parent().parent();
    var layer = $('.ierch__layer__overlay');
    var root = $('.ierch__layers__wrapper');
    
    layer.show();
    
    
 
    
    var left = parent.position().left + parent.width();
    
    var fixw = (root.width() - left - 1);
    

    if(fixw == 0){
      //fixw = 220;
    }
    
    //cl(left);
    //cl(fixw);
    
    parent.find('.ierch__layer__item').removeClass('ierch__layer__item__opened');
    item.addClass('ierch__layer__item__opened');
    
    layer.width(fixw);
    //layer.css('background-color', '#FFC1A4');
    layer.css('top', root.position().top + 1);
    layer.css('left', left + 1);
    


    
    $.get('?' + ($(item).data('get_var')) + '=' + $(item).data('id') + '&level=' + (parseInt($(parent).data('level')) + 1) + '&ajax' + ($(item).data('type') == 'division' && !arm.open_layer_props ? '&props' : ''), function(html){
      var ww = 0;
      $('.ierch__layer').each(function(k, el){
        if(parseInt($(el).data('level')) > parseInt($(parent).data('level'))){
          $(el).remove();
        } else {
          ww += $(el).width() + 1;
        }
      });
      var ww_plus = ($(item).data('get_var') == 'worker_id' ? 600 : ($(item).data('type') == 'division' && !arm.open_layer_props ? 800 : 220));
      
      
      
      
      if($.cookie('division_audit_on') == '1' && $(item).data('get_var') == 'division_id'){
        ww_plus = 800;
      }

      
      
      ww += ww_plus;
      
      
      
      //root.width(ww);

      var $html = $(html);
      $(parent).after($html);
      //arm.init();
      $(window).resize();
      
      
      
      layer.hide();
     
      
      var path = [];
      $('.ierch__layer__item__opened').each(function(k, v){
        var kk = 'division';
        if($(v).data('type') == 'division_profession'){
          kk = 'dp';
        }
        if($(v).data('type') == 'division_worker'){
          kk = 'worker';
        }
        path.push('.ierch__layer__item_' + kk + '.ierch__layer__item__' + $(v).data('id'));
      });
      var state = {
        title: $('title').html(),
        url: '?path=' + path.join(':') + ($html.hasClass('ierch__layer__props') ? '*dash' : '')
      }

      history.pushState(state, state.title, state.url);
      
      arm.open_layer_props = false;
      
      
      if(arm.open_layer_spool.length > 0){
        
        
        var elem_s = arm.open_layer_spool[arm.open_layer_spool.length - 1].split('*');
        arm.open_layer_props = elem_s.length == 1;
        var elem = elem_s[0];
        
        if($(elem).length == 1){
          arm.open_layer($(elem));
          arm.open_layer_spool.pop();
        }
      } else if($(item).data('get_var') == 'division_id' && $html.find('.ierch__layer__item_worker').length == 1){
        arm.open_layer($html.find('.ierch__layer__item_worker').first());
      } else if($(item).data('get_var') == 'parent_id' && $html.find('.ierch__layer__item').length == 1){
        arm.open_layer($html.find('.ierch__layer__item').first());
      }
      
      
      

    });
    
  },
  save_n_open: function(id){
    var item = $('.ierch__layer__item__' + id);
    if(item.length > 0){
      var parent = item.parent().parent();
      parent.find('.ierch__layer__actions__form form')[0].reset();
      parent.find('.ierch__layer__actions__form').hide();
      arm.open_layer(item);
    }
  },
  update_last_layer: function(html){
    $('.ierch__layer__item_worker.ierch__layer__item__opened').click();
  },
  overlay_layer: function(el, title){
    var o = $('<div class="ierch__layer__shadowed" style="left:100%;"><div class="ierch__layer__shadowed__title" style="z-index:1000;position:fixed;background-color:#E5E5E5;width:100%;margin-left:0px;padding:15px 0;"><span class="lui_form_layer__close"></span><span style="font-size:18px;margin-left:45px;opacity: 0.5;">' + title + '</span></div><div class="ierch__layer__shadowed__wrapper"></div></div>');
    
    var o_close = o.find('.lui_form_layer__close');
    var o_close_click = function(){
      $(o).css('left', '100%');
      setTimeout(function(){
        $(o).remove();
      }, 100);
    }
    
    o_close.click(function(){
      o_close_click();
    });
    $(el).parent().append(o);
    var f_onresize = function(){
      $(o).outerWidth($(el).outerWidth() - 20);
      $(o).outerHeight($(el).outerHeight());
      $(o).find('.ierch__layer__shadowed__wrapper').css('margin-top', $(o).find('.ierch__layer__shadowed__title').outerHeight());
      $(o).find('.ierch__layer__shadowed__wrapper').outerHeight($(o).outerHeight() - $(o).find('.ierch__layer__shadowed__title').outerHeight());
      
      //cl($(o).find('.ierch__layer__shadowed__title').outerHeight());
    };
    f_onresize();
    $(window).resize(function(){
      f_onresize();
    });
    $(o).css('left', $(el).position().left + 20);
    $(window).keydown(function( event ) {
      if ( event.which == 27 ) {
        //o_close_click();
      }
    });
    return o;
  },
  modal_layer: function(el, title, sov){
    var mo = $('<div class="ierch__layer__modal"><div style="position:relative;background-color:#E5E5E5;padding:15px 0;"><span class="lui_form_layer__close"></span><span style="font-size:18px;margin-left:45px;opacity: 0.5;">' + title + '</span></div><div class="ierch__layer__modal__wrapper"></div></div>');
    var mo_close = mo.find('.lui_form_layer__close');
    var mo_close_click = function(){
      $(mo).remove();
      $(sov).remove();
    }
    
    mo_close.click(function(){
      mo_close_click();
    });
    $(el).append(mo);

    $(window).keydown(function( event ) {
      if ( event.which == 27 ) {
        //mo_close_click();
      }
    });
    return mo;
  },
  expand: function(el){
    var layer = $(el).parents('.ierch__layer__props').find('.ierch__layer__shadowed');
    //cl($(el));
    layer.height(layer.parent().height() + 15);
    
    var html = '<span class="ierch__layer__shadowed__close" onclick="$(this).parent().css(\'left\', $(this).parent().width() + 100)">×<span>&nbsp;закрыть</span></span><i class="br20"></i><div class="ierch__layer__shadowed__wrapper">';
    if($(el).data('ajax')){
      layer.addClass('ierch__layer__loading');
      setTimeout(function(){
        $.get($(el).data('ajax'), function(rhtml){
          layer.removeClass('ierch__layer__loading');
          $(layer).find('.ierch__layer__shadowed__wrapper').append(rhtml);
          //$(layer).find('.ierch__layer__shadowed__wrapper').perfectScrollbar('update');
        });
      }, 120);
    } else {
      html += '<h1>' + $(el).html() + '</h1><i class="hr20"></i><table cellpadding="0" cellspacing="0" width="100%">';
      var fields = $(el).data('fields').split(';');
      $(fields).each(function(k, v){
        html += '<tr valign="top"><td><b>';
        html += v.replace(':', ':</b><i class="br10"></i></td><td align="right">');
        html += '<i class="br10"></i></td></tr>';
      });
      
      html += '</table>';
    }
    $(layer).html(html + '</div><i class="br20"></i>');
    $(layer).show();
    $(layer).css('left', '20px');
    //$(layer).find('.ierch__layer__shadowed__wrapper').perfectScrollbar();
  }
};