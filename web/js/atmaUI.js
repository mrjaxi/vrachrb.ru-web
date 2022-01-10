var atmaUI = {
  overlay: function(target, className, append){
    var div = $('<div class="sOverlay' + (className ? ' ' + className : '') + '"></div>');
    $((append ? append : 'body')).append(div);
    div.width(target.outerWidth());
    div.height(target.outerHeight());
    div.offset(target.offset());
    $(window).resize(function(){
      div.width(target.outerWidth());
      div.height(target.outerHeight());
    });
    return div;
  },
  overlayLayout: function(target, href, html, overflow){
    var so = this.overlay(target);
    if(href){
      $.get(href, function(html){
        var $html = $(html);
        var cururi = $.url();
        var layout = $('<div class="lui_form_layer"' + (overflow ? ' style="overflow-y:scroll;background-color: #fff;border:1px solid #E5E5E5;"' : '') + '><span class="lui_form_layer__before" style="visibility:hidden"></span><span class="lui_form_layer__close"></span>' + html + '</div>');
        target.append(layout);
        
        //cl(layout.find('.overflower'));
        
        var lui__scroller_class = layout.find('.overflower');
        
 
        
        if(lui__scroller_class.length){
          lui__scroller_class.outerHeight(layout.outerHeight() - lui__scroller_class.offset().top - 12);
        }
        layout.find('.lui_form_layer__close').click(function(){
          layout.remove();
          so.remove();
        });
        
      });
    }
  }
};