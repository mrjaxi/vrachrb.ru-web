/*! perfect-scrollbar - v0.5.9
* http://noraesae.github.com/perfect-scrollbar/
* Copyright (c) 2015 Hyunje Alex Jun; Licensed MIT */
(function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?e(require("jquery")):e(jQuery)})(function(e){"use strict";function t(e){return"string"==typeof e?parseInt(e,10):~~e}var o={wheelSpeed:1,wheelPropagation:!1,swipePropagation:!0,minScrollbarLength:null,maxScrollbarLength:null,useBothWheelAxes:!1,useKeyboard:!0,suppressScrollX:!1,suppressScrollY:!1,scrollXMarginOffset:0,scrollYMarginOffset:0,includePadding:!1},n=0,r=function(){var e=n++;return function(t){var o=".perfect-scrollbar-"+e;return t===void 0?o:t+o}},l="WebkitAppearance"in document.documentElement.style;e.fn.perfectScrollbar=function(n,i){return this.each(function(){function a(e,o){var n=e+o,r=D-R;j=0>n?0:n>r?r:n;var l=t(j*(Y-D)/(D-R));M.scrollTop(l)}function s(e,o){var n=e+o,r=E-k;W=0>n?0:n>r?r:n;var l=t(W*(C-E)/(E-k));M.scrollLeft(l)}function c(e){return P.minScrollbarLength&&(e=Math.max(e,P.minScrollbarLength)),P.maxScrollbarLength&&(e=Math.min(e,P.maxScrollbarLength)),e}function u(){var e={width:I};e.left=B?M.scrollLeft()+E-C:M.scrollLeft(),N?e.bottom=_-M.scrollTop():e.top=Q+M.scrollTop(),H.css(e);var t={top:M.scrollTop(),height:A};Z?t.right=B?C-M.scrollLeft()-V-J.outerWidth():V-M.scrollLeft():t.left=B?M.scrollLeft()+2*E-C-$-J.outerWidth():$+M.scrollLeft(),G.css(t),U.css({left:W,width:k-z}),J.css({top:j,height:R-et})}function p(){M.removeClass("ps-active-x"),M.removeClass("ps-active-y"),E=P.includePadding?M.innerWidth():M.width(),D=P.includePadding?M.innerHeight():M.height(),C=M.prop("scrollWidth"),Y=M.prop("scrollHeight"),!P.suppressScrollX&&C>E+P.scrollXMarginOffset?(X=!0,I=E-F,k=c(t(I*E/C)),W=t(M.scrollLeft()*(I-k)/(C-E))):(X=!1,k=0,W=0,M.scrollLeft(0)),!P.suppressScrollY&&Y>D+P.scrollYMarginOffset?(O=!0,A=D-tt,R=c(t(A*D/Y)),j=t(M.scrollTop()*(A-R)/(Y-D))):(O=!1,R=0,j=0,M.scrollTop(0)),W>=I-k&&(W=I-k),j>=A-R&&(j=A-R),u(),X&&M.addClass("ps-active-x"),O&&M.addClass("ps-active-y")}function d(){var t,o,n=function(e){s(t,e.pageX-o),p(),e.stopPropagation(),e.preventDefault()},r=function(){M.removeClass("ps-in-scrolling"),e(q).unbind(K("mousemove"),n)};U.bind(K("mousedown"),function(l){o=l.pageX,t=U.position().left,M.addClass("ps-in-scrolling"),e(q).bind(K("mousemove"),n),e(q).one(K("mouseup"),r),l.stopPropagation(),l.preventDefault()}),t=o=null}function f(){var t,o,n=function(e){a(t,e.pageY-o),p(),e.stopPropagation(),e.preventDefault()},r=function(){M.removeClass("ps-in-scrolling"),e(q).unbind(K("mousemove"),n)};J.bind(K("mousedown"),function(l){o=l.pageY,t=J.position().top,M.addClass("ps-in-scrolling"),e(q).bind(K("mousemove"),n),e(q).one(K("mouseup"),r),l.stopPropagation(),l.preventDefault()}),t=o=null}function v(e,t){var o=M.scrollTop();if(0===e){if(!O)return!1;if(0===o&&t>0||o>=Y-D&&0>t)return!P.wheelPropagation}var n=M.scrollLeft();if(0===t){if(!X)return!1;if(0===n&&0>e||n>=C-E&&e>0)return!P.wheelPropagation}return!0}function g(e,t){var o=M.scrollTop(),n=M.scrollLeft(),r=Math.abs(e),l=Math.abs(t);if(l>r){if(0>t&&o===Y-D||t>0&&0===o)return!P.swipePropagation}else if(r>l&&(0>e&&n===C-E||e>0&&0===n))return!P.swipePropagation;return!0}function b(){function e(e){var t=e.originalEvent.deltaX,o=-1*e.originalEvent.deltaY;return(t===void 0||o===void 0)&&(t=-1*e.originalEvent.wheelDeltaX/6,o=e.originalEvent.wheelDeltaY/6),e.originalEvent.deltaMode&&1===e.originalEvent.deltaMode&&(t*=10,o*=10),t!==t&&o!==o&&(t=0,o=e.originalEvent.wheelDelta),[t,o]}function t(t){if(l||!(M.find("select:focus").length>0)){var n=e(t),r=n[0],i=n[1];o=!1,P.useBothWheelAxes?O&&!X?(i?M.scrollTop(M.scrollTop()-i*P.wheelSpeed):M.scrollTop(M.scrollTop()+r*P.wheelSpeed),o=!0):X&&!O&&(r?M.scrollLeft(M.scrollLeft()+r*P.wheelSpeed):M.scrollLeft(M.scrollLeft()-i*P.wheelSpeed),o=!0):(M.scrollTop(M.scrollTop()-i*P.wheelSpeed),M.scrollLeft(M.scrollLeft()+r*P.wheelSpeed)),p(),o=o||v(r,i),o&&(t.stopPropagation(),t.preventDefault())}}var o=!1;window.onwheel!==void 0?M.bind(K("wheel"),t):window.onmousewheel!==void 0&&M.bind(K("mousewheel"),t)}function h(){var t=!1;M.bind(K("mouseenter"),function(){t=!0}),M.bind(K("mouseleave"),function(){t=!1});var o=!1;e(q).bind(K("keydown"),function(n){if((!n.isDefaultPrevented||!n.isDefaultPrevented())&&t){var r=document.activeElement?document.activeElement:q.activeElement;if(r){for(;r.shadowRoot;)r=r.shadowRoot.activeElement;if(e(r).is(":input,[contenteditable]"))return}var l=0,i=0;switch(n.which){case 37:l=-30;break;case 38:i=30;break;case 39:l=30;break;case 40:i=-30;break;case 33:i=90;break;case 32:case 34:i=-90;break;case 35:i=n.ctrlKey?-Y:-D;break;case 36:i=n.ctrlKey?M.scrollTop():D;break;default:return}M.scrollTop(M.scrollTop()-i),M.scrollLeft(M.scrollLeft()+l),o=v(l,i),o&&n.preventDefault()}})}function w(){function e(e){e.stopPropagation()}J.bind(K("click"),e),G.bind(K("click"),function(e){var o=t(R/2),n=e.pageY-G.offset().top-o,r=D-R,l=n/r;0>l?l=0:l>1&&(l=1),M.scrollTop((Y-D)*l)}),U.bind(K("click"),e),H.bind(K("click"),function(e){var o=t(k/2),n=e.pageX-H.offset().left-o,r=E-k,l=n/r;0>l?l=0:l>1&&(l=1),M.scrollLeft((C-E)*l)})}function m(){function t(){var e=window.getSelection?window.getSelection():document.getSlection?document.getSlection():{rangeCount:0};return 0===e.rangeCount?null:e.getRangeAt(0).commonAncestorContainer}function o(){r||(r=setInterval(function(){return x()?(M.scrollTop(M.scrollTop()+l.top),M.scrollLeft(M.scrollLeft()+l.left),p(),void 0):(clearInterval(r),void 0)},50))}function n(){r&&(clearInterval(r),r=null),M.removeClass("ps-in-scrolling"),M.removeClass("ps-in-scrolling")}var r=null,l={top:0,left:0},i=!1;e(q).bind(K("selectionchange"),function(){e.contains(M[0],t())?i=!0:(i=!1,n())}),e(window).bind(K("mouseup"),function(){i&&(i=!1,n())}),e(window).bind(K("mousemove"),function(e){if(i){var t={x:e.pageX,y:e.pageY},r=M.offset(),a={left:r.left,right:r.left+M.outerWidth(),top:r.top,bottom:r.top+M.outerHeight()};t.x<a.left+3?(l.left=-5,M.addClass("ps-in-scrolling")):t.x>a.right-3?(l.left=5,M.addClass("ps-in-scrolling")):l.left=0,t.y<a.top+3?(l.top=5>a.top+3-t.y?-5:-20,M.addClass("ps-in-scrolling")):t.y>a.bottom-3?(l.top=5>t.y-a.bottom+3?5:20,M.addClass("ps-in-scrolling")):l.top=0,0===l.top&&0===l.left?n():o()}})}function T(t,o){function n(e,t){M.scrollTop(M.scrollTop()-t),M.scrollLeft(M.scrollLeft()-e),p()}function r(){h=!0}function l(){h=!1}function i(e){return e.originalEvent.targetTouches?e.originalEvent.targetTouches[0]:e.originalEvent}function a(e){var t=e.originalEvent;return t.targetTouches&&1===t.targetTouches.length?!0:t.pointerType&&"mouse"!==t.pointerType&&t.pointerType!==t.MSPOINTER_TYPE_MOUSE?!0:!1}function s(e){if(a(e)){w=!0;var t=i(e);d.pageX=t.pageX,d.pageY=t.pageY,f=(new Date).getTime(),null!==b&&clearInterval(b),e.stopPropagation()}}function c(e){if(!h&&w&&a(e)){var t=i(e),o={pageX:t.pageX,pageY:t.pageY},r=o.pageX-d.pageX,l=o.pageY-d.pageY;n(r,l),d=o;var s=(new Date).getTime(),c=s-f;c>0&&(v.x=r/c,v.y=l/c,f=s),g(r,l)&&(e.stopPropagation(),e.preventDefault())}}function u(){!h&&w&&(w=!1,clearInterval(b),b=setInterval(function(){return x()?.01>Math.abs(v.x)&&.01>Math.abs(v.y)?(clearInterval(b),void 0):(n(30*v.x,30*v.y),v.x*=.8,v.y*=.8,void 0):(clearInterval(b),void 0)},10))}var d={},f=0,v={},b=null,h=!1,w=!1;t&&(e(window).bind(K("touchstart"),r),e(window).bind(K("touchend"),l),M.bind(K("touchstart"),s),M.bind(K("touchmove"),c),M.bind(K("touchend"),u)),o&&(window.PointerEvent?(e(window).bind(K("pointerdown"),r),e(window).bind(K("pointerup"),l),M.bind(K("pointerdown"),s),M.bind(K("pointermove"),c),M.bind(K("pointerup"),u)):window.MSPointerEvent&&(e(window).bind(K("MSPointerDown"),r),e(window).bind(K("MSPointerUp"),l),M.bind(K("MSPointerDown"),s),M.bind(K("MSPointerMove"),c),M.bind(K("MSPointerUp"),u)))}function y(){M.bind(K("scroll"),function(){p()})}function L(){M.unbind(K()),e(window).unbind(K()),e(q).unbind(K()),M.data("perfect-scrollbar",null),M.data("perfect-scrollbar-update",null),M.data("perfect-scrollbar-destroy",null),U.remove(),J.remove(),H.remove(),G.remove(),M=H=G=U=J=X=O=E=D=C=Y=k=W=_=N=Q=R=j=V=Z=$=B=K=null}function S(){p(),y(),d(),f(),w(),m(),b(),(ot||nt)&&T(ot,nt),P.useKeyboard&&h(),M.data("perfect-scrollbar",M),M.data("perfect-scrollbar-update",p),M.data("perfect-scrollbar-destroy",L)}var P=e.extend(!0,{},o),M=e(this),x=function(){return!!M};if("object"==typeof n?e.extend(!0,P,n):i=n,"update"===i)return M.data("perfect-scrollbar-update")&&M.data("perfect-scrollbar-update")(),M;if("destroy"===i)return M.data("perfect-scrollbar-destroy")&&M.data("perfect-scrollbar-destroy")(),M;if(M.data("perfect-scrollbar"))return M.data("perfect-scrollbar");M.addClass("ps-container");var E,D,C,Y,X,k,W,I,O,R,j,A,B="rtl"===M.css("direction"),K=r(),q=this.ownerDocument||document,H=e("<div class='ps-scrollbar-x-rail'>").appendTo(M),U=e("<div class='ps-scrollbar-x'>").appendTo(H),_=t(H.css("bottom")),N=_===_,Q=N?null:t(H.css("top")),z=t(H.css("borderLeftWidth"))+t(H.css("borderRightWidth")),F=t(H.css("marginLeft"))+t(H.css("marginRight")),G=e("<div class='ps-scrollbar-y-rail'>").appendTo(M),J=e("<div class='ps-scrollbar-y'>").appendTo(G),V=t(G.css("right")),Z=V===V,$=Z?null:t(G.css("left")),et=t(G.css("borderTopWidth"))+t(G.css("borderBottomWidth")),tt=t(G.css("marginTop"))+t(G.css("marginBottom")),ot="ontouchstart"in window||window.DocumentTouch&&document instanceof window.DocumentTouch,nt=null!==window.navigator.msMaxTouchPoints;return S(),M})}});






var cl = function(s){
  console.log(s);
};

var dashbTdEach = 11;

var current_division = 0;


var arm_p2_division_stat = [];
var arm_p2_division_lists = [];

var dashbTd = null,
    legend = null,
    legendH = null,
    j = 0,
    rightPanel = null,
    rightPanelBody = null;

    
    
var arm_p2_overlay__win__open = function(title, color, with_close){
  if(with_close){
    $('.arm_p2_right_panel__btn_close').show();
  } else {
    $('.arm_p2_right_panel__btn_close').hide();
  }
  $('.arm_p2_right_panel').width($('.arm_p2_w_right').width());
  $('.arm_p2_right_panel').addClass('arm_p2_right_panel__active');
  $('.arm_p2_overlay').width($('.arm_p2_dashb').width());
  
  
  
  $('.arm_p2_right_panel__b__cont').html('');
  $('.arm_p2_right_panel__h b').css('color', color);
  $('.arm_p2_right_panel__h b').html(title);
  $('.arm_p2_right_panel__b__cont').css({
    'background-image' : 'url(/i/arm/282-p.gif)',
    'background-repeat' : 'no-repeat',
    'background-position' : 'center center'
  });
};

var arm_p2_overlay__win__close = function(){
  $('.arm_p2_overlay').fadeOut(100);
  $('.arm_p2_right_panel').removeClass('arm_p2_right_panel__active');
  $('.arm_p2_overlay').width($(window).width());
  $('.arm_p2_right_panel__btn_close').hide();
};

var legend_res = function(){
  dashbTd = $('.arm_p2_dashb__td'),
  legend = $('.arm_p2_legend__num');
  rightPanel = $('.arm_p2_right_panel');
  rightPanelBody = $('.arm_p2_right_panel__b');

  dashbTd.each(function(x, td){
    $(td).find('.arm_p2_legend__num').each(function(i, item){
      var itemW = $(item).outerWidth() + 10,
          parent = $(item).parent(),
          parentW = parent.outerWidth();

      parent.outerHeight(legendH)
      if(itemW >= parentW){
        ++j;
        parent.css('height', parent.outerHeight() + dashbTdEach * j);
      }
    })
    j = 0;
  });
};

$(window).load(function(){

  $(window).resize();
  // dashbTd = $('.arm_p2_dashb__td'),
  // legend = $('.arm_p2_legend__num');
  // rightPanel = $('.arm_p2_right_panel');
  // rightPanelBody = $('.arm_p2_right_panel__b');

  // dashbTd.each(function(x, td){
  //   $(td).find('.arm_p2_legend__num').each(function(i, item){
  //     var itemW = $(item).outerWidth() + 10,
  //         parent = $(item).parent(),
  //         parentW = parent.outerWidth();

  //     if(itemW >= parentW){
  //       ++j;
  //       parent.css('height', parent.outerHeight() + dashbTdEach * j);
  //     }
  //   })
  //   j = 0;
  // });

  var overlayChartTd = $('.arm_p2_overlay__win__chart__line').parent().outerWidth();

  $('.arm_p2_overlay__win__chart__line').each(function(i, line){
    /*
    if(overlayChartTd - $(line).outerWidth() <=  100){
      $(line).find('b').css({
        'right': '10px',
        'color': '#30373e'
      })
    }
    */
  })

  $(document)
    .on('click', '.arm_p2_dashb__td', function(){
      var _this = $(this);
      arm_p2_overlay__win__close();
      current_division = _this.data('id');
      
      $('#p2_division_title').html(_this.find('.arm_p2_dashb__td__h').html());
      
      $('.arm_p2_overlay__win__chart__line').css('width', '0');
      //$('.arm_p2_overlay__win__chart__line b').html('0% <span class="arm_p2_arr">&uarr;</span>');
      $('.arm_p2_overlay__win__chart__line b').html('0%');
      
      _this.find('.arm_p2_dashb__chart td').each(function(k, el){
        var dest_el = $('.arm_p2_' + $(el).data('total') + ' .arm_p2_overlay__win__chart__line');
        var percent = $(el).prop('width');
        dest_el.css('width', percent);
        //dest_el.find('b').html(percent + ' <span class="arm_p2_arr">&uarr;</span>');
        dest_el.find('b').html(percent + '');
      });
      
      $('.arm_p2__future_price,.arm_p2__future_hours').css('opacity', 0);
      
      var price_el = _this.find('.arm_p2_dashb__chart__cost');
      
      var future_price = price_el.data('future_price');
      var future_hours = price_el.data('future_hours');
      
      if(future_price != '0'){
        $('.arm_p2__future_price').css('opacity', 1);
        $('.arm_p2__future_price__val').html(future_price + ' <span class="rub">a</span>');
      }
      if(future_hours != '0'){
        $('.arm_p2__future_hours').css('opacity', 1);
        $('.arm_p2__future_hours__val').html(future_hours + ' ч.');
      }
      
      
      $('.arm_p2_statistics__stat').html('');
      
      var id = _this.data('id');
      
      $.each(arm_p2_division_stat[id], function(k, v){
        if(k > 0){
          $('.arm_p2_statistics__stat').append($('<tr><td colspan="2"><i class="br20"></i></td></tr>'));
        }
        $.each(v, function(vk, vv){
          var split = vv.split(':|:');
          var tr = $('<tr><td width="50%">' + split[0] + ':</td><td width="50%">' + split[1] + '</td></tr>');
          $('.arm_p2_statistics__stat').append(tr);
        });
      });
      
      $('.arm_p2_statistics__lists').html('');
      
      $.each(arm_p2_division_lists[id], function(k, v){
        var div = '<div class="arm_p2_overlay__win__item"><b class="fs_20 arm_p2_tcolor_white' + (v.more_info ? ' arm_p2_statistics__lists__more" data-more_info="' + v.more_info : '') + '">' + v.title + '</b><i class="br10"></i><table cellspacing="0" cellpadding="0" width="100%" class="arm_p2_tcolor_white arm_p2_statistics" style="opacity:0.8;">';
        
        $.each(v.values, function(vk, vv){
          var split = vv.split(':|:');
          div += '<tr valign="bottom"><td><div>' + split[0] + '&nbsp;</div></td><td align="right">&nbsp;&nbsp;&nbsp;<div class="set_nowrap">&nbsp;' + split[1] + '</div></td></tr>';
          
        });
        
        
        $('.arm_p2_statistics__lists').append($(div + '</table></div>'));
      });
      
      
      
      $('.arm_p2_overlay').fadeIn(100);
    });
    
    /*
    .on('click', '.arm_p2_legend', function(e){
      
      e.stopPropagation();
    });
    */
  
  
  
  $(document).on('click', '.arm_p2_statistics__lists__more', function(){
    arm_p2_overlay__win__open($(this).html(), '#ffffff', false);
    $.get(sf_prefix + '/report/more_info?id=' + current_division + '&ref=p&type=' + $(this).data('more_info'), function(html){
      $('.arm_p2_right_panel__b__cont').css({'background-image' : 'url(/i/n.gif)'});
      $('.arm_p2_right_panel__b__cont').html(html);
      $('.arm_p2_right_panel__b__cont').perfectScrollbar('update');
      
    });
  });
  
  $('.arm_p2_overlay__win__chart tr, .arm_p2_general__chart li').click(function(){
    var td_first = $(this).find('td:first');
    
    var with_close = false;
    
    if(td_first.length == 0){
      td_first = $(this).parent().parent().find('.arm_p2_tcolor_white');
      arm_p2_overlay__win__close();
      with_close = true;
    }
    
    arm_p2_overlay__win__open(td_first.text().replace('<br />', ' '), td_first.css('color'), with_close);
    
    $.get(sf_prefix + '/report/learning/' + current_division + '?ref=p&groupby=' + ($(this).data('course_type') ? 'state' : 'type') + '&type[]=' + $(this).data('type') + ($(this).data('course_type') ? '&course_type=' + $(this).data('course_type') : ''), function(html){
      $('.arm_p2_right_panel__b__cont').css({'background-image' : 'url(/i/n.gif)'});
      $('.arm_p2_right_panel__b__cont').html(html);
      $('.arm_p2_right_panel__b__cont').perfectScrollbar('update');
      
    });
    
    
  });

  $(window).resize(function(){
    var h = $('.arm_p2_right_panel').outerHeight(),
        hHeader = $('.arm_p2_right_panel__h').outerHeight(),
        hBody = h - hHeader;

    $('.arm_p2_right_panel__b').outerHeight(hBody);
    $('.arm_p2_right_panel__b__cont').outerHeight(hBody);
    
    
    $('.arm_p2_right_panel').width($('.arm_p2_w_right').width());

    $('.arm_p2_event').outerHeight($(window).height() - $('.arm_p2_event').offset().top + 0);
    
    $('.arm_p2_event').perfectScrollbar('update');
    $('.arm_p2_right_panel__b__cont').perfectScrollbar('update');
    
    legendH = $(window).outerWidth() > 1600 ? 35 : 25;
    legend_res();
  }).resize();
  $('.arm_p2_event').perfectScrollbar();
  $('.arm_p2_right_panel__b__cont').perfectScrollbar();
});

$(document).ajaxSuccess(function(event, xhr, settings) {
  $('.arm_p2_right_panel__b__cont').perfectScrollbar('update');
});