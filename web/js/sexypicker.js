/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */

(function($) {

var types = ['DOMMouseScroll', 'mousewheel'];

if ($.event.fixHooks) {
    for ( var i=types.length; i; ) {
        $.event.fixHooks[ types[--i] ] = $.event.mouseHooks;
    }
}

$.event.special.mousewheel = {
    setup: function() {
        if ( this.addEventListener ) {
            for ( var i=types.length; i; ) {
                this.addEventListener( types[--i], handler, false );
            }
        } else {
            this.onmousewheel = handler;
        }
    },
    
    teardown: function() {
        if ( this.removeEventListener ) {
            for ( var i=types.length; i; ) {
                this.removeEventListener( types[--i], handler, false );
            }
        } else {
            this.onmousewheel = null;
        }
    }
};

$.fn.extend({
    mousewheel: function(fn) {
        return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
    },
    
    unmousewheel: function(fn) {
        return this.unbind("mousewheel", fn);
    }
});


function handler(event) {
    var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
    event = $.event.fix(orgEvent);
    event.type = "mousewheel";
    
    // Old school scrollwheel delta
    if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
    if ( orgEvent.detail     ) { delta = -orgEvent.detail/3; }
    
    // New school multidimensional scroll (touchpads) deltas
    deltaY = delta;
    
    // Gecko
    if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
        deltaY = 0;
        deltaX = -1*delta;
    }
    
    // Webkit
    if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
    if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }
    
    // Add event and delta to the front of the arguments
    args.unshift(event, delta, deltaX, deltaY);
    
    return ($.event.dispatch || $.event.handle).apply(this, args);
}

})(jQuery);









;(function($){
  
  var D = $(document), 
      AP = false, 
      pickers = {}, 
      picker_active = false, 
      pknum = 0;
  
  
  var spconfig = function () {
    
    this.defaults = {
      yearRange: [1901,2030],
      lang: ''
    }
    
    this.regional = [];
    
    this.regional[''] = { 
      closeText: 'Done', 
      prevText: 'Prev', 
      nextText: 'Next', 
      currentText: 'Today', 
      monthNames: ['January','February','March','April','May','June',
        'July','August','September','October','November','December'], 
      monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
      dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], 
      dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], 
      dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'], 
      weekHeader: 'Wk', 
      dateFormat: 'mm/dd/yy', 
      firstDay: 0, 
      isRTL: false, 
      showMonthAfterYear: false, 
      yearSuffix: '' 
    }
    
   

    this.regional['ru'] = {
      closeText: 'Закрыть',
      prevText: '&#x3c;Пред',
      nextText: 'След&#x3e;',
      currentText: 'Сегодня',
      monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
      monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
      dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
      dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
      dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
      weekHeader: 'Не',
      dateFormat: 'dd.mm.yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''
    };
    
    
    this.set = function (_options) {
      var options = $.extend(this.defaults,_options);
      if (this.regional[options.lang]) {
        options.regional = this.regional[options.lang];
      }
      return options;
    }

  }
  
  var spinpicker = function () {

    this.attach = function (el, _options) {

      pknum++;
      
      var sp = this;
      var pkid = new Date().getTime()+pknum+Math.floor(Math.random()*1000);
      
      var pck = {
        options: _options,
        pkid: pkid,
        date: new Date(),
        elem: el,
        state: {
          cal_height: 0,
          cal_visible: 0,
          active: false,
          mns: 11,
          drag_month_max: 242,
          cal_visible_p: 1,
          scr_height: 22,
          lih: 22,
          drag_month: false,
          drag_start_m: 0,  
          drag_year: false,
          drag_start_y: 0,
          block_year_click: false,
          scroll_start: 0
        },
        div: {}
      }

      
      pickers[pkid] = pck;
      
      

      pck.elem.bind("focus",function(ev){
        SP.show (pck);
      });

      pck.elem.bind("blur",function(ev){
        SP.hide (pck);
      }); 

      pck.elem.keydown(function(e){
        SP.doKeyDown (e,pck);
      });

      pck.elem.keyup(function(e){
        SP.doKeyUp (e,pck);
      });
      
    }
    
    
    
    
    
    
    
    this.month_mouse_down = function (picker, e, el) {

      AP = picker.pkid;

      if (!picker.state.drag_month) {
        document.body.onselectstart = function () { return false }
        document.ondragstart = function () { return false }
        var spos = picker.div.months.offset();
        var pos = picker.div.main.find(".spinpicker-month-slider-hit").position();
        var sptop = false;
        if (el.hasClass("spinpicker-months")) {         
          sptop = e.pageY-spos.top-(picker.div.month_hit.height()/2);
          picker.div.month_hit.css({top: sptop });
          picker.div.month_slider.css({top: sptop});
          pos = picker.div.month_hit.position();
        }
        picker.state.drag_start_m = e.pageY - pos.top;
        var crd = e.pageY - picker.state.drag_start_m;
        mousemove_month (picker.pkid,crd);
        picker.state.drag_month = true;
      }

      e.preventDefault();
    }
    
    var month_mouse_down = this.month_mouse_down;
    

    this.mousemove_month = function ( pkid, coord ) {

      if (isNaN(coord)) {return false;}


      var picker = pickers[pkid]
      var tableHeight = picker.state.cal_height;
      var visible = picker.state.cal_visible / tableHeight;
      var scroll = coord / picker.state.drag_month_max * (1-visible);
      var scrollTop = scroll * tableHeight
      if (coord > picker.state.drag_month_max - 1) { coord = picker.state.drag_month_max - 1 }
      if (coord < 1) { coord = 1 }

      picker.div.month_hit.css({top:coord});
      picker.div.month_slider.css({top:coord});
      picker.div.days.scrollTop(scrollTop); 
    }
  
    var mousemove_month = this.mousemove_month;
    
    
    
    this.mousemove_year = function ( pkid, pageY ) {
      
      var pck = pickers[pkid];
      
      var distance = pageY;
      if (distance<0){distance=distance*-1}

      var scr = pck.div.main.find(".spinpicker-year-scroll");
      var coord = (pck.state.scroll_start - pageY);
      if (distance>2) {
        pck.state.block_year_click = true;
      }
      scr.scrollTop(coord);
    
    }
    
    mousemove_year = this.mousemove_year;


    this.month_mouse_up = function ( picker, e ) {
      picker.state.drag_month = false; 
      picker.state.drag_start_m = 0;
      document.body.onselectstart = null;
      document.ondragstart = null;
      e.preventDefault();
    }



    this.selectYear = function (picker, strictYear) {


      var scr = picker.div.main.find(".spinpicker-year-scroll");
      
      


      if (strictYear) {
        var nyear = strictYear;
        var index = picker.div.main.find(".spinpicker-years a[rel='"+nyear+"']").parent().index();
      } else {
        var index = Math.round((scr.scrollTop()/picker.state.lih)-0.5);
        var nyear = Number(picker.div.main.find(".spinpicker-years ul li").eq(index).find("a").text());
      }

      var accurate = picker.state.lih*index+(picker.state.lih/2);
      var nd = new Date (picker.state.date);
      nd.setFullYear(nyear);


      picker.div.main.find(".spinpicker-days-table").html(picker.calend.year(nd));
      scrollheight(picker);

      scr.scrollTop(accurate); 


      if (picker.state.date.getFullYear() == nd.getFullYear()) {
        var actiived = picker.div.days.find('a[rel="'+nd.getFullYear()+'/'+nd.getMonth()+'/'+nd.getDate()+'"]');
        if (actiived.length>0) {
          actiived.addClass("spinpicker-day-active");
        }
      }
    }
    
    var selectYear = this.selectYear;

    this.selectDay = function (picker, dt, noScroll) {
      if (!picker.state.lastYear) {picker.state.lastYear = dt.getFullYear();}

      var nyear = dt.getFullYear();
      if (nyear != picker.state.lastYear) {
        selectYear (picker, nyear);
      }
      var gocoord = dt.getMonth() * (picker.state.month_height/12);
      
      if (!noScroll) {
        mousemove_month (picker.pkid, gocoord-picker.state.lih/2);
      }
      
      picker.state.lastYear = picker.state.date.getFullYear();

      picker.div.days.find(".spinpicker-day-active").removeClass("spinpicker-day-active");

      var actiived = picker.div.days.find('a[rel="'+nyear+'/'+dt.getMonth()+'/'+dt.getDate()+'"]'); 
      actiived.addClass("spinpicker-day-active");
      picker.state.date = dt;
    }
    
    var selectDay = this.selectDay;

    
    D.delegate(".spinpicker-month-slider-hit, .spinpicker-months", "mousedown touchstart", function(ev){
      AP = false;
      var e = ev.originalEvent;
      var pkid = $(this).parents('.spinpicker').data('pkid');
      var pck = pickers[pkid];
      month_mouse_down (pck, e, $(this));
    });

    
    
    D.delegate("body","mousemove touchmove", function(ev){
      if (AP && pickers[AP]) {
        var e = ev.originalEvent;
        var pck = pickers[AP];
        
        if (pck.state.drag_month) {
          pck.state.drag_year = false;
          var crd = e.pageY - pck.state.drag_start_m;
          mousemove_month(AP,crd);
        }

        if (pck.state.drag_year) {  
          pck.state.drag_month = false;
          var crd = e.pageY - pck.state.drag_start_y;
          mousemove_year(AP,crd);
        }
      }
    });




    D.delegate(".spinpicker-months","mouseleave",function(e){
      document.body.onselectstart = null;
      document.ondragstart = null;
    });
    
    

    
    D.delegate(".spinpicker-days, .spinpicker-months", "mousewheel", function(e, delta){ 
      var pkid = $(this).parents(".spinpicker").data("pkid");
      var picker = pickers[pkid];
      var pos = picker.div.main.find(".spinpicker-month-slider-hit").position();
      var crd = pos.top+(11*-delta);
      mousemove_month (pkid, crd);
      return false;
    });
    
    



    D.delegate(".spinpicker-year-slider-hit, .spinpicker-years", "mousedown touchstart", function(ev){
      
      var pkid = $(this).parents('.spinpicker').data('pkid');
      var pck = pickers[pkid];
      pck.state.drag_month = false;

      var e = ev.originalEvent;

      if (!pck.state.drag_year) {
        AP = pck.pkid;
        pck.state.block_year_click = false;
        var pos = pck.div.main.find(".spinpicker-year-slider-hit").position();
        pck.state.drag_start_y = e.pageY; 
        pck.state.scroll_start = pck.div.main.find(".spinpicker-year-scroll").scrollTop();
        pck.state.drag_year = true; 
        document.body.onselectstart = function () { return false }
        document.ondragstart = function () { return false }
      }
      ev.preventDefault();
    });
    
    
    
    /*
    D.delegate(".spinpicker-years a","click",function(e){

      var pkid = $(this).parents('.spinpicker').data('pkid');
      var pck = pickers[pkid];
      
      if (!pck.state.block_year_click) {
        var scr = $(".spinpicker-year-scroll", pck.div.main);
        var pos = $(this).position();
        var coord = pos.top - $(".spinpicker-year-slider-hit", pck.div.main).position().top;
        $(".spinpicker-days-table",pck.div.main).html(pck.calend.year(Number($(this).text())));
        scrollheight(pck);
        scr.scrollTop(coord);
      } else {
        block_year_click = false;
      }

      e.preventDefault();
      return false;
    });
    */
    
    
    

    D.delegate(".spinpicker-years","mousewheel",function(e,delta){
      var pkid = $(this).parents('.spinpicker').data('pkid');
      var pck = pickers[pkid];
      
      var scr = pck.div.main.find(".spinpicker-year-scroll");
      var scrollTop = scr.scrollTop();

      var nscroll = scrollTop+(delta*-pck.state.lih);
      if (nscroll < pck.state.lih/2) {
        nscroll = pck.state.lih/2;
      } 
      scr.scrollTop(nscroll);
      selectYear(pck);
      return false;
    });
    
    
    D.delegate(".spinpicker-days a", "click", function(e) {
      var pkid = $(this).parents('.spinpicker').data('pkid');
      var pck = pickers[pkid];
      var spd = $(this).attr("rel").split("/");
      var d = new Date (spd[0],spd[1],spd[2]);
      var formattedDate = SP.formatDate (pck.options.regional.dateFormat, d, pck.options.regional);

      selectDay (pck, d, true);
      pck.elem.val(formattedDate).focus().change();

    });
    
    

    D.bind("mouseup touchend",function(){
      if (AP) {
        if (pickers[AP]) {
          var pck = pickers[AP];
          if (pck.state.drag_year) {
            selectYear(pck);
          }
          pck.state.drag_month = false; 
          pck.state.drag_year = false;
        }
        AP = false;
      }

    });
    
    
    this.doKeyDown = function (e, pck) {
      switch (e.keyCode) {
        case 9: {
          pck.state.hover = false;
          SP.hide(pck);
        }
        case 27: {
          pck.state.hover = false;
          SP.hide(pck);
        }
      }
    }


    this.doKeyUp = function (e, pck) {
      var d = parseDate (pck.options.regional.dateFormat, pck.elem.val(), pck.options.regional);
      if (d) {

        selectDay (pck, d);
      }
    }


    
    
    this.show = function (pck) {

      if (!pck.state.active) {


        pck.state.D = {
          yy: this.formatDate( 'yy', pck.date, pck.options.regional )
        }

        var offset = pck.elem.offset();
        

        

        pck.div.main = $('<div>',{
          "id": "spinpicker-"+pck.pkid,
          "class": "spinpicker"
        }).css({
          top: offset.top+pck.elem.outerHeight(),
          left: offset.left
        }).data({'pkid':pck.pkid});
  
        pck.div.days = $('<div>',{
          "id": "spinpicker-days-"+pck.pkid,
          "class": "spinpicker-days"
        });        
        
        pck.div.months = $('<div>',{
          "id": "spinpicker-months-"+pck.pkid,
          "class": "spinpicker-months"
        });
        
        pck.div.years = $('<div>',{
          "id": "spinpicker-years-"+pck.pkid,
          "class": "spinpicker-years"
        });
  
        
        pck.calend = new this.calend(pck.options);
        pck.state.date = parseDate (pck.options.regional.dateFormat, pck.elem.val(), pck.options.regional);
        



        if (pck.state.date) {
          
        } else {
          pck.state.date = new Date();
        }


        pck.div.days.html('<div id="spinpicker-days-table" class="spinpicker-days-table">'+pck.calend.year(pck.state.date)+'</div>');

        var ml = '<div id="spinpicker-month-slider" class="spinpicker-month-slider"><div></div></div><ul>';
        for (var m=0;m<=11;m++) {
          ml += '<li><a rel="'+m+'">'+pck.options.regional.monthNames[m]+'</a></li>'
        }
        ml += '</ul><div class="spinpicker-month-over"></div><div id="spinpicker-month-slider-hit" class="spinpicker-month-slider-hit"></div>';
        var monthList = ml;
        
        pck.div.months.html(monthList);
        
        var years_slider = '<div id="spinpicker-year-slider" class="spinpicker-year-slider"><div></div></div><div id="spinpicker-year-scroll" class="spinpicker-year-scroll"><ul style="padding: 121px 0px 140px 0px;">';
        for (var y = pck.options.yearRange[0]; y<=pck.options.yearRange[1]; y++) {
          years_slider += '<li><a rel="'+y+'">'+y+'</a></li>';
        }
        years_slider += '</ul></div><div class="spinpicker-year-over"></div><div id="spinpicker-year-slider-hit" class="spinpicker-year-slider-hit"><div>';
        
        pck.div.years.html(years_slider);
        
        var weekDaysRaw = '<table class="spinpicker-weekdays"><tbody><tr>';
        for (var i = 0; i < 7; i++ ) {
          var j = i;
          if (pck.options.regional.firstDay) {
            j += pck.options.regional.firstDay;

            if (j >= 7) {
              var fstwks = pck.options.regional.firstDay/7;
              j = j-Math.floor(fstwks)*7-7;
            }
          }
          weekDaysRaw+= '<td>'+pck.options.regional.dayNamesMin[j]+'</td>';
        }
        weekDaysRaw += '</tr></tbody></table>';

        pck.div.weekDays = $(weekDaysRaw);
        pck.div.main.append(pck.div.weekDays, pck.div.days, pck.div.months, pck.div.years);
        $("body").append(pck.div.main);

        pck.div.main.bind("mouseenter", function(){
          pck.state.hover = true;
        });

        pck.div.main.bind("mouseleave", function(){
          pck.state.hover = false;
        });

        pck.div.main.bind ("click", function(e) {
          pck.elem.focus();
        })

        
        pck.div.month_hit = pck.div.main.find(".spinpicker-month-slider-hit");
        pck.div.month_slider = pck.div.main.find(".spinpicker-month-slider");
        pck.div.months = pck.div.main.find(".spinpicker-months");
        
        this.scrollheight (pck);
        
        pck.state.month_height = pck.div.months.height();
        pck.state.drag_month_max = pck.state.month_height - pck.div.month_hit.height();
        pck.div.yearcroll = pck.div.main.find(".spinpicker-year-scroll");
        pck.state.lih = pck.div.main.find(".spinpicker-year-scroll li").height();
        var index = pck.div.main.find(".spinpicker-years a[rel='"+pck.state.date.getFullYear()+"']").parent().index();
        pck.div.yearcroll.scrollTop (pck.state.lih*index+(pck.state.lih/2)); 
        this.selectDay (pck, pck.state.date);
               
        pck.state.active = true;        
        pck.div.main.show();
      }
    }
    
    show = this.show;
  
    this.hide = function (picker) { 
      if (!picker.state.hover) {
        picker.state.active = false;
        picker.div.main.hide()
        picker.div.main.remove();
      }
    }
    
    hide = this.hide;
  
  
    this.scrollheight = function (picker) {
      picker.state.cal_height = picker.div.main.find(".spinpicker-days-table").height();
      picker.state.cal_visible = picker.div.days.height();
      picker.state.cal_visible_p = picker.state.cal_visible/picker.state.cal_height;
      picker.state.scr_height = Math.floor(picker.div.months.height()*picker.state.cal_visible_p);
      picker.state.drag_month_max = picker.div.months.height() - picker.state.scr_height;
      var coord = $(picker.div.month_hit).position().top;
      if (coord > picker.state.drag_month_max - 1) { coord = picker.state.drag_month_max - 1 }
      picker.div.month_hit.css({
        height:picker.state.scr_height,
        top:coord
      });
      picker.div.month_slider.css({
        height:picker.state.scr_height,
        top:coord
      });

    }
    
    var scrollheight = this.scrollheight;
  
  
  
    
  

  
    this.calend =  function (options) {

      var start_day;

      return {

        isLeap: function (year) {
          if(year%4 == 0) { 
            if(year%100 == 0) { 
              if(year%400 == 0) { 
                return true; 
              } 
              else 
                return false; 
            } 
            else 
              return true; 
          } 
          return false; 
        }, 
        

        month: function (index, year) {
          var dim = [31,28,31,30,31,30,31,31,30,31,30,31]
          
        

          if (this.isLeap(year)) { dim[1]=29 }

          var m = dim[index];
          var day = 1;
          var table = '';
          var mtop = '';
          var cropping = (options.regional.monthNames[index].length - start_day);

          if ((start_day > 3 || (cropping>-2 && cropping <2)) && start_day < 8) {
            mtop = 'spinpicker-month-top';
          }
          table += '<h3><b>'+options.regional.monthNames[index]+'</b></h3><table id="spinpicker-month-'+(index+1)+'" class="spinpicker-month spinpicker-month-'+(index+1)+' '+mtop+'"><tbody><tr>';
          for (var i=1; i<start_day; i++){
            table += "<td></td>";
          }
          for (var i=start_day; i<8; i++){
            table += '<td><a rel="'+year+'/'+index+'/'+day+'">'+day+'</a></td>';
            day++;
          }
          
          table += "<tr>";
          while (day <= m) {
             for (var i=1; i<=7 && day<=m; i++){
               table += '<td><a rel="'+year+'/'+index+'/'+day+'">'+day+'</a></td>';
               day++;
             }
             table += "</tr><tr>";
             start_day = i;
          }
          table += "</tr></tbody></table>";
          return table;
        }, 
        
        year: function (dt) {

          var st = new Date ('January 1, '+dt.getFullYear());
          var year = st.getFullYear();
          start_day = st.getDay()+1;
          if (options.regional.firstDay) {
            start_day = start_day-options.regional.firstDay;
          }
          if (start_day<=0) {
            start_day+=7;
          }
          var calend = '';
          for (var f=0;f<=11;f++) {
            calend += this.month(f, year);
          }
          return calend;
        }
      }
    }
  

   
    
    
    this.parseDate = function (format, value, settings) {

      var shortYearCutoff = 0;
      this._daylightSavingAdjust = function(date) {
        if (!date) return null;
        date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
        return date;
      }

      if (format == null || value == null)
        return false;
      value = (typeof value == 'object' ? value.toString() : value + '');
      if (value == '')
        return null;

      var dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort;
      var dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames;
      var monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort;
      var monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames;
      var year = -1;
      var month = -1;
      var day = -1;
      var doy = -1;
      var literal = false;
      // Check whether a format character is doubled
      var lookAhead = function(match) {
        var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) == match);
        if (matches)
          iFormat++;
        return matches;
      };
      // Extract a number from the string value
      var getNumber = function(match) {
        var isDoubled = lookAhead(match);
        var size = (match == '@' ? 14 : (match == '!' ? 20 :
          (match == 'y' && isDoubled ? 4 : (match == 'o' ? 3 : 2))));
        var digits = new RegExp('^\\d{1,' + size + '}');
        var num = value.substring(iValue).match(digits);
        if (!num)
          return false
        iValue += num[0].length;
        return parseInt(num[0], 10);
      };
      // Extract a name from the string value and convert to an index
      var getName = function(match, shortNames, longNames) {
        var names = $.map(lookAhead(match) ? longNames : shortNames, function (v, k) {
          return [ [k, v] ];
        }).sort(function (a, b) {
          return -(a[1].length - b[1].length);
        });
        var index = -1;
        $.each(names, function (i, pair) {
          var name = pair[1];
          if (value.substr(iValue, name.length).toLowerCase() == name.toLowerCase()) {
            index = pair[0];
            iValue += name.length;
            return false;
          }
        });
        if (index != -1)
          return index + 1;
        else
          throw 'Unknown name at position ' + iValue;
      };
      // Confirm that a literal character matches the string value
      var checkLiteral = function() {
        if (value.charAt(iValue) != format.charAt(iFormat))
          return false
        iValue++;
      };
      var iValue = 0;
      for (var iFormat = 0; iFormat < format.length; iFormat++) {
        if (literal)
          if (format.charAt(iFormat) == "'" && !lookAhead("'"))
            literal = false;
          else
            checkLiteral();
        else
          switch (format.charAt(iFormat)) {
            case 'd':
              day = getNumber('d');
              break;
            case 'D':
              getName('D', dayNamesShort, dayNames);
              break;
            case 'o':
              doy = getNumber('o');
              break;
            case 'm':
              month = getNumber('m');
              break;
            case 'M':
              month = getName('M', monthNamesShort, monthNames);
              break;
            case 'y':
              year = getNumber('y');
              break;
            case '@':
              var date = new Date(getNumber('@'));
              year = date.getFullYear();
              month = date.getMonth() + 1;
              day = date.getDate();
              break;
            case '!':
              var date = new Date((getNumber('!') - this._ticksTo1970) / 10000);
              year = date.getFullYear();
              month = date.getMonth() + 1;
              day = date.getDate();
              break;
            case "'":
              if (lookAhead("'"))
                checkLiteral();
              else
                literal = true;
              break;
            default:
              checkLiteral();
          }
      }
      if (year == -1)
        year = new Date().getFullYear();
      else if (year < 100)
        year += new Date().getFullYear() - new Date().getFullYear() % 100 +
          (year <= shortYearCutoff ? 0 : -100);
      if (doy > -1) {
        month = 1;
        day = doy;
        do {
          var dim = this._getDaysInMonth(year, month - 1);
          if (day <= dim)
            break;
          month++;
          day -= dim;
        } while (true);
      }
      var date = this._daylightSavingAdjust(new Date(year, month - 1, day));
      if (date.getFullYear() != year || date.getMonth() + 1 != month || date.getDate() != day)
        return false
      return date;
    }
    
    parseDate = this.parseDate;
  

  
    this.defaultFormats = {
      ATOM: 'yy-mm-dd',
      COOKIE: 'D, dd M yy',
      ISO_8601: 'yy-mm-dd',
      RFC_822: 'D, d M y',
      RFC_850: 'DD, dd-M-y',
      RFC_1036: 'D, d M y',
      RFC_1123: 'D, d M yy',
      RFC_2822: 'D, d M yy',
      RSS: 'D, d M y',
      TICKS: '!',
      TIMESTAMP: '@',
      W3C: 'yy-mm-dd'
    }
  
    this._ticksTo1970 = (((1970 - 1) * 365 + Math.floor(1970 / 4) - Math.floor(1970 / 100) +
      Math.floor(1970 / 400)) * 24 * 60 * 60 * 10000000),
  
      
    this.formatDate = function (format, date, settings) {
      if (!date)
        return '';
      var dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort;
      var dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames;
      var monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort;
      var monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames;

      var lookAhead = function(match) {
        var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) == match);
        if (matches)
          iFormat++;
        return matches;
      };

      var formatNumber = function(match, value, len) {
        var num = '' + value;
        if (lookAhead(match))
          while (num.length < len)
            num = '0' + num;
        return num;
      };

      var formatName = function(match, value, shortNames, longNames) {
        return (lookAhead(match) ? longNames[value] : shortNames[value]);
      };
      var output = '';
      var literal = false;
      if (date)
        for (var iFormat = 0; iFormat < format.length; iFormat++) {
          if (literal)
            if (format.charAt(iFormat) == "'" && !lookAhead("'"))
              literal = false;
            else
              output += format.charAt(iFormat);
          else
            switch (format.charAt(iFormat)) {
              case 'd':
                output += formatNumber('d', date.getDate(), 2);
                break;
              case 'D':
                output += formatName('D', date.getDay(), dayNamesShort, dayNames);
                break;
              case 'o':
                output += formatNumber('o',
                  (date.getTime() - new Date(date.getFullYear(), 0, 0).getTime()) / 86400000, 3);
                break;
              case 'm':
                output += formatNumber('m', date.getMonth() + 1, 2);
                break;
              case 'M':
                output += formatName('M', date.getMonth(), monthNamesShort, monthNames);
                break;
              case 'y':
                output += (lookAhead('y') ? date.getFullYear() :
                  (date.getYear() % 100 < 10 ? '0' : '') + date.getYear() % 100);
                break;
              case '@':
                output += date.getTime();
                break;
              case '!':
                output += date.getTime() * 10000 + this._ticksTo1970;
                break;
              case "'":
                if (lookAhead("'"))
                  output += "'";
                else
                  literal = true;
                break;
              default:
                output += format.charAt(iFormat);
            }
        }
      return output;
    }
    
    formatDate = this.formatDate;

  }

  var SP = new spinpicker();
  
  $.fn.spinpicker = function(options){
    
    var config = new spconfig (options);
    
    var _options = config.set (options);

    return this.each(function(i,el){
      SP.attach($(el),_options);
    });
    
  }
  
  $.spinpicker = SP;
  
})(jQuery);



/*
if (!$.event.special.mousewheel) {
  (function($) {

    var types = ['DOMMouseScroll', 'mousewheel'];
    
    $.event.special.mousewheel = {
      setup: function() {
        if ( this.addEventListener )
          for ( var i=types.length; i; )
            this.addEventListener( types[--i], handler, false );
        else
          this.onmousewheel = handler;
      },
      
      teardown: function() {
        if ( this.removeEventListener )
          for ( var i=types.length; i; )
            this.removeEventListener( types[--i], handler, false );
        else
          this.onmousewheel = null;
      }
    };
    
    $.fn.extend({
      mousewheel: function(fn) { return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel") },
      unmousewheel: function(fn) { return this.unbind("mousewheel", fn) }
    });
    
    
    var handler = function (event) {
      var args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true;
      event = $.event.fix(event || window.event);
      event.type = "mousewheel";
      if ( event.wheelDelta ) delta = event.wheelDelta/120;
      if ( event.detail     ) delta =-event.detail/3;
      args.unshift(event, delta);
      return $.event.handle.apply(this, args);
    }

  })(jQuery);
}



*/