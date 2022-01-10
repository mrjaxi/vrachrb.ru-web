


/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '; path=/';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
/*
 * JQuery URL Parser plugin
 * Developed and maintanined by Mark Perkins, mark@allmarkedup.com
 * Source repository: https://github.com/allmarkedup/jQuery-URL-Parser
 * Licensed under an MIT-style license. See https://github.com/allmarkedup/jQuery-URL-Parser/blob/master/LICENSE for details.
 */ 
;(function($, undefined) {
    
    var tag2attr = {
        a       : 'href',
        img     : 'src',
        form    : 'action',
        base    : 'href',
        script  : 'src',
        iframe  : 'src',
        link    : 'href'
    },
    
  key = ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","fragment"], // keys available to query
  
  aliases = { "anchor" : "fragment" }, 

  parser = {
    strict  : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,  
    loose   :  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ 
  },
  
  querystring_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g, 
  
  fragment_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g; 
  
  function parseUri( url, strictMode )
  {
    var str = decodeURI( url ),
        res   = parser[ strictMode || false ? "strict" : "loose" ].exec( str ),
        uri = { attr : {}, param : {}, seg : {} },
        i   = 14;
    
    while ( i-- )
    {
      uri.attr[ key[i] ] = res[i] || "";
    }

    
    uri.param['query'] = {};
    uri.param['fragment'] = {};
    
    uri.attr['query'].replace( querystring_parser, function ( $0, $1, $2 ){
      if ($1)
      {
        uri.param['query'][$1] = $2;
      }
    });
    
    uri.attr['fragment'].replace( fragment_parser, function ( $0, $1, $2 ){
      if ($1)
      {
        uri.param['fragment'][$1] = $2;
      }
    });

    
        uri.seg['path'] = uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');
        
        uri.seg['fragment'] = uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');

        
        uri.attr['base'] = uri.attr.host ? uri.attr.protocol+"://"+uri.attr.host + (uri.attr.port ? ":"+uri.attr.port : '') : '';
        
    return uri;
  };
  
  function getAttrName( elm )
  {
    var tn = elm.tagName;
    if ( tn !== undefined ) return tag2attr[tn.toLowerCase()];
    return tn;
  }
  
  $.fn.url = function( strictMode )
  {
      var url = '';
      
      if ( this.length )
      {
          url = $(this).attr( getAttrName(this[0]) ) || '';
      }
      
        return $.url( url, strictMode );
  };
  
  $.url = function( url, strictMode )
  {
      if ( arguments.length === 1 && url === true )
        {
            strictMode = true;
            url = undefined;
        }
        strictMode = strictMode || false;
        url = url || window.location.toString();
        return {
            data : parseUri(url, strictMode),
            attr : function( attr )
            {
                attr = aliases[attr] || attr;
                return attr !== undefined ? this.data.attr[attr] : this.data.attr;
            },
            param : function( param )
            {
                return param !== undefined ? this.data.param.query[param] : this.data.param.query;
            },
            fparam : function( param )
            {
                return param !== undefined ? this.data.param.fragment[param] : this.data.param.fragment;
            },
            segment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.path;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.path.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.path[seg];                    
                }
            },
            fsegment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.fragment;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.fragment.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.fragment[seg];                    
                }
            }
            
        };
        
  };
  
})(jQuery);






/**контроль за формой
 *
 */
(function($) {
  var methods = {
    /**
     *initialization
     * @param {Object} options
     * @returns {jQuery}
     */
    init: function(options) {
      var settings = $.extend({
        insert_in_form: 1,
        input_name: 'changed_state',
        exclude: [],
        debug_mode: 0,
        ignore: null,
        if_changed: function() {
          return true;
        },
        save_state_history: 1,
        state_history_length: 10,
        controlling_attr: 'name',
        onAddField: null,
        onChange: null
      }, options);

      this.state_form('init_state', settings);

      return this;
    },
    /**
     * initialization first states
     * @param {Object} settings
     */
    init_state: function(settings) {
      this.each(function() {
        var $this = $(this);
        $this.submit($this.state_form('on_submit', settings));
        $this.data({
          settings: settings
        });

        var els = $(':input', $this).not('[type="button"],[type="submit"]');
        if(settings.ignore)
        {
          els = els.not(settings.ignore);
        }

        els.each(function() {
          var $$this = $(this);
          $this.state_form('add_field', $$this, settings);
        });
      });
    },
    /**
     * add field to controlling set
     * @param {string|object} field jquery selector or jquery object or html object
     * @param {type} settings object or undefined
     * @returns {undefined}
     */
    add_field: function(field, settings){

      var $this = $(field);
      settings = settings || this.state_form('get_settings');

      if(!settings || $this.data('state'))
      {
        return;
      }

      var tmp = {
        state: {
          element_name: null,
          first_val: null,
          raw_text_first: null,
          curent_val: null,
          raw_text_last: null,
          selected: false
        }
      };

      $this.state_form('set_value', tmp, settings);
      $this.state_form('set_name', tmp, settings);
      /**@todo какая-то магия вешать обработчик на change, что бы вызвать свой триггер,
       * надо подумать как от этого уйти*/
      $this.change($this.state_form('call_change'));
      $this.on('state_form.change', $this.state_form('change_state'));
      if(settings.onChange)
      {
        //биндим контекст
        $this.on('state_form.change', settings.onChange.bind($this));
      }

      $this.data(tmp);

      if(settings.onAddField)
      {
        settings.onAddField($this);
      }
    },
    /**
     * remove field from controlling set
     * @param {string|object} field jquery selector or jquery object or html object
     * @returns {undefined}
     */
    remove_field: function(field){
      var $this = $(field);
      $this.removeData('state');
      $this.off('state_form.change');
      /**@todo  удалить только назначенный плагином обработчик не получается ни одним из способов
       * $this.off('change', '**', $this.state_form('call_change'));
       * $this.unbind('change', $this.state_form('call_change'));
       * никакого эффекта не дают поэтому пойдём тёмным путём*/
      var ev = $._data($this[0], "events");
      var h = $this.state_form('call_change').toString();
      if(ev.change)
      {
        for(var i in ev.change)
        {
          if(isNaN(i))
          {
            continue;
          }
          if(ev.change[i].handler.toString() === h)
          {
            delete ev.change[i];
            ev.change.length--;
            break;
          }
        }
      }

      $this.removeAttr('data-state-is_changed');
    },
    /**
     * set name for field
     * @param {Object} tmp
     * @param {Object} settings
     * @returns {void}
     */
    set_name: function(tmp, settings) {
      if(void 0 !== this.attr(settings.controlling_attr))
      {
        if(settings.debug_mode)
        {
          var count = $('[' + settings.controlling_attr + '="' + this.attr(settings.controlling_attr) + '"]').size();
          if(count > 1)
          {
            window.console.warn('Внимание: атрибут ' + settings.controlling_attr + ' найден у более чем одного элемента');
            window.console.debug(this);
          }
        }

        tmp.state.element_name = this.attr(settings.controlling_attr);
      }
      else if(void 0 !== this.attr('id'))
      {
        tmp.state.element_name = this.attr('id');
      }
      else
      {
        if(settings.debug_mode)
        {
          window.console.warn('Внимание: у элемента нет имени! (WARNING: element has no name!)');
          window.console.debug(this);
        }
      }
    },
    /**
     * set value for field
     * @param {Object} tmp
     * @param {Object} settings
     * @returns {void}
     */
    set_value: function(tmp, settings) {
      var _this = this[0];
      switch(_this.tagName)
      {
        case 'INPUT':
          if(_this.type == 'checkbox' || _this.type == 'radio')
          {
            if(void 0 !== this.attr('value'))
            {
              if(_this.type != 'radio')
              {
                if(this.is(':checked'))
                {
                  tmp.state.first_val = this.val();
                }
              }
              else
              {
                if(this.is(':checked'))
                {
                  tmp.state.selected = true;
                }
                tmp.state.first_val = this.val();
              }
            }
            else
            {
              tmp.state.first_val = this.is(':checked');
            }
          }
          else if (void 0 === this.attr('value'))
          {
            if(settings.debug_mode)
            {
              window.console.warn('Внимание: у элемента нет атрибута value! (WARNING: element has no attr value!)');
              window.console.debug(_this);
            }
          }
          else
          {
            tmp.state.first_val = this.val();
          }

          break;
        case 'TEXTAREA':
          tmp.state.first_val = this.val();
          break;
        case 'SELECT':
          tmp.state.first_val = this.val();
          tmp.state.raw_text_first = this.find('option:selected').text();
          break;
      }
    },
    /**
     * call on form submit
     * @param {Object} settings
     * @returns {Function}
     */
    on_submit: function(settings) {
      return function() {
        var $this = $(this);
        if($this.state_form('is_changed'))
        {
          if(settings.insert_in_form)
          {
            var input = $('<input type="hidden" name="' + settings.input_name + '">');
            $this.append(input);
            input.val(JSON.stringify($this.state_form('get_changes')));
          }
          else
          {
            $('input[name="changed_state"]', $this).remove();
          }

          return settings.if_changed.call($this);
        }

        return true;
      };
    },
    /**
     * check changes
     * @returns {Boolean}
     */
    is_changed: function() {
      return (this.state_form('get_changes').length ? true : false);
    },
    /**
     * returns changes array
     * @returns {Array}
     */
    get_changes: function() {
      var changes = [];
      var opt = this.data().settings;
      if(opt)
      {
        $('[data-state-is_changed]', this).each(function() {
          var d = $(this).data().state;
          if($.inArray(d.element_name, opt.exclude) === -1)
          {
            changes.push(d);
          }
        });

        //отдельно обрабатываем скрытые поля
        //так как change у них не произойдёт
        //и исключаем те, у которых он вызван руками
        $('input[type="hidden"]', this).not('[data-state-is_changed]').each(function() {
          var $this = $(this);
          var d = $this.data();
          if(typeof d === 'object' && d.hasOwnProperty('state'))
          {
            var data = d.state;
            if($.inArray(data.element_name, opt.exclude) === -1)
            {
              if(data.first_val != $this.val())
              {
                changes.push(data);
              }
            }
          }
        });
      }

      return changes;
    },
    call_change: function(){
      return function(){
        $(this).trigger('state_form.change');
      };
    },
    /**
     * call on event change control
     * @returns {Function}
     */
    change_state: function() {
      return function() {
        var $this = $(this);
        var data = $this.data();
        var val = $this.val();

        if(this.type == 'checkbox' || this.type == 'radio')
        {
          if($this.is(':checked') && void 0 !== $this.attr('value'))
          {
            data.state.curent_val = val;
          }
          else
          {
            data.state.curent_val = val =  $this.is(':checked');
          }
        }
        else
        {
          data.state.curent_val = val;
          if(this.tagName == 'SELECT')
          {
            data.state.raw_text_last = $this.find('option:selected').text();
          }
        }

        if(val === null)
        {
          val = '';
        }

        if(data.state.first_val === null)
        {
          data.state.first_val = '';
        }

        if(val != data.state.first_val)
        {
          $this.attr('data-state-is_changed', '1');
        }
        else if(this.type == 'radio')
        {
          if($this.is(':checked') && !data.state.selected)
          {
            $this.attr('data-state-is_changed', '1');
          }
          else
          {
            var $this = $(this);
            var settings = $this.parents('form').state_form('get_settings');
            var context = $this.parents('form');
            $('[' + settings.controlling_attr + '="' + $this.attr(settings.controlling_attr) + '"]', context).removeAttr('data-state-is_changed');
          }
        }
        else
        {
          $this.removeAttr('data-state-is_changed');
        }
      };
    },
    /**
     * save state for field(s) or form
     * @param {String} key key for state
     * @returns {jQuery}
     */
    save_state: function(key) {

      var field = false;
      var settings = this.state_form('get_settings');

      //если функция вызвана в контексте поля формы
      if(this[0].tagName != 'FORM')
      {
        field = this;
      }

      if(settings.save_state_history)
      {
        this.state_form('create_snapshot', key);
      }

      if(field )
      {
        var data = field.data();
        if(typeof data === 'object' && data.hasOwnProperty('state'))
        {
          data.state.first_val = field.val();
          field.state_form('set_value', data, settings);
          field.removeAttr('data-state-is_changed');
        }
      }
      else
      {
        $(':input', this).not('[type="button"],[type="submit"]').each(function() {
          var $$this = $(this);
          var data = $$this.data();
          if(typeof data === 'object' && data.hasOwnProperty('state'))
          {
            data.state.first_val = $$this.val();
            $$this.state_form('set_value', data, settings);
            $$this.removeAttr('data-state-is_changed');
          }
        });

      }

      return this;
    },
    /**
     * return init form settings
     * @returns {Object}
     */
    get_settings: function() {
      var settings = {};

      if(this[0].tagName != 'FORM')
      {
        settings = $(this[0].form).data().settings;
      }
      else
      {
        settings = this.data().settings;
      }

      return settings;
    },
    /**
     * create snapshot for elements
     * @param {string} key
     * @returns {void}
     */
    create_snapshot: function(key) {
      var curent_state = {};
      var hist = [];

      if(typeof window.localStorage.form_state_snapshot !== 'undefined')
      {
        hist = JSON.parse(window.localStorage.form_state_snapshot);
      }

      key = key || hist.length;

      var fields = [];
      var context = null;
      var max_length = 0;
      var settings = this.state_form('get_settings');

      if(this[0].tagName == 'FORM')
      {
        context = $(':input', this).not('[type="button"],[type="submit"]');
        max_length = settings.state_history_length;
      }
      else
      {
        context = this;
        max_length = settings.state_history_length;
      }
      $(context).not('[type="button"],[type="submit"]').each(function() {
        var data = $(this).data();
        if(typeof data === 'object' && data.hasOwnProperty('state'))
        {
          fields.push(data.state);
        }
      });

      curent_state[key] = fields;
      var ex = false;
      for(var i in hist)
      {
        if(hist[i].hasOwnProperty(key))
        {
          hist[i] = curent_state;
          ex = true;
          break;
        }
      }
      if(!ex)
      {
        if(hist.length >= max_length)
        {
          hist.shift();
        }
        hist.push(curent_state);
      }

      window.localStorage.form_state_snapshot = JSON.stringify(hist);
      if(this[0].tagName == 'FORM')
      {
        window.localStorage.form_state_last_form_snapshot = JSON.stringify(curent_state);
      }
    },
    /**
     * restore form state by key or last saved state
     * @param {String} key key for restore
     * @returns {jQuery}
     */
    restore_state: function(key) {
      var hist = null;

      if(key)
      {
        hist = this.state_form('get_history', key);
      }
      else
      {
        if(this[0].tagName == 'FORM')
        {
          if(typeof window.localStorage.form_state_snapshot !== 'undefined')
          {
            hist = JSON.parse(window.localStorage.form_state_last_form_snapshot);
          }
        }
        else
        {
          hist = this.state_form('find_state');
        }
      }

      if(hist)
      {
        var settings = this.state_form('get_settings');

        for(var k in hist)
        {
          for(var i in hist[k])
          {
            var old = hist[k][i];
            var el_name = old.element_name;
            var el = null;
            if($('[' + settings.controlling_attr + '="' + el_name + '"]').size())
            {
              el = $('[' + settings.controlling_attr + '="' + el_name + '"]');
            }
            else if($('#' + el_name).size())
            {
              el = $('#' + el_name);
            }
            else
            {
              if(settings.debug_mode)
              {
                window.console.warn('Внимание: поле не найдено в форме! (WARNING: field not found in the form!)');
                window.console.debug(this);
              }
            }

            var data  = el.data().state;
            data.curent_val = old.curent_val;
            //@todo не понятно какое поведение правильное
            //с одной стороны при восстановлении состояния
            //должны восстанавливаться и изменения с другой
            //можем иметь дело с формой после обновления страницы,
            //тогда изменений быть не должно
            data.first_val = old.curent_val;
            //data.first_val = old.first_val;
            data.raw_text_first = old.raw_text_first;
            data.raw_text_last = old.raw_text_last;
            data.selected = old.selected;
            if(el[0].type === 'radio')
            {
              el.each(function() {
                var $this = $(this);
                var val = $this.val();

                if(void 0 !== this.value)
                {
                  if(old.curent_val !== null && val == old.curent_val)
                  {
                    if(old.selected)
                    {
                      $this.attr('checked', 'cheked').change();
                    }

                    $this.data().state.first_val = old.curent_val;
                    $this.data().state.curent_val = old.curent_val;
                  }
                }

              });
            }
            else if(el[0].type === 'checkbox')
            {
              if(old.curent_val === false)
              {
                el.removeAttr('checked').change();
              }
              else
              {
                el.attr('checked', 'cheked').change();
              }
            }
            else
            {
              if(old.curent_val !== null)
              {
                el.val(old.curent_val).change();
              }
            }
          }
        }
      }
      return this;
    },
    /**
     * returns history by key, context
     * or all history if key == state_form_all
     * @param {String} key
     * @returns {Array|Object}
     */
    get_history: function(key) {
      key = key || false;
      var hist = [];
      var state = {};

      if(typeof window.localStorage.form_state_snapshot !== 'undefined')
      {
        hist = JSON.parse(window.localStorage.form_state_snapshot);
      }

      if(key)
      {
        if(key == 'state_form_all')
        {
          state = hist;
        }
        else
        {
          for(var i in hist)
          {
            if(hist[i].hasOwnProperty(key))
            {
              state = hist[i];
              break;
            }
          }
        }
      }
      else
      {
        state = hist.pop();
      }

      return state;
    },
    /**
     * find last state by context
     * @returns {Object}
     */
    find_state: function() {
      var names = [];
      var state = {};
      var settings = this.parents('form').state_form('get_settings');
      this.not('[type="button"],[type="submit"]').each(function() {
        var $this = $(this);
        if(void 0 !== $this.attr(settings.controlling_attr))
        {
          names.push(this.attr(settings.controlling_attr));
        }
        else if(void 0 !== this.id)
        {
          names.push(this.id);
        }

      });

      names = names.join(',');
      var hist = this.state_form('get_history', 'state_form_all');
      hist.reverse();

      top:
      for(var i in hist)
      {
        for(var j in hist[i])
        {
          var hist_names = [];
          for(var k in hist[i][j])
          {
            hist_names.push(hist[i][j][k]['element_name']);
          }

          hist_names = hist_names.join(',');

          if(hist_names == names)
          {
            state = hist[i];
            break top;
          }
        }
      }

      return state;

    },
    /**
     * return true if object is jQuery
     * @param {Object} obj
     * @returns {Boolean}
     */
    is_jQuery : function(obj) {
      return obj!=null && obj.constructor === jQuery;
    }
  };

  $.fn.state_form = function(method) {

    if (methods[method])
    {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    else if (typeof method === 'object' || !method)
    {
      return methods.init.apply(this, arguments);
    }
    else
    {
      $.error('Метод с именем ' + method + ' не существует для jQuery');
    }

  };
})(jQuery);







































/*!
* SortedList - jQuery Plugin
* SortedList is a jQuery plugin to sort list the way you want
*
* Examples and documentation at: https://github.com/Lutrasoft/SortedList
*
* Copyright (c) 2010-2013 - Lutrasoft
*
* Version: 0.0.3
* Requires: jQuery v1.3.4+
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*/
function SortedList( domUL, s )
{
    var _me = this,
        _ul = $( domUL);
    
  _me.settings = s;

    _me.init = function () {
        // Set LI index
        _ul.children( _me.settings.selector ).each( function () {
      var t = $( this );
            t.data( "al-index", t.index() );
        });
    
    // Order now
    _me.order();
    }

    _me.order = function () {
        _ul.append(
            _ul.find( _me.settings.selector ).sort(function (a, b) {
        var i, r;
                for( i=0 ; i<_me.settings.sort.length ; i++ )
        {
          r = _me.handleSort( i, $(a), $(b) )
          if( r ) { return r; }
        }
        
        return 0;
            })
        );
    }
  
  _me.handleSort = function( index, a, b )
  {
    var item = _me.settings.sort[ index ], k, r, ar, br;
    switch( typeof item )
    {
      case "function":
        ar = item( a );
        br = item( b );
        
        return typeof ar == "object" ? _me.handle( ar.data, br.data, ar.order  ) : _me.handle( ar, br  );
        
      case "object":
        var keys = _me.getKeys( item ),
          sort = keys[ 0 ],
          item = item[ sort ];
        for( k in item )
        {
          if( typeof a[ k ] == "function" )
          {
            // javascrip
            r = _me.handle( a[ k ]( item[ k ] ), b[ k ]( item[ k ] ), sort );            
            if( r ){ return r; }
          }
        }
        break;
    }
  }
  
  _me.handle = function( a, b, s )
  {
    return (a == b ? 0 : a > b ? -1 : 1) * (s == "desc" ? 1 : -1);
  }

  _me.getKeys = function( o )
  {  
    var k = [], i;
    for( i in o ) k.push( i );
    return k;
  }
    _me.init();
}
$.sortedList = {
  defaults : {
    selector : "li",
    sort : [
      { asc : { data : "al-index" } }
    ]
  }
};

$.fn.sortedList = function( settings, value ){
    return this.each( function(){
    var _this = this,
      _$this = $( _this ),
      t, sl;
      
    if( _$this.data( "al" ) )
    {
      sl = _$this.data("al");
      if( value )
      {
        sl.settings[ settings ] = value;
      }
      else
      {
        // Call function or return setting
        t = sl[ settings ];
        return typeof t == "function" ? t( ) : sl.settings[ settings ];
      }
    }
    else
    {
      _$this.data( "al", new SortedList( _this, $.extend( { }, $.sortedList.defaults, settings ) ) );
    }
    } );
};






/*!
 PowerTip - v1.2.0 - 2013-04-03
 http://stevenbenner.github.com/jquery-powertip/
 Copyright (c) 2013 Steven Benner (http://stevenbenner.com/).
 Released under MIT license.
 https://raw.github.com/stevenbenner/jquery-powertip/master/LICENSE.txt
*/
(function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){function t(){var t=this;t.top="auto",t.left="auto",t.right="auto",t.bottom="auto",t.set=function(o,n){e.isNumeric(n)&&(t[o]=Math.round(n))}}function o(e,t,o){function n(n,i){r(),e.data(v)||(n?(i&&e.data(m,!0),o.showTip(e)):(P.tipOpenImminent=!0,l=setTimeout(function(){l=null,s()},t.intentPollInterval)))}function i(n){r(),P.tipOpenImminent=!1,e.data(v)&&(e.data(m,!1),n?o.hideTip(e):(P.delayInProgress=!0,l=setTimeout(function(){l=null,o.hideTip(e),P.delayInProgress=!1},t.closeDelay)))}function s(){var i=Math.abs(P.previousX-P.currentX),s=Math.abs(P.previousY-P.currentY),r=i+s;t.intentSensitivity>r?o.showTip(e):(P.previousX=P.currentX,P.previousY=P.currentY,n())}function r(){l=clearTimeout(l),P.delayInProgress=!1}function a(){o.resetPosition(e)}var l=null;this.show=n,this.hide=i,this.cancel=r,this.resetPosition=a}function n(){function e(e,i,r,a,l){var p,c=i.split("-")[0],u=new t;switch(p=s(e)?n(e,c):o(e,c),i){case"n":u.set("left",p.left-r/2),u.set("bottom",P.windowHeight-p.top+l);break;case"e":u.set("left",p.left+l),u.set("top",p.top-a/2);break;case"s":u.set("left",p.left-r/2),u.set("top",p.top+l);break;case"w":u.set("top",p.top-a/2),u.set("right",P.windowWidth-p.left+l);break;case"nw":u.set("bottom",P.windowHeight-p.top+l),u.set("right",P.windowWidth-p.left-20);break;case"nw-alt":u.set("left",p.left),u.set("bottom",P.windowHeight-p.top+l);break;case"ne":u.set("left",p.left-20),u.set("bottom",P.windowHeight-p.top+l);break;case"ne-alt":u.set("bottom",P.windowHeight-p.top+l),u.set("right",P.windowWidth-p.left);break;case"sw":u.set("top",p.top+l),u.set("right",P.windowWidth-p.left-20);break;case"sw-alt":u.set("left",p.left),u.set("top",p.top+l);break;case"se":u.set("left",p.left-20),u.set("top",p.top+l);break;case"se-alt":u.set("top",p.top+l),u.set("right",P.windowWidth-p.left)}return u}function o(e,t){var o,n,i=e.offset(),s=e.outerWidth(),r=e.outerHeight();switch(t){case"n":o=i.left+s/2,n=i.top;break;case"e":o=i.left+s,n=i.top+r/2;break;case"s":o=i.left+s/2,n=i.top+r;break;case"w":o=i.left,n=i.top+r/2;break;case"nw":o=i.left,n=i.top;break;case"ne":o=i.left+s,n=i.top;break;case"sw":o=i.left,n=i.top+r;break;case"se":o=i.left+s,n=i.top+r}return{top:n,left:o}}function n(e,t){function o(){d.push(p.matrixTransform(u))}var n,i,s,r,a=e.closest("svg")[0],l=e[0],p=a.createSVGPoint(),c=l.getBBox(),u=l.getScreenCTM(),f=c.width/2,w=c.height/2,d=[],h=["nw","n","ne","e","se","s","sw","w"];if(p.x=c.x,p.y=c.y,o(),p.x+=f,o(),p.x+=f,o(),p.y+=w,o(),p.y+=w,o(),p.x-=f,o(),p.x-=f,o(),p.y-=w,o(),d[0].y!==d[1].y||d[0].x!==d[7].x)for(i=Math.atan2(u.b,u.a)*O,s=Math.ceil((i%360-22.5)/45),1>s&&(s+=8);s--;)h.push(h.shift());for(r=0;d.length>r;r++)if(h[r]===t){n=d[r];break}return{top:n.y+P.scrollTop,left:n.x+P.scrollLeft}}this.compute=e}function i(o){function i(e){e.data(v,!0),O.queue(function(t){s(e),t()})}function s(e){var t;if(e.data(v)){if(P.isTipOpen)return P.isClosing||r(P.activeHover),O.delay(100).queue(function(t){s(e),t()}),void 0;e.trigger("powerTipPreRender"),t=p(e),t&&(O.empty().append(t),e.trigger("powerTipRender"),P.activeHover=e,P.isTipOpen=!0,O.data(g,o.mouseOnToPopup),o.followMouse?a():(b(e),P.isFixedTipOpen=!0),O.fadeIn(o.fadeInTime,function(){P.desyncTimeout||(P.desyncTimeout=setInterval(H,500)),e.trigger("powerTipOpen")}))}}function r(e){P.isClosing=!0,P.activeHover=null,P.isTipOpen=!1,P.desyncTimeout=clearInterval(P.desyncTimeout),e.data(v,!1),e.data(m,!1),O.fadeOut(o.fadeOutTime,function(){var n=new t;P.isClosing=!1,P.isFixedTipOpen=!1,O.removeClass(),n.set("top",P.currentY+o.offset),n.set("left",P.currentX+o.offset),O.css(n),e.trigger("powerTipClose")})}function a(){if(!P.isFixedTipOpen&&(P.isTipOpen||P.tipOpenImminent&&O.data(T))){var e,n,i=O.outerWidth(),s=O.outerHeight(),r=new t;r.set("top",P.currentY+o.offset),r.set("left",P.currentX+o.offset),e=c(r,i,s),e!==I.none&&(n=u(e),1===n?e===I.right?r.set("left",P.windowWidth-i):e===I.bottom&&r.set("top",P.scrollTop+P.windowHeight-s):(r.set("left",P.currentX-i-o.offset),r.set("top",P.currentY-s-o.offset))),O.css(r)}}function b(t){var n,i;o.smartPlacement?(n=e.fn.powerTip.smartPlacementLists[o.placement],e.each(n,function(e,o){var n=c(y(t,o),O.outerWidth(),O.outerHeight());return i=o,n===I.none?!1:void 0})):(y(t,o.placement),i=o.placement),O.addClass(i)}function y(e,n){var i,s,r=0,a=new t;a.set("top",0),a.set("left",0),O.css(a);do i=O.outerWidth(),s=O.outerHeight(),a=k.compute(e,n,i,s,o.offset),O.css(a);while(5>=++r&&(i!==O.outerWidth()||s!==O.outerHeight()));return a}function H(){var e=!1;!P.isTipOpen||P.isClosing||P.delayInProgress||(P.activeHover.data(v)===!1||P.activeHover.is(":disabled")?e=!0:l(P.activeHover)||P.activeHover.is(":focus")||P.activeHover.data(m)||(O.data(g)?l(O)||(e=!0):e=!0),e&&r(P.activeHover))}var k=new n,O=e("#"+o.popupId);0===O.length&&(O=e("<div/>",{id:o.popupId}),0===d.length&&(d=e("body")),d.append(O)),o.followMouse&&(O.data(T)||(f.on("mousemove",a),w.on("scroll",a),O.data(T,!0))),o.mouseOnToPopup&&O.on({mouseenter:function(){O.data(g)&&P.activeHover&&P.activeHover.data(h).cancel()},mouseleave:function(){P.activeHover&&P.activeHover.data(h).hide()}}),this.showTip=i,this.hideTip=r,this.resetPosition=b}function s(e){return window.SVGElement&&e[0]instanceof SVGElement}function r(){P.mouseTrackingActive||(P.mouseTrackingActive=!0,e(function(){P.scrollLeft=w.scrollLeft(),P.scrollTop=w.scrollTop(),P.windowWidth=w.width(),P.windowHeight=w.height()}),f.on("mousemove",a),w.on({resize:function(){P.windowWidth=w.width(),P.windowHeight=w.height()},scroll:function(){var e=w.scrollLeft(),t=w.scrollTop();e!==P.scrollLeft&&(P.currentX+=e-P.scrollLeft,P.scrollLeft=e),t!==P.scrollTop&&(P.currentY+=t-P.scrollTop,P.scrollTop=t)}}))}function a(e){P.currentX=e.pageX,P.currentY=e.pageY}function l(e){var t=e.offset(),o=e[0].getBoundingClientRect(),n=o.right-o.left,i=o.bottom-o.top;return P.currentX>=t.left&&P.currentX<=t.left+n&&P.currentY>=t.top&&P.currentY<=t.top+i}function p(t){var o,n,i=t.data(y),s=t.data(H),r=t.data(k);return i?(e.isFunction(i)&&(i=i.call(t[0])),n=i):s?(e.isFunction(s)&&(s=s.call(t[0])),s.length>0&&(n=s.clone(!0,!0))):r&&(o=e("#"+r),o.length>0&&(n=o.html())),n}function c(e,t,o){var n=P.scrollTop,i=P.scrollLeft,s=n+P.windowHeight,r=i+P.windowWidth,a=I.none;return(n>e.top||n>Math.abs(e.bottom-P.windowHeight)-o)&&(a|=I.top),(e.top+o>s||Math.abs(e.bottom-P.windowHeight)>s)&&(a|=I.bottom),(i>e.left||e.right+t>r)&&(a|=I.left),(e.left+t>r||i>e.right)&&(a|=I.right),a}function u(e){for(var t=0;e;)e&=e-1,t++;return t}var f=e(document),w=e(window),d=e("body"),h="displayController",v="hasActiveHover",m="forcedOpen",T="hasMouseMove",g="mouseOnToPopup",b="originalTitle",y="powertip",H="powertipjq",k="powertiptarget",O=180/Math.PI,P={isTipOpen:!1,isFixedTipOpen:!1,isClosing:!1,tipOpenImminent:!1,activeHover:null,currentX:0,currentY:0,previousX:0,previousY:0,desyncTimeout:null,mouseTrackingActive:!1,delayInProgress:!1,windowWidth:0,windowHeight:0,scrollTop:0,scrollLeft:0},I={none:0,top:1,bottom:2,left:4,right:8};e.fn.powerTip=function(t,n){if(!this.length)return this;if("string"===e.type(t)&&e.powerTip[t])return e.powerTip[t].call(this,this,n);var s=e.extend({},e.fn.powerTip.defaults,t),a=new i(s);return r(),this.each(function(){var t,n=e(this),i=n.data(y),r=n.data(H),l=n.data(k);n.data(h)&&e.powerTip.destroy(n),t=n.attr("title"),i||l||r||!t||(n.data(y,t),n.data(b,t),n.removeAttr("title")),n.data(h,new o(n,s,a))}),s.manual||this.on({"mouseenter.powertip":function(t){e.powerTip.show(this,t)},"mouseleave.powertip":function(){e.powerTip.hide(this)},"focus.powertip":function(){e.powerTip.show(this)},"blur.powertip":function(){e.powerTip.hide(this,!0)},"keydown.powertip":function(t){27===t.keyCode&&e.powerTip.hide(this,!0)}}),this},e.fn.powerTip.defaults={fadeInTime:200,fadeOutTime:100,followMouse:!1,popupId:"powerTip",intentSensitivity:7,intentPollInterval:100,closeDelay:100,placement:"n",smartPlacement:!1,offset:10,mouseOnToPopup:!1,manual:!1},e.fn.powerTip.smartPlacementLists={n:["n","ne","nw","s"],e:["e","ne","se","w","nw","sw","n","s","e"],s:["s","se","sw","n"],w:["w","nw","sw","e","ne","se","n","s","w"],nw:["nw","w","sw","n","s","se","nw"],ne:["ne","e","se","n","s","sw","ne"],sw:["sw","w","nw","s","n","ne","sw"],se:["se","e","ne","s","n","nw","se"],"nw-alt":["nw-alt","n","ne-alt","sw-alt","s","se-alt","w","e"],"ne-alt":["ne-alt","n","nw-alt","se-alt","s","sw-alt","e","w"],"sw-alt":["sw-alt","s","se-alt","nw-alt","n","ne-alt","w","e"],"se-alt":["se-alt","s","sw-alt","ne-alt","n","nw-alt","e","w"]},e.powerTip={show:function(t,o){return o?(a(o),P.previousX=o.pageX,P.previousY=o.pageY,e(t).data(h).show()):e(t).first().data(h).show(!0,!0),t},reposition:function(t){return e(t).first().data(h).resetPosition(),t},hide:function(t,o){return t?e(t).first().data(h).hide(o):P.activeHover&&P.activeHover.data(h).hide(!0),t},destroy:function(t){return e(t).off(".powertip").each(function(){var t=e(this),o=[b,h,v,m];t.data(b)&&(t.attr("title",t.data(b)),o.push(y)),t.removeData(o)}),t}},e.powerTip.showTip=e.powerTip.show,e.powerTip.closeTip=e.powerTip.hide});



/**
 * @author Aleksey Martov (c) 2014 <a-martov@linber.ru>
 * The MIT License (MIT)
 * @version 0.1
 */
var PassGenJS = (function () {
    "use strict";

    var strLetters = 'qwertyuiopasdfghjklzxcvbnm';
    var strLettersUpper = strLetters.toUpperCase();
    var strNumbers = '0123456789';
    var strSymbols = '!@#$%^&*()_+-={}[];|?<>/"\'~';
    var defaultGenerateRecursion = 1;

    var strLettersArray = strLetters.split('');
    var strLettersUpperArray = strLettersUpper.split('');
    var strNumbersArray = strNumbers.split('');
    var strSymbolsArray = strSymbols.split('');

    /* Содержит объект параметров для генерации паролей */
    var scoreVariants = null;

    /**
     * Возвращает случайное число в диапазоне min-max (служебный метод)
     * @private
     * @param {Number} min Минимальное значение
     * @param {Number} max Максимальное значение
     * @returns {Number} Случайное число
     */
    function _getRandom(min, max) {
        var range = max - min + 1;
        return Math.floor(Math.random() * range) + min;
    }

    /**
     * Возвращает случайный элемент из массива arrayVariants (служебный метод)
     * @private
     * @param {Array} arrayVariants Массив элементов из которых будет выбран случайный
     * @returns {*} Значение выбранного элемента из массива arrayVariants
     */
    function _getRandomOfVariants(arrayVariants) {
        arrayVariants = arrayVariants ? arrayVariants : [];
        return arrayVariants.length > 0 ? arrayVariants[_getRandom(0, arrayVariants.length - 1)] : null;
    }

    /**
     * Генерирует и возвращает пароль (служебный метод)
     * @private
     * @param {Object} obj Объект, содержащий параметры для генерации пароля
     * obj.numbers {Number} Число цифр в пароле
     * obj.letters {Number} Число букв в пароле
     * obj.lettersUpper {Number} Число заглавных букв в пароле
     * obj.symbols {Number} Число символов в пароле
     * @returns {String} Сгенерированный пароль
     */
    function _generate(obj) {
        obj = obj ? obj : {};
        var symbols = obj.symbols ? obj.symbols : 0;
        var numbers = obj.numbers ? obj.numbers : 0;
        var letters = obj.letters ? obj.letters : 0;
        var lettersUpper = obj.lettersUpper ? obj.lettersUpper : 0;

        var totalLength = symbols + numbers + letters + lettersUpper;
        var result = '';

        var objGeneratedChars = {
            letters: 0,
            lettersUpper: 0,
            numbers: 0,
            symbols: 0
        };
        var objVariantsSource = {
            letters: true,
            lettersUpper: true,
            numbers: true,
            symbols: true
        };

        for (var i = 0; i < totalLength; i++) {

            if (objVariantsSource['letters'] && objGeneratedChars.letters == letters) {
                objVariantsSource['letters'] = false;
            }

            if (objVariantsSource['lettersUpper'] && objGeneratedChars.lettersUpper == lettersUpper) {
                objVariantsSource['lettersUpper'] = false;
            }

            if (objVariantsSource['numbers'] && objGeneratedChars.numbers == numbers) {
                objVariantsSource['numbers'] = false;
            }

            if (objVariantsSource['symbols'] && objGeneratedChars.symbols == symbols) {
                objVariantsSource['symbols'] = false;
            }

            var arrayVariantsSource = [];
            for (var key in objVariantsSource) {

                if (objVariantsSource[key]) {
                    arrayVariantsSource[arrayVariantsSource.length] = key;
                }
            }

            var typeChar = _getRandomOfVariants(arrayVariantsSource);
            var resultChar = '';

            switch (typeChar) {
                case 'letters':
                {
                    resultChar = strLettersArray[_getRandom(0, strLettersArray.length - 1)];
                    objGeneratedChars.letters++;
                    break;
                }
                case 'lettersUpper':
                {
                    resultChar = strLettersArray[_getRandom(0, strLettersArray.length - 1)].toUpperCase();
                    objGeneratedChars.lettersUpper++;
                    break;
                }
                case 'numbers':
                {
                    resultChar = strNumbersArray[_getRandom(0, strNumbersArray.length - 1)];
                    objGeneratedChars.numbers++;
                    break;
                }
                case 'symbols':
                {
                    resultChar = strSymbolsArray[_getRandom(0, strSymbolsArray.length - 1)];
                    objGeneratedChars.symbols++;
                    break;
                }
            }

            result += resultChar;
        }

        return result;
    }

    /**
     * Возвращает сгенерированный пароль
     * @private
     * @param {Object} obj Объект, содержащий параметры для генерации пароля
     * obj.score {Number} Число в диапазоне 1-4. Чем больше, тем надежнее пароль
     * obj.maxGenerateRecursion {Number} Сколько итераций использовать для нахождения более стойкого пароля
     * От 0 до n. Значение по умолчанию 6. Чем больше значение, тем больше времени требуется на генерацию
     * и получение более надежного пароля.
     * obj.numbers {Number} Число цифр в пароле
     * obj.letters {Number} Число букв в пароле
     * obj.lettersUpper {Number} Число заглавных букв в пароле
     * obj.symbols {Number} Число символов в пароле
     * @returns {String} Сгенерированный пароль
     */
    function _getPassword(obj) {
        var result = '';
        var resultEntropy = 0;
        var maxEntropy = 0;
        var generateRecursion = obj.maxGenerateRecursion !== undefined ? obj.maxGenerateRecursion : defaultGenerateRecursion;
        var objParams = {};

        if (!obj) {
            objParams = {
                letters: 4,
                lettersUpper: 2,
                numbers: 2,
                symbols: 1
            };
        } else {

            if (obj.score || obj.reliabilityPercent) {

                /* Если не генерировали параметры для генерации паролей, то генерируем их */
                if (!scoreVariants) {
                    scoreVariants = _generateScoreVariants();
                }

                var tmpScoreVariants = scoreVariants;
                var arrayVariants = [];

                if (obj.score !== undefined) {
                    obj.score = parseInt(obj.score);

                    if (obj.score == 0) {
                        return '';
                    }

                    if (obj.score > 4 || obj.score < 0) {
                        obj.score = 4;
                    }

                    for (var keyChars in tmpScoreVariants) {

                        if (tmpScoreVariants[keyChars].score == obj.score) {
                            arrayVariants[arrayVariants.length] = keyChars;
                        }
                    }
                } else if (obj.reliabilityPercent !== undefined) {
                    obj.reliabilityPercent = parseInt(obj.reliabilityPercent);
                    var arrayReliabilityPercentExist = [];

                    if (obj.reliabilityPercent == 0) {
                        return '';
                    }

                    if (obj.reliabilityPercent > 100 || obj.reliabilityPercent < 0) {
                        obj.reliabilityPercent = 100;
                    }

                    for (var keyChars in tmpScoreVariants) {
                        arrayReliabilityPercentExist[arrayReliabilityPercentExist.length] = tmpScoreVariants[keyChars].reliabilityPercent;

                        if (tmpScoreVariants[keyChars].reliabilityPercent == obj.reliabilityPercent) {
                            arrayVariants[arrayVariants.length] = keyChars;
                        }
                    }

                    if (!arrayVariants.length) {
                        arrayReliabilityPercentExist.sort(function(a, b){
                            return (a < b) ? -1 : (a > b) ? 1 : 0;
                        });
                        var arrayReliabilityPercentExistLength = arrayReliabilityPercentExist.length;
                        var reliabilityPercentExist = null;

                        for (var i = 0; i < arrayReliabilityPercentExistLength; i++) {

                            if (arrayReliabilityPercentExist[i] > obj.reliabilityPercent) {
                                reliabilityPercentExist = arrayReliabilityPercentExist[i];
                                break;
                            }
                        }

                        if (!reliabilityPercentExist) {
                            arrayReliabilityPercentExist.reverse();

                            for (var i = 0; i < arrayReliabilityPercentExistLength; i++) {

                                if (arrayReliabilityPercentExist[i] < obj.reliabilityPercent) {
                                    reliabilityPercentExist = arrayReliabilityPercentExist[i];
                                    break;
                                }
                            }
                        }

                        for (var keyChars in tmpScoreVariants) {

                            if (tmpScoreVariants[keyChars].reliabilityPercent == reliabilityPercentExist) {
                                arrayVariants[arrayVariants.length] = keyChars;
                            }
                        }
                    }
                }

                var randomVariant = _getRandomOfVariants(arrayVariants);

                if (randomVariant) {
                    randomVariant = randomVariant.split('-');

                    objParams = {
                        letters: randomVariant[1],
                        lettersUpper: randomVariant[3],
                        numbers: randomVariant[0],
                        symbols: randomVariant[2]
                    };
                }
            } else {
                objParams = {
                    letters: obj.letters,
                    lettersUpper: obj.lettersUpper,
                    numbers: obj.numbers,
                    symbols: obj.symbols
                };
            }
        }

        for (var i = 0; i <= generateRecursion; i++) {
            var tmpResult = _generate(objParams);
            resultEntropy = _getScore(tmpResult).entropy;

            if (!obj.score) {

                if (maxEntropy < resultEntropy) {
                    maxEntropy = resultEntropy;
                    result = tmpResult;
                }
            } else {

                if (obj.score == _getScore(tmpResult).score) {
                    result = tmpResult;
                    break;
                }
            }
        }

        if (!result) {
            result = tmpResult;
        }

        return result;
    }

    function _getScore(password) {
        var objLengthMany = {};
        var entropy = 0;
        var score = 0;
        var passwordArray = password.split('');

        for (var key in passwordArray) {

            if (!objLengthMany['strLetters'] && strLetters.indexOf(passwordArray[key]) > -1) {
                objLengthMany['strLetters'] = strLetters.length;
            } else if (!objLengthMany['strNumbers'] && strNumbers.indexOf(passwordArray[key]) > -1) {
                objLengthMany['strNumbers'] = strNumbers.length;
            } else if (!objLengthMany['strSymbols'] && strSymbols.indexOf(passwordArray[key]) > -1) {
                objLengthMany['strSymbols'] = strSymbols.length;
            } else if (!objLengthMany['strLettersUpper'] && strLettersUpper.indexOf(passwordArray[key]) > -1) {
                objLengthMany['strLettersUpper'] = strLettersUpper.length;
            }
        }

        var lengthMany = 0;
        for (var key in objLengthMany) {
            lengthMany += objLengthMany[key];
        }

        if (lengthMany) {
            entropy = Math.round(password.length * (Math.log(lengthMany) / Math.log(2)));
        } else {
            entropy = 0;
        }

        if (entropy > 0 && entropy < 56) {
            score = 1;
        } else if (entropy >= 56 && entropy < 64) {
            score = 2;
        } else if (entropy >= 64 && entropy < 128) {
            score = 3;
        } else if (entropy >= 128) {
            score = 4;
        }

        var reliability = entropy / (128 / 100);
        reliability = reliability < 100 ? reliability : 100;

        return {
            password: password,
            score: score,
            entropy: entropy,
            reliability: reliability,
            reliabilityPercent: Math.round(reliability)
        };
    }

    /**
     * Генерирует и возвращает объект параметров для последующей генерации паролей
     * @private
     * (будет больше вариантов параметров для генерации паролей)
     * @returns {Object}
     */
    function _generateScoreVariants() {
        var objResult = {};

        for (var i = 0; i < 6666; i++) {
            var strIndexIteration = i + '';

            if (i < 10) {
                strIndexIteration = '000' + i;
            } else if (i >= 10 && i < 100) {
                strIndexIteration = '00' + i;
            } else if (i >= 100 && i < 1000) {
                strIndexIteration = '0' + i;
            }

            var strIndexIterationArray = strIndexIteration.split('');

            if (parseInt(strIndexIterationArray[0]) + parseInt(strIndexIterationArray[1]) + parseInt(strIndexIterationArray[2]) + parseInt(strIndexIterationArray[3]) > 20) {
                continue;
            }

            var keyForObjResult = strIndexIterationArray.join('-');

            var result = strNumbers.substr(0, strIndexIterationArray[0]) + strLetters.substr(0, strIndexIterationArray[1])
                + strSymbols.substr(0, strIndexIterationArray[2]) + strLettersUpper.substr(0, strIndexIterationArray[3]);

            var score = _getScore(result);

            if (score.score == 0) {
                continue;
            }

            objResult[keyForObjResult] = {
                score: score.score,
                reliabilityPercent: score.reliabilityPercent
            }
        }

        return objResult;
    }

    return {
        /**
         * Обертка над _getPassword
         * @see _getPassword
         * @returns {String}
         */
        getPassword: function (params) {
            var result = _getPassword(params);
            return result;
        },

        /**
         * Обертка над _getScore
         * @see _getScore
         * @returns {Object}
         */
        getScore: function (password) {
            return _getScore(password);
        }
    };
})();


/*!
 * miniTip v1.5.3
 *
 * Updated: July 15, 2012
 * Requires: jQuery v1.3+
 *
 * (c) 2011, James Simpson
 * http://goldfirestudios.com
 *
 * Dual licensed under the MIT and GPL
 *
 * Documentation found at:
 * http://goldfirestudios.com/blog/81/miniTip-jQuery-Plugin
*/
(function(e){e.fn.miniTip=function(t){var n={title:"",content:!1,delay:300,anchor:"n",event:"hover",fadeIn:200,fadeOut:200,aHide:!0,maxW:"250px",offset:5,stemOff:0,doHide:!1},r=e.extend(n,t);e("#miniTip")[0]||e("body").append('<div id="miniTip"><div id="miniTip_t"></div><div id="miniTip_c"></div><div id="miniTip_a"></div></div>');var i=e("#miniTip"),s=e("#miniTip_t"),o=e("#miniTip_c"),u=e("#miniTip_a");return r.doHide?(i.stop(!0,!0).fadeOut(r.fadeOut),!1):this.each(function(){var t=e(this),n=r.content?r.content:t.attr("title");if(n!=""&&typeof n!="undefined"){window.delay=!1;var a=!1,f=!0;r.content||t.removeAttr("title"),r.event=="hover"?(t.hover(function(){i.removeAttr("click"),f=!0,l.call(this)},function(){f=!1,c()}),r.aHide||i.hover(function(){a=!0},function(){a=!1,setTimeout(function(){!f&&!i.attr("click")&&c()},20)})):r.event=="click"&&(r.aHide=!0,t.click(function(){return i.attr("click","t"),i.data("last_target")!==t?l.call(this):i.css("display")=="none"?l.call(this):c(),i.data("last_target",t),e("html").unbind("click").click(function(t){i.css("display")=="block"&&!e(t.target).closest("#miniTip").length&&(e("html").unbind("click"),c())}),!1}));var l=function(){r.show&&r.show.call(this,r),r.content&&r.content!=""&&(n=r.content),o.html(n),r.title!=""?s.html(r.title).show():s.hide(),r.render&&r.render(i),u.removeAttr("class"),i.hide().width("").width(i.width()).css("max-width",r.maxW);var a=t.is("area");if(a){var f,l=[],c=[],h=t.attr("coords").split(",");function p(e,t){return e-t}for(f=0;f<h.length;f++)l.push(h[f++]),c.push(h[f]);var d=t.parent().attr("name"),v=e("img[usemap=\\#"+d+"]").offset(),m=parseInt(v.left,10)+parseInt((parseInt(l.sort(p)[0],10)+parseInt(l.sort(p)[l.length-1],10))/2,10),g=parseInt(v.top,10)+parseInt((parseInt(c.sort(p)[0],10)+parseInt(c.sort(p)[c.length-1],10))/2,10)}else var g=parseInt(t.offset().top,10),m=parseInt(t.offset().left,10);var y=a?0:parseInt(t.outerWidth(),10),b=a?0:parseInt(t.outerHeight(),10),w=i.outerWidth(),E=i.outerHeight(),S=Math.round(m+Math.round((y-w)/2)),x=Math.round(g+b+r.offset+8),T=Math.round(w-16)/2-parseInt(i.css("borderLeftWidth"),10),N=0,C=m+y+w+r.offset+8>parseInt(e(window).width(),10),k=w+r.offset+8>m,L=E+r.offset+8>g-e(window).scrollTop(),A=g+b+E+r.offset+8>parseInt(e(window).height()+e(window).scrollTop(),10),O=r.anchor;if(k||r.anchor=="e"&&!C){if(r.anchor=="w"||r.anchor=="e")O="e",N=Math.round(E/2-8-parseInt(i.css("borderRightWidth"),10)),T=-8-parseInt(i.css("borderRightWidth"),10),S=m+y+r.offset+8,x=Math.round(g+b/2-E/2)}else if(C||r.anchor=="w"&&!k)if(r.anchor=="w"||r.anchor=="e")O="w",N=Math.round(E/2-8-parseInt(i.css("borderLeftWidth"),10)),T=w-parseInt(i.css("borderLeftWidth"),10),S=m-w-r.offset-8,x=Math.round(g+b/2-E/2);if(A||r.anchor=="n"&&!L){if(r.anchor=="n"||r.anchor=="s")O="n",N=E-parseInt(i.css("borderTopWidth"),10),x=g-(E+r.offset+8)}else if(L||r.anchor=="s"&&!A)if(r.anchor=="n"||r.anchor=="s")O="s",N=-8-parseInt(i.css("borderBottomWidth"),10),x=g+b+r.offset+8;r.anchor=="n"||r.anchor=="s"?w/2>m?(S=S<0?T+S:T,T=0):m+w/2>parseInt(e(window).width(),10)&&(S-=T,T*=2):L?(x+=N,N=0):A&&(x-=N,N*=2),u.css({"margin-left":(T>0?T:T+parseInt(r.stemOff,10)/2)+"px","margin-top":N+"px"}).attr("class",O),delay&&clearTimeout(delay),delay=setTimeout(function(){i.css({"margin-left":S+"px","margin-top":x+"px"}).stop(!0,!0).fadeIn(r.fadeIn)},r.delay)},c=function(){if(!r.aHide&&!a||r.aHide)delay&&clearTimeout(delay),delay=setTimeout(function(){h()},r.delay)},h=function(){!r.aHide&&!a||r.aHide?(i.stop(!0,!0).fadeOut(r.fadeOut),r.hide&&r.hide.call(this)):setTimeout(function(){c()},200)}}})}})(jQuery);


/*! Magnific Popup - v1.0.1 - 2015-12-30
 * http://dimsemenov.com/plugins/magnific-popup/
 * Copyright (c) 2015 Dmitry Semenov; */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):window.jQuery||window.Zepto)}(function(a){var b,c,d,e,f,g,h="Close",i="BeforeClose",j="AfterClose",k="BeforeAppend",l="MarkupParse",m="Open",n="Change",o="mfp",p="."+o,q="mfp-ready",r="mfp-removing",s="mfp-prevent-close",t=function(){},u=!!window.jQuery,v=a(window),w=function(a,c){b.ev.on(o+a+p,c)},x=function(b,c,d,e){var f=document.createElement("div");return f.className="mfp-"+b,d&&(f.innerHTML=d),e?c&&c.appendChild(f):(f=a(f),c&&f.appendTo(c)),f},y=function(c,d){b.ev.triggerHandler(o+c,d),b.st.callbacks&&(c=c.charAt(0).toLowerCase()+c.slice(1),b.st.callbacks[c]&&b.st.callbacks[c].apply(b,a.isArray(d)?d:[d]))},z=function(c){return c===g&&b.currTemplate.closeBtn||(b.currTemplate.closeBtn=a(b.st.closeMarkup.replace("%title%",b.st.tClose)),g=c),b.currTemplate.closeBtn},A=function(){a.magnificPopup.instance||(b=new t,b.init(),a.magnificPopup.instance=b)},B=function(){var a=document.createElement("p").style,b=["ms","O","Moz","Webkit"];if(void 0!==a.transition)return!0;for(;b.length;)if(b.pop()+"Transition"in a)return!0;return!1};t.prototype={constructor:t,init:function(){var c=navigator.appVersion;b.isIE7=-1!==c.indexOf("MSIE 7."),b.isIE8=-1!==c.indexOf("MSIE 8."),b.isLowIE=b.isIE7||b.isIE8,b.isAndroid=/android/gi.test(c),b.isIOS=/iphone|ipad|ipod/gi.test(c),b.supportsTransition=B(),b.probablyMobile=b.isAndroid||b.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),d=a(document),b.popupsCache={}},open:function(c){var e;if(c.isObj===!1){b.items=c.items.toArray(),b.index=0;var g,h=c.items;for(e=0;e<h.length;e++)if(g=h[e],g.parsed&&(g=g.el[0]),g===c.el[0]){b.index=e;break}}else b.items=a.isArray(c.items)?c.items:[c.items],b.index=c.index||0;if(b.isOpen)return void b.updateItemHTML();b.types=[],f="",c.mainEl&&c.mainEl.length?b.ev=c.mainEl.eq(0):b.ev=d,c.key?(b.popupsCache[c.key]||(b.popupsCache[c.key]={}),b.currTemplate=b.popupsCache[c.key]):b.currTemplate={},b.st=a.extend(!0,{},a.magnificPopup.defaults,c),b.fixedContentPos="auto"===b.st.fixedContentPos?!b.probablyMobile:b.st.fixedContentPos,b.st.modal&&(b.st.closeOnContentClick=!1,b.st.closeOnBgClick=!1,b.st.showCloseBtn=!1,b.st.enableEscapeKey=!1),b.bgOverlay||(b.bgOverlay=x("bg").on("click"+p,function(){b.close()}),b.wrap=x("wrap").attr("tabindex",-1).on("click"+p,function(a){b._checkIfClose(a.target)&&b.close()}),b.container=x("container",b.wrap)),b.contentContainer=x("content"),b.st.preloader&&(b.preloader=x("preloader",b.container,b.st.tLoading));var i=a.magnificPopup.modules;for(e=0;e<i.length;e++){var j=i[e];j=j.charAt(0).toUpperCase()+j.slice(1),b["init"+j].call(b)}y("BeforeOpen"),b.st.showCloseBtn&&(b.st.closeBtnInside?(w(l,function(a,b,c,d){c.close_replaceWith=z(d.type)}),f+=" mfp-close-btn-in"):b.wrap.append(z())),b.st.alignTop&&(f+=" mfp-align-top"),b.fixedContentPos?b.wrap.css({overflow:b.st.overflowY,overflowX:"hidden",overflowY:b.st.overflowY}):b.wrap.css({top:v.scrollTop(),position:"absolute"}),(b.st.fixedBgPos===!1||"auto"===b.st.fixedBgPos&&!b.fixedContentPos)&&b.bgOverlay.css({height:d.height(),position:"absolute"}),b.st.enableEscapeKey&&d.on("keyup"+p,function(a){27===a.keyCode&&b.close()}),v.on("resize"+p,function(){b.updateSize()}),b.st.closeOnContentClick||(f+=" mfp-auto-cursor"),f&&b.wrap.addClass(f);var k=b.wH=v.height(),n={};if(b.fixedContentPos&&b._hasScrollBar(k)){var o=b._getScrollbarSize();o&&(n.marginRight=o)}b.fixedContentPos&&(b.isIE7?a("body, html").css("overflow","hidden"):n.overflow="hidden");var r=b.st.mainClass;return b.isIE7&&(r+=" mfp-ie7"),r&&b._addClassToMFP(r),b.updateItemHTML(),y("BuildControls"),a("html").css(n),b.bgOverlay.add(b.wrap).prependTo(b.st.prependTo||a(document.body)),b._lastFocusedEl=document.activeElement,setTimeout(function(){b.content?(b._addClassToMFP(q),b._setFocus()):b.bgOverlay.addClass(q),d.on("focusin"+p,b._onFocusIn)},16),b.isOpen=!0,b.updateSize(k),y(m),c},close:function(){b.isOpen&&(y(i),b.isOpen=!1,b.st.removalDelay&&!b.isLowIE&&b.supportsTransition?(b._addClassToMFP(r),setTimeout(function(){b._close()},b.st.removalDelay)):b._close())},_close:function(){y(h);var c=r+" "+q+" ";if(b.bgOverlay.detach(),b.wrap.detach(),b.container.empty(),b.st.mainClass&&(c+=b.st.mainClass+" "),b._removeClassFromMFP(c),b.fixedContentPos){var e={marginRight:""};b.isIE7?a("body, html").css("overflow",""):e.overflow="",a("html").css(e)}d.off("keyup"+p+" focusin"+p),b.ev.off(p),b.wrap.attr("class","mfp-wrap").removeAttr("style"),b.bgOverlay.attr("class","mfp-bg"),b.container.attr("class","mfp-container"),!b.st.showCloseBtn||b.st.closeBtnInside&&b.currTemplate[b.currItem.type]!==!0||b.currTemplate.closeBtn&&b.currTemplate.closeBtn.detach(),b.st.autoFocusLast&&b._lastFocusedEl&&a(b._lastFocusedEl).focus(),b.currItem=null,b.content=null,b.currTemplate=null,b.prevHeight=0,y(j)},updateSize:function(a){if(b.isIOS){var c=document.documentElement.clientWidth/window.innerWidth,d=window.innerHeight*c;b.wrap.css("height",d),b.wH=d}else b.wH=a||v.height();b.fixedContentPos||b.wrap.css("height",b.wH),y("Resize")},updateItemHTML:function(){var c=b.items[b.index];b.contentContainer.detach(),b.content&&b.content.detach(),c.parsed||(c=b.parseEl(b.index));var d=c.type;if(y("BeforeChange",[b.currItem?b.currItem.type:"",d]),b.currItem=c,!b.currTemplate[d]){var f=b.st[d]?b.st[d].markup:!1;y("FirstMarkupParse",f),f?b.currTemplate[d]=a(f):b.currTemplate[d]=!0}e&&e!==c.type&&b.container.removeClass("mfp-"+e+"-holder");var g=b["get"+d.charAt(0).toUpperCase()+d.slice(1)](c,b.currTemplate[d]);b.appendContent(g,d),c.preloaded=!0,y(n,c),e=c.type,b.container.prepend(b.contentContainer),y("AfterChange")},appendContent:function(a,c){b.content=a,a?b.st.showCloseBtn&&b.st.closeBtnInside&&b.currTemplate[c]===!0?b.content.find(".mfp-close").length||b.content.append(z()):b.content=a:b.content="",y(k),b.container.addClass("mfp-"+c+"-holder"),b.contentContainer.append(b.content)},parseEl:function(c){var d,e=b.items[c];if(e.tagName?e={el:a(e)}:(d=e.type,e={data:e,src:e.src}),e.el){for(var f=b.types,g=0;g<f.length;g++)if(e.el.hasClass("mfp-"+f[g])){d=f[g];break}e.src=e.el.attr("data-mfp-src"),e.src||(e.src=e.el.attr("href"))}return e.type=d||b.st.type||"inline",e.index=c,e.parsed=!0,b.items[c]=e,y("ElementParse",e),b.items[c]},addGroup:function(a,c){var d=function(d){d.mfpEl=this,b._openClick(d,a,c)};c||(c={});var e="click.magnificPopup";c.mainEl=a,c.items?(c.isObj=!0,a.off(e).on(e,d)):(c.isObj=!1,c.delegate?a.off(e).on(e,c.delegate,d):(c.items=a,a.off(e).on(e,d)))},_openClick:function(c,d,e){var f=void 0!==e.midClick?e.midClick:a.magnificPopup.defaults.midClick;if(f||!(2===c.which||c.ctrlKey||c.metaKey||c.altKey||c.shiftKey)){var g=void 0!==e.disableOn?e.disableOn:a.magnificPopup.defaults.disableOn;if(g)if(a.isFunction(g)){if(!g.call(b))return!0}else if(v.width()<g)return!0;c.type&&(c.preventDefault(),b.isOpen&&c.stopPropagation()),e.el=a(c.mfpEl),e.delegate&&(e.items=d.find(e.delegate)),b.open(e)}},updateStatus:function(a,d){if(b.preloader){c!==a&&b.container.removeClass("mfp-s-"+c),d||"loading"!==a||(d=b.st.tLoading);var e={status:a,text:d};y("UpdateStatus",e),a=e.status,d=e.text,b.preloader.html(d),b.preloader.find("a").on("click",function(a){a.stopImmediatePropagation()}),b.container.addClass("mfp-s-"+a),c=a}},_checkIfClose:function(c){if(!a(c).hasClass(s)){var d=b.st.closeOnContentClick,e=b.st.closeOnBgClick;if(d&&e)return!0;if(!b.content||a(c).hasClass("mfp-close")||b.preloader&&c===b.preloader[0])return!0;if(c===b.content[0]||a.contains(b.content[0],c)){if(d)return!0}else if(e&&a.contains(document,c))return!0;return!1}},_addClassToMFP:function(a){b.bgOverlay.addClass(a),b.wrap.addClass(a)},_removeClassFromMFP:function(a){this.bgOverlay.removeClass(a),b.wrap.removeClass(a)},_hasScrollBar:function(a){return(b.isIE7?d.height():document.body.scrollHeight)>(a||v.height())},_setFocus:function(){(b.st.focus?b.content.find(b.st.focus).eq(0):b.wrap).focus()},_onFocusIn:function(c){return c.target===b.wrap[0]||a.contains(b.wrap[0],c.target)?void 0:(b._setFocus(),!1)},_parseMarkup:function(b,c,d){var e;d.data&&(c=a.extend(d.data,c)),y(l,[b,c,d]),a.each(c,function(a,c){if(void 0===c||c===!1)return!0;if(e=a.split("_"),e.length>1){var d=b.find(p+"-"+e[0]);if(d.length>0){var f=e[1];"replaceWith"===f?d[0]!==c[0]&&d.replaceWith(c):"img"===f?d.is("img")?d.attr("src",c):d.replaceWith('<img src="'+c+'" class="'+d.attr("class")+'" />'):d.attr(e[1],c)}}else b.find(p+"-"+a).html(c)})},_getScrollbarSize:function(){if(void 0===b.scrollbarSize){var a=document.createElement("div");a.style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",document.body.appendChild(a),b.scrollbarSize=a.offsetWidth-a.clientWidth,document.body.removeChild(a)}return b.scrollbarSize}},a.magnificPopup={instance:null,proto:t.prototype,modules:[],open:function(b,c){return A(),b=b?a.extend(!0,{},b):{},b.isObj=!0,b.index=c||0,this.instance.open(b)},close:function(){return a.magnificPopup.instance&&a.magnificPopup.instance.close()},registerModule:function(b,c){c.options&&(a.magnificPopup.defaults[b]=c.options),a.extend(this.proto,c.proto),this.modules.push(b)},defaults:{disableOn:0,key:null,midClick:!1,mainClass:"",preloader:!0,focus:"",closeOnContentClick:!1,closeOnBgClick:!0,closeBtnInside:!0,showCloseBtn:!0,enableEscapeKey:!0,modal:!1,alignTop:!1,removalDelay:0,prependTo:null,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" type="button" class="mfp-close">&#215;</button>',tClose:"Close (Esc)",tLoading:"Loading...",autoFocusLast:!0}},a.fn.magnificPopup=function(c){A();var d=a(this);if("string"==typeof c)if("open"===c){var e,f=u?d.data("magnificPopup"):d[0].magnificPopup,g=parseInt(arguments[1],10)||0;f.items?e=f.items[g]:(e=d,f.delegate&&(e=e.find(f.delegate)),e=e.eq(g)),b._openClick({mfpEl:e},d,f)}else b.isOpen&&b[c].apply(b,Array.prototype.slice.call(arguments,1));else c=a.extend(!0,{},c),u?d.data("magnificPopup",c):d[0].magnificPopup=c,b.addGroup(d,c);return d};var C,D,E,F="inline",G=function(){E&&(D.after(E.addClass(C)).detach(),E=null)};a.magnificPopup.registerModule(F,{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){b.types.push(F),w(h+"."+F,function(){G()})},getInline:function(c,d){if(G(),c.src){var e=b.st.inline,f=a(c.src);if(f.length){var g=f[0].parentNode;g&&g.tagName&&(D||(C=e.hiddenClass,D=x(C),C="mfp-"+C),E=f.after(D).detach().removeClass(C)),b.updateStatus("ready")}else b.updateStatus("error",e.tNotFound),f=a("<div>");return c.inlineElement=f,f}return b.updateStatus("ready"),b._parseMarkup(d,{},c),d}}});var H,I="ajax",J=function(){H&&a(document.body).removeClass(H)},K=function(){J(),b.req&&b.req.abort()};a.magnificPopup.registerModule(I,{options:{settings:null,cursor:"mfp-ajax-cur",tError:'<a href="%url%">The content</a> could not be loaded.'},proto:{initAjax:function(){b.types.push(I),H=b.st.ajax.cursor,w(h+"."+I,K),w("BeforeChange."+I,K)},getAjax:function(c){H&&a(document.body).addClass(H),b.updateStatus("loading");var d=a.extend({url:c.src,success:function(d,e,f){var g={data:d,xhr:f};y("ParseAjax",g),b.appendContent(a(g.data),I),c.finished=!0,J(),b._setFocus(),setTimeout(function(){b.wrap.addClass(q)},16),b.updateStatus("ready"),y("AjaxContentAdded")},error:function(){J(),c.finished=c.loadError=!0,b.updateStatus("error",b.st.ajax.tError.replace("%url%",c.src))}},b.st.ajax.settings);return b.req=a.ajax(d),""}}});var L,M=function(c){if(c.data&&void 0!==c.data.title)return c.data.title;var d=b.st.image.titleSrc;if(d){if(a.isFunction(d))return d.call(b,c);if(c.el)return c.el.attr(d)||""}return""};a.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:!0,tError:'<a href="%url%">The image</a> could not be loaded.'},proto:{initImage:function(){var c=b.st.image,d=".image";b.types.push("image"),w(m+d,function(){"image"===b.currItem.type&&c.cursor&&a(document.body).addClass(c.cursor)}),w(h+d,function(){c.cursor&&a(document.body).removeClass(c.cursor),v.off("resize"+p)}),w("Resize"+d,b.resizeImage),b.isLowIE&&w("AfterChange",b.resizeImage)},resizeImage:function(){var a=b.currItem;if(a&&a.img&&b.st.image.verticalFit){var c=0;b.isLowIE&&(c=parseInt(a.img.css("padding-top"),10)+parseInt(a.img.css("padding-bottom"),10)),a.img.css("max-height",b.wH-c)}},_onImageHasSize:function(a){a.img&&(a.hasSize=!0,L&&clearInterval(L),a.isCheckingImgSize=!1,y("ImageHasSize",a),a.imgHidden&&(b.content&&b.content.removeClass("mfp-loading"),a.imgHidden=!1))},findImageSize:function(a){var c=0,d=a.img[0],e=function(f){L&&clearInterval(L),L=setInterval(function(){return d.naturalWidth>0?void b._onImageHasSize(a):(c>200&&clearInterval(L),c++,void(3===c?e(10):40===c?e(50):100===c&&e(500)))},f)};e(1)},getImage:function(c,d){var e=0,f=function(){c&&(c.img[0].complete?(c.img.off(".mfploader"),c===b.currItem&&(b._onImageHasSize(c),b.updateStatus("ready")),c.hasSize=!0,c.loaded=!0,y("ImageLoadComplete")):(e++,200>e?setTimeout(f,100):g()))},g=function(){c&&(c.img.off(".mfploader"),c===b.currItem&&(b._onImageHasSize(c),b.updateStatus("error",h.tError.replace("%url%",c.src))),c.hasSize=!0,c.loaded=!0,c.loadError=!0)},h=b.st.image,i=d.find(".mfp-img");if(i.length){var j=document.createElement("img");j.className="mfp-img",c.el&&c.el.find("img").length&&(j.alt=c.el.find("img").attr("alt")),c.img=a(j).on("load.mfploader",f).on("error.mfploader",g),j.src=c.src,i.is("img")&&(c.img=c.img.clone()),j=c.img[0],j.naturalWidth>0?c.hasSize=!0:j.width||(c.hasSize=!1)}return b._parseMarkup(d,{title:M(c),img_replaceWith:c.img},c),b.resizeImage(),c.hasSize?(L&&clearInterval(L),c.loadError?(d.addClass("mfp-loading"),b.updateStatus("error",h.tError.replace("%url%",c.src))):(d.removeClass("mfp-loading"),b.updateStatus("ready")),d):(b.updateStatus("loading"),c.loading=!0,c.hasSize||(c.imgHidden=!0,d.addClass("mfp-loading"),b.findImageSize(c)),d)}}});var N,O=function(){return void 0===N&&(N=void 0!==document.createElement("p").style.MozTransform),N};a.magnificPopup.registerModule("zoom",{options:{enabled:!1,easing:"ease-in-out",duration:300,opener:function(a){return a.is("img")?a:a.find("img")}},proto:{initZoom:function(){var a,c=b.st.zoom,d=".zoom";if(c.enabled&&b.supportsTransition){var e,f,g=c.duration,j=function(a){var b=a.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),d="all "+c.duration/1e3+"s "+c.easing,e={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},f="transition";return e["-webkit-"+f]=e["-moz-"+f]=e["-o-"+f]=e[f]=d,b.css(e),b},k=function(){b.content.css("visibility","visible")};w("BuildControls"+d,function(){if(b._allowZoom()){if(clearTimeout(e),b.content.css("visibility","hidden"),a=b._getItemToZoom(),!a)return void k();f=j(a),f.css(b._getOffset()),b.wrap.append(f),e=setTimeout(function(){f.css(b._getOffset(!0)),e=setTimeout(function(){k(),setTimeout(function(){f.remove(),a=f=null,y("ZoomAnimationEnded")},16)},g)},16)}}),w(i+d,function(){if(b._allowZoom()){if(clearTimeout(e),b.st.removalDelay=g,!a){if(a=b._getItemToZoom(),!a)return;f=j(a)}f.css(b._getOffset(!0)),b.wrap.append(f),b.content.css("visibility","hidden"),setTimeout(function(){f.css(b._getOffset())},16)}}),w(h+d,function(){b._allowZoom()&&(k(),f&&f.remove(),a=null)})}},_allowZoom:function(){return"image"===b.currItem.type},_getItemToZoom:function(){return b.currItem.hasSize?b.currItem.img:!1},_getOffset:function(c){var d;d=c?b.currItem.img:b.st.zoom.opener(b.currItem.el||b.currItem);var e=d.offset(),f=parseInt(d.css("padding-top"),10),g=parseInt(d.css("padding-bottom"),10);e.top-=a(window).scrollTop()-f;var h={width:d.width(),height:(u?d.innerHeight():d[0].offsetHeight)-g-f};return O()?h["-moz-transform"]=h.transform="translate("+e.left+"px,"+e.top+"px)":(h.left=e.left,h.top=e.top),h}}});var P="iframe",Q="//about:blank",R=function(a){if(b.currTemplate[P]){var c=b.currTemplate[P].find("iframe");c.length&&(a||(c[0].src=Q),b.isIE8&&c.css("display",a?"block":"none"))}};a.magnificPopup.registerModule(P,{options:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){b.types.push(P),w("BeforeChange",function(a,b,c){b!==c&&(b===P?R():c===P&&R(!0))}),w(h+"."+P,function(){R()})},getIframe:function(c,d){var e=c.src,f=b.st.iframe;a.each(f.patterns,function(){return e.indexOf(this.index)>-1?(this.id&&(e="string"==typeof this.id?e.substr(e.lastIndexOf(this.id)+this.id.length,e.length):this.id.call(this,e)),e=this.src.replace("%id%",e),!1):void 0});var g={};return f.srcAction&&(g[f.srcAction]=e),b._parseMarkup(d,g,c),b.updateStatus("ready"),d}}});var S=function(a){var c=b.items.length;return a>c-1?a-c:0>a?c+a:a},T=function(a,b,c){return a.replace(/%curr%/gi,b+1).replace(/%total%/gi,c)};a.magnificPopup.registerModule("gallery",{options:{enabled:!1,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:!0,arrows:!0,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%"},proto:{initGallery:function(){var c=b.st.gallery,e=".mfp-gallery",g=Boolean(a.fn.mfpFastClick);return b.direction=!0,c&&c.enabled?(f+=" mfp-gallery",w(m+e,function(){c.navigateByImgClick&&b.wrap.on("click"+e,".mfp-img",function(){return b.items.length>1?(b.next(),!1):void 0}),d.on("keydown"+e,function(a){37===a.keyCode?b.prev():39===a.keyCode&&b.next()})}),w("UpdateStatus"+e,function(a,c){c.text&&(c.text=T(c.text,b.currItem.index,b.items.length))}),w(l+e,function(a,d,e,f){var g=b.items.length;e.counter=g>1?T(c.tCounter,f.index,g):""}),w("BuildControls"+e,function(){if(b.items.length>1&&c.arrows&&!b.arrowLeft){var d=c.arrowMarkup,e=b.arrowLeft=a(d.replace(/%title%/gi,c.tPrev).replace(/%dir%/gi,"left")).addClass(s),f=b.arrowRight=a(d.replace(/%title%/gi,c.tNext).replace(/%dir%/gi,"right")).addClass(s),h=g?"mfpFastClick":"click";e[h](function(){b.prev()}),f[h](function(){b.next()}),b.isIE7&&(x("b",e[0],!1,!0),x("a",e[0],!1,!0),x("b",f[0],!1,!0),x("a",f[0],!1,!0)),b.container.append(e.add(f))}}),w(n+e,function(){b._preloadTimeout&&clearTimeout(b._preloadTimeout),b._preloadTimeout=setTimeout(function(){b.preloadNearbyImages(),b._preloadTimeout=null},16)}),void w(h+e,function(){d.off(e),b.wrap.off("click"+e),b.arrowLeft&&g&&b.arrowLeft.add(b.arrowRight).destroyMfpFastClick(),b.arrowRight=b.arrowLeft=null})):!1},next:function(){b.direction=!0,b.index=S(b.index+1),b.updateItemHTML()},prev:function(){b.direction=!1,b.index=S(b.index-1),b.updateItemHTML()},goTo:function(a){b.direction=a>=b.index,b.index=a,b.updateItemHTML()},preloadNearbyImages:function(){var a,c=b.st.gallery.preload,d=Math.min(c[0],b.items.length),e=Math.min(c[1],b.items.length);for(a=1;a<=(b.direction?e:d);a++)b._preloadItem(b.index+a);for(a=1;a<=(b.direction?d:e);a++)b._preloadItem(b.index-a)},_preloadItem:function(c){if(c=S(c),!b.items[c].preloaded){var d=b.items[c];d.parsed||(d=b.parseEl(c)),y("LazyLoad",d),"image"===d.type&&(d.img=a('<img class="mfp-img" />').on("load.mfploader",function(){d.hasSize=!0}).on("error.mfploader",function(){d.hasSize=!0,d.loadError=!0,y("LazyLoadError",d)}).attr("src",d.src)),d.preloaded=!0}}}});var U="retina";a.magnificPopup.registerModule(U,{options:{replaceSrc:function(a){return a.src.replace(/\.\w+$/,function(a){return"@2x"+a})},ratio:1},proto:{initRetina:function(){if(window.devicePixelRatio>1){var a=b.st.retina,c=a.ratio;c=isNaN(c)?c():c,c>1&&(w("ImageHasSize."+U,function(a,b){b.img.css({"max-width":b.img[0].naturalWidth/c,width:"100%"})}),w("ElementParse."+U,function(b,d){d.src=a.replaceSrc(d,c)}))}}}}),function(){var b=1e3,c="ontouchstart"in window,d=function(){v.off("touchmove"+f+" touchend"+f)},e="mfpFastClick",f="."+e;a.fn.mfpFastClick=function(e){return a(this).each(function(){var g,h=a(this);if(c){var i,j,k,l,m,n;h.on("touchstart"+f,function(a){l=!1,n=1,m=a.originalEvent?a.originalEvent.touches[0]:a.touches[0],j=m.clientX,k=m.clientY,v.on("touchmove"+f,function(a){m=a.originalEvent?a.originalEvent.touches:a.touches,n=m.length,m=m[0],(Math.abs(m.clientX-j)>10||Math.abs(m.clientY-k)>10)&&(l=!0,d())}).on("touchend"+f,function(a){d(),l||n>1||(g=!0,a.preventDefault(),clearTimeout(i),i=setTimeout(function(){g=!1},b),e())})})}h.on("click"+f,function(){g||e()})})},a.fn.destroyMfpFastClick=function(){a(this).off("touchstart"+f+" click"+f),c&&v.off("touchmove"+f+" touchend"+f)}}(),A()});