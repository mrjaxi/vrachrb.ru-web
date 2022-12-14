// Expromptum JavaScript Library
// Copyright Art. Lebedev | http://www.artlebedev.ru/
// License: BSD | http://opensource.org/licenses/BSD-3-Clause
// Author: Vladimir Tokmakov | vlalek
// Updated: 2015-05-08


(function(window,$){

if(window.expromptum){return}

window.expromptum=window.xP=(function(undefined){



var xP=function(params,parent){

if(!params){
params='[data-xp],[data-expromptum]';
for(var i=0,ii=xP_controls_registered.length;i<ii;i++){
params+=','
+xP.controls[xP_controls_registered[i]]
.prototype.element_selector
}
}

if(

$.type(params)==='string'

||params&&(
params.nodeType

||params[0]&&params[0].nodeType
)
){
params=$(
params,
parent
?(
parent instanceof xP.controls._item
?parent.$container
:parent
)
:null
)
}

if(params instanceof jQuery){
return xP.controls.init(params)
}else if(params instanceof Object&&parent){
return xP.controls.create(params,parent)

}else{
xP.debug('','error','unknown params',params);

return new xP.list()
}
};





xP.register=function(params){
var prototype=params.prototype||{},
expromptum=prototype.init
?function(){
this._={};
prototype.init.apply(this,arguments)
}
:null,
base=params.base;


prototype.toString=function(){return params.name};

if(base){
if(!expromptum){
expromptum=base.prototype.init
?function(){
this._={};
base.prototype.init.apply(this,arguments)
}
:function(){this._={}}
}

var f=function(){};

f.prototype=base.prototype;

expromptum.prototype=new f();

expromptum.prototype.constructor=expromptum;

expromptum.base=base.prototype
}else if(!expromptum){
expromptum=function(){}
}

$.extend(expromptum.prototype,prototype);

return expromptum
};

xP.list=function(arr){
var result=$.type(arr)==='array'?arr:(arr?[arr]:[]);

result.append=function(obj){
if(!obj){
return this
}

if($.type(obj)==='array'){
for(var i=0,ii=obj.length;i<ii;i++){
this.append(obj[i])
}

return this
}

if(this.index(obj)===-1){
this.push(obj)
}

return this
};

result.remove=function(obj){
if($.type(obj)==='array'){
var i=obj.length;

while(i--){
this.remove(obj[i])
}

return this
}

var i=this.index(obj);

if(i>-1){
this.splice(i,1)
}

return this
};

result.filter=function(handler){
return this.each(handler,true)
};

result.each=function(handler,_filter){
var i=0,ii=this.length,current,result;

while(i<ii){
current=this[i];

result=handler.apply(current,[i]);

if(result===false){
if(_filter){
this.splice(i,1)
}else{
break
}
}

if(this[i]===current){
i++
}else{
ii=this.length
}
}

return this
};

result.first=function(handler){
return this.eq(0,handler)
};

result.last=function(handler){
return this.eq(this.length-1,handler)
};

result.eq=function(i,handler){
if(!this.length){
return null
}

if(handler){
handler.apply(this[i%this.length])
}

return this[i%this.length]
};

result.index=function(obj){
var i=this.length;

while(i--){
if(obj===this[i]){
return i
}
}

return-1
};

return result
};

xP.debug=function(){
if(location.href.indexOf('xP='+arguments[0])>0){
console.log.apply(this,Array.prototype.slice.call(arguments,1));

return true
}else{
return false
}
};

xP.after=function(handler,i){
if(i){
return setTimeout(function(){xP.after(handler,--i)},0)
}else{
return setTimeout(function(){handler()},0)
}
};

xP.taint_regexp=function(value){
return value.replace(xP.taint_regexp_pattern,'\\')
};

xP.taint_regexp_pattern=/(?=[\\\^\$\.\[\]\|\(\)\?\*\+\{\}])/g;

xP.taint_css=function(value){
return value.replace(xP.taint_css_pattern,'\\')
};

xP.taint_css_pattern
=/(?=[\\\^\$\.\[\]\|\(\)\?\*\+\{\}\:\<\>\@\/\~\&\=])/g;





xP.locale={
init:function(){
var t=xP.locale.number;

$.extend(
t,
{
format:{
decimal:/\./,
grouping:/(\d\d|\d(?=\d{4}))(?=(\d{3})+([^\d]|$)())/g
},

unformat:{
decimal:new RegExp('[\\.\\'+t.decimal+']'),
grouping:new RegExp('\\'+t.grouping,'g')
}
}
)
},

destroy:function(){
},


abbr:'ru',

number:{decimal:',',grouping:' '},

date:'dd.mm.yy',

month:[
{abbr:'??????',name:'????????????',name_genitive:'????????????'},
{abbr:'??????',name:'??????????????',name_genitive:'??????????????'},
{abbr:'??????',name:'????????',name_genitive:'??????????'},
{abbr:'??????',name:'????????????',name_genitive:'????????????'},
{abbr:'??????',name:'??????',name_genitive:'??????'},
{abbr:'??????',name:'????????',name_genitive:'????????'},
{abbr:'??????',name:'????????',name_genitive:'????????'},
{abbr:'??????',name:'????????????',name_genitive:'??????????????'},
{abbr:'??????',name:'????????????????',name_genitive:'????????????????'},
{abbr:'??????',name:'??????????????',name_genitive:'??????????????'},
{abbr:'??????',name:'????????????',name_genitive:'????????????'},
{abbr:'??????',name:'??????????????',name_genitive:'??????????????'}
],

first_day:1,

weekday:[
{abbr:'????',name:'??????????????????????'},
{abbr:'????',name:'??????????????'},
{abbr:'????',name:'??????????'},
{abbr:'????',name:'??????????????'},
{abbr:'????',name:'??????????????'},
{abbr:'????',name:'??????????????'},
{abbr:'????',name:'??????????????????????'}
],

prev_month:'????????????????????',

next_month:'??????????????????',

yesterday:'??????????',

today:'??????????????',

tomorrow:'????????????'
};

xP.locale.init();





xP.base=xP.register({name:'xP.base',prototype:{

init:function(params){
this._.on_destroy=new xP.list();
this._.on_change=new xP.list();

$.extend(this,params)
},

destroy:function(handler,remove){
if(!arguments.length){
clearTimeout(this._.change_inquiry);

var that=this;

this._.on_destroy.each(function(){
this.call(that)
})
}else{
if(remove){
this._.on_destroy.remove(handler)
}else{
this._.on_destroy.append(handler)
}
}

return this
},

change:function(handler,remove){
if(!arguments.length){
if(!this._.change_inquiry){
clearTimeout(this._.change_inquiry);

var that=this;

that._.change_inquiry=xP.after(function(){
that._.change_inquiry=null;

that._.on_change.each(function(){
this.call(that)
})
})
}
}else{
if(remove){
this._.on_change.remove(handler)
}else{
this._.on_change.append(handler)
}
}

return this
},

param:function(name,value){
if(arguments.length===2){
this[name]=value
}

return this[name]
},

_param:function(name,value){
if(arguments.length===2){
this._[name]=value
}

return this._[name]
}
}});





var xP_controls_registered=[];

xP.controls={
register:function(params){
var name=params.name;

if(!params.prototype){
params.prototype={}
}

params.prototype.type=name;

this[params.name]=xP.register(
$.extend(
params,
{
name:'expromptum.controls.'+name,
base:$.type(params.base)==='string'
?this[params.base]
:params.base
}
)
);

if(params.prototype&&params.prototype.element_selector){
xP_controls_registered.push(name)
}
},

init:function($elements){
var result=new xP.list(),that=this;

$elements.each(function(){
var $element=$(this),control=that.link($element);

if(!control){
var params=$element.data('xp')
||$element.data('expromptum');

if($.type(params)==='string'){
if(!params.match(/^^\s*\{/)){
params='{'+params+'}'
}

params=eval(
'(function(){return '
+params
.replace(/([\{,])\s*do\s*:/g,'$1\'do\':')
+'})()'
)
}

$element
.removeAttr('data-xp')
.removeAttr('data-expromptum');

if(!params){
params={}
}

if(!params.type){
var i=xP_controls_registered.length;

while(i--){
if(
$element.is(
xP.controls[xP_controls_registered[i]]
.prototype.element_selector
)
){
params.type=xP_controls_registered[i];

break
}
}
}

if(
xP.controls[params.type]
&&xP.controls[params.type].base
){
params.$element=$element;

control=new xP.controls[params.type](params)
}
}

if(control){
result.append(control)
}
});

return result
},

link:function($element,control){
if(control){
$element.data('expromptum.control',control);

$element[0].expromptum=control
}else{
return $element.data('expromptum.control')
}
}
};


xP.controls.register({name:'_item',base:xP.base,prototype:{
init:function(params){
xP.controls._item.base.init.apply(this,arguments);

if(!this.$element){
this.create()
}

xP.debug('controls','control',this.type,this.$element,this);

if(!this.$container&&this.container_selector){
this.$container
=this.$element.parents(this.container_selector).first()
}

if(!this.$container||!this.$container.length){
this.$container=this.$element
}

var a=['disabled','required','autofocus','min','max','step'],
i=a.length,v;

while(i--){
v=this.$element.attr(a[i]);

if(v!==undefined&&!this[a[i]]!==undefined){
this[a[i]]=v
}
}

if(this.autofocus){

this.$element.focus()
}

















var that=this;


this._init_val();

if(this.disabled||this.enabled===false){
this.disabled=false;

xP.after(function(){
that.disable()
})
}

xP.after(function(){
that.change();
that._init_val()
});


if(!this._.parent){
this.$element.parentsUntil('body').each(function(){
var control=xP.controls.link($(this));

if(control){
that._.parent=control;

return false
}
})
}

if(this._.parent&&!(this instanceof xP.controls.form)){
this._.parent.children().each(function(){
if(that.$container.has(this.$container)[0]){
that._.parent._.children.remove(this);

this._.parent=that;

that._.children.append(this)
}
});

this._.no_root_dependencies
=this._.parent._.no_root_dependencies;

this._.parent._.children.append(this);

this._.root=this._.parent._.root
}else{
this._.root=xP.root||this
}

xP.controls.link(this.$element,this);
xP.controls.link(this.$container,this);

this.name=this.$element.attr('name')||this.name;

if(xP.repeats){
xP.repeats.init(this)
}

xP.dependencies.init(this)
},

remove:function(){
var $container=this.$container,

destroy_with_children=function(parent){
if(parent.children){
parent.children().each(function(){
destroy_with_children(this)
})
}

parent.destroy()
};

destroy_with_children(this);

$container.remove();

var parent=this.parent();

if(parent){
parent.change()
}
},

destroy:function(handler,remove){
xP.controls._item.base.destroy.apply(this,arguments);

if(!arguments.length){
if(this._.parent){
this._.parent._.children.remove(this)
}

this.$container
=this.$element
=null
}
return this
},

parent:function(){
return this._.parent
},

root:function(){
return this._.root
},

_init_val:function(){
this._.initial_value
=this._.value
=this.val()
},

val:function(value){
if(!arguments.length){
return ''
}else{
this.change();

return this
}
},

disable:function(disabled){
disabled=!arguments.length||disabled;

if(this.disabled!==disabled){
if(disabled){
this.$element.add(
this.$container.addClass(
this.container_disabled_class
)
).attr('disabled',true)
}else{
var parent=this;

while((parent=parent.parent())&&parent!=this){
if(parent.disabled){
return this
}
}

this.$element.add(
this.$container.removeClass(
this.container_disabled_class
)
).removeAttr('disabled')
}

this.disabled=disabled;

this.change()
}
return this
},

container_disabled_class:'disabled',

_get_html:function(){
return this.html
}
}});


xP.controls.register({name:'html',base:'_item',prototype:{
element_selector:'.xp_html',

val:function(value){
if(!arguments.length){
return this.disabled?undefined:this.$element.html()
}else{
this.$element.html(value);

this.change();

return this
}
}
}});


xP.controls.register({name:'_parent',base:'_item',prototype:{
element_selector:'.xp',

init:function(params){
this.changed={};

this._.children=new xP.list();

xP.controls._parent.base.init.apply(this,arguments);

this._.$pocus=this.$element;

this._.children_values={};

var parent4values=this._.parent||this._.root;

while(
!parent4values.name
&&!parent4values.repeat
&&parent4values._.parent
&&parent4values!==this._.root
){
parent4values=parent4values._.parent
}

this._.parent4values=parent4values
},

children:function(){
return this._.children
},

destroy:function(handler,remove){
if(!arguments.length){
this._.parent4values._unsave_val(this)
}

return xP.controls._parent.base.destroy.apply(this,arguments)
},

disable:function(disabled){
disabled=!arguments.length||disabled;

if(this.disabled!==disabled){
xP.controls._parent.base.disable.apply(this,arguments);

if(this.disabled){
this._.parent4values._unsave_val(this)
}

this._.children.each(function(){
this.disable(disabled)
})
}

return this
},

val:function(value,_suffix){
if(!arguments.length){
return this._.children_values
}else{
if(this.repeat){

if($.type(value)!=='array'){
value=[value]
}

var siblings=this.repeat.children(),
ii=value.length,
sibling;

while(siblings.length>ii){
this.repeat.remove(siblings[siblings.length-1])
}

for(var i=0;i<ii;i++){
sibling=siblings[i];
if(!sibling){
sibling=this.repeat.append(siblings[i-1])
}
sibling._set_vals(
value[i],
_suffix+this.repeat.name_suffix_before
+i+this.repeat.name_suffix_after
)
}
}else{
this._set_vals(value,'')
}

return this
}
},

_set_vals:function(value,suffix){
var that=this;
xP.after(function(){
$.each(value,function(name,value){
var controls=that._find_by_name(name)
||that._find_by_name(name+suffix);

if(controls){
for(var i=0,ii=controls.length;i<ii;i++){
controls[i].val(value,suffix)
}
}
})
},4)

},

change:function(handler,remove){
if(!arguments.length&&this._.parent4values){
this._.parent4values._save_val(this)
}

return xP.controls._parent.base.change.apply(this,arguments)
},

_save_val:function(child){
if(child.name){
if(child.repeat){
var values=this._.children_values[child.name];

if($.type(values)!=='array'){
values=this._.children_values[child.name]=[]
}

if(!child._.repeat_template){
values[child._.repeat_position]=child.val()
}
}else{

var name=this.repeat
?child.name.split(
this.repeat.name_suffix_splitter
)[0]
:child.name;

if(child instanceof xP.controls.checkbox){

this._.children_values[name]=values=[];

if(!child._.group){
return
}

child._.group.siblings.each(function(){
var value=this.val();

if(value!==''){
values.push(value)
}
})
}else{
this._.children_values[name]=child.val()
}
}
}
},

_unsave_val:function(child){
if(child.name){
if(child.repeat){
var values=this._.children_values[child.name];

if(!child._.repeat_template){
values.splice(child._.repeat_position,1)
}
}else{
var name=this.repeat
?child.name.split(
this.repeat.name_suffix_splitter
)[0]
:child.name;

delete this._.children_values[name]
}
}
},

focus:function(){
var that=this,f=function(){
that._.$pocus.focus()[0].scrollIntoView()
};

if(!this._.$pocus.is(':visible')){
var parent=this;

while((parent=parent.parent())&&parent!=this){
if(parent.show){
parent.show(f)
}
}
}else{
f()
}

return this
},

_find_by_name:function(name){
var result=[],subresult;

this.children().each(function(){
if(this.name==name){
subresult=[this]
}else if(this._find_by_name){
subresult=this._find_by_name(name)
}else{
subresult=null
}

if(subresult){
result=result.concat(subresult);

if(!(subresult[0] instanceof xP.controls._option)){
return false
}
}
});

return result.length?result:null
}
}});


xP.controls.register({name:'form',base:'_parent',prototype:{
element_selector:'form',

init:function(params){

this.completed_on_required=true;
this.completed_on_valid_required=true;



xP.controls.form.base.init.apply(this,arguments);

this._.root=this;


if(this.uncomplete_if_invalid!==undefined){
this.completed_on_valid=this.uncomplete_if_invalid
}

if(this.uncomplete_if_unchanged!==undefined){
this.completed_on_changed=this.uncomplete_if_unchanged
}


this._.onsubmit=new xP.list();

var that=this;

this.$element.bind('submit',function(){
return that.submit()
});

this.submit(function(){
var uncompleted=this.uncompleted();

if(uncompleted){
xP.debug('submit',uncompleted);

return false
}else if(this.locked){
xP.debug('submit','locked');

return false
}else{
this.locked=true
}

return!xP.debug('submit','submit')
})
},

submit:function(handler,remove){
if(!arguments.length){
var that=this,result=true;

this._.onsubmit.each(function(){
if(!this.call(that)){
result=false
}
});

return result
}else{
if(remove){
this._.onsubmit.remove(handler)
}else{
this._.onsubmit.append(handler)
}
return this
}
},

uncompleted:function(){
if(
this.completed_on_required
&&this._.required
&&$.grep(
this._.required,
function(ctrl){return!ctrl.disabled}
).length
){
return 'required'
}

if(
this.completed_on_valid_required
&&this._.invalid
&&$.grep(
this._.invalid,
function(ctrl){return!ctrl.disabled&&ctrl.required}
).length
){
return 'invalid_required'
}

if(
this.completed_on_valid
&&this._.invalid
&&$.grep(
this._.invalid,
function(ctrl){return!ctrl.disabled}
).length
){
return 'invalid'
}

if(
this.completed_on_changed
&&!(
this._.changed
&&$.grep(
this._.changed,
function(ctrl){return!ctrl.disabled}
).length
)
){
return 'unchanged'
}

return null
}
}});


xP.controls.register({name:'fields',base:'_parent',prototype:{
element_selector:'fieldset,.fields,.sheets',

init:function(params){
xP.controls.fields.base.init.apply(this,arguments);

var id=this.$element.attr('id');

if(!this.$label&&id){
this.$label=$("[for='"+xP.taint_css(id)+"']")
}
},

count:function(){
if(this.disabled||!this.children().length){
return undefined
}

var result=0;

this.children().each(function(){
if(this instanceof xP.controls.fields){
if(this.val()){
result++
}
}else if(this.val()!=''){
result++
}
});

return result
}
}});


xP.controls.register({name:'sheet',base:'fields',prototype:{
element_selector:'.sheet',

init:function(params){
xP.controls.sheet.base.init.apply(this,arguments);

if(this.$label&&this.$label[0]){
var parent=this.parent(),that=this;

this.select(
!parent._param('selected_sheet')
||this.selected
||this.$label.hasClass(this.selected_class)
);

this.$label.click(function(){
that.select()
})
}
},

show:function(complete){
if(!this.selected){
this.select();

if(complete){
complete()
}
}

return this
},

select:function(select){
if(this.disabled){
return this
}

this.selected=!arguments.length||select;

if(this.selected){
var parent=this.parent(),
previous=parent._param('selected_sheet');

if(previous!==this){
if(previous){
previous.select(false)
}

parent._param('selected_sheet',this);

this.$container.add(this.$label)
.removeClass(this.unselected_class)
.addClass(this.selected_class)
}
}else{
this.$container.add(this.$label)
.removeClass(this.selected_class)
.addClass(this.unselected_class)
}
return this
},

disable:function(disabled){
xP.controls.sheet.base.disable.apply(this,arguments);

if(this.disabled){
this.$label
.addClass(this.container_disabled_class)
.attr('disabled',true)
}else{
this.$label
.removeClass(this.container_disabled_class)
.removeAttr('disabled',true)
}

return this
},

selected_class:'selected',
unselected_class:'unselected'
}});


xP.controls.register({name:'foldable',base:'fields',prototype:{
element_selector:'.foldable',
duration:200,

init:function(params){

xP.controls.foldable.base.init.apply(this,arguments);

if(this.$label&&this.$label[0]){
this.fold(
!(
this.unfolded
||this.$label.hasClass(this.unfolded_class)
),
null,
1
);

var that=this;

this.$label.click(function(){
that.fold(that.unfolded)
})
}
},

show:function(complete){
return this.fold(false,complete)
},

fold:function(fold,complete,_duration){
this.unfolded=!fold;

if(fold){
this.$container.add(this.$label)
.removeClass(this.unfolded_class)
.addClass(this.folded_class);

this.$container.slideUp(_duration||this.duration,complete)
}else{
this.$container.add(this.$label)
.removeClass(this.folded_class)
.addClass(this.unfolded_class);

this.$container.slideDown(_duration||this.duration,complete)
}

return this
},

folded_class:'folded',
unfolded_class:'unfolded'
}});


xP.controls.register({name:'_field',base:'_parent',prototype:{
element_selector:'input',
container_selector:'.field',

init:function(params){
xP.controls._field.base.init.apply(this,arguments);

var that=this;

this.$element.bind(this.change_events,function(){
that.change()
});

this.$element.blur(function(){
that.$container.addClass(that.container_blured_class)
});

var id=this.$element.attr('id');

if(!this.$label&&id){
this.$label=$("[for='"+xP.taint_css(id)+"']")
}

if(this.$container==this.$element){
this.$container=this.$container.add(this.$label)
}

if(this.allow_chars_pattern){
this.$element.keypress(function(ev){
if(
ev.charCode
&&!(ev.metaKey||ev.ctrlKey||ev.altKey)
&&!String.fromCharCode(ev.charCode).match(
that.allow_chars_pattern
)
){
return false
}
})
}

xP.controls._field.base.change.apply(this)
},

change_events:'keyup input change',
container_blured_class:'blured',

destroy:function(handler,remove){
if(!arguments.length){
this.$label=null
}

return xP.controls._field.base.destroy.apply(this,arguments)
},

change:function(handler,remove){
if(!arguments.length){
var that=this,
changed=false,
old=this._param('value'),
cur=this.val();

if(old!==cur){
changed=true;
this._param('value',cur)
}

if(changed){
return xP.controls._field.base.change.apply(
this,
arguments
)
}else{
return this
}
}else{
return xP.controls._field.base.change.apply(this,arguments)
}
},

val:function(value){
if(!arguments.length){
return this.disabled?undefined:this.$element.val()
}else{
var el=this.$element[0];

if(this.$element.is(':focus')){
var start=el.selectionStart,
end=el.selectionEnd
}

if(el.value!=value){
el.value=value;

if(this.$element.is(':focus')){
el.selectionStart=start;

el.selectionEnd=end
}

this.change()
}

return this
}
}
}});


xP.controls.register({name:'string',base:'_field',prototype:{
element_selector:'input[type=text],input:not([type])'
}});


xP.controls.register({name:'text',base:'_field',prototype:{
element_selector:'textarea'
}});


xP.controls.register({name:'file',base:'_field',prototype:{
element_selector:'input[type=file]'
}});


xP.controls.register({name:'button',base:'_parent',prototype:{
element_selector:'input[type=button],button,.button',

init:function(params){
xP.controls.button.base.init.apply(this,arguments);

this._.on_click=new xP.list();

var that=this;

this.$element.click(function(){
if(that._.on_click.length){
that.click();

return false
}
})
},

click:function(handler,remove){
if(!arguments.length){
if(!this.disabled){
var that=this;

this._.on_click.each(function(){
this.call(that)
})
}
}else{
if(remove){
this._.on_click.remove(handler)
}else{
this._.on_click.append(handler)
}
}

return this
}
}});


xP.controls.register({name:'submit',base:'_item',prototype:{
element_selector:'input[type=submit],button[type=submit]'
}});


xP.controls.register({name:'select',base:'_field',prototype:{
element_selector:'select',

hide_disabled_option:true,
enable_by:'value',

init:function(params){
xP.controls.select.base.init.apply(this,arguments);

this._.element=this.$element[0];
this._.options=this._.element.options;

this._.all_options=xP.list(this._.all_options);
this._.enabled_options=xP.list(this._.enabled_options);

for(var i=0,ii=this._.options.length;i<ii;i++){
this._.all_options.append(this._.options[i]);
this._.enabled_options.append(this._.options[i])
}
},

append:function(params){
if($.type(params)!=='array'){
params=[params]
}

var options=this._.options,i=0,ii=params.length,iii;

for(;i<ii;i++){
iii=options.length;

options[iii]=$.type(params[i])==='array'
?new Option(params[i][0],params[i][1])
:(
$.type(params[i])!=='object'
?new Option(params[i])
:(
params[i] instanceof Option
?params[i]
:new Option(params[i].label,params[i].value)
)
);

this._.all_options.push(options[iii]);

this._.enabled_options.push(options[iii])
}

return this
},

remove:function(){
this._.options.length=0;
this._.all_options.length=0;
this._.enabled_options.length=0;

return this
},

disable:function(disabled,dependence){
if(dependence&&dependence.values!==undefined){

var that=this,values=dependence.values,
i,ii=values.length;

if(!this._.enable_options){
this._.enabled_options.length=0
}

this._.all_options.each(function(){
var disable=true;

if(!disabled){
for(i=0;i<ii;i++){
if($.type(values[i])==='regexp'){
disable
=!this[that.enable_by].match(values[i])
}else{
disable=this[that.enable_by]!=values[i]
}

if(!disable){
that._.enabled_options.append(this);

break
}
}
}

if(disable){
this.disabled='true'
}
});

clearTimeout(this._.enable_options);

this._.enable_options=xP.after(function(){
that._.enable_options=null;

var options=that._.options;

if(that.hide_disabled_option){
options.length=0;

that._.enabled_options.each(function(i){
this.disabled='';

if(!this.parentNode){
options[options.length]=this
}
})
}else{
that._.enabled_options.each(function(i){
this.disabled=''
});

var selected=that._.element.selectedIndex;

if(!options[selected]||options[selected].disabled){
that._.element.selectedIndex=
that._.all_options.index(
that._.enabled_options.first()
)
}
}

that.change()


});

return this
}else{
return xP.controls.select.base.disable.apply(this,arguments)
}
},

val:function(value){
if(!arguments.length){
return this.disabled?undefined:this.$element.val()
}else{
if(this._.element.value!=value){
this.$element.val(value);

this.change()
}

return this
}
},

text:function(){
if(this.disabled){
return undefined
}else{
var option=this._.options[this._.element.selectedIndex];
return option?option.text:null
}
},

change:function(handler,remove){
if(!arguments.length){
this.$selected=$(this._.options[this._.element.selectedIndex])
}

return xP.controls.select.base.change.apply(this,arguments)
}
}});


xP.controls.register({name:'options',base:'fields',prototype:{
element_selector:'.options'
}});


xP.controls.register({name:'selectus',base:'options',prototype:{
element_selector:'.selectus',

init:function(params){
xP.controls.selectus.base.init.apply(this,arguments);


var $options=this.$element.find('[type=radio],[type=checkbox]'),
that=this;

this.$selectors=this.$container.find('.'+this.selectors_class);

this.options=xP($options).each(function(i){
var option=this;

option.$element.on('focus',function(){
option.$container.addClass('focus')
}).on('blur',function(){
option.$container.removeClass('focus')
}).on('keydown',function(ev){
if(ev.keyCode===40||ev.keyCode===38){
var ii=i+(ev.keyCode===40?1:-1);

if(ii>=0&&ii<that.options.length){
that.options.eq(ii).$element.focus()
.not('[type=checkbox]').click()
}
return false
}
})
});

if(!this.$selectors[0]){
this.options.each(function(){
$options=$options.add(this.$container)
});

this.$selectors=$options
.wrapAll('<div class="'+this.selectors_class+'"></div>')
.parents('.'+this.selectors_class)
}

this.close();

this.$select=$('<ins class="'+this.select_class+'" tabindex="0"></ins>')
.insertBefore(this.$selectors);

new xP.dependencies.computed(
{
from:this.options,
on:function(){
var html='';

that.options.first()._.group.siblings.each(function(){
if(this.$element.is(':checked')){
html+='<ins class="selected">'
+this.$label.html()+'</ins>'
}
});
return html
}
},
new xP.controls.html({
$element:this.$select,
type:'html'
})
);

this.$select.on('mouseup keypress',function(ev){
if(
!that.disabled
&&(
ev.type==='mouseup'
||ev.keyCode===13
||ev.keyCode===40
)
){
if(that.$selectors.hasClass('hidden')){
that.open();

return false
}
}
});

this.$selectors.on('keydown',function(ev){
if(ev.keyCode===13||ev.keyCode===27){
that.close();

that.$select.focus();

return false
}
}).on('mouseup',function(){
return false
})
},

selectors_class:'selectors',
select_class:'select',

open:function(){
if(xP.controls.opened){
xP.controls.opened.close()
}

xP.controls.opened=this;

var that=this;

this.$selectors.removeClass('hidden');

if(this.options.first()._.group.selected){
this.options.first()._.group.selected.$element.focus()
}else{
this.options.first().$element.focus()
}

return this
},

close:function(){
xP.controls.opened=null;

this.$selectors.addClass('hidden');

return this
}
}});


$(document).bind('mouseup.expromptum_controls_opened',function(ev){
if(xP.controls.opened){
xP.controls.opened.close()
}
});


xP.controls.register({name:'_option',base:'_field',prototype:{
container_selector:'.option',

init:function(params){
xP.controls._option.base.init.apply(this,arguments);

if(!this.root()._param('_option')){
this.root()._param('_option',{})
}

if(!this.root()._param('_option')[this.name]){
this.root()._param('_option')[this.name]
={siblings:new xP.list()}
}

this._.group=this.root()._param('_option')[this.name];

this._.group.siblings.append(this);

this.selected=null;

this._init_val()
},

destroy:function(handler,remove){
if(!arguments.length){
if(this._.group.selected===this){
this._.group.selected=null
}

this.disable();

this._.group.siblings.remove(this)
}

return xP.controls._option.base.destroy.apply(this,arguments)
},


append:function(params){
if($.type(params)!=='array'){
params=[params]
}

var i=0,ii=params.length,html='',id;

for(;i<ii;i++){
id=this.name+'_'+(this._.group.siblings.length+i);

html+='<div class="option"><input type="'+this.type
+'" name="'+this.name+'" id="'+id
+'" value="'+(
$.type(params[i])==='array'
?params[i][1]
:params[i]
)+'"/><label for="'+id+'">'+(
$.type(params[i])==='array'
?params[i][0]
:params[i]
)+'</label></div>'
}

return xP($(html).insertAfter(this.$container).find('input'))
},

change_events:'change',

change:function(handler,remove){
this.select(this.$element.is(':checked'),true);

xP.controls._option.base.change.apply(this,arguments);

return this
},

_init_val:function(){


if(this.selected){
this.$container.addClass(this.container_initial_selected_class)
}

xP.controls._option.base._init_val.apply(this,arguments)
},

container_initial_selected_class:'initial_selected',
container_selected_class:'selected',

val:function(value){
if(!arguments.length){
return!this.selected
?''
:xP.controls._option.base.val.apply(this,arguments)
}else if($.type(value)==='array'){
var i=value.length;

while(i--){
if(this.$element[0].value==value[i]){
break
}
}

this.select(i>-1)
}else{
this.select(this.$element[0].value==value)
}

return this
},

select:function(selected,_onchange){
selected=!arguments.length||selected;

if(this.selected!==selected){
this.selected=selected;

this.$container.toggleClass(
this.container_selected_class,
selected
);

if(selected){
this.$element.attr('checked',true);
this.$element[0].checked=true
}else{
this.$element.removeAttr('checked')
}

if(!_onchange){
this.change()
}
}
return this
}
}});


xP.controls.register({name:'radio',base:'_option',prototype:{
element_selector:'input[type=radio]',

disable:function(disabled){
disabled=!arguments.length||disabled;

if(this.disabled!==disabled){
xP.controls.radio.base.disable.apply(this,arguments);

if(disabled){
if(this.selected){
var that=this;


this._.group.siblings.each(function(){
if(!this.disabled&&this!==that){
this.select();

return false
}
})

}
}else if(
this._.group.selected
&&this._.group.selected.disabled
&&this._.group.selected!=this
){
this.select();

this.change()
}
}
return this
},

select:function(selected,_onchange){
selected=!arguments.length||selected;

if(this.selected!==selected){
if(selected&&this._.group){
var that_selected=this._.group.selected;

this._.group.selected=this;

if(that_selected){

that_selected.select(false)

}
}
xP.controls.radio.base.select.apply(this,arguments)
}

return this
}
}});


xP.controls.register({name:'checkbox',base:'_option',prototype:{
element_selector:'input[type=checkbox]'
}});


xP.controls.register({name:'email',base:'_field',prototype:{
element_selector:'.email input,input.email',
valid:/^\S+@\S+\.\S{2,}$/
}});


xP.controls.register({name:'phone',base:'_field',prototype:{
element_selector:'.phone input,input.phone',
valid:/^(?=[^()]*\(([^()]*\)[^()]*)?$|[^()]*$)(?=[\s(]*\+[^+]*$|[^+]*$)([-+.\s()]*\d){10,18}$/
}});


xP.controls.register({name:'_secret',base:'_field',prototype:{
init:function(params){
xP.controls._secret.base.init.apply(this,arguments);

this.$secret=$(
$('<div>')
.append(this.$element.clone().hide())
.html()
.replace(/\s+(type|id)\s*=\s*[^\s>]+/g,'')
).insertAfter(this.$element);

this.$element.removeAttr('name');

xP.controls.link(this.$secret,this)
},

change:function(handler,remove){
if(!arguments.length&&this.$secret){
this.$secret.val(this.val())
}

return xP.controls._secret.base.change.apply(this,arguments)
},

destroy:function(handler,remove){
if(!arguments.length){
this.$secret=null
}

return xP.controls._secret.base.destroy.apply(this,arguments)
},

disable:function(disabled){
disabled=!arguments.length||disabled;

if(this.disabled!==disabled){
if(disabled){
this.$secret.attr('disabled',true)
}else{
this.$secret.removeAttr('disabled')
}
xP.controls._secret.base.disable.apply(this,arguments)
}

return this
}
}});


xP.controls.register({name:'password',base:'_secret',prototype:{
element_selector:'input[type=password]',

init:function(params){
xP.controls.password.base.init.apply(this,arguments);

var that=this;

this.$secret.bind(this.change_events,function(){
that.$element.val(that.$secret.val())
});

this.control_button_view
=$(this.control_button_view_html)
.insertAfter(this.$secret)
.click(function(){
if(that.disabled){
return false
}

that.$container.toggleClass(
that.container_view_class
);

that.control_button_view.toggleClass(
that.control_button_view_class
);

that.$element.toggle();

that.$secret.toggle();

(
that.$secret.is(':visible')
?that.$secret
:that.$element
).focus()[0].selectionStart=1000
})
},

container_view_class:'alt',
control_button_view_class:'control_button_password_view',
control_button_view_html:
'<span class="control_button control_button_password"/>'
}});


xP.controls.register({name:'number',base:'_secret',prototype:{
element_selector:'input.number,.number input',

step:1,
min:1-Number.MAX_VALUE,
def:0,
max:Number.MAX_VALUE-1,
locale:xP.locale,

init:function(params){
this.allow_chars_pattern=new RegExp(
'^[-0-9'
+this.locale.number.decimal
+this.locale.number.grouping
+']$'
);

this.valid='[this].min<=[this]&&[this]<=[this].max';

xP.controls.number.base.init.apply(this,arguments);

this.$element.wrap(this.element_wrap_html);

var that=this;

$(this.control_button_dec_html)
.insertBefore(this.$element)
.mousedown(function(){
if(!that.disabled){
that.dec()
}

return false
});

$(this.control_button_inc_html)
.insertAfter(this.$element)
.mousedown(function(){
if(!that.disabled){
that.inc()
}

return false
});

this.$element
.val(this._format(this.$element.val()))
.keydown(function(ev){
if(ev.which===38){ 
that.inc();

return false
}else if(ev.which===40){ 
that.dec();

return false
}
});

this.$element.blur(function(){
that.val(that.val())
})
},

element_wrap_html:'<ins class="number_control"/>',

control_button_dec_html:
'<span class="control_button control_button_dec"/>',

control_button_inc_html:
'<span class="control_button control_button_inc"/>',

inc:function(){
var value=this.val();

if(!value&&value!==0){
this.val(value=this.def)
}

value=value-0+this.step*1;

if(value>this.max*1){
return false
}else if(value<this.min*1){
value=this.min
}

return this.val(value)
},

dec:function(){
var value=this.val();

if(!value&&value!==0){
this.val(value=this.def)
}

value=value-this.step*1;

if(value<this.min*1){
return false
}else if(value>this.max*1){
value=this.max
}

return this.val(value)
},

param:function(name,value){
if(
(name==='min'&&this.val()<value)
||(name==='max'&&this.val()>value)
){
this.val(value)
}

if((name==='min'||name==='max')&&this.valid.process){
var result=xP.controls.number.base.param.apply(
this,arguments
);

this.valid.process();

return result
}

return xP.controls.number.base.param.apply(
this,arguments
)
},

val:function(value){
if(!arguments.length){
return this.disabled
?undefined
:this._unformat(this.$element.val())
}else{
value=this._unformat(value);
this.$secret.val(value);

return xP.controls.number.base.val.apply(
this,
[this._format(value)]
)
}
},

_format:function(value){
var num=this.locale.number;

value=(value+'').split('.');

return value[0].replace(num.format.grouping,'$1'+num.grouping)
+(value[1]
?num.decimal+value[1]
:'')
},

_unformat:function(value){
var num=this.locale.number;

return value!==''
?((value+'')
.replace(num.unformat.grouping,'')
.replace(num.unformat.decimal,'.')*1)
.toPrecision(15)*1
:''
}
}});


xP.controls.register({name:'datemonth',base:'_field',prototype:{
element_selector:'input.datemonth,.datemonth input',

locale:xP.locale,

init:function(params){

xP.controls.datemonth.base.init.apply(this,arguments);

this.$element.wrap(this.element_wrap_html);

this.$element.hide();

this._.values=params.$element.val().split(this._split_pattern);

if(this._.values.length<2){
this._.values=['','','','','']
}

var html='',
format=this.locale.date.split(this._split_pattern);

for(var i=0,ii=format.length;i<ii;i++){
if(format[i]=='yy'){
html+=this._number_begin_html+',min:0" value="'
+this._.values[0]
+'" size="4" maxlength="4" class="year"/>'
}else if(format[i]=='mm'){
html+='<select class="month">';
for(var j=1;j<13;j++){
html+='<option value="'+j+'"'
+(j==this._.values[1]?' selected="true"':'')
+'>'
+this.locale.month[j-1][this._month_name]
+'</option>'
}
html+='</select>'
}else if(format[i]=='dd'){
if(this._month_name==='name'){
html+='<input type="hidden" value="1"'
}else{
html+=this._number_begin_html
+',min:1,max:31" value="'
+(this._.values[2]!==undefined?this._.values[2]:'')
+'" size="2" maxlength="2"'
}
html+=' class="day"/>'
}
}

var $pseudo=$(html).insertBefore(this.$element);

this._.$pocus=$pseudo.filter('input,select').first();

this._.$pseudo=$(
[$pseudo.filter('.year'),
$pseudo.filter('.month'),
$pseudo.filter('.day')]
);


var that=this;

this._.pseudo=xP(this._.$pseudo).each(function(){
if(this.max!==31){
this.change(function(){
that._.pseudo[2].param(
'max',
33-new Date(
that._.pseudo[0].val(),
that._.pseudo[1].val()-1,
33
).getDate()
)
})
}

this.change(function(){
that._change_pseudo()
})
});

this.change(function(){
that._change_val();

var val=this.val();

if(val){
var m=val.match(/(\d+)/g);

this._.date=new Date(
m[0],m[1]-1,m[2]||1,m[3]||0,m[4]||0,0
)
}
})
},

element_wrap_html:'<ins class="date_control"/>',

_month_name:'name',

_split_pattern:/[-\s:.\/\\]/,

_spliters:['-',''],

_number_begin_html:'<input data-xp="type:\'number\','
+'container_selector:\'.none\','
+'allow_chars_pattern:/\\d/,'
+'_format:function(v){return v},'
+'_unformat:function(v){return v}',

date:function(date){
if(!arguments.length){
return this._.date
}else{
this.val(
date.getFullYear()+'-'
+(date.getMonth()+1+'').replace(/^(\d)$/,'0$1')+'-'
+(date.getDate()+'').replace(/^(\d)$/,'0$1')+' '
+(date.getHours()+'').replace(/^(\d)$/,'0$1')+':'
+(date.getMinutes()+'').replace(/^(\d)$/,'0$1')
);

return this
}
},

_change_pseudo:function(){
if(!this.disabled){
var i=0,ii=this._spliters.length,val,value='',s;

for(;i<ii;i++){
val=this._.pseudo[i].val();

if(!val){
value='';

break
}else{
s='000'+val;
value+=s.substr(s.length-(!i?4:2))
+this._spliters[i]
}
}

this.val(value)
}
},

_change_val:function(){
var a;

if(
!this.disabled
&&(a=this.val())
&&(a=a.split(this._split_pattern)).length
){
var i=0,
ii=this._.pseudo.length<a.length
?this._.pseudo.length:a.length;

for(;i<ii;i++){
if(a[i]){
this._.pseudo[i].val(a[i]*1)
}
}
}
}
}});


xP.controls.register({name:'date',base:'datemonth',prototype:{
element_selector:'input.date,.date input',

_month_name:'name_genitive',

_spliters:['-','-','']
}});


xP.controls.register({name:'datetime',base:'date',prototype:{
element_selector:'input.datetime,.datetime input',

_spliters:['-','-',' ',':',''],

init:function(params){
xP.controls.datetime.base.init.apply(this,arguments);

var html=this._number_begin_html+',min:0,max:23" value="'
+(this._.values[3]!==undefined?this._.values[3]:'')
+'" size="2" maxlength="2" class="hours"/>'
+'<span class="time_spliter"></span>'
+this._number_begin_html+',min:0,max:59" value="'
+(this._.values[4]!==undefined?this._.values[4]:'')
+'" size="2" maxlength="2" class="minutes"/>';

var $time=$(html).insertBefore(this.$element),that=this;

this._.pseudo.append(xP($time).each(function(){
this.change(function(){
that._change_pseudo()
})
}));

this._.$pseudo.add($time)
}

}});


xP.controls.register({name:'combobox',base:'string',prototype:{
element_selector:'.combobox input,input.combobox,input[list]',

search_from_start:true,
case_sensitive:false,

init:function(params){
xP.controls.combobox.base.init.apply(this,arguments);

var $list=$(
"select#"+xP.taint_css(this.$element.attr('list')+'')
);

if($list[0]){
$list.addClass('combolist');

var list=xP($list).first(),
that=this;

this.list=list;

list.combobox=this;

this.change(function(){
if(list._param('do_not_filter')){
return
}

var value=that.val(),i=0,ii=list._.options.length,
options=list._.options;

options.length=0;

if(value!=''&&value!=undefined){
var mask=new RegExp(
(that.search_from_start?'^':'')
+xP.taint_css(value),
that.case_sensitive?'':'i'
);

list._.enabled_options.each(function(i){
if(this.text.match(mask)){
this.disabled='';

options[options.length]=this
}
})
}else{
list._.enabled_options.each(function(i){
this.disabled='';

options[options.length]=this
})
}

xP.after(function(){
var selected_index=list._.element.selectedIndex;

list._.element.selectedIndex=-1;

if(list._.options.length===0){
list.hide()
}else if(value){
if(!that.case_sensitive){
value=value.toLowerCase()
}

var i=0,ii=list._.options.length;

for(;i<ii;i++){
var ovalue=list._.options[i].text;

if(!that.case_sensitive){
ovalue=ovalue.toLowerCase()
}

if(value===ovalue){
list._.element.selectedIndex=i;
break
}
}
}

if(selected_index!==list._.element.selectedIndex){
list.$element.change()
}

if(that.$element.is(':focus')){
list.show()
}

list._param('do_not_show',false)
})
});

this.$element
.bind('focus',function(ev){
list._param('do_not_filter',false);
list.show()
})
.bind('blur',function(ev){
list.hide()
})
.bind('keydown',function(ev){
if(ev.keyCode===38||ev.keyCode===40){

list._param('do_not_filter',true);
list._param('do_not_hide',true);
list.show();
list.$element.focus();
if(list.$element[0].selectedIndex===-1){
list.$element[0].selectedIndex=0;
list.$element.change()
}
}else if(ev.keyCode===13||ev.keyCode===27){
list.hide()
}
});

list.$element
.bind('blur',function(ev){
list._param('do_not_hide',false);
list.hide()
})
.bind('change',function(ev){
var value=list.text();
if(value){
that.val(value)
}
})
.bind('mousedown',function(ev){
list._param('do_not_hide',true)
})
.bind('click keyup',function(ev){
if(
ev.type==='click'
||ev.keyCode===13
||ev.keyCode===27
){
list._param('do_not_filter',false);
list._param('do_not_show',true);
that.val(list.text());
that.$element.focus();
list.hide()
}
})
}
}
}});


xP.controls.register({name:'_combolist',base:'select',prototype:{
element_selector:'.combolist select,select.combolist',

init:function(params){
xP.controls._combolist.base.init.apply(this,arguments);

this.$element
.css({'position':'absolute','z-index':888})
.attr('size',7).attr('tabIndex',-1)
.hide()[0].selectedIndex=-1;

},

show:function(){
if(!this._param('do_not_show')&&this._.options.length){
this.$element.show();

var offset=this.combobox.$element.offset();

offset.top+=this.combobox.$element.outerHeight();

this.$element.offset(offset)
}
return this
},

hide:function(){
if(!this._param('do_not_hide')){
this.$element.hide()
}
return this
}
}});


xP.controls.register({name:'hidden',base:'_field',prototype:{
element_selector:'input[type=hidden]'
}});





var xP_dependencies_registered=[];

xP.dependencies={
_controls:{},
_functions:[],

register:function(params){
var name=params.name;

if(!params.prototype){
params.prototype={}
}

params.prototype.type=name;

this[params.name]=xP.register(
$.extend(
params,
{
name:'expromptum.dependencies.'+name,
base:$.type(params.base)==='string'
?this[params.base]
:params.base
}
)
);

xP_dependencies_registered.push(name)
},

init:function(params,control){
var that=this;

xP.after(function(){
if(!control&&params instanceof xP.controls._item){
control=params
}

var i=0,ii=xP_dependencies_registered.length,param;

for(;i<ii;i++){
param=params[xP_dependencies_registered[i]];

if(param&&!(param instanceof xP.dependencies._item)){
if($.type(param)==='array'){
for(var j=0,jj=param.length;j<jj;j++){
new that[xP_dependencies_registered[i]](
param[j],control
)
}
}else{
new that[xP_dependencies_registered[i]](
param,control
)
}
}
}
})
}
};

xP.dependencies.register({name:'_item',base:xP.base,prototype:{
init:function(params,control){

if($.type(params)==='string'){
params={on:params}
}

if(!control){
control=params.to
}else{
this.to=control
}

xP.dependencies._item.base.init.apply(this,arguments);

var that=this,
root=control.parent&&control.parent()
?control.root():null;

var parse_controls=function(param,values){
if($.type(param)!=='array'){
param=[param]
}

var result=new xP.list();
for(var i=0,ii=param.length;i<ii;i++){
if($.type(param[i])==='string'){
result.append(xP(param[i]),root)
}else{
result.append(param[i])
}
}

if(values!==undefined){
result.filter(function(){
if(!this.$element.is('[value]')){
return true
}else{
var i,ii=values.length;
for(i=0;i<ii;i++){
if($.type(values[i])==='regexp'){
if(
this.$element.val()
.match(values[i])
){
return true
}
}else{
if(this.$element.val()==values[i]){
return true
}
}
}
}
return false
})
}

return result
};

if(this.values!==undefined&&$.type(this.values)!=='array'){
this.values=[this.values]
}

this.to=parse_controls(this.to,this.values);

this.from=parse_controls(this.from);


if($.type(this.on)==='string'){
this.on=this.on.replace(
/((?:\[(?:[^\[\]]+=(?:[^\[\]]|\[[^\[\]]*\])+|this|self)\])+)(\.?)/g,
function(){
var control;

if(
arguments[1]==='[this]'
||arguments[1]==='[self]'
){
control=that.to
}else{
control=xP(arguments[1],root);

if(
!control[0]
&&root&&root.$element.is(arguments[1])
){
control=[root]
}
}

that.from.append(control);

var id=that.from.index(control[0]);

if(id<0){

xP.debug(
'','error',
arguments[1]+' in dependence not found',
that
);

return arguments[0]
}

return 'arguments["'+id+'"].'
+(arguments[2]=='.'
?''
:(control[0] instanceof xP.controls.fields
?'count'
:'val')+'()'
)
});

eval('this.on=function(){return '+this.on+'}')
}

if(!this.from.length){
this.from.push(control)
}

var destroy=function(){that.destroy()};

this.suprocess=function(){
that.process()
};

this.from.each(function(){
this.change(that.suprocess);

this.destroy(destroy)
});

var dependence_init_inquiry=that.type+'_init_inquiry';

this.to.each(function(){
var control=this,dependence=control[that.type];

if(!dependence||!dependence.append){
dependence=control[that.type]=new xP.list()
}

dependence.append(that);


control.destroy(destroy);


if(!control._param(dependence_init_inquiry)){
control._param(
dependence_init_inquiry,
xP.after(function(){
dependence.each(function(){
this.suprocess()
});

control._param(dependence_init_inquiry,null)
},0)
)
}
});

xP.debug(
'dependencies','dependence',
this.type,this.to.first().$element,this
)
},

destroy:function(){
var that=this;

if(this.from){
this.from.each(function(){
this.change(that.suprocess,true)
})
}

if(this.to){
this.to.each(function(){
this[that.type]=null
})
}

this.from
=this.to
=this.on
=this['do']
=null;

return this
},

process:function(){
this.result=this.on.apply(this,this.from)
}

}});


xP.dependencies.register({name:'classed',base:'_item',prototype:{
process:function(){
xP.debug('classed','classed',this.to.first().$element,this.to);

xP.dependencies.classed.base.process.apply(this);

var that=this;

this.to.each(function(){
if(that.result){
this.$container
.addClass(that['do'])
}else{
this.$container
.removeClass(that['do'])
}
})
}
}});


xP.dependencies.register({name:'computed',base:'_item',prototype:{
process:function(){
xP.debug('computed','computed',this.to.first().$element,this.to);

xP.dependencies.classed.base.process.apply(this);

var that=this;

this.to.each(function(){
if(that['do']){
this.param(that['do'],that.result)
}else{
this.val(that.result)
}
})
}
}});


xP.dependencies.register({name:'enabled',base:'_item',prototype:{
process:function(){

xP.dependencies.enabled.base.process.apply(this);


var subprocess=function(children){
children.each(function(){
if(this.enabled&&this.enabled.process){
this.enabled.process()
}

if(this.children){
subprocess(this.children())
}
})
};

var that=this,enable;

this.to.each(function(){
this.disable(!that.result,that);

if(that.result&&this.children){
subprocess(this.children())
}
});

xP.debug(
'enabled','enabled',
this.to.first().$element,this.to,this.result
)
}
}});



xP.dependencies.register({name:'enabled_on_completed',base:'_item',prototype:{
init:function(params,control){
xP.dependencies.enabled_on_completed.base.init.apply(
this,
[{from:[control.root()]},control]
)
},

process:function(){
xP.debug(
'enabled_on_completed','enabled_on_completed',
this.to.first().$element,this.to
);

this.result=this.to.first().root().uncompleted();

var that=this;

this.to.each(function(){
this.disable(that.result)
})
}
}});


xP.dependencies.register({name:'_rooted',base:'_item',prototype:{
init:function(params,control){
if(!this._.root_type){
this._.root_type=this.type
}

xP.dependencies._rooted.base.init.apply(this,arguments);

var root=this.to.first().root();

this._.root=root._param(this._.root_type)
||root._param(this._.root_type,new xP.list())
},

destroy:function(){
if(this.to){
this.to_root(false);

this.to.first().root().change()
}

return xP.dependencies._rooted.base.destroy.apply(this,arguments)
},

to_root:function(append){
var that=this;

this.to.each(function(){
if(this._.no_root_dependencies){return}

if(append){
that._.root.append(this)
}else{
that._.root.remove(this)
}
})
}
}});


xP.dependencies.register({name:'required',base:'_rooted',prototype:{
init:function(params,control){
if($.type(params)==='string'){
params={on:params}
}
if(
$.type(params.on)==='string'
&&!params.on.match(/\[(?:this|self)\]/)
){
params.on='('+params.on+')&&![this]&&[this]!==0'
}
if(!params.on){
this.on='![this]&&[this]!==0'
}
xP.dependencies.required.base.init.apply(this,[params,control])
},

process:function(){
xP.debug('required','required',this.to.first().$element,this.to);

xP.dependencies.required.base.process.apply(this);

var that=this;

this.to.each(function(){
if(that.result){
this.$container
.addClass(that.container_required_class)
.removeClass(that.container_unrequired_class)
}else{
this.$container
.removeClass(that.container_required_class)
.addClass(that.container_unrequired_class)
}
});

this.to_root(this.result)
},

container_required_class:'required',
container_unrequired_class:'unrequired'
}});


xP.dependencies.register({name:'valid',base:'_rooted',prototype:{
init:function(params,control){
this._.root_type='invalid';
if($.type(params)==='regexp'){
params=params.toString()
}
if($.type(params)==='string'&&params.indexOf('/')===0){
params='[this]!==undefined&&[this].val().match('+params+')'
}

xP.dependencies.valid.base.init.apply(this,arguments)
},

process:function(){
xP.debug(
'valid','valid',this.result,this.to.first().$element,this.to
);

var that=this;

this.to.each(function(){

var value=this.val();
if(
!value&&value!==0&&!isNaN(value)
&&!(this instanceof xP.controls.fields)
){
this.$container
.removeClass(that.container_valid_class)
.removeClass(that.container_invalid_class);

that.result=true
}else{
xP.dependencies.valid.base.process.apply(that);

if(that.result){
this.$container
.addClass(that.container_valid_class)
.removeClass(that.container_invalid_class)
}else{
this.$container
.removeClass(that.container_valid_class)
.addClass(that.container_invalid_class)
}
}
});

this.to_root(!this.result)
},

container_valid_class:'valid',
container_invalid_class:'invalid'
}});


xP.dependencies.register({name:'changed',base:'_rooted',prototype:{
init:function(params,control){
xP.dependencies.changed.base.init.apply(this,arguments);

var that=this
},

process:function(){
if(!this.to){return} 
xP.debug('changed','changed',this.to.first().$element,this.to);

var that=this;

this.to.each(function(){

var cur=this.val(),
ini=this._param('initial_value');

that.result=(ini===undefined?'':ini)!=cur;

this.$container.toggleClass(
that.container_changed_class,
that.result
);

that.to_root(that.result);

var parent=this.parent();

if(parent){
parent.change()
}
})
},

container_changed_class:'changed'
}});


xP.root=new xP.controls.fields({$element:$('html')});




xP.controls.register({name:'repeat_append_button',base:'button',prototype:{
element_selector:'.repeat_append_button',

init:function(params){
xP.controls.repeat_append_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){repeat.append(parent)})
.enabled={
on:function(){return repeat.val()<repeat.max},
from:repeat
}
}
}});

xP.controls.register({name:'repeat_insert_button',base:'button',prototype:{
element_selector:'.repeat_insert_button',

init:function(params){
xP.controls.repeat_insert_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){repeat.append(parent,true)})
.enabled={
on:function(){return repeat.val()<repeat.max},
from:repeat
}
}
}});


xP.controls.register({name:'repeat_remove_button',base:'button',prototype:{
element_selector:'.repeat_remove_button',

init:function(params){
xP.controls.repeat_remove_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){repeat.remove(parent)})
.enabled={
on:function(){return repeat.val()>repeat.min},
from:repeat
}
}
}});


xP.controls.register({name:'repeat_first_button',base:'button',prototype:{
element_selector:'.repeat_first_button',

init:function(params){
xP.controls.repeat_first_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){repeat.move(parent,0)})
.enabled={
on:function(){return 0<parent._param('repeat_position')*1},
from:repeat
}
}
}});


xP.controls.register({name:'repeat_prev_button',base:'button',prototype:{
element_selector:'.repeat_prev_button',

init:function(params){
xP.controls.repeat_first_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){
repeat.move(parent,parent._param('repeat_position')-1)
}).enabled={
on:function(){
return 0<parent._param('repeat_position')*1
},
from:repeat
}
}
}});


xP.controls.register({name:'repeat_next_button',base:'button',prototype:{
element_selector:'.repeat_next_button',

init:function(params){
xP.controls.repeat_first_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){
repeat.move(parent,parent._param('repeat_position')*1+1)
}).enabled={
on:function(){
return repeat.children().length-1>parent._param('repeat_position')*1
},
from:repeat
}
}
}});


xP.controls.register({name:'repeat_last_button',base:'button',prototype:{
element_selector:'.repeat_last_button',

init:function(params){
xP.controls.repeat_first_button.base.init.apply(this,arguments);

var parent=this.parent(),repeat=parent.repeat;

if(!(repeat instanceof xP.repeats.item)){
return
}

this.click(function(){
repeat.move(parent,repeat.children().length-1)
}).enabled={
on:function(){return repeat.children().length-1>parent._param('repeat_position')*1},
from:repeat
}
}
}});


xP.repeats={
init:function(control){
if(control.repeat){
if($.type(control.repeat)!=='object'){
control.repeat={}
}

if(!control.root()._param('repeats')){
control.root()._param('repeats',{})
}

control.repeat.id=control.repeat.id||control.name;

var id=control.repeat.id,
repeats=control.root()._param('repeats');

if(!repeats[id]){
repeats[id]=new xP.repeats.item(control)
}else{
repeats[id].adopt(control)
}
}
}
};


xP.repeats.item=xP.register({name:'expromptum.repeats.item',base:xP.base,prototype:{
min:1,
max:300,

name_suffix_before:'[',
name_suffix_after:']',

id_suffix_before:'~',
id_suffix_after:'',

container_inited_class:'repeated',
container_position_class:'repeated_',
container_template_class:'repeated_template',

init:function(control){
xP.debug(
'repeats','repeat',
control.repeat.id,control.$element,this
);

xP.repeats.item.base.init.apply(this);

this.name_suffix_splitter=new RegExp(
'('
+xP.taint_regexp(this.name_suffix_before)
+'\\d+'
+xP.taint_regexp(this.name_suffix_after)
+')(?=(?:'
+xP.taint_regexp(this.name_suffix_before)
+'\\d+'
+xP.taint_regexp(this.name_suffix_after)
+')*$)'
);

this.id_suffix_pattern=new RegExp(
xP.taint_regexp(this.id_suffix_before)
+'\\d+'
+xP.taint_regexp(this.id_suffix_after)
+'$'
);

this.container_position_class_pattern=new RegExp(
'(^|\\s)'
+xP.taint_regexp(this.container_position_class)
+'\\d+(?=\\s|$)'
);

this._.children=[];

this.nesting=0;

var parent=control;

while(parent&&(parent=parent.parent())){
if(parent.repeat){
this.parent=parent;
this.nesting=parent.repeat.nesting+1;
break
}
}

this.adopt(control,true);


var that=this;

xP.after(function(){
if(!that.template){
that.temp_template=true;


var children=that.children(),
control=children[children.length-1];

that.append(control);

that.temp_template=false
}
},1)
},

destroy:function(handler,remove){
xP.repeats.item.base.destroy.apply(this,arguments);

if(!arguments.length&&this.control._){
this.control.root()._param('repeats')[this.id]=null
}
return this
},

val:function(value){
return this.children().length
},

children:function(){
return this._.children
},

adopt:function(control,first){

control._param('repeat_position',0);

var that=this,
template=(control.repeat.template||this.temp_template)
&&!this.template;

$.extend(this,control.repeat);

if(!this.control||template){
xP.after(function(){
that.control=control
});

control.$container
.find('*:not([id])').andSelf('*:not([id])').each(function(){
this.id='xP'+(Math.random()+'').substr(2,8)
});

if(!control.html){
control.html=$('<div>')
.append(control.$container.clone())
.html()
}
}

xP.after(function(){
control.$container
.find('*[id^=xP]').andSelf('*[id^=xP]').each(function(){
var $e=$(this),
control=xP.controls.link($e);

if(!control||control.$element[0]!==this){
$e.removeAttr('id')
}
})
});

if(template){
control._.repeat_template=true;
control._.no_root_dependencies=true;
repeat_change_suffixes(
this,
control,
888
);

control.$container.hide();

control.$container.addClass(this.container_template_class);

xP.after(function(){
control.$container
.find('input,textarea,select,button').andSelf()
.attr('disabled',true)
})
}else{
repeat_change_suffixes(
this,
control,
this.position!==undefined
?this.position
:this.children().length
);

this.children().push(control)
}

if(this.control){
repeat_new_control_count++
}

control.repeat=this;

control.destroy(function(){
var children=that.children(),i=children.length;

while(i--){
if(control===children[i]){
that._.children.splice(i,1);

break
}
}

if(!that.children().length){
that.destroy()
}
});

control.$container.addClass(this.container_inited_class)

},

move:function(control,new_position){
var children=this.children(),i,ii,
old_position=control._param('repeat_position');

if(new_position<old_position){
control.$container.insertBefore(
children[new_position].$container
);

i=new_position;
ii=children.length
}else{
control.$container.insertAfter(
children[new_position].$container
);

i=old_position;
ii=new_position+1
}

children.splice(
new_position,0,children.splice(old_position,1)[0]
);

for(;i<ii;i++){
repeat_change_suffixes(
this,
children[i],
i
)
}

this.change()
},

remove:function(control){
var children=this.children(),i=children.length,ii=i-1;

while(i--){
if(control===children[i]){
children.splice(i,1);

break
}
}

control.remove();

while(i<ii){
repeat_change_suffixes(
this,
children[i],
i
);

i++
}

this.change()
},

append:function(control,before){
var children=this.children(),i=children.length,ii=i;

while(i--){
if(control===children[i]){
break
}
}

if(before){
i--
}

while(ii--&&ii>i){
repeat_change_suffixes(
this,
children[ii],
ii+1
)
}

var id_suffix=this.id_suffix_before
+repeat_new_control_count+this.id_suffix_after,
$container=$(
this.control._get_html().replace(
/(\s(id|for|list)\s*=\s*([\"\'])?[^\s>]+)\3/g,
'$1'+id_suffix+'$3'
)
);

$container.find('[data-xp],[data-expromptum]')
.removeAttr('data-xp').removeAttr('data-expromptum');

$container.find('[disabled]')
.add($container.filter('[disabled]'))
.removeAttr('disabled');

if(before){
$container.insertBefore(control.$container)
}else{
$container.insertAfter(control.$container)
}

var result=repeat_init_new_control(
this,
$container,
this.control,
id_suffix,
this.temp_template?888:i+1
);

var that=this;

$container.find('input,textarea')
.add($container.filter('input,textarea'))
.not(function(){
var reset=xP(this).first().reset_on_repeat;
return that.reset&&reset===false
||!that.reset&&!reset
})
.removeAttr('checked')
.not(
'[type=button],[type=img],[type=submit],'
+'[type=checkbox],[type=radio]'
)
.val('');

if(!this.temp_template){
var c=this.children().pop();

this.children().splice(i+1,0,c)
}

this.change();

return result
}

}},'xP.repeats.item');


var repeat_init_new_control=function(
repeat,$container,control,id_suffix,position
){
var id=control.$element.attr('id');

if(!id){
return
}

var selector='#'
+xP.taint_css(
id.replace(
repeat.id_suffix_pattern,''
)
+id_suffix
),
$element=$container.is(selector)
?$container
:$(selector,$container);

if(!$element[0]){
if(window.console){


console.warn(
'In',$container,
'not found',selector,
'by',control.$element.first(),'via suffix',id_suffix
)
}
return
}

var params=repeat_get_params(
repeat,
$container,
control,
id_suffix
);

params.$element=$element;

params.changed=undefined;

if(control.repeat){
if(control.repeat.id!==repeat.id){
params.repeat={
id:control.repeat.id+id_suffix
};

if(
control===control.repeat.control
&&!repeat.temp_template
){
params.repeat.template=true
}
}else{
control.repeat.position=position
}
}

var result=xP.controls.link(params.$element);

if(!result){
result=new xP.controls[params.type](params)
}

if(control.children){
control.children().each(function(){
repeat_init_new_control(
repeat,
$container,
this,
id_suffix,
position
)
})
}

return result
},

repeat_change_suffixes=function(repeat,control,position){
control._param('repeat_position',position);

control.$container[0].className=
control.$container[0].className.replace(
repeat.container_position_class_pattern,''
);

if(!repeat.name_suffix_before){
return
}

control._.repeat_suffix
=(repeat.parent&&repeat.parent._.repeat_suffix
?repeat.parent._.repeat_suffix:'')
+repeat.name_suffix_before
+position+repeat.name_suffix_after;

control.$container.addClass(
repeat.container_position_class+position
);

var option_names={},
root_options=control.root()._param('_option');

control.$container.find('[name]').andSelf('[name]').each(function(){
var $e=$(this),
name=$e.attr('name'),
type=$e.attr('type'),
parts=name.split(repeat.name_suffix_splitter),
new_name=parts[0]+control._.repeat_suffix;

for(var i=repeat.nesting*2+3;i<parts.length;i++){
new_name+=parts[i]
}

if(name!==new_name){
$e.attr('name',new_name);

if(type==='checkbox'||type==='radio'){
option_names[name]=new_name
}
}
});

$.each(option_names,function(name,value){
if(root_options&&root_options[name]){
root_options[value]=root_options[name];

root_options[name]=null
}
});

var parent=control.parent();

if(parent){
parent.children().sort(function(a,b){
if(
a._param('repeat_position')
<b._param('repeat_position')
){
b.change();

return-1
}else{
return 1
}
})
}
},

repeat_get_params=function(repeat,$container,object,id_suffix){
var result={};

$.each(object,function(name,value){
if(
(name.indexOf('_')!=0)
&&!(value instanceof jQuery)
&&!(value instanceof jQuery)
&&($.type(value)!=='function'||name==='on')
){
result[name]=repeat_get_params_value(
repeat,
$container,
value,
id_suffix
)
}
});

return result
},

repeat_get_params_value=function(
repeat,$container,object,id_suffix
){
var	result,id,new_id,tainted_new_id;

if($.type(object)==='array'){
result=[];

for(var i=0,ii=object.length,v;i<ii;i++){
v=repeat_get_params_value(
repeat,$container,object[i],id_suffix
);

if(v!==undefined){
result.push(v)
}
}
}else{
if(
object instanceof xP.controls._item
&&object.$element
&&object.$element.attr('id')
){
id=object.$element.attr('id')
}else if(object&&object.id){
id=object.id
}

if(id){
new_id=id.replace(repeat.id_suffix_pattern,'')
+id_suffix;

tainted_new_id=xP.taint_css(new_id)
}

if(
id
&&(
$container.attr('id')==new_id
||$container.find('#'+tainted_new_id).length
)
){
result='[id='+tainted_new_id+']'
}else if(
object instanceof xP.repeats.item
||object instanceof xP.controls._item
){
result=object
}else if($.type(object)==='object'){
result=repeat_get_params(
repeat,$container,object,id_suffix
)
}else{
result=object
}
}

return result
},

repeat_new_control_count=0;


return xP
})()})(window,jQuery);
