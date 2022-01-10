<style>
.sf_sheet_history__fields__btn{
  vertical-align: top;
  width: 24px;
  height: 24px;
  margin-left: 5px !important;
  display: inline-block;
  cursor: pointer;
}
.sf_sheet_history__fields__btn_remove{
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAVklEQVQ4jaWTuQ0AIAwDbww2ZoSMwYpUSCBe41QU9okYA5CBhD4JiHYoImTyKJCt9gVy1ZwEz7dcCeWcesNPyAPky2wDrBWsEK1ntIpkVdn+TCGae0hU9EsasVfv2tQAAAAASUVORK5CYII=') no-repeat center center;
}
.sf_sheet_history__fields__btn_up{
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAl0lEQVQ4T7XSsQnDMBBG4acxUmWEkF57ZAQ3KVIavEDKtBkhe6Q36dN4A49gDiQQyp0vIORWfh+/QIHGLzT2dAeOaeFiLd1bIPEnhSdARSwgx0MCnoCKWMAKSPxKwAV4AIf6KhowA/cizo0gI3AukRqwYhMpAS9WkQz8G/8gAnyBSbmz90hvwFWACLy9v43z2P0pu8OaF2xGshkpS9IEMwAAAABJRU5ErkJggg==') no-repeat center center;
}
.sf_sheet_history__fields__btn_down{
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAl0lEQVQ4T62SURGAIBAFlwZGMIIRjGAEIxjBCEYwglGMYAQjOG9GHFFQFPgC5nZ53JwhcZlEniyCElh+JimVYAQGYP4oqYHWfkECiWIllWCgO/cgVnLASnxt4pvEgX0C3YUkNzgk0P0E9KeeCNa5uTY6NAfFLul2QKkEr7EC1VmJ9l746Qv2IUm0bi/bgiyj/HEA3fLkBBtBdRjRkbLhLwAAAABJRU5ErkJggg==') no-repeat center center;
}
.sf_sheet_history__fields__item:first-child .sf_sheet_history__fields__btn_up{
  display: none;
}
.sf_sheet_history__fields__item:last-child .sf_sheet_history__fields__btn_down{
  display: none;
}
.sf_sheet_history__fields{
  margin: 0;
  padding: 0;
}
.sf_sheet_history__fields__item{
  margin: 0 0 15px 15px !important;
  padding: 0 0 15px 0 !important;
  border-bottom: 2px dashed #E0E0E0;
}
.sf_sheet_history__fields__item:last-child{
  border-bottom: none;
}
.sf_sheet_history__fields__types{
  position: absolute;
  box-shadow: 0px 2px 15px -2px rgba(0, 0, 0, 0.4);
  padding: 0;
  background: #fff;
  z-index: 100;
  margin-top: 10px;
  display: none;
}
.sf_sheet_history__fields__types:before{
  content: '';
  display: inline-block;
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 10px solid #ffffff;
  position: absolute;
  margin-left: 20px;
  margin-top: -10px;
}
.sf_sheet_history__fields__types__item:hover{
  background: #FFE3D7;
}
.sf_sheet_history__fields__types__item{
  padding: 5px 10px;
  cursor: pointer;
}
.radio_over_input{
  vertical-align: top;
  top: 3px;
  left: 0px;
}
.choices_inner_div{
  margin-bottom: 5px;
}
</style>
<span class="lui_pseudo bnt__add" onclick="$('.sf_sheet_history__fields__types').toggle();">Добавить вопрос</span>
<!--span class="lui_pseudo" onclick="sf_sheet_build();">Build</span-->
<input type="hidden" id="sheet_history__fields_id" name="sheet_history__fields" />
<div class="sf_sheet_history__fields__types">
<?php
foreach(SheetHistory::getTypes() as $type_key => $type_title)
{
  echo '<div class="sf_sheet_history__fields__types__item" onclick="sf_sheet_history.add(\'' . $type_key . '\', \'\', \'\', \'\');$(this).parent().hide();">' . $type_title . '</div>';
}
?>
</div>
<i class="br15"></i>
<ol class="sf_sheet_history__fields"></ol>
<script>
var sf_sheet_history = {
  add: function(type, id, html, title, is_required){
    if(html == ''){
      $.get(sf_prefix + '/sheet_history/get_template?template=' + type, function(html){
        sf_sheet_history.item(type, id, html, title, is_required);
      });
    } else {
      sf_sheet_history.item(type, id, html, title, is_required);
    }
  },
  item: function(type, id, html, title, is_required){
    var li = $('<li class="sf_sheet_history__fields__item" data-type="' + type + '" data-id="' + id + '">' +
      '<input type="text" style="width:70%;" placeholder="Подпись" value="' + title + '" class="sf_sheet_history__fields__item__title" />' +
      '&nbsp;&nbsp;<label><input type="checkbox" class="is_required"' + (!is_required ? '' : ' checked="checked"') + ' />Обязательное</label>' +
      '<span class="sf_sheet_history__fields__btn sf_sheet_history__fields__btn_remove" onclick="$(this).parent().remove();"></span>' +
      '<span class="sf_sheet_history__fields__btn sf_sheet_history__fields__btn_up" onclick="sf_sheet_build_up($(this).parent());"></span>' +
      '<span class="sf_sheet_history__fields__btn sf_sheet_history__fields__btn_down" onclick="sf_sheet_build_down($(this).parent());"></span>' +
      '<i class="br10"></i>' + html + '</li>');
    sf_sheet_history_resizeable(li);
    $('.sf_sheet_history__fields').append(li);
  }
};

var sf_sheet_build_up = function($el){
  var clone = $el.clone();
  $el.prev().before(clone);
  $el.remove();
}
var sf_sheet_build_down = function($el){
  var clone = $el.clone();
  $el.next().after(clone);
  $el.remove();
}

var sf_sheet_choices_switch = function($el){
  var type = $el.is(':checked') ? 'checkbox' : 'radio';
  $el.parent().parent().find('.radio_over_input input').attr('type', type);
  $el.parent().parent().find('.radio_over_input .custom_input').removeClass('custom_input_checkbox').removeClass('custom_input_radio').addClass('custom_input_' + type);
}

var sf_sheet_choices_add = function($el){
  var clone = $el.parent().find('.choices_inner_div').first().clone();
  clone.find('input[type=text]').val('');
  $el.parent().append(clone);
}
var sf_sheet_build = function(){
  var json = [];
  $('.sf_sheet_history__fields__item').each(function(k, el){
    var $el = $(el);
    var options = {};
    $el.find('*[data-option]').each(function(k, el){
      var $el = $(el);
      $.each($el.data('option').split(';'), function(ik, iel){
        var option = iel.split(':');
        if(option[1] == 'val()'){
          options[option[0]] = $el.val().trim();
        } else if(option[1] == 'inner'){
          var inner = [];
          $el.find('input[type=text]').each(function(tk, tel){
            inner.push($(tel).val());
          });
          options[option[0]] = inner;
        } else if($el.attr(option[1])){
          options[option[0]] = $el.attr(option[1]);
        }
      });
    });
    var item = {
      'id': $el.data('id'),
      'type': $el.data('type'),
      'title': $el.find('.sf_sheet_history__fields__item__title').val().trim(),
      'is_required': $el.find('.is_required').is(':checked'),
      'options': options
    };
    json.push(item);
  });
  $('#sheet_history__fields_id').val(JSON.stringify(json));
  cl(json);
}
var sf_sheet_history_resizeable = function(li){
  li.find('.sf_sheet_history_resizeable').each(function(){
    $(this).keyup(function(e){
      var _this = $(this);
      if(e.keyCode == 13){
        var val = _this.val().trim();
        var attr = _this.is('textarea') ? 'rows' : 'size';
        if(val == ''){
          _this.removeAttr(attr);
        } else if(!isNaN(parseInt(val))){
          _this.attr(attr, val);
        }
        _this.val('');
      }
    });
  });
};
$('.sf_admin_action_save input').click(function(){
  sf_sheet_build();
});
<?php
$sheet_history = $form->getObject();
if($form->isNew() == false)
{
  foreach($sheet_history->getSheetHistoryField() as $field)
  {
    echo "sf_sheet_history.add('" . $field['field_type'] . "', " . $field['id'] . ", '" . get_partial('sheet_history/field_' . $field['field_type'], array('edit' => true, 'field' => $field)) . "', '" . $field['title'] . "', " . ($field['is_required'] ? 'true' : 'false') . ");\n";
  }
}
?>
</script>