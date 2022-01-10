var photo = {
  parent: null,
  //pseudo_text: null,
  upload: function ($this, event){
    this.parent = $this.closest('.lite_uploader_photos_wrap');
    _this = this;
    if ($this.hasClass('init_uploader')) {
      return;
    }
    $this.addClass('init_uploader');
    elUploader = $this.find('input[type="file"]');
    elUploader.liteUploader({
      script: $this.data('url'),
      rules: {
        allowedFileTypes: 'image/jpeg,image/png,image/gif'
      }
    })
      .on('lu:before', function (e, files){
        $(this).closest('.content').find('.lite_uploader__preloader').css('visibility', 'visible');
      })
      .on('lu:errors', function (e, errors){
        var isErrors = false;

        $.each(errors, function (i, error){
          //console.log(error);
          if (error.errors.length > 0) {
            isErrors = true;
            message.error('Изображение слишком маленькое или не соответствует типу. Допустимые типы: jpeg, png, gif');
          }
        });
      })
      .on('lu:success', function (e, response){
        var response = $.parseJSON(response);
        var html = '';
        var photos = _this.parent.find('.lite_uploader_photos');
        $.each(response, function(k, el){
          if(el.success){
            html += '<div class="lite_uploader_photo lite_uploader_photo_partners">';
            html += '<img src="' + el.filelink.replace('.', '-S.') + '" />';
            html += '<input type="hidden" name="' + photos.data('name') + '" value="' + el.filename + '">';
            html += '</div>';
          }else{
            html += '<div class="lite_uploader_photo lite_uploader_photo_partners" style="color:#C75E5E;">Изображение слишком маленькое или не соответствует типу. Допустимые типы: jpeg, png, gif</div>';
          }
        });
        photos.html(html);
        $(this).closest('.content').find('.lite_uploader__preloader').css('visibility', 'hidden');
      });
  },
  remove: function($this, evemt){
    this.parent = $this.closest('.lite_uploader_photos_wrap');
    $img = this.parent.find('.lite_uploader_photo img');
    $id = this.parent.find('.lite_uploader_photo input');
    $img.remove();
    $id.val('');
  }
}



var rangeSlider = {
  events: {
    liMouseUp: function(e, _this){
      var parent = $(_this).parent();
      var html = $(_this).html();
      var selected = parent.find('.p_range_slider__select__list__selected');
      var wrap = parent.closest('.p_range_slider_wrap');
      var inp = wrap.find('.p_range_slider__select__inp');

     
      if(!$(_this).hasClass('p_range_slider__select__list__selected')){
        parent.find('.p_range_slider__select__list__selected').html(html);
        
        if(wrap.prev().data('type') != html){
          wrap.prev().data('type', html);
          if(wrap.prev().data('type') == '%'){
            inp.val(Math.floor(parseInt(inp.val()) / (parseInt(wrap.data('max')) / 100)));
          }else{
            inp.val(Math.ceil(parseInt(inp.val()) * (parseInt(wrap.data('max')) / 100)));
          }
        }
        console.log(wrap.prev().data('type'), html)
        parent.removeClass('p_range_slider__select__list_active');
      };

      e.stopPropagation();
    },

    selectedMouseUp: function(e, _this){
      var parent = $(_this).parent();
      $('.p_range_slider__select__list_active').removeClass('p_range_slider__select__list_active');
      if(parent.hasClass('p_range_slider__select__list_active')){
        parent.removeClass('p_range_slider__select__list_active');
      }else{
        parent.addClass('p_range_slider__select__list_active');
      }
      e.stopPropagation();
    },

    toddlerMouseDown: function(e){
      rangeSlider.mouseDown = true;
      rangeSlider.mDownPosX = e.pageX;
      e.stopPropagation();
      return false;
    },

    all: function(e, _this){
      rangeSlider.mouseDown = false;
      if(rangeSlider.currWrap){
        rangeSlider.currWrap.find('.p_range_slider__select__inp').data('val', rangeSlider.toddlerPosY);
      }
      $(this).find('.p_range_slider__select__list_active').removeClass('p_range_slider__select__list_active');

      e.stopPropagation();
      return false;
    }

  },
  inEvent: function(){
    rangeSlider.mouseDown = false;
    rangeSlider.mDownPosX = 0;
    rangeSlider.mUpPosX = 0;
    rangeSlider.currWrap = 0;
    rangeSlider.toddlerPosY = 0;

    $(document)
      .on('mousemove', '.p_range_slider_wrap', function(e){
        var toddler = $(this).find('.p_range_slider__toddler');
        var w = $(this).find('.p_range_slider').width();
        var max = $(this).data('max');
        var inpVal = null;
        var _input = $(this).find('.p_range_slider__select__inp');


        if($(this).find('.p_range_slider__select__list__selected').text() == '%'){
          inpVal = Math.ceil((rangeSlider.toddlerPosY / (w / max)) / (max / 100));
        }else{
          inpVal = Math.ceil(rangeSlider.toddlerPosY / (w / max));
        }

        if(rangeSlider.mouseDown){
          rangeSlider.currWrap = $(this);
          rangeSlider.toddlerPosY = parseInt(_input.data('val'));

          rangeSlider.toddlerPosY = (rangeSlider.toddlerPosY + (e.pageX - rangeSlider.mDownPosX));

          rangeSlider.toddlerPosY = rangeSlider.toddlerPosY < 0 ? 0 : rangeSlider.toddlerPosY; 
          rangeSlider.toddlerPosY = rangeSlider.toddlerPosY >= w ? w : rangeSlider.toddlerPosY; 

          toddler.css('left', rangeSlider.toddlerPosY);
          _input.val(inpVal);
          $(this).prev().val(Math.ceil(rangeSlider.toddlerPosY / (w / max)));
        }
        return false;
      })
      .on('mouseup', function(e){
        rangeSlider.events.all(e);
      })
      .on('mouseleave', '.p_range_slider_wrap', function(e){
        rangeSlider.events.all(e);
      });



    },
    inDom: function(input){
      var max = input.data('max');
      var type = input.data('type');
      var _nextElem = null;
      var template = '<div data-max="'+ max +'" class="p_range_slider_wrap" data-mousedown="1" onclick="rangeSlider.events.all(event);" mouseup="rangeSlider.events.all(event);">' +
        '<div class="p_range_slider">' +
          '<div class="p_range_slider__toddler" onmousedown="rangeSlider.events.toddlerMouseDown(event);"></div></div>' +
        '<div class="p_range_slider__select">' +
          '<input type="text" class="p_range_slider__select__inp" data-val="0" />' +
          '<ul class="p_range_slider__select__list">' +
            '<li class="p_range_slider__select__list__selected" onclick="rangeSlider.events.selectedMouseUp(event, $(this));">'+ type +'</li>' +
            '<li onclick="rangeSlider.events.liMouseUp(event, $(this));">%</li>' +
            '<li onclick="rangeSlider.events.liMouseUp(event, $(this));">Сотрудников</li>' +
          '</ul>' +
        '</div>' +
      '</div>';
      var inpVal = null;
      var w = null;

      input.hide();
      input.after(template);

      _nextElem = input.next();
      w = _nextElem.find('.p_range_slider').width();
      inpVal = ( w / max) * input.val();
      


      _nextElem.find('.p_range_slider__toddler').css('left', inpVal + 'px');
      if(type == '%'){
        _nextElem.find('.p_range_slider__select__inp').val(parseInt(input.val() / (max / 100)));
      }else{
        _nextElem.find('.p_range_slider__select__inp').val(input.val());
      }
      
      _nextElem.find('.p_range_slider__select__inp').data('val', inpVal);
      input.data('initialized', '1');
    },

    init: function(elems){
      var _this = this;
      elems.each(function(i, item){
        _this.inDom($(item));
      });
      this.inEvent();
    }
};



/** 
* @description This plugin allows you to make a select box editable like a text box while keeping it's select-option features
* @description no stylesheets or images are required to run the plugin
*
* @version 0.0.1
* @author Martin Mende
* @license Attribution-NonCommercial 3.0 Unported (CC BY-NC 3.0)
* @license For comercial use please contact me: martin.mende(at)aristech.de
* 
* @requires jQuery 1.9+
*
* @class editableSelect
* @memberOf jQuery.fn
*
* @example
*
* var selectBox = $("select").editableSelect();
* selectBox.addOption("I am dynamically added");
*/

(function ( $ ) {
 
    $.fn.editableSelect = function() {
      var instanceVar;
      
      this.each(function(){
          var originalSelect = $(this);
          //check if element is a select
          if(originalSelect[0].tagName.toUpperCase()==="SELECT"){
            //wrap the original select
            originalSelect.wrap($("<div/>"));
            var wrapper = originalSelect.parent();
            wrapper.css({display: "inline-block"});
            //place an input which will represent the editable select
            var inputSelect = $('<input type="text" />').insertBefore(originalSelect);
              //get and remove the original id
              var objID = originalSelect.attr("id");
              originalSelect.removeAttr("id");
            //add the attributes from the original select
            inputSelect.attr({
              alt: originalSelect.attr("alt"),
              title: originalSelect.attr("title"),
              class: originalSelect.attr("class"),
              name: originalSelect.attr("name"),
              disabled: originalSelect.attr("disabled"),
              tabindex: originalSelect.attr("tabindex"),
              id: objID
            });
            //get the editable css properties from the select
            var rightPadding = 15;
            inputSelect.css({
              width: originalSelect.width()-rightPadding,
              height: originalSelect.height(),
              fontFamily: originalSelect.css("fontFamily"),
              fontSize: originalSelect.css("fontSize"),
              background: originalSelect.css("background"),
              paddingRight: rightPadding,
              paddingLeft: '1px'
            });
            inputSelect.val(originalSelect.val());
            //add the triangle at the right
            var triangle = $("<div/>").css({
              height: 0, width: 0,
              borderLeft: "5px solid transparent",
              borderRight: "5px solid transparent", 
              borderTop: "7px solid #999",
              position: "relative",
              top: -(inputSelect.height()/2)-7,
              left: inputSelect.width()+rightPadding-15,
              marginBottom: "-7px",
              pointerEvents: "none"
            }).insertAfter(inputSelect);
            //create the selectable list that will appear when the input gets focus
            var selectList = $("<ol/>").css({
              display: "none",
              listStyleType: "none",
              width: inputSelect.outerWidth(),
              padding: 0,
              margin: 0,
              border: "solid 1px #ccc",
              fontFamily: inputSelect.css("fontFamily"),
              fontSize: inputSelect.css("fontSize"),
              background: "#fff",
              position: "absolute",
              zIndex: 1000000
            }).insertAfter(triangle);
            //add options
            originalSelect.children().each(function(index, value){
              prepareOption($(value).text(), wrapper);
            });
            //bind the focus handler
            inputSelect.focus(function(){
              selectList.fadeIn(100);
            }).blur(function(){
              selectList.fadeOut(100);  
            }).keyup(function(e){
              if(e.which==13)  inputSelect.trigger("blur");
            });
            //hide original element
            originalSelect.css({visibility: "hidden", display: "none"});
            
            //save this instance to return it
            instanceVar = inputSelect
          }else{
            //not a select
            return false;
          }
        });//-end each
        
        /** public methods **/
        
        /**
    * Adds an option to the editable select
    * @param {String} value - the options value
    * @returns {void}
    */
        instanceVar.addOption = function(value){
          prepareOption(value, instanceVar.parent());  
        };
        
        /**
    * Removes a specific option from the editable select
    * @param {String, Number} value - the value or the index to delete
    * @returns {void}
    */
        instanceVar.removeOption = function(value){
          switch(typeof(value)){
            case "number":
              instanceVar.parent().children("ol").children(":nth("+value+")").remove();
              break;  
            case "string":
              instanceVar.parent().children("ol").children().each(function(index, optionValue){
                if($(optionValue).text()==value){
                  $(optionValue).remove();
                }
              });
              break;
          }    
        };
        
         /**
    * Resets the select to it's original
    * @returns {void}
    */
        instanceVar.restoreSelect = function(){
          var originalSelect = instanceVar.parent().children("select");
          var objID = instanceVar.attr("id");
          instanceVar.parent().before(originalSelect);
          instanceVar.parent().remove();
          originalSelect.css({visibility: "visible", display: "inline-block"});
          originalSelect.attr({id: objID});
        };
        
        //return the instance
        return instanceVar;
    };
    
    /** private methods **/
    
    function prepareOption(value, wrapper){
      var selectOption = $("<li>"+value+"</li>").appendTo(wrapper.children("ol"));
      var inputSelect = wrapper.children("input");
      selectOption.css({
         padding: "3px",
         textAlign: "left",
         display: 'block',
         cursor: "pointer"  
       }).hover(
       function(){
         selectOption.css({backgroundColor: "#eee"});
       },
       function(){
         selectOption.css({backgroundColor: "#fff"});  
       });
       //bind click on this option
       selectOption.click(function(){
         inputSelect.val(selectOption.text());
         inputSelect.trigger("change");
       });  
    }
 
}( jQuery ));


function number_format( number, decimals, dec_point, thousands_sep ) {  // Format a number with grouped thousands
  // 
  // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfix by: Michael White (http://crestidg.com)

  var i, j, kw, kd, km;

  // input sanitation & defaults
  if( isNaN(decimals = Math.abs(decimals)) ){
    decimals = 2;
  }
  if( dec_point == undefined ){
    dec_point = ",";
  }
  if( thousands_sep == undefined ){
    thousands_sep = ".";
  }

  i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

  if( (j = i.length) > 3 ){
    j = j % 3;
  } else{
    j = 0;
  }

  km = (j ? i.substr(0, j) + thousands_sep : "");
  kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
  //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
  kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


  return km + kw + kd;
}









var cl = function(m){
  console.log(m);
}
var f = {
  niceRusEnds: function(c, e1, e2, e3){
    var s = (c + '').substr(-1);
    var s2 = (c + '').substr(-2);
    
    var e = '';
    if(c == 0)
    {
      return '';
    }
    else if(s2 == '11' || s2 == '12' || s2 == '13' || s2 == '14')
    {
      e = e3;
    }
    else if(s == '1')
    {
      e = e1;
    }
    else if(s == '2' || s == '3' || s == '4')
    {
      e = e2;
    }
    else
    {
      e = e3;
    }
    return e;

  },
  genPass: function(length){
    var pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var s = '';
    for (var i = 1; i <= length; i++)
    {
      s += pool[Math.floor(Math.random() * (pool.length - 1))];
    }
    return s;
  },
  checkBatch: function(){
    var l = $('#lui__list_table_donor input:checked').length;
    var checkBatchOverlay = $('.batch_actions_wrapper .sOverlay_white');
    
    
    if(checkBatchOverlay.length == 0){
      checkBatchOverlay = sOverlay($('.batch_actions_wrapper'), 'sOverlay_white', $('.batch_actions_wrapper'));
    }
    
    checkBatchOverlay.css('visibility', l > 0 ? 'hidden' : 'visible');

    $('#list_checked').html(l);
  },
  updateShowedCount: function(){
    $('.lui__list_count_from_to').html($('.sf_admin_batch_checkbox').length + ' из');
  },
  calcModal: function(modal){
    $(modal).css({
      'margin-top': Math.round($(modal).innerHeight() / 2) * -1,
      'margin-left': Math.round($(modal).innerWidth() / 2) * -1
    });
  },
  ajax_eval: function(selector){
    $(selector).each(function(k, code){
      if(!$(code).data('initialized')){
        eval($(code).html());
        $(code).data('initialized', '1');
      }
    });
  },
  createHiddenFormAndSubmit: function(action, fields){
    var form = $('<form method="post" action="' + action + '"></form>');
    $.each(fields, function(k, v){
      form.append('<input type="hidden" name="' + k + '" value="' + v + '" />');
    });
    $('body').append(form);
    form.submit();
  }
};
(function ($) {
  $.fn.customizeForm = function () {
    this.each(function () {
      var input = $(this);
      if (!input.data('initialized')) {
        do_customInput(input);
      }
    });
    
    return this;
  };
  var do_customInput = function (input) {
    if(input.is('input')){
      var type = input.attr('type');
      if(type == 'radio' || type == 'checkbox'){
        
        if(input.parent().is('label')){
          input.parent().addClass('custom_input_label');
        }
        input.addClass('custom_input');
        input.after($('<span class="custom_input custom_input_' + type + '"></span>'));
      }
    } else if(input.is('select') && !input.prop('multiple')){
      //var span = $('<span></span>');
      return;
      
      
      var options_idx = {};
      var options_html = '';
      
      input.find('option').each(function(k, v){
        options_idx[$(v).val()] = $(v).html();
        var p = '';
        var matches = /^(\s+)/.exec($(v).text());
        if(matches){
          p = ' style="padding-left:' + (5 + matches[0].length * 20) + 'px"';
        }
        options_html += '<div class="custom_select__option"' + p + '>' + $(v).html() + '</div>';
      });
      var inp_w = input.outerWidth();
      
      
      var inp_search = '<div class="custom_select__search"><input type="text" style="width:100%" /></div>';
      
      
      var div = $('<div class="custom_select__div"><input readonly="true" class="custom_select__input" value="' + options_idx[input.val()] + 
                  '" type="text" /><span class="custom_select__span"></span>' + inp_search + '<div class="custom_select__options">' + options_html + '</div></div>');
      var inp = div.find('.custom_select__input');
      var options_layer = div.find('.custom_select__options');
      
      options_layer.css({
        'min-width': inp_w
      })
      
      inp.width(input.width());
      
      
      inp.click(function(){
        div.toggleClass('custom_select__div__opened');
        
      });
      //$(dicument.click)
      
      input.before(div);
      input.css({
        'visibility': 'hidden'
      });
      //input.change();
    }
    input.data('initialized', '1')
  }
})(jQuery);

function sOverlay(el, className, append){
  var div = $('<div class="sOverlay' + (className ? ' ' + className : '') + '"></div>');
  $((append ? append : 'body')).append(div);
  div.width(el.outerWidth());
  div.height(el.outerHeight());
  div.offset(el.offset());
  /*
  $(window).resize(function(){
    div.width(el.outerWidth());
    div.height(el.outerHeight());
    div.offset(el.offset());
  });
  */
  return div;
}

function ya_translate(el, from, notlower){
  if(from.val().trim() == ''){
    return;
  }
  el.attr('disabled', true);
  $.ajax({
    url: 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20130902T144345Z.5863936671655044.0537dfa3017a4bba694fb9cac72e8e9bc7cf2800&lang=ru-en&text=' + from.val().trim(),
    dataType: 'json',
    success: function(json){
      alert(json);
      var s = json.text[0];
      if(notlower){
        el.val(s);
      } else {
        
        s = s.replace(/ /gi, '-');
        s = s.replace(/[^a-z0-9\-]/gi, '');
        s = s.replace(/\-{2,}/gi, '-');
        s = s.replace(/^\-/gi, '');
        s = s.replace(/\-$/gi, '');
        el.val(s.toLowerCase());
      }
      el.attr('disabled', false);
    }
  });
}

(function ($) {
  $.fn.luiManaged = function () {
    this.each(function () {
      var select = $(this);
      if (!select.data('initialized')) {
        do_luiManaged(select);
        select.data('initialized', '1');
      }
    });
    return this;
  };
  var bodyo = false;
  var id = '';
  var do_luiManaged = function (select) {
    var parent = select.parent();
    var form = create_form(select.data('managed_type') == 'modal' ? 'modal' : 'inline');
    var add = $('<span class="managed_add" title="Добавить">+</span>');
    var edit = $('<span class="managed_edit" title="Редактировать">/</span>');
    var remove = $('<span class="managed_remove" title="Удалить">-</span>');
    if(select.data('managed_type') == 'modal'){
      $('body').append(form);
      add.insertAfter(select);
    } else {
      form.insertAfter(select);
      add.insertAfter(form);
    }
    edit.insertAfter(add);
    remove.insertAfter(edit);
    form.css('visibility', 'hidden');
    select.change(function(){
      if($(this).val()){
        edit.show();
        remove.show();
      } else {
        edit.hide();
        remove.hide();
      }
    }).change();
    
    add.click(function(){
      get_form('/arm/' + select.data('managed_module') + '/new', parent.parent(), form, 'GET', {}, 'add', select);
    });
    edit.click(function(){
      get_form('/arm/' + select.data('managed_module') + '/' + select.val() + '/edit', parent.parent(), form, 'GET', {}, 'edit', select);
    });
    remove.click(function(){
      form.empty();
      var warning = $('<b style="color:#FF0000">Вы действительно хотите удалить обьект?</b><span class="br15"></span>');
      var submit = $('<button>Удалить</button>');
      var cancel = $('<button style="margin-left:10px !important">Отмена</button>');
      form.append(warning);
      form.append(submit);
      form.append(cancel);
      form.css('visibility', 'visible');
      submit.click(function(){
        var tmp_form = $('<form><input type="hidden" name="sf_method" value="delete" /></form>');
        get_form('/arm/' + select.data('managed_module') + '/' + select.val(), form, form, 'POST', $(tmp_form).serialize(), 'remove', select);
        return false;
      });
      cancel.click(function(){
        form.css('visibility', 'hidden');
        return false;
      });
    });
  }

  var create_form = function(type){
    var form = $('<div class="managed_form managed_form_' + type + '"></div>');
    if(type == 'modal'){
      $(window).resize(function(){
        f.calcModal(form);
      });
    }
    form.data('form_type', type);
    return form;
  }
  
  var update_select = function(select, type, uid){
    var o = sOverlay(select.parent().parent());
    if(select.data('managed_type') == 'modal'){
      if(bodyo){
        bodyo.remove();
      }
    }
    var url = window.location;
    if(select.prop('name')){
      var ex = select.prop('name').split('[');
      var id = $('#' + ex[0] + '_id').val();
      url = id != '' ? '/arm/' + ex[0] + '/' + id + '/edit' : '/arm/' + ex[0] + '/new';
    }
    $.ajax({
      url: url,
      dataType: 'html',
      success: function(html){
        if(select.data('managed_update')){
          var selector = select.data('managed_update');
          $(selector).html($(html).find(selector).html());
        } else {
          var options = $(html).find('#' + select.attr('id'));
          //var val = type == 'add' ? options.children().last().prop('value') : select.val();
          select.html(options.html());
          select.val(uid);
        }
        select.change();
        o.remove();
      }
    });
  }
  
  var get_form = function(url, layer, form, method, data, type, select){
    var o = sOverlay(layer);
    $.ajax({
      url: url,
      type: method,
      dataType: 'html',
      data: data,
      complete: function(jqXHR, textStatus){
        var html = jqXHR.responseText;
        o.remove();
        if(method == 'POST' && !jqXHR.getResponseHeader('Sf-Form-Error')){
          form.css('visibility', 'hidden');
          var exp = url.split('/');
          update_select(select, type, $(html).find('#' + exp[2] + '_id').val());
        } else{
          if(type == 'remove'){
            var append = $(html).find('.sf_admin_action_delete').html();
            form.html(append);
            form.css('visibility', 'visible');
          } else {
            
            
            var append = '<form>' + (type == 'edit' ? '<input type="hidden" name="sf_method" value="put" />' : '') + $(html).find('fieldset').html() + '<i class="br10"></i></form>';
            
            
            
            form.html(append);
            
            
            
            var submit = $('<button>Сохранить</button>');
            var cancel = $('<button style="margin-left:10px !important">Отмена</button>');
            form.css('visibility', 'visible');
            form.append(submit);
            form.append(cancel);
            $('input,select').customizeForm();
            f.ajax_eval($('.ajax_eval'));
            $('.managed').luiManaged();
            form.find('input.translated,textarea.translated').each(function(k, el){
              var from = $('#' + $(el).data('translated_from'));
              if(from.length == 1){
                var link = $('<i class="icon-syncalt" style="cursor:pointer;margin-left:10px;" title="Перевести"></i>');
                link.insertAfter($(el));
                link.click(function(){
                  ya_translate($(el), from, $(el).data('notlower') == '1');
                });
              }
            });
            
            submit.click(function(){
              var surl = url.replace('/new', '').replace('/edit', '');
                   
              var fform = $('<form>' + (type == 'edit' ? '<input type="hidden" name="sf_method" value="put" />' : '') + '</form>');
              
              fform.append($(form));
              
              
              
              get_form(surl, form, form, 'POST', fform.serialize(), type, select);
              return false;
            
            });
            cancel.click(function(){
              form.css('visibility', 'hidden');
              if(form.data('form_type') == 'modal'){
                if(bodyo){
                  bodyo.remove();
                }
              }
              return false;
            });
            if(form.data('form_type') == 'modal'){
              $(window).resize();
              if(bodyo){
                bodyo.remove();
              }
              bodyo = sOverlay($('body'), 'sOverlay_black');
            }
          }
        }
      }
    });
  }
  

})(jQuery);
var bid_ok = function(){
  $(".ierch__layer__shadowed__wrapper .xs__button").val("Заявка сформирована");
  $.get($('.ierch__layer__shadowed__wrapper form').data('r'), function(html){
    $('.ierch__layer__shadowed__wrapper').html(html);
  });

};












var create_custom_table_head_recalc = function(){
  $('.lui__list_table__sourse').each(function(k, table){
    if ($(table).data('initialized')) {
      var donor = $(table).find('thead tr th');
      
      var summ = 0;
      var th_w = 0;
      var tf = $(table).parent().find('.lui__list_table_fixed');

      //$(table).parent().find('#lui__list_table_donor').attr('width','100%');
      
      var offset = $('.lui_scroller').offset();
      tf.css('top', offset.top);
      tf.find('th').width('auto');
      tf.find('th').each(function(k, th){
        if(!$(th).hasClass('last')){
          th_w = $(donor[k]).outerWidth();
          $(th).outerWidth(th_w);
          summ += th_w;
        }
      });
      var diff = $(table).parent().outerWidth() - $(table).outerWidth();
      tf.find('.last').outerWidth(diff);
      if(diff > 0){
        $(table).find('thead th:last-child').width(diff + 30);
      }
    }
  });
};
$(window).resize(function(){
  create_custom_table_head_recalc();
});
var create_custom_table_head = function(){
  f.checkBatch();
    $('.lui__list_table__sourse').each(function(k, table){
      if (!$(table).data('initialized')) {
        $(table).data('initialized', '1');
        var clone = $(table).find('thead tr').clone();
        var tf = $('<table cellspacing="0" cellpadding="3" style="z-index:100" border="0" id="lui__list_table_fixed" class="lui__list_table lui__list_table_fixed"><thead>' + clone.html() + '<th class="last" style="padding:0;"></th></thead></table>');
        tf.insertBefore(table);
        
        
        var offset = $('.lui_scroller').offset();
        tf.css('top', offset.top);
        
        
        
        
        $(document).on('click', '.lui__list_table input[type="checkbox"]', function(){
          f.checkBatch();
        });
        
        /*var tfw = tf.outerWidth();
        tf.outerWidth(tfw - 17);
        */
        var ls = $('.lui_scroller');
        
        var offset = ls.offset();
        ls.height($(window).height() - offset.top);
        
        $(window).resize(function(){
          var offset = ls.offset();
          ls.height($(window).height() - offset.top);
          $('.lui__scroller_wrapper_shadow').width($('.lui__scroller_wrapper').width());
          if(typeof resChart == 'function'){
            resChart();
          }
        });
        var offset = ls.offset();
        $('.lui__scroller_wrapper_shadow').css('top', offset.top);
        ls.on('scroll', function(){
          if($(this).scrollTop() > 0){
            $(this).addClass('scrolled');
            
          } else {
            $(this).removeClass('scrolled');
          }
        });
      }
      
      f.updateShowedCount();
      
    });
    
  if($('.lui__list_table__sourse').length == 0 && $('.lui_scroller').length > 0){
    var ls = $('.lui_scroller');
    var stupedfunction = function(){
      var offset = ls.offset();
      ls.height($(window).height() - offset.top);
    }
    $(window).resize(stupedfunction);
    stupedfunction();
  }
};









(function ($) {
  $.fn.formTabs = function () {
    this.each(function () {
      var form = $(this);
      if (!form.data('initialized')) {
        do_formTabs(form);
      }
    });
    return this;
  };
  var do_formTabs = function (form) {
    //return;
    var tabs = 0;
    var tabs_c = [];
    if(form.is('form')){
      if(form.prop('action').indexOf('/protocol/') != -1){
        return;
      }
      /*
      $('input,textarea,select,div.force_tab,ul.radio_list').each(function(k, v){
        if($(v).data('tab')){
          tabs++;
          tabs_c.push('<div data-tab_id="' + tabs + '" data-tab_order="' + ($(v).data('tab_order') ? $(v).data('tab_order') : tabs) + '" class="lui__form_tab' + (tabs == 1 ? ' lui__form_tab__current' : '') + '">' + $(v).data('tab') + '</div>');
        }
        if($(v).parent().hasClass('content')){
          var parent = $(v).parent().parent().parent();
          parent.addClass('tabbed_div');
          parent.data('tab_id', tabs);
        }
        if($(v).parent().parent().hasClass('content')){
          var parent = $(v).parent().parent().parent().parent();
          parent.addClass('tabbed_div');
          parent.data('tab_id', tabs);
        }
      });
      */
    }
    if(tabs > 1){
      tabs_c.sort(function(a, b) {
        if($(a).data('tab_order') == $(b).data('tab_order')){
          return 0;
        }
        return $(a).data('tab_order') > $(b).data('tab_order') ? 1 : -1;
      });
      var tabs_ce = $('<div class="lui__form_tabs">' + tabs_c.join('') + '</div>');
      form.find('.lui__scroller_wrapper').prepend(tabs_ce);
      check_formTabs(form);
      tabs_ce.find('.lui__form_tab').click(function(){
        $('.lui__form_tab').removeClass('lui__form_tab__current');
        $(this).addClass('lui__form_tab__current');
        check_formTabs(form);
      });
      /*
      form.find('.content textarea,.content input[type=text]').first().keyup(function(){
        var h = form.parent().find('span.lui__h1');
        h.find('b').remove();
        if($(this).val() != ''){
          h.append('<b style="font-size:12px">&nbsp;—&nbsp;«' + $(this).val() + '»</b>');
        }
      }).keyup();
      */
    }
    form.data('initialized', '1');
  };
  var check_formTabs = function(form){
    var tab_idx = form.find('.lui__form_tab__current').first().data('tab_id');
    form.find('.tabbed_div').each(function(k, v){
      if($(v).data('tab_id')){
        if($(v).data('tab_id') == tab_idx){
          $(v).show();
        } else {
          $(v).hide();
        }
        //$(v).css('display', ($(v).data('tab_id') == tab_idx ? 'block' : 'none'));
      }
    });
  };
})(jQuery);












/*!
 * jQuery Plugin: Are-You-Sure (Dirty Form Detection)
 * https://github.com/codedance/jquery.AreYouSure/
 *
 * Copyright (c) 2012-2014, Chris Dance and PaperCut Software http://www.papercut.com/
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Author:  chris.dance@papercut.com
 * Version: 1.9.0
 * Date:    13th August 2014
 */
(function($) {
  
  $.fn.areYouSure = function(options) {
      
    var settings = $.extend(
      {
        'message' : 'You have unsaved changes!',
        'dirtyClass' : 'dirty',
        'change' : null,
        'silent' : false,
        'addRemoveFieldsMarksDirty' : false,
        'fieldEvents' : 'change keyup propertychange input',
        'fieldSelector': ":input:not(input[type=submit]):not(input[type=button])"
      }, options);

    var getValue = function($field) {
      if ($field.hasClass('ays-ignore')
          || $field.hasClass('aysIgnore')
          || $field.attr('data-ays-ignore')
          || $field.attr('name') === undefined) {
        return null;
      }

      if ($field.is(':disabled')) {
        return 'ays-disabled';
      }

      var val;
      var type = $field.attr('type');
      if ($field.is('select')) {
        type = 'select';
      }

      switch (type) {
        case 'checkbox':
        case 'radio':
          cl($field.attr('name') + ':' + $field.is(':checked'));
          val = $field.prop('checked') !== undefined && $field.is(':checked') ? true : false;
          
          break;
        case 'select':
          val = '';
          $field.find('option').each(function(o) {
            var $option = $(this);
            if ($option.is(':selected')) {
              val += $option.val();
            }
          });
          break;
        default:
          val = $field.val();
      }

      return val;
    };

    var storeOrigValue = function($field) {
      if($field.data('ays-orig') !== undefined){
        return;
      }
      $field.data('ays-orig', getValue($field));
      cl('orig^' + $field.data('ays-orig'));
    };

    var checkForm = function(evt) {

      var isFieldDirty = function($field) {
        var origValue = $field.data('ays-orig');
        if($field.attr('type') == 'checkbox'){
          
        }
        
        
        if (undefined === origValue) {
          return false;
        }
        return (getValue($field) != origValue);
      };

      var $form = ($(this).is('form')) 
                    ? $(this)
                    : $(this).parents('form');

      // Test on the target first as it's the most likely to be dirty
      if (isFieldDirty($(evt.target))) {
        setDirtyStatus($form, true);
        return;
      }

      $fields = $form.find(settings.fieldSelector);

      if (settings.addRemoveFieldsMarksDirty) {              
        // Check if field count has changed
        var origCount = $form.data("ays-orig-field-count");
        if (origCount != $fields.length) {
          setDirtyStatus($form, true);
          return;
        }
      }

      // Brute force - check each field
      var isDirty = false;
      $fields.each(function() {
        $field = $(this);
        if (isFieldDirty($field)) {
          
          $field.addClass('dirty_field');
          isDirty = true;
          //return false; // break
        } else {
          $field.removeClass('dirty_field');
        }
      });
      
      setDirtyStatus($form, isDirty);
    };

    var initForm = function($form) {
      var fields = $form.find(settings.fieldSelector);
      $(fields).each(function() { storeOrigValue($(this)); });
      $(fields).unbind(settings.fieldEvents, checkForm);
      $(fields).bind(settings.fieldEvents, checkForm);
      $form.data("ays-orig-field-count", $(fields).length);
      setDirtyStatus($form, false);
    };

    var setDirtyStatus = function($form, isDirty) {
      var changed = isDirty != $form.hasClass(settings.dirtyClass);
      $form.toggleClass(settings.dirtyClass, isDirty);
        
      // Fire change event if required
      if (changed) {
        if (settings.change) settings.change.call($form, $form);

        if (isDirty) $form.trigger('dirty.areYouSure', [$form]);
        if (!isDirty) $form.trigger('clean.areYouSure', [$form]);
        $form.trigger('change.areYouSure', [$form]);
      }
    };

    var rescan = function() {
      var $form = $(this);
      var fields = $form.find(settings.fieldSelector);
      $(fields).each(function() {
        var $field = $(this);
        if (!$field.data('ays-orig')) {
          storeOrigValue($field);
          $field.bind(settings.fieldEvents, checkForm);
        }
      });
      // Check for changes while we're here
      $form.trigger('checkform.areYouSure');
    };

    var reinitialize = function() {
      initForm($(this));
    }

    if (!settings.silent && !window.aysUnloadSet) {
      window.aysUnloadSet = true;
      $(window).bind('beforeunload', function() {
        $dirtyForms = $("form").filter('.' + settings.dirtyClass);
        if ($dirtyForms.length == 0) {
          return;
        }
        // Prevent multiple prompts - seen on Chrome and IE
        if (navigator.userAgent.toLowerCase().match(/msie|chrome/)) {
          if (window.aysHasPrompted) {
            return;
          }
          window.aysHasPrompted = true;
          window.setTimeout(function() {window.aysHasPrompted = false;}, 900);
        }
        return settings.message;
      });
    }

    return this.each(function(elem) {
      if (!$(this).is('form')) {
        return;
      }
      var $form = $(this);
        
      $form.submit(function() {
        $form.removeClass(settings.dirtyClass);
      });
      $form.bind('reset', function() { setDirtyStatus($form, false); });
      // Add a custom events
      $form.bind('rescan.areYouSure', rescan);
      $form.bind('reinitialize.areYouSure', reinitialize);
      $form.bind('checkform.areYouSure', checkForm);
      initForm($form);
    });
  };
})(jQuery);






















$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
  //cl(thrownError);
});

$(document).ajaxSend(function(event, xhr, settings) {

  
  //settings.url += (settings.url.indexOf('?') == -1 ? '?' : '&') + 'decorator=0';
  //settings.url = encodeURI(settings.url);
});

var redactors = {};

$(document).ajaxSuccess(function(event, xhr, settings) {
  if(settings.url == sf_prefix + '/main/event' || settings.url == sf_prefix + '/purpose/rels'){
    return;
  }
  $('input,select').customizeForm();
  $('form').formTabs();
  arm.init();
  
  $('select.chosen').chosen({disable_search_threshold: 10});
  $('.managed').luiManaged();
  $('.set_readonly input, .set_readonly select').prop('readonly', 'true');
  $('.set_readonly input, .set_readonly select').prop('disabled', 'true');
  
  $('textarea.rich').each(function(){
    if($(this).data('redactor') != '1'){
      $(this).data('redactor', '1');
      var id = $(this).prop('id');
      redactors[id] = $(this).redactor();
    } 
  });
  $('.tabs_item').hide();
  if($('.tabs_item').length < 2){
    $('.tabs_item').show();
  }
  if($.cookie('ierch__layer__tabs__item') && $('.ierch__layer__tabs__item[data-tab="' + $.cookie('ierch__layer__tabs__item') + '"]').length > 0){
    $('.ierch__layer__tabs__item[data-tab="' + $.cookie('ierch__layer__tabs__item') + '"]').trigger('click');
  } else {
    $('.ierch__layer__tabs__item').first().click();
  }
  
  $(window).resize();
  create_custom_table_head_recalc();
  
});





var qFns = {
  requestAndRefresh: function(url, de){
    var cururi = $.url();
    $.get(url, function(){
      a_infomat.updateMonitor();
    });
  }
  
};





















var wsObjectBusy = false;
var wsObject = null;
var wsObjectChannel = null;
var wsObjectInit = function(channel){
  wsObjectBusy = true;
  wsObjectChannel = channel;
  if(wsObject != null){
    wsObject.close();
    wsObject = null;
  }
  //wsObject = new WebSocket('ws' + (window.location.protocol == 'https:' ? 's' : '') + '://' + window.location.host + '/ws/?channel=common/user_' + sf_user + (wsObjectChannel != '' ? '/' + wsObjectChannel : ''));
  //wsObject.onopen = function(evt) {
  //  cl('open');
  //  wsObjectBusy = false;
  //};
  //wsObject.onerror = function() {
  //  cl('error');
  //
  //  var pushstream = new PushStream({
  //    host: window.location.hostname,
  //    port: window.location.port,
  //    modes: "longpolling",
  //    channelsByArgument: true,
  //    channelsArgument: 'channel'
  //  });
  //  pushstream.onmessage = wsFunctions.msg;
  //  pushstream.addChannel('common');
  //  pushstream.addChannel(sf_user);
  //  if(wsObjectChannel != ''){
  //    pushstream.addChannel(wsObjectChannel);
  //  }
  //  pushstream.connect();
  //
  //};
  //wsObject.onclose = function() {
  //  cl('close');
  //  wsObjectBusy = false;
  //};
  //wsObject.onmessage = wsFunctions.msg;
};




var wsFunctions = {
  msg: function(evt){
    var json = JSON.parse(evt.data);
    if(json.text.state == 'fn' && typeof wsFunctions[json.text.fn_name] == 'function'){
      wsFunctions[json.text.fn_name](json.text.fn_args);
    }
  },
  follow: function(url){
    window.location = sf_prefix + url;
  },
  reload: function(){
    window.location.reload();
  },
  menu: function(){
    $.ajax({
      url: sf_prefix + '/main/event',
      headers: {
        'X-Requested-With': '0'
      }
    }).done(function(html){
      var lui__nav_menu__count_now = $('.lui__nav_menu__count');
      var lui__nav_menu__count_new = $(html).find('.lui__nav_menu__count');
      lui__nav_menu__count_now.each(function(i, el){
        var el_new = $(lui__nav_menu__count_new[i]);
        if(parseInt(el_new.text()) > parseInt($(el).text()) || ($(el).text() == '' && el_new.text() != '')){
          $(el).addClass('lui__nav_menu__count__jump');
        } else if(parseInt(el_new.text()) < parseInt($(el).text())){
          $(el).removeClass('lui__nav_menu__count__jump');
        }
        $(el).html(el_new.html());
      });
    });
  }
};




if(sf_user){
  wsObjectInit('');
  setInterval(function(){
    if(wsObjectBusy){
      return;
    }
    if(wsObject.readyState != 1){
      wsObjectInit(wsObjectChannel);
    }
  }, 1000);
}
setInterval(function(){
  wsFunctions.menu();
}, 60000);

$(document).ready(function(){
  
  
  
  
  
  
  
  
  var suri = $.url();
  var sget = suri.attr('query').split('&');
  $.each(sget, function(qk, qv){
    var svar = qv.split('=');
    var sid = svar[0].replace(']', '').replace('[', '_');
    if($('#' + sid).length == 1){
      $('#' + sid).val(svar[1]);
    }
  });
  
  
  
  
  
  $('.managed').luiManaged();
  
  $('input,select').customizeForm();
  $('form').formTabs();

  create_custom_table_head();
  
  arm.init();
  $(window).resize(function(){
    $('#lui__desktop_right__inner').outerHeight($(window).height());

    $('.ierch__layers__wrapper').outerHeight($('.ierch__layers__wrapper').parent().outerHeight());
    // if($('.ierch__layer__tabs_all').length > 0 && $('.ierch__layer__tabs_all').outerHeight() < $('.ierch__layers__wrapper').parent().outerHeight()){
    //   $('.ierch__layer__tabs_all').outerHeight($('.ierch__layers__wrapper').parent().outerHeight())
    // }
     
    var summ = 0;
    var mw = $(window).width() - $('.lui__desktop_left').width();

    $('.ierch__layer__props').outerWidth(mw - (($('.ierch__layer').length - 1) * 220));

    $('.ierch__layer').each(function(ik, iel){
      summ += $(iel).outerWidth();
    });
    
    
    var root = $('.ierch__layers');
    root.width(summ);
    
    
    
    
    $('.ierch__layers__wrapper').width(mw >= 660 ? mw : 660);
    var h = $('.ierch__layers').outerHeight();
    $('.ierch__layer__overlay').outerHeight(h);
    var lw = $('.ierch__layers').width();
    var llf = lw - mw;
    if(llf > 0){
      $('.ierch__layers').css('left', llf * -1);
      $('.lui__desktop_left').addClass('ierch__layers__shadow');
    } else {
      $('.ierch__layers').css('left', 0);
      $('.lui__desktop_left').removeClass('ierch__layers__shadow');
      if($('.ierch__layer__props').length > 0){
        $('.ierch__layer__props').outerWidth(mw - (($('.ierch__layer').length - 1) * 220));
        $('.ierch__layer__props .ierch__layer__shadowed').outerWidth($('.ierch__layer__props').outerWidth() - 20);
        $('.ierch__layer__props').outerHeight(h - 16);
      }
      if($('.ierch__layer__worker').length > 0){
        $('.ierch__layer__worker').outerWidth(mw - (($('.ierch__layer').length - 1) * 220));
        $('.ierch__layer__worker .ierch__layer__shadowed').outerWidth($('.ierch__layer__props').outerWidth() - 20);
        $('.ierch__layer__worker').outerHeight(h - 16);
      }
      
    }
    
    
    
    
    
    
    $('.ierch__layer__items').each(function(ik, iel){
      //$(iel).height( - $(iel).parent().find('.ierch__layer__actions_f').height() - 20);
      $(iel).outerHeight(h - 16);
      $(iel).perfectScrollbar('update');
    });
    

    if($('.tabs_item').length > 0){
      $('.tabs_item').each(function(i, ti){
        var posY = $(ti).position().top;
        var h = $('.ierch__layer__props').outerHeight() - posY;
        if(h < $('.ierch__layer__props').outerHeight()){
          $(ti).outerHeight(h, true);
        }
      });
    };
    
  }).resize();
  
  
 
  var lui__desktop_left__wrapper = $('.lui__desktop_left__wrapper');
  var lui__desktop_left__wrapper_started_width = lui__desktop_left__wrapper.width();
  lui__desktop_left__wrapper.width(lui__desktop_left__wrapper_started_width);
  if($.cookie('lui__desktop_left_hider__hidded') == 1){
    $('.lui__desktop_left_hider').addClass('lui__desktop_left_hider__hidded');
    lui__desktop_left__wrapper.addClass('lui__desktop_left__wrapper__closed');
    $('.lui__desktop_left_hider').prop('title', 'Развернуть');
  }
  var lui__desktop_left_hider__click = function(el){
    if(lui__desktop_left__wrapper.hasClass('lui__desktop_left__wrapper__closed')){
      lui__desktop_left__wrapper.width(lui__desktop_left__wrapper_started_width);
      lui__desktop_left__wrapper.removeClass('lui__desktop_left__wrapper__closed');
      $(el).removeClass('lui__desktop_left_hider__hidded');
      $(el).prop('title', 'Свернуть');
      $.cookie('lui__desktop_left_hider__hidded', 0);
    } else {
      $(el).addClass('lui__desktop_left_hider__hidded');
      lui__desktop_left__wrapper.addClass('lui__desktop_left__wrapper__closed');
      $(el).prop('title', 'Развернуть');
      $.cookie('lui__desktop_left_hider__hidded', 1);
    }
    setTimeout(function(){
      $(window).resize();
    }, 200);
  };
  $('.lui__desktop_left_hider').click(function(){
    lui__desktop_left_hider__click($(this));
  });
  
  


  
  $(document).on('click', '.sf_admin_row td, .sf_admin_action_new a, .sf_admin_action_new_rel a, .custom_stuped_window', function(){

    if($(this).find('.sf_admin_batch_checkbox,.sf_admin_td_actions,.sf_admin_td_stop').length > 0 || $(this).parent().hasClass('sf_admin_tr_stop')){
      return;
    }

    
    var tr = $(this).parent().hasClass('sf_admin_row') ? $(this).parent() : $(this).parent().parent();
    var before_top =  $(this).offset().top - 22;

    $('.lui__desktop_right__wrapper .lui_form_layer').remove();
    
    //tr.removeClass('sf_admin_row__unread');
    
    $('.sOverlay_fix').remove();
    
    var o = sOverlay($('.lui__desktop_right__wrapper'), 'sOverlay_fix', $('.lui__desktop_right__wrapper'));
    
    var href = '';
    if($(this).parent().hasClass('sf_admin_row')){
      href = $(this).parent().data('href');
    } else if($(this).hasClass('custom_stuped_window')){
      href = $(this).data('href') || $(this).prop('href');
    } else {
      href = $(this).prop('href')
    }

    
    $.get(href, function(html){
      var $html = $(html);
      
      var cururi = $.url();
      
      var return_path = cururi.attr('path') + (cururi.attr('query') ? '?' + cururi.attr('query') : '');
      
      var state = {
        title: $('title').html(),
        url: href
      }
      history.pushState(state, state.title, state.url);
      

      
      var layout = $('<div class="lui_form_layer"><span class="lui_form_layer__close"></span>' + html + '</div>');
      $('.lui__desktop_right__wrapper').append(layout);
      
      var $form = layout.find('form');
      
      $(window).resize();
      
      
      $(layout).on('click', '.lui_form_layer__close', function(){
        
        //wsObjectInit('');
        
        /*
        if($(this).data('disabled') != '1'){
          if(!confirm('Закрыть не сохранив измения?')){
            return false;
          }
        }
        */
        

        var state = {
          title: $('title').html(),
          url: return_path
        }

        
        history.pushState(state, state.title, state.url);

        
        if(typeof global_form_interval != 'undefined'){
          clearInterval(global_form_interval);
        }
        layout.remove();
        //tr.css('background-color', '');
        if($('.lui_form_layer__close').data('need_reload')){
          
          var jqxhr = $.ajax(return_path).done(function(html){
            
            var new_html = $(html);
            
            $('.lui_pager').html(new_html.find('.lui_pager').length > 0 ? new_html.find('.lui_pager').html() : '');
            $('.lui__scroller_wrapper').html(new_html.find('.lui__scroller_wrapper').html());
            
            create_custom_table_head();
            create_custom_table_head_recalc();
            
            $('.lui__list_count').html('&nbsp;' + ($('.type_search').val() != '' ? 'найдено:&nbsp;' : '') + new_html.data('count'));
            if($('.type_search').val() != ''){
              $('.lui__list_table__sourse').removeHighlight().highlight($('.type_search').val());
            }
            $('.export_excel').prop('href', cururi.attr('query') + '&export');
            if(new_html.data('count') == '0'){
              $('.export_excel').hide();
            } else {
              $('.export_excel').show();
            }
            $('.lui_scroller').scrollTop(lui_scroller_s);
            o.remove();

          });
          
        } else {
          o.remove();
        }
        $('.sOverlay_fix').remove();
      });
      
      $(layout).on('click', '.sf_admin_action_save input,.other_save', function(){
        var ol = sOverlay(layout, 'more_index2');
        $form.find('textarea.rich').each(function(){
          if($(this).data('redactor') == '1'){
            var id = $(this).prop('id');
            var _this = redactors[id];
            if(_this && _this.opts.visual !== false){
              _this.clean(false);
              _this.syncCodeToTextarea();
            }
          }
        });
        $.post($form.prop('action'), $form.serialize(), function(html_post){
          var module = $(html_post).find('input[id$="_id"]').first().prop('id').replace('_id', '');
          ol.remove();

          
          if($(html_post).find('.error_list').length > 0 || $('<div>' + html_post + '</div>').find('.lui__error').length > 0){
            layout.html('<span class="lui_form_layer__close"></span><span class="lui_form_layer__close"></span>' + html_post);
            $form = layout.find('form');
            var lui__scroller_class = layout.find('.lui__scroller_class');
            lui__scroller_class.outerHeight(layout.outerHeight() - lui__scroller_class.offset().top + 20);
          } else {
            var lui_scroller_s = $('.lui_scroller').scrollTop();
            
            if($('.lui_form_layer__close').data('post_action')){
              window.open($('.lui_form_layer__close').data('post_action'));
            }
            
            layout.remove();
            
            
            
            
            var jqxhr = $.ajax(return_path).done(function(html){
              
              var state = {
                title: $('title').html(),
                url: return_path
              }

              
              history.pushState(state, state.title, state.url);
              
              var new_html = $(html);
              
              $('.lui_pager').html(new_html.find('.lui_pager').length > 0 ? new_html.find('.lui_pager').html() : '');
              $('.lui__scroller_wrapper').html(new_html.find('.lui__scroller_wrapper').html());
              
              create_custom_table_head();
              create_custom_table_head_recalc();
              
              $('.lui__list_count').html('&nbsp;' + ($('.type_search').val() != '' ? 'найдено:&nbsp;' : '') + new_html.data('count'));
              if($('.type_search').val() != ''){
                $('.lui__list_table__sourse').removeHighlight().highlight($('.type_search').val());
              }
              $('.export_excel').prop('href', cururi.attr('query') + '&export');
              if(new_html.data('count') == '0'){
                $('.export_excel').hide();
              } else {
                $('.export_excel').show();
              }
              $('.lui_scroller').scrollTop(lui_scroller_s);
              o.remove();

            });

          }
        });
        return false;
      });
      
      
      
    });
    return false;
  });
  
  $('.make__toggle').each(function(k, v){
    var options = $(v).find('option');
    if(options.length == 2 && !$(v).data('initialized')){
      var html = '<div class="lui__toggler lui__toggler__o' + ($(options[1]).is(':selected') ? 'n' : 'ff') + '">';
      html += '<div class="lui__toggler__item lui__toggler__item__off" data-val="' + $(options[0]).prop('value') + '" data-state="off">' + $(options[0]).html() + '</div>';
      html += '<div class="lui__toggler__content"><div class="lui__toggler__content__switcher"></div></div>';
      html += '<div class="lui__toggler__item lui__toggler__item__on" data-val="' + $(options[1]).prop('value') + '" data-state="on">' + $(options[1]).html() + '</div>';
      html += '</div>';
      $(v).parent().append(html);
      $(v).parent().find('.lui__toggler__item').click(function(){
        $(this).parent().removeClass('lui__toggler__' + ($(this).data('state') == 'off' ? 'on' : 'off'));
        $(this).parent().addClass('lui__toggler__' + ($(this).data('state') == 'off' ? 'off' : 'on'));
        $(options).removeAttr('selected');
        $(v).val($(this).data('val'));
        $(v).find('option[value=' + $(this).data('val') + ']').attr('selected', 'selected');
        $(v).change();
      });
      $(v).data('initialized', 1);
      $(v).hide();
    }
  });
  
  $('select.chosen').chosen({disable_search_threshold: 10});
  
  
  $('textarea.rich').each(function(){
    if($(this).data('redactor') != '1'){
      $(this).data('redactor', '1');
      $(this).redactor();
    }
  });
  
  
  
  $('.another_tabs').each(function(k, tabs){
    var another_tabs_items = $(tabs).find('.another_tabs__item');
    
    another_tabs_items.click(function(){
      $(another_tabs_items).removeClass('another_tabs__item__active');
      $(this).addClass('another_tabs__item__active');
    });
    
  });


  $(document).on('click', '.ierch__layer__tabs__item', function(){
    var tab = $(this).data('tab'),
        parent = $(this).parent().parent();
    parent.find('.ierch__layer__tabs__item').removeClass('ierch__layer__tabs__active');
    $(this).addClass('ierch__layer__tabs__active');
    parent.find('.tabs_item').hide();
    parent.find('.tabs_item[data-tab="'+ tab +'"]').show();
    console.log(tab);
    
    $.cookie('ierch__layer__tabs__item', tab);
    
    $(window).resize();
  });

  create_custom_table_head_recalc();
  
  
  
  
  $('.input_with_erase').each(function(k, el){
    var eraser = $('<div class="input_with_erase__eraser"></div>');
    $(el).after(eraser);
    eraser.click(function(){
      $(el).val('');
      $(el).keyup();
    });
    $(el).keyup(function(){
      if($(this).val() != ''){
        eraser.css('visibility', 'visible');
      } else {
        eraser.css('visibility', 'hidden');
      }
    });
    $(el).keyup();
  });
  
  
  
  
  try{
    $('.spinpicker_input').spinpicker({lang: 'ru'});
  } catch(e){
    
  }

























  /**
   * jquery.Jcrop.min.js v0.9.12 (build:20130202)
   * jQuery Image Cropping Plugin - released under MIT License
   * Copyright (c) 2008-2013 Tapmodo Interactive LLC
   * https://github.com/tapmodo/Jcrop
   */
  (function(a){a.Jcrop=function(b,c){function i(a){return Math.round(a)+"px"}function j(a){return d.baseClass+"-"+a}function k(){return a.fx.step.hasOwnProperty("backgroundColor")}function l(b){var c=a(b).offset();return[c.left,c.top]}function m(a){return[a.pageX-e[0],a.pageY-e[1]]}function n(b){typeof b!="object"&&(b={}),d=a.extend(d,b),a.each(["onChange","onSelect","onRelease","onDblClick"],function(a,b){typeof d[b]!="function"&&(d[b]=function(){})})}function o(a,b,c){e=l(D),bc.setCursor(a==="move"?a:a+"-resize");if(a==="move")return bc.activateHandlers(q(b),v,c);var d=_.getFixed(),f=r(a),g=_.getCorner(r(f));_.setPressed(_.getCorner(f)),_.setCurrent(g),bc.activateHandlers(p(a,d),v,c)}function p(a,b){return function(c){if(!d.aspectRatio)switch(a){case"e":c[1]=b.y2;break;case"w":c[1]=b.y2;break;case"n":c[0]=b.x2;break;case"s":c[0]=b.x2}else switch(a){case"e":c[1]=b.y+1;break;case"w":c[1]=b.y+1;break;case"n":c[0]=b.x+1;break;case"s":c[0]=b.x+1}_.setCurrent(c),bb.update()}}function q(a){var b=a;return bd.watchKeys
  (),function(a){_.moveOffset([a[0]-b[0],a[1]-b[1]]),b=a,bb.update()}}function r(a){switch(a){case"n":return"sw";case"s":return"nw";case"e":return"nw";case"w":return"ne";case"ne":return"sw";case"nw":return"se";case"se":return"nw";case"sw":return"ne"}}function s(a){return function(b){return d.disabled?!1:a==="move"&&!d.allowMove?!1:(e=l(D),W=!0,o(a,m(b)),b.stopPropagation(),b.preventDefault(),!1)}}function t(a,b,c){var d=a.width(),e=a.height();d>b&&b>0&&(d=b,e=b/a.width()*a.height()),e>c&&c>0&&(e=c,d=c/a.height()*a.width()),T=a.width()/d,U=a.height()/e,a.width(d).height(e)}function u(a){return{x:a.x*T,y:a.y*U,x2:a.x2*T,y2:a.y2*U,w:a.w*T,h:a.h*U}}function v(a){var b=_.getFixed();b.w>d.minSelect[0]&&b.h>d.minSelect[1]?(bb.enableHandles(),bb.done()):bb.release(),bc.setCursor(d.allowSelect?"crosshair":"default")}function w(a){if(d.disabled)return!1;if(!d.allowSelect)return!1;W=!0,e=l(D),bb.disableHandles(),bc.setCursor("crosshair");var b=m(a);return _.setPressed(b),bb.update(),bc.activateHandlers(x,v,a.type.substring
      (0,5)==="touch"),bd.watchKeys(),a.stopPropagation(),a.preventDefault(),!1}function x(a){_.setCurrent(a),bb.update()}function y(){var b=a("<div></div>").addClass(j("tracker"));return g&&b.css({opacity:0,backgroundColor:"white"}),b}function be(a){G.removeClass().addClass(j("holder")).addClass(a)}function bf(a,b){function t(){window.setTimeout(u,l)}var c=a[0]/T,e=a[1]/U,f=a[2]/T,g=a[3]/U;if(X)return;var h=_.flipCoords(c,e,f,g),i=_.getFixed(),j=[i.x,i.y,i.x2,i.y2],k=j,l=d.animationDelay,m=h[0]-j[0],n=h[1]-j[1],o=h[2]-j[2],p=h[3]-j[3],q=0,r=d.swingSpeed;c=k[0],e=k[1],f=k[2],g=k[3],bb.animMode(!0);var s,u=function(){return function(){q+=(100-q)/r,k[0]=Math.round(c+q/100*m),k[1]=Math.round(e+q/100*n),k[2]=Math.round(f+q/100*o),k[3]=Math.round(g+q/100*p),q>=99.8&&(q=100),q<100?(bh(k),t()):(bb.done(),bb.animMode(!1),typeof b=="function"&&b.call(bs))}}();t()}function bg(a){bh([a[0]/T,a[1]/U,a[2]/T,a[3]/U]),d.onSelect.call(bs,u(_.getFixed())),bb.enableHandles()}function bh(a){_.setPressed([a[0],a[1]]),_.setCurrent([a[2],
    a[3]]),bb.update()}function bi(){return u(_.getFixed())}function bj(){return _.getFixed()}function bk(a){n(a),br()}function bl(){d.disabled=!0,bb.disableHandles(),bb.setCursor("default"),bc.setCursor("default")}function bm(){d.disabled=!1,br()}function bn(){bb.done(),bc.activateHandlers(null,null)}function bo(){G.remove(),A.show(),A.css("visibility","visible"),a(b).removeData("Jcrop")}function bp(a,b){bb.release(),bl();var c=new Image;c.onload=function(){var e=c.width,f=c.height,g=d.boxWidth,h=d.boxHeight;D.width(e).height(f),D.attr("src",a),H.attr("src",a),t(D,g,h),E=D.width(),F=D.height(),H.width(E).height(F),M.width(E+L*2).height(F+L*2),G.width(E).height(F),ba.resize(E,F),bm(),typeof b=="function"&&b.call(bs)},c.src=a}function bq(a,b,c){var e=b||d.bgColor;d.bgFade&&k()&&d.fadeTime&&!c?a.animate({backgroundColor:e},{queue:!1,duration:d.fadeTime}):a.css("backgroundColor",e)}function br(a){d.allowResize?a?bb.enableOnly():bb.enableHandles():bb.disableHandles(),bc.setCursor(d.allowSelect?"crosshair":"default"),bb
      .setCursor(d.allowMove?"move":"default"),d.hasOwnProperty("trueSize")&&(T=d.trueSize[0]/E,U=d.trueSize[1]/F),d.hasOwnProperty("setSelect")&&(bg(d.setSelect),bb.done(),delete d.setSelect),ba.refresh(),d.bgColor!=N&&(bq(d.shade?ba.getShades():G,d.shade?d.shadeColor||d.bgColor:d.bgColor),N=d.bgColor),O!=d.bgOpacity&&(O=d.bgOpacity,d.shade?ba.refresh():bb.setBgOpacity(O)),P=d.maxSize[0]||0,Q=d.maxSize[1]||0,R=d.minSize[0]||0,S=d.minSize[1]||0,d.hasOwnProperty("outerImage")&&(D.attr("src",d.outerImage),delete d.outerImage),bb.refresh()}var d=a.extend({},a.Jcrop.defaults),e,f=navigator.userAgent.toLowerCase(),g=/msie/.test(f),h=/msie [1-6]\./.test(f);typeof b!="object"&&(b=a(b)[0]),typeof c!="object"&&(c={}),n(c);var z={border:"none",visibility:"visible",margin:0,padding:0,position:"absolute",top:0,left:0},A=a(b),B=!0;if(b.tagName=="IMG"){if(A[0].width!=0&&A[0].height!=0)A.width(A[0].width),A.height(A[0].height);else{var C=new Image;C.src=A[0].src,A.width(C.width),A.height(C.height)}var D=A.clone().removeAttr("id").
  css(z).show();D.width(A.width()),D.height(A.height()),A.after(D).hide()}else D=A.css(z).show(),B=!1,d.shade===null&&(d.shade=!0);t(D,d.boxWidth,d.boxHeight);var E=D.width(),F=D.height(),G=a("<div />").width(E).height(F).addClass(j("holder")).css({position:"relative",backgroundColor:d.bgColor}).insertAfter(A).append(D);d.addClass&&G.addClass(d.addClass);var H=a("<div />"),I=a("<div />").width("100%").height("100%").css({zIndex:310,position:"absolute",overflow:"hidden"}),J=a("<div />").width("100%").height("100%").css("zIndex",320),K=a("<div />").css({position:"absolute",zIndex:600}).dblclick(function(){var a=_.getFixed();d.onDblClick.call(bs,a)}).insertBefore(D).append(I,J);B&&(H=a("<img />").attr("src",D.attr("src")).css(z).width(E).height(F),I.append(H)),h&&K.css({overflowY:"hidden"});var L=d.boundary,M=y().width(E+L*2).height(F+L*2).css({position:"absolute",top:i(-L),left:i(-L),zIndex:290}).mousedown(w),N=d.bgColor,O=d.bgOpacity,P,Q,R,S,T,U,V=!0,W,X,Y;e=l(D);var Z=function(){function a(){var a={},b=["touchstart"
    ,"touchmove","touchend"],c=document.createElement("div"),d;try{for(d=0;d<b.length;d++){var e=b[d];e="on"+e;var f=e in c;f||(c.setAttribute(e,"return;"),f=typeof c[e]=="function"),a[b[d]]=f}return a.touchstart&&a.touchend&&a.touchmove}catch(g){return!1}}function b(){return d.touchSupport===!0||d.touchSupport===!1?d.touchSupport:a()}return{createDragger:function(a){return function(b){return d.disabled?!1:a==="move"&&!d.allowMove?!1:(e=l(D),W=!0,o(a,m(Z.cfilter(b)),!0),b.stopPropagation(),b.preventDefault(),!1)}},newSelection:function(a){return w(Z.cfilter(a))},cfilter:function(a){return a.pageX=a.originalEvent.changedTouches[0].pageX,a.pageY=a.originalEvent.changedTouches[0].pageY,a},isSupported:a,support:b()}}(),_=function(){function h(d){d=n(d),c=a=d[0],e=b=d[1]}function i(a){a=n(a),f=a[0]-c,g=a[1]-e,c=a[0],e=a[1]}function j(){return[f,g]}function k(d){var f=d[0],g=d[1];0>a+f&&(f-=f+a),0>b+g&&(g-=g+b),F<e+g&&(g+=F-(e+g)),E<c+f&&(f+=E-(c+f)),a+=f,c+=f,b+=g,e+=g}function l(a){var b=m();switch(a){case"ne":return[
    b.x2,b.y];case"nw":return[b.x,b.y];case"se":return[b.x2,b.y2];case"sw":return[b.x,b.y2]}}function m(){if(!d.aspectRatio)return p();var f=d.aspectRatio,g=d.minSize[0]/T,h=d.maxSize[0]/T,i=d.maxSize[1]/U,j=c-a,k=e-b,l=Math.abs(j),m=Math.abs(k),n=l/m,r,s,t,u;return h===0&&(h=E*10),i===0&&(i=F*10),n<f?(s=e,t=m*f,r=j<0?a-t:t+a,r<0?(r=0,u=Math.abs((r-a)/f),s=k<0?b-u:u+b):r>E&&(r=E,u=Math.abs((r-a)/f),s=k<0?b-u:u+b)):(r=c,u=l/f,s=k<0?b-u:b+u,s<0?(s=0,t=Math.abs((s-b)*f),r=j<0?a-t:t+a):s>F&&(s=F,t=Math.abs(s-b)*f,r=j<0?a-t:t+a)),r>a?(r-a<g?r=a+g:r-a>h&&(r=a+h),s>b?s=b+(r-a)/f:s=b-(r-a)/f):r<a&&(a-r<g?r=a-g:a-r>h&&(r=a-h),s>b?s=b+(a-r)/f:s=b-(a-r)/f),r<0?(a-=r,r=0):r>E&&(a-=r-E,r=E),s<0?(b-=s,s=0):s>F&&(b-=s-F,s=F),q(o(a,b,r,s))}function n(a){return a[0]<0&&(a[0]=0),a[1]<0&&(a[1]=0),a[0]>E&&(a[0]=E),a[1]>F&&(a[1]=F),[Math.round(a[0]),Math.round(a[1])]}function o(a,b,c,d){var e=a,f=c,g=b,h=d;return c<a&&(e=c,f=a),d<b&&(g=d,h=b),[e,g,f,h]}function p(){var d=c-a,f=e-b,g;return P&&Math.abs(d)>P&&(c=d>0?a+P:a-P),Q&&Math.abs
  (f)>Q&&(e=f>0?b+Q:b-Q),S/U&&Math.abs(f)<S/U&&(e=f>0?b+S/U:b-S/U),R/T&&Math.abs(d)<R/T&&(c=d>0?a+R/T:a-R/T),a<0&&(c-=a,a-=a),b<0&&(e-=b,b-=b),c<0&&(a-=c,c-=c),e<0&&(b-=e,e-=e),c>E&&(g=c-E,a-=g,c-=g),e>F&&(g=e-F,b-=g,e-=g),a>E&&(g=a-F,e-=g,b-=g),b>F&&(g=b-F,e-=g,b-=g),q(o(a,b,c,e))}function q(a){return{x:a[0],y:a[1],x2:a[2],y2:a[3],w:a[2]-a[0],h:a[3]-a[1]}}var a=0,b=0,c=0,e=0,f,g;return{flipCoords:o,setPressed:h,setCurrent:i,getOffset:j,moveOffset:k,getCorner:l,getFixed:m}}(),ba=function(){function f(a,b){e.left.css({height:i(b)}),e.right.css({height:i(b)})}function g(){return h(_.getFixed())}function h(a){e.top.css({left:i(a.x),width:i(a.w),height:i(a.y)}),e.bottom.css({top:i(a.y2),left:i(a.x),width:i(a.w),height:i(F-a.y2)}),e.right.css({left:i(a.x2),width:i(E-a.x2)}),e.left.css({width:i(a.x)})}function j(){return a("<div />").css({position:"absolute",backgroundColor:d.shadeColor||d.bgColor}).appendTo(c)}function k(){b||(b=!0,c.insertBefore(D),g(),bb.setBgOpacity(1,0,1),H.hide(),l(d.shadeColor||d.bgColor,1),bb.
  isAwake()?n(d.bgOpacity,1):n(1,1))}function l(a,b){bq(p(),a,b)}function m(){b&&(c.remove(),H.show(),b=!1,bb.isAwake()?bb.setBgOpacity(d.bgOpacity,1,1):(bb.setBgOpacity(1,1,1),bb.disableHandles()),bq(G,0,1))}function n(a,e){b&&(d.bgFade&&!e?c.animate({opacity:1-a},{queue:!1,duration:d.fadeTime}):c.css({opacity:1-a}))}function o(){d.shade?k():m(),bb.isAwake()&&n(d.bgOpacity)}function p(){return c.children()}var b=!1,c=a("<div />").css({position:"absolute",zIndex:240,opacity:0}),e={top:j(),left:j().height(F),right:j().height(F),bottom:j()};return{update:g,updateRaw:h,getShades:p,setBgColor:l,enable:k,disable:m,resize:f,refresh:o,opacity:n}}(),bb=function(){function k(b){var c=a("<div />").css({position:"absolute",opacity:d.borderOpacity}).addClass(j(b));return I.append(c),c}function l(b,c){var d=a("<div />").mousedown(s(b)).css({cursor:b+"-resize",position:"absolute",zIndex:c}).addClass("ord-"+b);return Z.support&&d.bind("touchstart.jcrop",Z.createDragger(b)),J.append(d),d}function m(a){var b=d.handleSize,e=l(a,c++
  ).css({opacity:d.handleOpacity}).addClass(j("handle"));return b&&e.width(b).height(b),e}function n(a){return l(a,c++).addClass("jcrop-dragbar")}function o(a){var b;for(b=0;b<a.length;b++)g[a[b]]=n(a[b])}function p(a){var b,c;for(c=0;c<a.length;c++){switch(a[c]){case"n":b="hline";break;case"s":b="hline bottom";break;case"e":b="vline right";break;case"w":b="vline"}e[a[c]]=k(b)}}function q(a){var b;for(b=0;b<a.length;b++)f[a[b]]=m(a[b])}function r(a,b){d.shade||H.css({top:i(-b),left:i(-a)}),K.css({top:i(b),left:i(a)})}function t(a,b){K.width(Math.round(a)).height(Math.round(b))}function v(){var a=_.getFixed();_.setPressed([a.x,a.y]),_.setCurrent([a.x2,a.y2]),w()}function w(a){if(b)return x(a)}function x(a){var c=_.getFixed();t(c.w,c.h),r(c.x,c.y),d.shade&&ba.updateRaw(c),b||A(),a?d.onSelect.call(bs,u(c)):d.onChange.call(bs,u(c))}function z(a,c,e){if(!b&&!c)return;d.bgFade&&!e?D.animate({opacity:a},{queue:!1,duration:d.fadeTime}):D.css("opacity",a)}function A(){K.show(),d.shade?ba.opacity(O):z(O,!0),b=!0}function B
  (){F(),K.hide(),d.shade?ba.opacity(1):z(1),b=!1,d.onRelease.call(bs)}function C(){h&&J.show()}function E(){h=!0;if(d.allowResize)return J.show(),!0}function F(){h=!1,J.hide()}function G(a){a?(X=!0,F()):(X=!1,E())}function L(){G(!1),v()}var b,c=370,e={},f={},g={},h=!1;d.dragEdges&&a.isArray(d.createDragbars)&&o(d.createDragbars),a.isArray(d.createHandles)&&q(d.createHandles),d.drawBorders&&a.isArray(d.createBorders)&&p(d.createBorders),a(document).bind("touchstart.jcrop-ios",function(b){a(b.currentTarget).hasClass("jcrop-tracker")&&b.stopPropagation()});var M=y().mousedown(s("move")).css({cursor:"move",position:"absolute",zIndex:360});return Z.support&&M.bind("touchstart.jcrop",Z.createDragger("move")),I.append(M),F(),{updateVisible:w,update:x,release:B,refresh:v,isAwake:function(){return b},setCursor:function(a){M.css("cursor",a)},enableHandles:E,enableOnly:function(){h=!0},showHandles:C,disableHandles:F,animMode:G,setBgOpacity:z,done:L}}(),bc=function(){function f(b){M.css({zIndex:450}),b?a(document).bind("touchmove.jcrop"
      ,k).bind("touchend.jcrop",l):e&&a(document).bind("mousemove.jcrop",h).bind("mouseup.jcrop",i)}function g(){M.css({zIndex:290}),a(document).unbind(".jcrop")}function h(a){return b(m(a)),!1}function i(a){return a.preventDefault(),a.stopPropagation(),W&&(W=!1,c(m(a)),bb.isAwake()&&d.onSelect.call(bs,u(_.getFixed())),g(),b=function(){},c=function(){}),!1}function j(a,d,e){return W=!0,b=a,c=d,f(e),!1}function k(a){return b(m(Z.cfilter(a))),!1}function l(a){return i(Z.cfilter(a))}function n(a){M.css("cursor",a)}var b=function(){},c=function(){},e=d.trackDocument;return e||M.mousemove(h).mouseup(i).mouseout(i),D.before(M),{activateHandlers:j,setCursor:n}}(),bd=function(){function e(){d.keySupport&&(b.show(),b.focus())}function f(a){b.hide()}function g(a,b,c){d.allowMove&&(_.moveOffset([b,c]),bb.updateVisible(!0)),a.preventDefault(),a.stopPropagation()}function i(a){if(a.ctrlKey||a.metaKey)return!0;Y=a.shiftKey?!0:!1;var b=Y?10:1;switch(a.keyCode){case 37:g(a,-b,0);break;case 39:g(a,b,0);break;case 38:g(a,0,-b);break;
    case 40:g(a,0,b);break;case 27:d.allowSelect&&bb.release();break;case 9:return!0}return!1}var b=a('<input type="radio" />').css({position:"fixed",left:"-120px",width:"12px"}).addClass("jcrop-keymgr"),c=a("<div />").css({position:"absolute",overflow:"hidden"}).append(b);return d.keySupport&&(b.keydown(i).blur(f),h||!d.fixedSupport?(b.css({position:"absolute",left:"-20px"}),c.append(b).insertBefore(D)):b.insertBefore(D)),{watchKeys:e}}();Z.support&&M.bind("touchstart.jcrop",Z.newSelection),J.hide(),br(!0);var bs={setImage:bp,animateTo:bf,setSelect:bg,setOptions:bk,tellSelect:bi,tellScaled:bj,setClass:be,disable:bl,enable:bm,cancel:bn,release:bb.release,destroy:bo,focus:bd.watchKeys,getBounds:function(){return[E*T,F*U]},getWidgetSize:function(){return[E,F]},getScaleFactor:function(){return[T,U]},getOptions:function(){return d},ui:{holder:G,selection:K}};return g&&G.bind("selectstart",function(){return!1}),A.data("Jcrop",bs),bs},a.fn.Jcrop=function(b,c){var d;return this.each(function(){if(a(this).data("Jcrop")){if(
      b==="api")return a(this).data("Jcrop");a(this).data("Jcrop").setOptions(b)}else this.tagName=="IMG"?a.Jcrop.Loader(this,function(){a(this).css({display:"block",visibility:"hidden"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d)}):(a(this).css({display:"block",visibility:"hidden"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d))}),this},a.Jcrop.Loader=function(b,c,d){function g(){f.complete?(e.unbind(".jcloader"),a.isFunction(c)&&c.call(f)):window.setTimeout(g,50)}var e=a(b),f=e[0];e.bind("load.jcloader",g).bind("error.jcloader",function(b){e.unbind(".jcloader"),a.isFunction(d)&&d.call(f)}),f.complete&&a.isFunction(c)&&(e.unbind(".jcloader"),c.call(f))},a.Jcrop.defaults={allowSelect:!0,allowMove:!0,allowResize:!0,trackDocument:!0,baseClass:"jcrop",addClass:null,bgColor:"black",bgOpacity:.6,bgFade:!1,borderOpacity:.4,handleOpacity:.5,handleSize:null,aspectRatio:0,keySupport:!0,createHandles:["n","s","e","w","nw","ne","se","sw"],createDragbars:["n","s","e","w"],createBorders:["n","s","e","w"],drawBorders:!0,dragEdges
      :!0,fixedSupport:!0,touchSupport:null,shade:null,boxWidth:0,boxHeight:0,boundary:2,fadeTime:400,animationDelay:20,swingSpeed:3,minSelect:[0,0],maxSize:[0,0],minSize:[0,0],onChange:function(){},onSelect:function(){},onDblClick:function(){},onRelease:function(){}}})(jQuery);



  jCrop = {

    crop: function (elem, minWidth, minHeight) {
      $('.jcrop_curtain').show();
      imgSrc = elem;
      $('.jcrop_curtain__item').html('<img id="target" src="/u/i/' + imgSrc + '" /><div class="jcrop_btn_wrap"><div onclick="jCrop.submit();" class="apply_changes">Применить</div><div class="close_jcrop" onclick="$(\'.jcrop_curtain\').hide();">Закрыть</div></div>');

      function showCoords(Coords)
      {
        CoordsX = Math.round(Coords.x);
        CoordsY = Math.round(Coords.y);
        CoordsX2 = Math.round(Coords.x2);
        CoordsY2 = Math.round(Coords.y2);
        CoordsW = Math.round(Coords.w);
        CoordsH = Math.round(Coords.h);
      };

      jQuery(function($) {
        $('#target').Jcrop({
          onSelect:    showCoords,
          bgColor:     'black',
          bgOpacity:   .2,
          setSelect:   [ minWidth, minHeight, 0, 0 ],
          aspectRatio: minWidth / minHeight
        });
      });
      var jcropHolder = $('.jcrop-holder'),
          jcropHolderHeight = jcropHolder.height(),
          windowHeight = $(window).height(),
          jcropHolderMarginTop = ($(window).height() - $('.jcrop-holder').height()) / 2;

      if(jcropHolderMarginTop > 0){
        $('.jcrop-holder').css('margin-top', jcropHolderMarginTop + 'px');
      };
    },

    submit: function () {
      coefficient =  $('#target').prop('width') / $('#target').width();
      $.ajax({
        type: 'GET',
        url: '/arm/jcrop',
        data: {
          x: CoordsX,
          y: CoordsY,
          h: CoordsH,
          w: CoordsW,
          c: coefficient,
          key: jcropKey,
          src: imgSrc
        },
        success: function (data) {
          if(data == 'small'){
            alert('Выбрана слишком маленькая область!');
          }else if(data == 'ok'){
            $('.uploader_preview__item').find('img').attr('src', '/u/i/' + imgSrc.replace('.', '-M.'))
            $('.jcrop_curtain').hide();
          }
        }
      });
    }
  };









  
  $(window).resize(function(){
    $('.lui_form_layer').each(function(k, layout){
      layout = $(layout);
      var lui__scroller_class = layout.find('.lui__scroller_class');
      lui__scroller_class.outerHeight(layout.outerHeight() - lui__scroller_class.offset().top + 0);
    });
  }).resize();

});
