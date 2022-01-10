var arm_puspose = {
  clicks_saved: [],
  checked: 0,
  checkboxes: '',
  xhr: null,
  o: null,
  check_profession: function(_this){
    if(_this.data('vary')){

      $('.arm_puspose_dest_list_division .arm_p_checkboxes__by_p__' + _this.data('vary')).each(function(k, el){
        var chb = $(el).find('.arm_p_course_checkbox__' + _this.val());
        
        if(_this.is(':checked')){
          $(chb).prop('checked', true);
          $(chb).data('confirmed', '1');
          $(chb).addClass('custom_input_blue');
        } else {
          $(chb).removeProp('checked');
          $(chb).data('confirmed', '0');
          $(chb).removeClass('custom_input_blue');
        }
        
      });
    }

  },
  check: function(){
    if(arm_puspose.xhr != null){
      arm_puspose.xhr.abort();
      arm_puspose.xhr = null;
    }
    if(arm_puspose.o != null){
      arm_puspose.o.remove();
    }
    arm_puspose.o = sOverlay($('.arm_puspose_dest_list'), 'sOverlay_white');

    
    var arm_puspose_courses_list_filter_id = $('.arm_last_tabs_item_active').data('tab');
    var checked = [];
    var checked_courses = [];
    var tab_id = $('.arm_last_tabs_item_active').data('tab');
    var arm_p_checkboxes = '';
    arm_puspose.checkboxes = '';
    $('.arm_puspose_courses_list .arm_puspose_courses_list_item_div__' + arm_puspose_courses_list_filter_id + ' .arm_puspose_courses_list_item input').each(function(){
      if($(this).is(':checked')){
        if(!$(this).data('rc')){
          var rc = randomColor({luminosity: 'light'});
          $(this).data('rc', rc);
        }
        $(this).parent().parent().css('background-color', $(this).data('rc'));
        arm_p_checkboxes += '<span title="' + $(this).parent().text().replace(/"/g, '') + '" class="arm_p_checkboxes__span" style="background:' + $(this).data('rc') + ';border-color:' + $(this).data('rc') + '"><input class="arm_p_course_checkbox arm_p_course_checkbox__' + $(this).val() + '" type="checkbox" oncontextmenu="$(this).toggleClass(\'arm_p_course_checkbox__edit\');arm_puspose.arm_puspose_info__changes();return false;" onclick="arm_puspose.check_profession($(this));arm_puspose.arm_puspose_info__changes();" id="%%ID%%__' + $(this).val() + '" value="' + $(this).val() + '" /></span>';
        checked.push($(this).val());
        checked_courses[$(this).val()] = {'title' : $(this).parent().text(), 'color' : $(this).data('rc'), 'count' : 0};
      } else {
        $(this).parent().parent().css('background-color', 'transparent');
      }
    });
    
  
    
    
    arm_puspose.checkboxes = arm_p_checkboxes;
    arm_puspose.checked = checked.length;
    
    $('.arm_puspose_info').css('visibility', (arm_puspose.checked > 0 ? 'visible' : 'hidden'));
    $('.arm_puspose_info_course_count').html(arm_puspose.checked);
    
    
    
    
    
    arm_puspose.xhr = $.ajax({
      type: 'POST',
      url: sf_prefix + '/purpose/rels?tab_id=' + $.url().param('tab_id'),
      data: { ids: checked },
      dataType: 'json'
    }).done(function(json) {
      $('.how_match_span').html('');
      $('.profession_cat_' + arm_puspose_courses_list_filter_id + ' .arm_p_checkboxes').each(function(k, el){
        if(arm_puspose.xhr == null){
          return false;
        }
        
        
        
        
        //var old_checked = $(el).find('input.custom_input_green:checked');
        
        $(el).parent().find('label:first').css('padding-right', (18 * checked.length) + 10 + 'px');
        var this_arm_p_checkboxes = arm_p_checkboxes.replace(/%%ID%%/ig, $(el).data('id'));
        $(el).html(this_arm_p_checkboxes);
        $(el).find('input').customizeForm();
        
        
        
        var type_of = $(el).data('typeof');
        
        //data-="%%VARY_PARENT%%" data-vary_children="%%VARY_CHILDREN%%"
        
        
        
        $(el).find('input').each(function(ik, ich){
          //cl(ich);
          if(type_of == 'p'){
            $(ich).data('vary', $(el).data('pid'));
            arm_puspose.check_profession($(ich));
            /*
            $(ich).data('vary_parent', 'arm_p_course_checkbox__' + $(el).val());
            $(ich).data('vary_children', 'arm_p_course_checkbox__' + $(el).data('id'));
            */
            
          } else if(type_of == 'w') {
            
          }
        });
        
        
        
        //cl();
        
        //cl(this_arm_p_checkboxes);
        
        
        
        /*
        if(old_checked.length > 0){
          old_checked.each(function(ko, cho){
            $(el).find('input').each(function(k, ch){
            
              if($(cho).attr('id') == $(ch).attr('id')){
                $(ch).prop('checked', true);
                
              }
            });
          });
        }
        */
        
      });
      
      $.each(json.courses, function(k, v){
        if(arm_puspose.xhr == null){
          return false;
        }
        var chb = $('#' + v.model + '__' + v.id + '__' + v.course_id);
        if(chb.length > 0){
          if(v.how_match){
            chb.data('how_match', v.how_match);
            chb.data('how_match_type', v.how_match_type);
            //chb.parent().prop('title', chb.parent().prop('title') + ' - ' + v.how_match);
            
            
            
            var how_match_str = v.how_match + ' ' + (v.how_match_type == 'percent' ? '%' : f.niceRusEnds(v.how_match, 'сотрудник', 'сотрудника', 'сотрудников'));
            
            
            
            if(arm_puspose.checked == 1){
              chb.parent().parent().parent().find('.how_match_span:first').html(how_match_str);
            } else {
              chb.mouseenter(function(){
                $(this).parent().parent().parent().find('.how_match_span:first').html(how_match_str);
              });
              chb.mouseleave(function(){
                $(this).parent().parent().parent().find('.how_match_span:first').html('');
              });
            }
          }
          
          if(typeof v.tmp == 'undefined'){
            chb.prop('checked', true);
            chb.data('confirmed', '1');
          } else {
            if(v.tmp == 'link'){
              chb.prop('checked', true);
            } else if(v.tmp == 'unlink') {
              chb.data('confirmed', '1');
              chb.removeProp('checked');
            } else if(v.tmp == 'edit'){
              chb.addClass('arm_p_course_checkbox__edit');
              chb.prop('checked', true);
              chb.data('confirmed', '1');
            }
          }

          if(v.gray) {
            chb.addClass('custom_input_gray');
          }
          
        }
      });
      
      
      
      
      $('.arm_p_dlsi_count').html('');
      var count_html = '';
      var count_html2 = '';
      $.each(checked, function(k, v){
        var item = checked_courses[v];
        count_html += '<span data-course_id="' + v + '" class="arm_p_dlsi_count__item arm_p_dlsi_count__item_' + k + '" title="' + item.title + '" style="background-color:' + item.color + '">' + (typeof json.counts.professions[v] == 'number' ? json.counts.professions[v] : 0) + '</span>';
        count_html2 += '<span data-course_id="' + v + '" class="arm_p_dlsi_count__item arm_p_dlsi_count__item_' + k + '" title="' + item.title + '" style="background-color:' + item.color + '">' + (typeof json.counts.divisions[v] == 'number' ? json.counts.divisions[v] : 0) + '</span>';
      });
      $('.arm_p_dlsi_count__profession').html(count_html);
      $('.arm_p_dlsi_count__root').html(count_html2);

      

      $('.arm_p_dlsi_count__item').click(function(){
        var _this = $(this);
        $('.arm_puspose_dest_list_division .arm_p_dlsi_cl').removeClass('arm_p_dli_o');
        $('.arm_puspose_dest_list_division .has_hover').removeClass('arm_p_dli_o2');
        
        if(_this.html() == '0'){
          //return false;
        }
        
        //cl($('.arm_p_course_checkbox__' + _this.data('course_id') + ':checked'));
        
        $('.arm_puspose_dest_list_division .arm_p_dlsi_cl_root').addClass('arm_p_dli_o');
        
        
        
        $('.arm_p_course_checkbox__' + _this.data('course_id') + ':checked').each(function(k, el){
          
          //cl($(el).parents('.arm_p_dlsii'));
          
          cl(el);
          
          $(el).parents('.arm_p_dlsii').each(function(kk, par){
            //if(kk > 0){
              $(par).find('.arm_p_dlsi_cl:first').addClass('arm_p_dli_o');
              if($(par).find('.arm_p_dlsi_cl:first').parent().hasClass('has_hover')){
                $(par).find('.arm_p_dlsi_cl:first').parent().addClass('arm_p_dli_o2');
              }
              //$(par).find('.has_hover:first');
              
            //}

          });
        });
      });
      
      
      
      /*
      $('.arm_puspose_dest_list input[type=checkbox]').each(function(){
        if($(this).hasClass('arm_p_primary_checkbox__changed')){
          
        } else {
          $(this).prop('checked', false);
          var parent = $(this).parent().parent();
          parent.css('padding-right', 0);
          parent.find('.arm_p_checkboxes').html('');
        }
      });
      
      $('.arm_p_dlsi_count').html('');
      
      
      */
      
      /*
      
      
      var json_profession_l = 0;
      var kk = 0;
      $.each(json.profession, function(k, v){
        kk++;
        json_profession_l += v.length;
        
        
        
        $.each(v, function(ck, cv){
          
          checked_courses[k].count++;
          
          var chb = $('#short_id_p_' + cv);
          chb.prop('checked', true);

          var arm_p_checkboxes_parent = chb.parent().parent();
          var arm_p_checkboxes_this = arm_p_checkboxes_parent.find('.arm_p_checkboxes');
          if(arm_p_checkboxes_this.html() == ''){
            arm_p_checkboxes_this.html(arm_p_checkboxes);
            arm_p_checkboxes_parent.css('padding-right', (18 * checked.length) + 'px');
          }
          arm_p_checkboxes_this.find('input[value="' + k + '"]').prop('checked', true);
        });
        
      });
      var json_division_course_l = 0;
      var kk = 0;
      $.each(json.division_course, function(k, v){
        kk++;
        json_division_course_l += v.length;
        
        
        
        $.each(v, function(ck, cv){
          
          var chb = $('#short_id_dp_' + cv);
          chb.prop('checked', true);
          
          var arm_p_checkboxes_parent = chb.parent().parent();
          var arm_p_checkboxes_this = arm_p_checkboxes_parent.find('.arm_p_checkboxes');
          if(arm_p_checkboxes_this.html() == ''){
            arm_p_checkboxes_this.html(arm_p_checkboxes);
          }
          arm_p_checkboxes_this.find('input[value="' + k + '"]').prop('checked', true);
        });
        
      });
      
      
      var count_html = '';
      
      $.each(checked, function(k, item_k){
        var item = checked_courses[item_k];
        if(typeof item == 'object'){
          count_html += '<span class="arm_p_dlsi_count__item arm_p_dlsi_count__item_' + item_k + '" title="' + item.title + '" style="background-color:' + item.color + '">' + item.count + '</span>';
        }
      });
      
      //count_html += '<span class="arm_p_dlsi_count__item" title="Всего">' + json_profession_l + '</span>';
      
      $('.arm_puspose_dest_list_profession .arm_p_dlsi_count').html(count_html);
      
      
      $('.arm_puspose_dest_list_division .arm_p_dlsi_count__root').html(count_html);
      $('.arm_puspose_dest_list_division .arm_p_dlsi_count__root .arm_p_dlsi_count__item').html('0');
      
      var json_division_l = 0;
      var json_worker_only_course_l = 0;
      var kk = 0;
      $.each(json.worker_only_course, function(k, v){
        kk++;
        json_worker_only_course_l += v.length;
        
        var cch = $('.arm_puspose_dest_list_division .arm_p_dlsi_count__root .arm_p_dlsi_count__item_' + k);
        cch.html(v.length);
        
        $.each(v, function(ck, cv){
          var chb = $('#short_id_w_' + cv.w_id);
          chb.prop('checked', true);
          var arm_p_checkboxes_parent = chb.parent().parent();
          var arm_p_checkboxes_this = arm_p_checkboxes_parent.find('.arm_p_checkboxes');
          if(arm_p_checkboxes_this.html() == ''){
            arm_p_checkboxes_this.html(arm_p_checkboxes);
          }
          arm_p_checkboxes_this.find('input[value="' + k + '"]').prop('checked', true);
          
          arm_p_checkboxes_parent.css('padding-right', (18 * checked.length) + 'px');
          
          var bad_method = $('.arm_puspose_dest_list_division .arm_p_dlsi_count__' + cv.d_id);
          if(bad_method.html() == ''){
            bad_method.html(count_html);
            bad_method.find('.arm_p_dlsi_count__item').html('0');
            bad_method.find('.arm_p_dlsi_count__item_' + k).html('1');
          } else {
            bad_method.find('.arm_p_dlsi_count__item_' + k).html(parseInt(bad_method.find('.arm_p_dlsi_count__item_' + k).html()) + 1);
          }
          
          bad_method.parent().parents('.arm_p_dlsii').each(function(pak, pav){
            
            
            var parent = $(pav).find('.arm_p_dlsi_count:first');
            if(parent.html() == ''){
              parent.html(count_html);
              parent.find('.arm_p_dlsi_count__item').html('0');
              parent.find('.arm_p_dlsi_count__item_' + k).html('1');
            } else {
              
              parent.find('.arm_p_dlsi_count__item_' + k).html(parseInt(parent.find('.arm_p_dlsi_count__item_' + k).html()) + 1);
            }

            $(pav).find('span:first').css('padding-right', parent.width() + 'px');
          });
          
        });
        
      });
      
      
      
      
      json_division_l += json_worker_only_course_l;
      
      
      
      
      
      
      
      
      
      */
      
      
      /*
      if(arm_puspose.clicks_saved.length > 0){
        cl(arm_puspose.clicks_saved);
        $(arm_puspose.clicks_saved.join(',')).click();
      }
      */

      var tab = $('.arm_last_tabs_item_active').data('tab');
      $('.profession_cat_' + tab + ' .arm_p_primary_checkbox').each(function(){
        if(arm_puspose.xhr == null){
          return false;
        }
        arm_puspose.not_all_checked($(this));
      });
      arm_puspose.arm_puspose_info__changes();
      
      /*
      $('.arm_p_checkboxes__span').miniTip({
        anchor: 's',
        delay: 50,
        fadeOut: 0,
        fadeIn: 0,
        offset: 10,
        show: function(){
          //$(this).parent().parent().find('label:first').addClass('arm_p_checkboxes__hover');
        },
        hide: function(){
          //$(this).parent().parent().find('label:first').removeClass('arm_p_checkboxes__hover');
        }
      });
      */
      arm_puspose.o.remove();
      arm_puspose.xhr = null;
    });
  },
  not_all_checked: function(_this){
    var ichsl = $(_this).parent().parent().find('.arm_p_checkboxes:first input:checked').length;
    
    if(ichsl > 0){
      $(_this).prop('checked', true);
      if(ichsl == arm_puspose.checked){
        $(_this).removeClass('not_all_checked');
      } else {
        $(_this).addClass('not_all_checked');
      }
    } else {
      $(_this).prop('checked', false);
      $(_this).removeClass('not_all_checked');
    }
  },
  arm_puspose_info__changes: function(){
    arm_puspose.check_changes();
  },
  search: {
    idx: [],
    courses: function(){
      var q = $('#arm_puspose_courses_search_field__input').val().trim().toLowerCase();
      var tab = $('.arm_last_tabs_item_active').data('tab');
      var items = $('.arm_puspose_courses_list_item_div__' + tab);
      var finded = [];
      if(q != ''){
        items.hide();
        items.each(function(k, item){
          var iof = $(item).text().toLowerCase().indexOf(q);
          if(iof == 0){
            finded.push({item: $(item), iof: iof});
          }
        });
        if(finded.length == 0){
          items.each(function(k, item){
            var iof = $(item).text().toLowerCase().indexOf(q);
            if(iof != -1){
              finded.push({item: $(item), iof: iof});
            }
          });
        }
      } else {
        items.show();
      }
      $.each(finded, function(k, el){
        $(el.item).show();
      });
      $('.arm_puspose_courses_list_item_div__' + tab + ' input:checked').each(function(k, el){
        $(el).parent().parent().show();
      });
      arm_puspose.check_scroll();
    },
    dest: function(){
      
      
      
      var q = $('#arm_puspose_dest_search_field__input').val().trim().toLowerCase();
      if(q != ''){
        $('.arm_puspose_dest_list_division .arm_p_dlsii').hide();
        $('.arm_puspose_dest_list_profession .arm_p_dlsii').hide();
        if(q.length < 2){
          return false;
        }
        $('.arm_puspose_dest_list_division .arm_p_dlsi_cl_root').addClass('arm_p_dli_o');
        $('.arm_puspose_dest_list_profession .arm_p_dlsi_cl').addClass('arm_p_dli_o');
      } else {
        $('.arm_puspose_dest_list_division .arm_p_dlsii').show();
        $('.arm_puspose_dest_list_profession .arm_p_dlsii').show();
        $('.arm_puspose_dest_list_division .arm_p_dlsi_cl').removeClass('arm_p_dli_o');
        $('.arm_puspose_dest_list_profession .arm_p_dlsi_cl').removeClass('arm_p_dli_o');
        return false;
      }
     
      $('.arm_puspose_dest_list_division .search_content').each(function(k, el){
        if($('#arm_puspose_dest_search_field__input').val().trim().toLowerCase() != q){
          return false;
        }
        if($(el).text().toLowerCase().indexOf(q) != -1){
          $(el).parents('.arm_p_dlsii').show();
          $(el).parents('.arm_p_dlsii').each(function(k, par){
            if(k > 0){
              $(par).find('.arm_p_dlsi_cl:first').addClass('arm_p_dli_o');
              if($(par).find('.arm_p_dlsi_cl:first').parent().hasClass('has_hover')){
                $(par).find('.arm_p_dlsi_cl:first').parent().addClass('arm_p_dli_o2');
              }
            }
            //cl();
            //.find('.arm_p_dlsi_cl:first').addClass('arm_p_dli_o');
          });
          if($(el).hasClass('search_content_d') || $(el).hasClass('search_content_p')){
            $(el).parent().find('.arm_p_dlsii').show();
          }
        }
      });
      $('.arm_puspose_dest_list_profession .search_content').each(function(k, el){
        if($('#arm_puspose_dest_search_field__input').val().trim().toLowerCase() != q){
          return false;
        }
        if($(el).text().toLowerCase().indexOf(q) != -1){
          $(el).parent().parent().show();
        }
      });
    }
  },
  check_changes_data: {
    link: [],
    unlink: []
  },
  set_fio_disabled: function(el){
    
  },
  check_changes: function(){
    var tab = $('.arm_last_tabs_item_active').data('tab');
    
    /*
    $('.arm_puspose_dest_list_profession .profession_cat_' + tab + ' .arm_p_checkboxes input').each(function(k, el){
       var $el = $(el);
       var st_id = $el.parent().parent().data('id').replace('profession_course__', '');
       
       //cl($el.is(':checked'));
      
       if($el.is(':checked')){
         
        
         //cl($el.is(':checked'));
         
         $('.arm_p_checkboxes__by_p__' + st_id + ' .arm_p_course_checkbox__' + $el.val()).each(function(sk, sel){
           var $sel = $(sel);
           //$sel.prop('disabled', true);
           $sel.prop('checked', true);
           //arm_puspose.set_fio_disabled($sel);
         });
       } else {
         $('.arm_p_checkboxes__by_p__' + st_id + ' .arm_p_course_checkbox__' + $el.val()).each(function(sk, sel){
           var $sel = $(sel);
           if($sel.is(':disabled')){
             //$sel.removeProp('disabled');
             $sel.removeProp('checked');
           }
           //arm_puspose.set_fio_disabled($sel);
         });
       }
       
       
    });
    */
    
    
    var edit = [];
    var link = [];
    var unlink = [];
    
    
    
    $('.profession_cat_' + tab + ' .arm_p_checkboxes').each(function(pk, pel){
      var this_changed = false;

      $(pel).find('.arm_p_course_checkbox').each(function(k, el){
      
        var split = $(el).attr('id').split('__');
        var push_object = {'course_id' : split[2], 'course_title' : $(el).parent().prop('title'), 'id' : split[1], 'tablename' : split[0], 'label' : $(el).parent().parent().data('label')};
        if($(el).is(':checked') && !$(el).is(':disabled') && !$(el).data('confirmed')){
          link.push(push_object);
          $(el).addClass('custom_input_green');
          this_changed = true;
        } else if(!$(el).is(':checked') && $(el).data('confirmed') == '1'){
          unlink.push(push_object);
          this_changed = true;
        } else if($(el).is(':checked') && $(el).data('confirmed') == '1' && $(el).hasClass('arm_p_course_checkbox__edit') && push_object.tablename == 'division_course'){
          push_object.how_match = $(el).data('how_match');
          push_object.how_match_type = $(el).data('how_match_type');
          edit.push(push_object);
          this_changed = true;
        }
      });
      if(this_changed){
        $(this).parent().addClass('arm_p_primary_checkbox__changed');
      } else {
        $(this).parent().removeClass('arm_p_primary_checkbox__changed');
      }
    });

    //cl(unlink);
    
    
    var old_vals = {};
    $('.arm_puspose_info__changes_log .course_link_how_match').each(function(oldk, oldv){
      old_vals[$(oldv).data('id')] = {
        'value' : $(oldv).find('input').val(),
        'value_type' : $(oldv).find('select').val()
      };
    });
    
    
    
    
    $('.arm_puspose_info__changes_log').html('');
    
    
    
    if(link.length > 0 || unlink.length > 0 || edit.length > 0){
      $('.arm_puspose_info__changes').css('visibility', 'visible');
      $('#arm_purpose_next_btn').removeAttr('disabled');
      
      
      if(link.length > 0){
        $('.arm_puspose_info__changes_link .arm_puspose_info__changes__count').html(link.length);
        var prew = null;
        var html = '<ol class="arm_puspose_info__changes_log">';
        $.each(link, function(lk, lv){
          var lkey = lv.tablename + '__' + lv.id + '__' + lv.course_id;
          if(prew != lv.label){
            var b = '<div class="arm_puspose_info__changes_log_b">' + lv.label + '</div>';
            html += prew != null ? '</ol>' + b + '<ol class="arm_puspose_info__changes_log">' : b;
          }
          html += '<li class="arm_puspose_info__changes_log_item__link"><span>' + lv.course_title + '</span>';
          if(lv.tablename == 'division_course'){
            var course_link_how_match = typeof old_vals[lkey] != 'undefined' ? old_vals[lkey].value : 100;
            var course_link_how_match_type = typeof old_vals[lkey] != 'undefined' ? old_vals[lkey].value_type : 'percent';

            html += '<div class="course_link_how_match" data-id="' + lkey + '">' +
              '<i class="br5"></i><input type="text" id="course_link_' + lk + '_val" value="' + course_link_how_match + '" size="1">&nbsp;' +
              '<select id="course_link_' + lk + '_val_type">' +
                '<option value="percent">%</option>' +
                '<option value="worker"' + (course_link_how_match_type == 'worker' ? ' selected="selected"' : '') + '>количество</option>' +
              '</select></div>';
          }
          html += '</li>';
          prew = lv.label;
        });
        $('.arm_puspose_info__changes_link').show();
        html += '</ol>';
        $('.arm_puspose_info__changes_link .arm_puspose_info__changes_log').html(html);
      } else {
        $('.arm_puspose_info__changes_link').hide();
      }
      
      
      if(unlink.length > 0){
        $('.arm_puspose_info__changes_unlink .arm_puspose_info__changes__count').html(unlink.length);
        var prew = null;
        var html = '<ol class="arm_puspose_info__changes_log">';
        $.each(unlink, function(lk, lv){
          if(prew != lv.label){
            var b = '<div class="arm_puspose_info__changes_log_b">' + lv.label + '</div>';
            html += prew != null ? '</ol>' + b + '<ol class="arm_puspose_info__changes_log">' : b;
          }
          html += '<li class="arm_puspose_info__changes_log_item__unlink"><span><s>' + lv.course_title + '</s></span></li>';
          prew = lv.label;
        });
        $('.arm_puspose_info__changes_unlink').show();
        
        html += '</ol>';
        
        $('.arm_puspose_info__changes_unlink .arm_puspose_info__changes_log').html(html);
        
      } else {
        $('.arm_puspose_info__changes_unlink').hide();
      }
      
      if(edit.length > 0){
        
        
        
        $('.arm_puspose_info__changes_edit .arm_puspose_info__changes__count').html(edit.length);
        var prew = null;
        var html = '<ol class="arm_puspose_info__changes_log">';
        $.each(edit, function(lk, lv){
          if(prew != lv.label){
            var b = '<div class="arm_puspose_info__changes_log_b">' + lv.label + '</div>';
            html += prew != null ? '</ol>' + b + '<ol class="arm_puspose_info__changes_log">' : b;
          }
          html += '<li class="arm_puspose_info__changes_log_item__edit"><span>' + lv.course_title + '</span>';
          
          var lkey = lv.tablename + '__' + lv.id + '__' + lv.course_id;
          
          var course_link_how_match = typeof old_vals[lkey] != 'undefined' ? old_vals[lkey].value : lv.how_match;
          var course_link_how_match_type = typeof old_vals[lkey] != 'undefined' ? old_vals[lkey].value_type : lv.how_match_type;

          html += '<div class="course_link_how_match" data-id="' + lkey + '">' +
            '<i class="br5"></i><input type="text" id="course_link_' + lk + '_val" value="' + course_link_how_match + '" size="1">&nbsp;' +
            '<select id="course_link_' + lk + '_val_type">' +
              '<option value="percent">%</option>' +
              '<option value="worker"' + (course_link_how_match_type == 'worker' ? ' selected="selected"' : '') + '>количество</option>' +
            '</select></div>';
          
          html += '</li>';
          prew = lv.label;
        });
        $('.arm_puspose_info__changes_edit').show();
        
        html += '</ol>';
        
        $('.arm_puspose_info__changes_edit .arm_puspose_info__changes_log').html(html);
        
      } else {
        $('.arm_puspose_info__changes_edit').hide();
      }

      
    } else {
      $('.arm_puspose_info__changes').css('visibility', 'hidden');
      $('#arm_purpose_next_btn').attr('disabled', true);
    }
    
    this.check_changes_data = {
      'edit' : edit,
      'link' : link,
      'unlink' : unlink
    };
    if(link.length > 0 || unlink.length > 0  || edit.length > 0 ){
      $.post(sf_prefix + '/purpose/tmp_state?tab_id=' + $.url().param('tab_id'), {'data': this.check_changes_data}, function(html){});
    }
  },
  next_step: function(){
    var size_to = $('#lui_scroller');
    var oo = sOverlay(size_to, 'sOverlay_white');
    
    cl(this.check_changes_data);
    
    var _this = this;
    $.each(this.check_changes_data.link, function(k, v){
      var try_find = $('#course_link_' + k + '_val');
      if(try_find.length > 0){
        _this.check_changes_data.link[k].how_match = try_find.val();
        _this.check_changes_data.link[k].how_match_type = $('#course_link_' + k + '_val_type').val();
      }
    })
    
    $.post(sf_prefix + '/purpose/step2', {'json': JSON.stringify(this.check_changes_data)}, function(html){
      $('.arm_purpose_step').removeClass('arm_purpose_step_current');
      $('.arm_purpose_step_2').addClass('arm_purpose_step_current');
      if($('.arm_purpose_step_2_overlay').length == 0){
        $('#lui_scroller').append('<div class="arm_purpose_step_2_overlay"></div>');
      }
      var o = $('.arm_purpose_step_2_overlay');
      o.width(size_to.width());
      o.height(size_to.height());
      o.css('top', size_to.position().top);
      oo.remove();
      $(o).html(html);
      //cl($('.arm_purpose_step_2_overlay select'));
      //$('.arm_purpose_step_2_overlay').find('select.chosen').chosen();
    });
  },
  preview: function(){
    
    
  },
  confirm: function(){

    
    var post = this.check_changes_data;

    $('.arm_purpose_regulation_id').each(function(rk, rv){
      post.link[rk].regulation = $(rv).val();
    });
    $('.arm_purpose_regulation_id_edit').each(function(rk, rv){
      post.edit[rk].regulation = $(rv).val();
    });
    $('.arm_purpose_why').each(function(rk, rv){
      post.unlink[rk].regulation = $(rv).val();
    });
    

    
    var size_to = $('#lui_scroller');
    var oo = sOverlay(size_to, 'sOverlay_white');
    
    $.post(sf_prefix + '/purpose/confirm', {'json': JSON.stringify(post)}, function(html){
      
      $.get('', function(html){
        
      });
      
      $('.arm_purpose_step').removeClass('arm_purpose_step_current');
      $('.arm_purpose_step_3').addClass('arm_purpose_step_current');

      var o = $('.arm_purpose_step_2_overlay');
      $(o).html(html);
      oo.remove();
      
      

    });
    
    
  },
  check_scroll_pos: 0,
  check_scroll: function(){
    arm_puspose.check_scroll_pos = 0;
    var vtop = 86;
    //cl(vtop);
    //vtop = 0;
    $('.arm_puspose_courses_list .arm_course_primary_checkbox:checked').each(function(){
      var _this = $(this).parent().parent();
      var clone = $('#arm_course_primary_checkbox__p_clone__' + $(this).val());
      if(!clone.length){
        clone = _this.clone(true);
        clone.addClass('arm_course_primary_checkbox__p_clones');
        clone.attr('id', 'arm_course_primary_checkbox__p_clone__' + $(this).val());
        clone.css({
          'position': 'absolute',
          'display': 'none',
          'width': _this.width(),
          'z-index': 1000,
          'top': 0
        });
        var cloned_input = clone.find('input');
        cloned_input.click(function(event){
          event.preventDefault();
          var tv = $(this).val();
          $(this).parent().parent().remove();
          $('.arm_course_primary_checkbox[value="' + tv + '"]').click();
          
        });
        $(_this).parent().parent().append(clone);
      }
      if(_this.css('display') == 'block'){
        var p = _this.position();
        if(p.top < arm_puspose.check_scroll_pos){
          clone.css({
            'top': vtop,
            'display': 'block'
          });
          vtop += _this.outerHeight();
          arm_puspose.check_scroll_pos += _this.outerHeight();
        } else {
          clone.hide();
        }
      }
    });
  }
};