<?php
if($view == 'horizontall')
{
  slot('title', 'Специалисты');
  $view_class = 'specialist_page clearfix';
  $view_style = ' style="display: flex;flex-flow: row wrap;max-width:935px;"';
//    $view_style = ' style="display: flex;flex-flow: row wrap;visibility: hidden;"';
}
else
{
  $view_class = 'rating_doctors';
  $view_style =  $style == '' ? '' : ' style="' . $style . '"';
}
if($type == 'general')
{
  $view_class = 'rating_doctors rating_doctors_general';
}
?>
<div class="white_box <?php echo $view_class; ?>"<?php echo $view_style; ?>>
  <?php
  if($type == 'general')
  {
    echo '<div class="general_specialist_sorting sorting">';
    echo '<div class="sorting__item">';
    echo '<a onclick="doctorGeneralSort(this, \'rating\');return false;" href="" class="sorting__link sorting__link_active sorting_desc sorting__link_rating"><span>по рейтингу</span></a><a onclick="doctorGeneralSort(this, \'name\');return false;" href="" class="sorting__link sorting_asc sorting__link_name"><span>по алфавиту</span></a>';
    echo '<label class="sorting__radio_label"><input onclick="doctorGeneralSort(this, \'doctor_online\');" class="sorting__radio specialist_sorting__radio" type="checkbox">онлайн</label>';
    echo '</div>';


    echo '</div>';

    echo '<div class="rating_doctors__item_wrap">';
  }
  ?>
  <img class="specialist_preloader" src="/i/preloader.GIF" width="40" height="40" alt="">

  <?php

  foreach($specialists as $key => $specialist)
  {
    if($view == 'horizontall')
    {
      echo '<div class="specialist_page__item fl_l">';
    }
    $doctor_status = '';
    if($specialist['Specialist_online'][0]['date'])
    {
      $time_difference = strtotime(date('Y-m-d' . ' ' . 'H:i:s')) - strtotime($specialist['Specialist_online'][0]['date']);
      $doctor_status = $time_difference < sfConfig::get('app_waiting_time_online') ? 'rating_doctors_online' : '';
    }
    $specialist_name = $specialist['User']['first_name'] . ' ' . $specialist['User']['second_name'];
    $specialist_rating = $specialist['rating'] == '' ? '&mdash;' : number_format($specialist['rating'], 1, ',', '');
    $specialist_answ = $specialist['answers_count'] == '' ? '0' : number_format($specialist['answers_count'], 0, ',', ' ');
    ?>
    <div data-specialty_id="<?php echo $specialist['specialty_id'] . '" ' . 'data-specialist_id="' . $specialist['id'] . '"';?> class="rating_doctors__item <?php echo $doctor_status;?>">
      <div class="specialist_link_item">
        <div class="rating_doctors__item__photo" <?php echo ($specialist['User']['photo'] ? 'style="background-image: url(/u/i/' . Page::replaceImageSize($specialist['User']['photo'],'S') . ');" data-photo="/u/i/' . Page::replaceImageSize($specialist['User']['photo'],'S') . '"' : '');?>></div>
        <a class="rating_doctors__item__name" href="<?php echo url_for('@specialist_index') . $specialist['title_url'];?>/"><?php echo $specialist_name;?></a>
        <i class="br5"></i>
        <div class="rating_doctors__item__pos"><?php echo $specialist['about'];?></div>
      </div>
      <table cellpadding="0" cellspacing="0" class="rating_doctors__item__num">
        <tr valign="top">
          <td class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;"><span class="fs_20 rating_doctors__item__rating"><?php echo $specialist_rating;?></span><br/><span class="fs_13">рейтинг</span></td>
          <td class="tcolor_red" style="padding-left: 10px;"><span class="fs_20 rating_doctors__item__consultation"><?php echo $specialist_answ;?></span><br/><span class="fs_13">консультац<?php echo Page::niceRusEnds($specialist_answ,'ия','ии','ий');?></span></td>
        </tr>
      </table>
    </div>
  <?php
    if($view == 'horizontall')
    {
      echo '</div>';
    }
  }
  if($type == 'general')
  {
    echo '</div>';
  }
  ?>
</div>
<?php
if($type == 'general')
{
?>
  <script type="text/javascript">
    var sorting = function (_arr, _need, type) {
      var enterArr = [];
      for(var id = 0; id < _arr.length; id ++){
        enterArr[id] = _arr[id];
      }
      var resultArr = [];
      var _arrLength = enterArr.length;
      var maxValue = 0;
      var minValue = 100000000;

      if(type == 'simbol'){
        maxValue = 'а';
        minValue = 'я';
      }

      for(var i1 = 0; i1 < _arrLength; i1++){
        var t = (_need == 'max' ? maxValue : minValue);
        var ti = null;
        var c = t;

        for(var i = 0; i <= _arrLength; i++){
          if(enterArr[i] != 'null' && enterArr[i]){

            var _arrValue = (type == 'symbol' ? enterArr[i][0] : enterArr[i]);
            if(type == 'float'){
              var spl = enterArr[i].split('___');
              _arrValue = parseFloat(spl[0].replace('—', '0.001'));
              if(_arrValue == 0){
                _arrValue = 0.001;
              }
            }

            if((c > _arrValue && _need == 'min') || (c < _arrValue && _need == 'max')){
              c = _arrValue;
              t = enterArr[i];
              ti = i;
            }
          }
        }
        resultArr.push(t);
        enterArr[ti] = 'null';
      }
      return resultArr;
    };

    var doctorItemArr = [];
    var doctorRating = [];
    var doctorName = [];
    var doctorSpecialty = {};
    var doctorSpecialtyBack = [];
    var doctorSpecialistId = {};

    var doctorAllParam = {};

    $('.rating_doctors__item').each(function(_idx, _elem){
      var _thisElem = $(_elem);
      var dName = _thisElem.find('.rating_doctors__item__name');
      var dRating = _thisElem.find('.rating_doctors__item__rating').text();
      var dSpecialtyId = _thisElem.data('specialty_id');

      doctorName[_idx] = dName.text().toLowerCase() + '___' + _idx;
      doctorRating[_idx] = dRating.replace(',', '.') + '___' + _idx;

      if(typeof doctorSpecialty[dSpecialtyId] != 'undefined'){
        if(typeof doctorSpecialty[dSpecialtyId] == 'object'){
          doctorSpecialty[dSpecialtyId].push(_idx);
        }else{
          doctorSpecialty[dSpecialtyId] = [doctorSpecialty[dSpecialtyId], _idx];
        }
      }else{
        doctorSpecialty[dSpecialtyId] = _idx;
      }

      var back = false;
      for(var b1 = 0; b1 < doctorSpecialtyBack.length; b1 ++){
        if(doctorSpecialtyBack[b1] == dSpecialtyId){
          back = true;
          break;
        }
      }
      if(!back){
        doctorSpecialtyBack.push(dSpecialtyId);
      }

      doctorSpecialistId[_idx] = _thisElem.data('specialist_id');

      doctorItemArr[_idx] = '<div class="rating_doctors__item rd__item">' +
        '<div class="specialist_link_item ' + dSpecialtyId + '">' +
        '<div class="rating_doctors__item__photo" style="background-image:url(' + _thisElem.find('.rating_doctors__item__photo').data('photo') + ')"></div>' +
        '<a class="rating_doctors__item__name" href="' + dName.attr('href') + '">' + dName.text() + '</a>' +
        '<i class="br5"></i>' +
        '<div class="rating_doctors__item__pos">' + _thisElem.find('.rating_doctors__item__pos').text() + '</div>' +
        '</div>' +
        '<table class="rating_doctors__item__num" cellpadding="0" cellspacing="0">' +
        '<tbody><tr valign="top">' +
        '<td class="tcolor_green" style="border-right:1px solid #dbdcdd;padding-right: 10px;"><span class="fs_20 rating_doctors__item__rating">' + dRating + '</span><br><span class="fs_13">рейтинг</span></td>' +
        '<td class="tcolor_red" style="padding-left: 10px;"><span class="fs_20 rating_doctors__item__consultation">' + _thisElem.find('.rating_doctors__item__consultation').text() + '</span><br><span class="fs_13">консультаций</span></td>' +
        '</tr>' +
        '</tbody></table>' +
        '</div>';


      doctorAllParam[_idx] = {
        spt_id: _thisElem.data('specialist_id'),
        online: false
      };

    });
    
    var rDoctorItemWrap = $('.rating_doctors__item_wrap');

    var doctorGeneralSort = function(_this, type){
      var htmlValue = '';
      var doctorHtml = '';
      var sLinkActive = null;
      doctorStatus = false;
      var specialistOnlineLength = specialistOnline.length;
      var _thisOnlineCheckbox = $('.specialist_sorting__radio');
      doctorStatus = (_thisOnlineCheckbox.is(':checked') ? true : false);

      if(doctorStatus){
        if(specialistOnlineLength == 0){
          rDoctorItemWrap.html('Нет специалистов онлайн');
          return false;
        }
      }

      if(type != 'doctor' && type != 'doctor_online') {
        var sortValue = 'min';
        var _thisElem = $(_this);
        var removeCl = 'sorting_';

        if (_thisElem.hasClass('sorting__link_active')) {
          if (_thisElem.hasClass('sorting_asc')) {
            sortValue = 'max';
            removeCl += 'asc';
          } else {
            removeCl += 'desc';
          }
          _thisElem.removeClass(removeCl);
          _thisElem.addClass((removeCl == 'sorting_asc' ? 'sorting_desc' : 'sorting_asc'));
        }

        $('.sorting__link').removeClass('sorting__link_active');
        _thisElem.addClass('sorting__link_active');

        var arr = (type == 'rating' ? doctorRating : doctorName);

        var nodArr = arr;

        sLinkActive = $('.sorting__link_active');

        var nodSortValue = (sLinkActive.hasClass('sorting__link_rating') ? [doctorRating, 'float'] : [doctorName, 'simbol']);
        nodSortValue[2] = sLinkActive.hasClass('sorting_desc') ? 'max' : 'min';

        var nodSortArr = [];
        if (gfArr.length > 0) {
          for (var i3 = 0; i3 < gfArr.length; i3++) {
            if (typeof doctorSpecialty[gfArr[i3]] == 'object') {
              for (var i2 = 0; i2 < doctorSpecialty[gfArr[i3]].length; i2++) {
                if(doctorStatus){
                  if(doctorAllParam[doctorSpecialty[gfArr[i3]][i2]]['online']){
                    nodSortArr.push(nodSortValue[0][doctorSpecialty[gfArr[i3]][i2]]);
                  }
                }else{
                  nodSortArr.push(nodSortValue[0][doctorSpecialty[gfArr[i3]][i2]]);
                }
              }
            } else {
              if(doctorStatus){
                if(doctorAllParam[doctorSpecialty[gfArr[i3]]]['online']){
                  nodSortArr.push(nodSortValue[0][doctorSpecialty[gfArr[i3]]]);
                }
              }else{
                nodSortArr.push(nodSortValue[0][doctorSpecialty[gfArr[i3]]]);
              }
            }
          }
        }else{
          nodSortArr = [];
          var counter = 0;
          for (var key in doctorSpecialistId) {
            counter++;
          }
          for(var so = 0; so < counter; so ++){
            if(doctorStatus){
              for(var so2 = 0; so2 < specialistOnline.length; so2 ++){
                if(doctorSpecialistId[so] == specialistOnline[so2]){
                  nodSortArr.push(nodSortValue[0][so]);
                }
              }
            }else{
              nodSortArr.push(nodSortValue[0][so]);
            }
          }
        }
        nodArr = nodSortArr;

        var doctorSort = sorting(nodArr, nodSortValue[2], (type == 'rating' ? 'float' : 'simbol'));

        for (var i10 = 0; i10 < doctorSort.length; i10++) {
          var dSSplit = doctorSort[i10].split('___');
          htmlValue = doctorItemArr[parseInt(dSSplit[1])];

          if(doctorAllParam[dSSplit[1]]['online']){
            htmlValue = doctorItemArr[dSSplit[1]].replace('rd__item', 'rd__item rating_doctors_online');
          }
          doctorHtml += htmlValue;
        }
      }else{
        if(type == 'doctor_online'){
          _this = gfArr;
        }

        sLinkActive = $('.sorting__link_active');
        var doctorArr = _this;

        if(doctorArr.length == 0){
          doctorArr = doctorSpecialtyBack;
        }

        var dArrLength = doctorArr.length;

        if(dArrLength == 0 && doctorStatus && specialistOnline.legnth == 0){
          rDoctorItemWrap.html('Нет специалистов онлайн');
          return false;
        }

        var dLenght = dArrLength;
        if(dArrLength == 0){
          dLenght = doctorItemArr.length;
        }

        var dSortValue = (sLinkActive.hasClass('sorting__link_rating') ? [doctorRating, 'float'] : [doctorName, 'simbol']);
        dSortValue[2] = sLinkActive.hasClass('sorting_desc') ? 'max' : 'min';

        var dSortArr = dSortValue[0];
        if(dArrLength != 0 || doctorStatus){
          dSortArr = [];
          for(var i = 0; i < dLenght; i ++){
            if(typeof doctorSpecialty[doctorArr[i]] == 'object'){
              for(var i1 = 0; i1 < doctorSpecialty[doctorArr[i]].length; i1 ++){
                if(specialistOnlineLength > 0 && doctorStatus){
                  for(var s1 = 0; s1 < specialistOnlineLength; s1 ++){
                    if(doctorSpecialistId[doctorSpecialty[doctorArr[i]][i1]] == specialistOnline[s1]){
                      dSortArr.push(dSortValue[0][doctorSpecialty[doctorArr[i]][i1]]);
                    }
                  }
                }else{
                  dSortArr.push(dSortValue[0][doctorSpecialty[doctorArr[i]][i1]]);
                }
              }
            }else{
              if(specialistOnlineLength > 0 && doctorStatus){
                for(var s1 = 0; s1 < specialistOnlineLength; s1 ++){
                  if(doctorSpecialistId[doctorSpecialty[doctorArr[i]]] == specialistOnline[s1]){
                    dSortArr.push(dSortValue[0][doctorSpecialty[doctorArr[i]]]);
                  }
                }
              }else{
                dSortArr.push(dSortValue[0][doctorSpecialty[doctorArr[i]]]);
              }
            }
          }
        }

        var dSortResult = sorting(dSortArr, dSortValue[2], dSortValue[1]);

        for(var i4 = 0; i4 < dSortResult.length; i4 ++){
          var dSplit = dSortResult[i4].split('___');
          htmlValue = doctorItemArr[dSplit[1]];
            if(doctorAllParam[dSplit[1]]['online']){
              htmlValue = doctorItemArr[dSplit[1]].replace('rd__item', 'rd__item rating_doctors_online');
            }
          doctorHtml += htmlValue;
        }
      }

      if(doctorHtml == ''){
        doctorHtml = 'Нет специалистов онлайн';
      }
      rDoctorItemWrap.html(doctorHtml);
    };
  </script>
<?php
}
?>