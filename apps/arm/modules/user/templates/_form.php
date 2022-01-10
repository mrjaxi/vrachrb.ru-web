<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php echo form_tag_for($form, '@user') ?>

<div id="lui_scroller" class="lui__scroller_class">
<div class="lui__scroller_wrapper" style="position:relative;z-index:102;padding-left:10px;">
<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
  <?php include_partial('user/form_fieldset', array('groups' => $groups, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
<?php endforeach; ?>
<div class="sf_admin_form_row sf_admin_text" style="position:relative;top:-10px;">
<div>
<span class="inline-label">Контактные данные</span>
<div class="content">
<?php
//$userdata = !$form->getObject()->isNew() ? Doctrine::getTable('Userdata')->findOneByUserIdAndDataType($form->getObject()->getId(), 'contacts') : false;
?>
<input type="text" name="userdata[contacts]" size="80" value="<?php echo $userdata ? $userdata->getDataValue() : '';?>" />
</div>
</div>
</div>


<?php
$permissions = Doctrine_Query::create()
  ->select("p.*")
  ->from("Permission p")
  ->orderBy("p.description")
  ->fetchArray()
  ;
$rows = array();
$cols = array();
$rows_cols = array();
$rows_cols_title = array();
$base_permissions = Permission::getBasePermission();
unset($base_permissions['batch|batchMerge']);

foreach($permissions as $permission)
{
  $c_split = explode('-', $permission['credential']);
  $d_split = explode(':', $permission['description']);
  $rows[$c_split[0]] = $d_split[0];
  $cols[$c_split[1]] = $d_split[1];
  $rows_cols[$c_split[0]][$c_split[1]] = $permission['id'];
  $rows_cols_title[$permission['id']] = $d_split[1];
}

$user_permissions = array();
if($sf_request->isMethod('POST'))
{
  $user_permissions = $sf_request->hasParameter('user_permissions') ? $sf_request->getParameter('user_permissions')->getRawValue() : array();
}
else
{
  foreach($form->getObject()->getUserPermissions() as $user_permission)
  {
    $user_permissions[] = $user_permission->getId();
  }
}


$user_group_permissions = array();
if(!$form->getObject()->isNew())
{
  $user_groupss = Doctrine_Query::create()
    ->select("ugs.*")
    ->from("UserGroupUsers ugs")
    ->where("ugs.user_id = " . $form->getObject()->getId())
    ->execute()
  ;

  foreach($user_groupss as $user_groups)
  {
    $user_group = Doctrine::getTable("UserGroup")->find($user_groups->getUserGroupId());
    if($user_group)
    {
      foreach($user_group->getUserGroupPermissions() as $user_group_permission)
      {
        $user_group_permissions[] = $user_group_permission->getId();
      }
    }
  }
  
}

?>

<table class="lui__list_table lui__list_table_permissions" cellpadding="0" cellspacing="0">
<thead>
  <tr>
    <th style="padding:5px 30px 5px 10px;">Модуль</th>
    <th colspan="2"></th>
  </tr>
</thead>
<tbody>
<?php
foreach($rows as $row_key => $row_value)
{
  echo '<tr>';
  echo '<td style="padding:5px 30px 5px 10px;white-space:nowrap" title="' . $row_key . '" class="magick_checker__row">';
    echo '<b>' . $row_value . '</b>';
  echo '</td>';
  echo '<td style="padding:0 0 5px 0;" align="left">';
  $perm_buff = '';
  foreach($rows_cols[$row_key] as $col_key => $col_value)
  {
    $perm_buff_item = '<div style="display:inline-block;vertical-align:top;width:140px;padding:4px 0 0 0;" title="' . $col_key . '">';
    $perm_buff_item .= '<label style="font-size:11px"><input type="checkbox" name="user_permissions[]"' . (in_array($col_value, $user_group_permissions) ? ' checked="checked" disabled="disabled"' : (in_array($col_value, $user_permissions) ? ' checked="checked"' : '')) . ' value="' . $col_value . '" />' . $rows_cols_title[$col_value] . '</label>';
    $perm_buff_item .= '</div>';
    if(array_key_exists($col_key, $base_permissions))
    {
      echo $perm_buff_item;
    }
    else
    {
      $perm_buff .= $perm_buff_item;
    }
  }
  echo $perm_buff;
  echo '</td>';
  echo '<td></td>';
  echo '</tr>';
}
?>
</tbody>
</table>

<span class="br20"></span>
<span class="br20"></span>
</div>
</div>
</form>

<script type="text/javascript">
$('.magick_checker__col').click(function(){
  var checker = $(this);
  var check = false;
  $(this).parent().parent().parent().find('td').each(function(k, de){
    if(checker.prop('cellIndex') == $(de).prop('cellIndex') && !$(de).find('input').is(':disabled')){
      if(!$(de).find('input').is(':checked')){
        check = true;
      }
    }
  });
  $(this).parent().parent().parent().find('td').each(function(k, de){
    if(checker.prop('cellIndex') == $(de).prop('cellIndex') && !$(de).find('input').is(':disabled')){
      $(de).find('input').prop('checked', check);
    }
  });
});
$('.magick_checker__row').click(function(){
  var checker = $(this);
  var check = false;
  $(this).parent().find('td').each(function(k, de){
    if($(de).find('input').length > 0  && !$(de).find('input').is(':disabled') && !$(de).find('input').is(':checked')){
      check = true;
    }
  });
  $(this).parent().find('td').each(function(k, de){
    if(!$(de).find('input').is(':disabled')){
      $(de).find('input').prop('checked', check);
    }
  });
});
if($('#user_password').val() != ''){
  $('#user_password').val('');
  $('#user_password').prop('placeholder', 'Скрыт');
}
var check_is_admin = function(){
  if($('#user_is_super_admin').is(':checked')){
    $('#user_password,#user_email').parent().parent().find('label').css('font-weight', 'bold');
    $('.lui__list_table_permissions,.sf_admin_form_row_user_division').show();
  } else {
    $('#user_password,#user_email').parent().parent().find('label').css('font-weight', 'normal');
    $('.lui__list_table_permissions,.sf_admin_form_row_user_division').hide();
  }
}
check_is_admin();
$('#user_is_super_admin').click(check_is_admin);

var check_password_score = function(){
  var score = PassGenJS.getScore($('#user_password').val());
  if(score.score == 1){
    $('#user_password').css('border-color', '#FF0000');
  } else if(score.score == 2){
    $('#user_password').css('border-color', '#FF8000');
  } else if(score.score > 2){
    $('#user_password').css('border-color', '#008000');
  }
}

var gen_pass_btn = $('<button style="position:relative;left:5px;top:-1px;">Сгенерировать</button>');

$('#user_password').after(gen_pass_btn);
$('#user_password').keyup(check_password_score);
gen_pass_btn.click(function(){
  $('#user_password').val(PassGenJS.getPassword({letters: 6, lettersUpper: 6, numbers: 6}));
  check_password_score();
  return false;
});
</script>