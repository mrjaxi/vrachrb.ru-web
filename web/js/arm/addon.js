/**
 * Created by Николай on 16.02.2016.
 */

var comment = {
  remove: function($this, event) {
    if (confirm('Вы уверены?')){
      sOverlay($('.lui__desktop_right'));
      var parent = $this.closest('.comment_item');
      $.ajax({
        url: $this.attr('href'),
        type: 'get',
        success: function(data) {
          parent.remove();
          alert('Данные успешно обновлены');
        }
      });
    }
    event.preventDefault();
  },
  add: function($this, event) {
    $.magnificPopup.open({
      type: 'inline',
      midClick: true
    }, 0);
    event.preventDefault();
  },
  edit: function($this, event) {

  }
};

var receptionContract = {
  remove: function($this, event) {
    if (confirm('Вы уверены?')){
      sOverlay($('.lui__desktop_right'));
      var parent = $this.closest('.comment_item_field');
      $.ajax({
        url: $this.attr('href'),
        type: 'get',
        success: function(data) {
          parent.remove();
          alert('Данные успешно обновлены');
        }
      });
    }
    event.preventDefault();
  },
  add: function($this, event) {
    $.magnificPopup.open({
      type: 'inline',
      midClick: true
    }, 0);
    event.preventDefault();
  },
  edit: function($this, event) {

  }
};

var work_location = {
  remove: function($this, event) {
    if (confirm('Вы уверены?')){
      sOverlay($('.lui__desktop_right'));
      var parent = $this.closest('.comment_item');
      $.ajax({
        url: $this.attr('href'),
        type: 'get',
        success: function(data) {
          parent.remove();
          alert('Данные успешно обновлены');
        }
      });
    }
    event.preventDefault();
  },
  add: function($this, event) {
    $.magnificPopup.open({
      type: 'inline',
      midClick: true
    }, 0);
    event.preventDefault();
  },
  edit: function($this, event) {

  }
};

var eventElem = {
  remove: function($this, event, param) {
    if (confirm('Вы уверены?')){
      sOverlay($('.lui__desktop_right'));
      var paramSplit = param.split('__');
      var parent = $this.closest('.comment_item');
      $.ajax({
        url: '/arm/event-elem/',
        type: 'get',
        data: {
          type: paramSplit[0],
          event: paramSplit[1],
          advanced_id: paramSplit[2],
          id: paramSplit[3]
        },
        success: function(data) {
          parent.remove();
          alert('Данные успешно обновлены');
        }
      });
    }
    event.preventDefault();
  },
  add: function($this, event, param) {
    var paramSplit = param.split('__');

    $('.lpu_specialist_add').each(function (idx, elem) {
      if($(elem).is(':checked')){
        paramSplit[3] = $(elem).val();
        return false;
      }
    });
    $.ajax({
      url: '/arm/event-elem/',
      type: 'get',
      data: {
        type: paramSplit[0],
        event: paramSplit[1],
        advanced_id: paramSplit[2],
        id: paramSplit[3]
      },
      success: function(data) {
        alert('Данные успешно обновлены');
      }
    });
  },
  edit: function($this, event) {

  }
};
