<?php
slot('title', 'Профиль');
?>
<table class="table_cont" cellspacing="0" cellpadding="0" width="100%" height="100%">
  <tr valign="top">
    <td width="1">
      <div class="menu_l">
        <div data-href="/" data-title="Инфомат" class="load_work menu_l__item menu_l__item_active">
          <div class="h">Профиль</div>
        </div>
        <!--div data-href="/" data-title="Информационный модуль" class="load_work menu_l__item">
          <div class="h">Уведомления</div>
        </div-->
      </div>
    </td>
    <td height="100%">
      <div class="cont__cont_r">
        <div class="cont__cont_r__pad">
          <div class="cont__cont__top">
            <table width="100%" cellspacing="0" cellpadding="0">
              <tr valign="top">
                <td width="355">
                  <img src="data:image/jpeg;charset=utf-8;base64,<?php echo $user_info['Photo'];?>" width="320" style="border:2px solid #242424;" />
                </td>
                <td>
                  <b class="fs_36" style="line-height:34px;">
                    <?php
                      echo $user_info['FirstName'] . ' ' . $user_info['MiddleName'] . '<i class="br5"></i>' . $user_info['LastName'];
                    ?>
                  </b>
                  <i class="br20"></i>
                  <div>
                    <b>Номер электронного паспорта</b>
                    <i class="br3"></i>
                    <?php
                    echo $sf_user->getUsername();
                    ?>
                    <i class="br30"></i>
                  </div>
                  <div>
                    <b>Дата рождения</b>
                    <i class="br3"></i>
                    <?php
                    echo Page::rusDate($user_info['Birthdate']);
                    ?>
                    <i class="br30"></i>
                  </div>
                  <div>
                    <b>Место работы</b>
                    <i class="br3"></i>
                    <?php
                    echo $user_info['workplace'];
                    ?>
                    <i class="br30"></i>
                  </div>
                  <div>
                    <b>Должность</b>
                    <i class="br3"></i>
                    <?php
                    echo $user_info['position'];
                    ?>
                    <i class="br30"></i>
                  </div>
                </td>
              </tr>
            </table>
            
            <?php
            /*
            ?>
            
            <b class="fs_24">Статистика</b>
            <i class="br20"></i>
            <div class="whoami_statistics_item"></div>
            <table cellspacing="0" cellpadding="0" width="100%">
              <tr>
                <td width="1" style="padding-right:20px;">
                  <svg width="82" height="82">
                    <circle cx="41" cy="41" r="40" fill="rgb(255,255,255)" stroke-width="1" stroke="rgb(219,225,234)"/>
                    <text x="50%" y="48" text-anchor="middle"><tspan style="font-weight: bold;">70%</tspan></text>
                    <path fill="none" stroke="#dc4f33" d="M 40,2 A39,39,0,1,1,6,60" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                  </svg>
                </td>
                <td style="padding-right:20px;" width="25%">
                  <div class="fs_14">Просроченные<br />курсы</div>
                  <i class="br1"></i>
                  <b class="fs_30">3/4</b>
                </td>
                <td width="1" style="padding-right:20px;">
                  <svg width="82" height="82">
                    <circle cx="41" cy="41" r="40" fill="rgb(255,255,255)" stroke-width="1" stroke="rgb(219,225,234)"/>
                    <text x="50%" y="48" text-anchor="middle"><tspan style="font-weight: bold;">50%</tspan></text>
                    <path fill="none" stroke="#f8995d" d="M 40,2 A40,39,0,0,1,40,80" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                  </svg>
                </td>
                <td style="padding-right:20px;" width="25%">
                  <div class="fs_14">Переаттестация менее<br />чем через месяц</div>
                  <i class="br1"></i>
                  <b class="fs_30">2/8</b>
                </td>
                <td width="1" style="padding-right:20px;">
                  <svg width="82" height="82">
                    <circle cx="41" cy="41" r="40" fill="rgb(255,255,255)" stroke-width="1" stroke="rgb(219,225,234)"/>
                    <text x="50%" y="48" text-anchor="middle"><tspan style="font-weight: bold;">15%</tspan></text>
                    <path fill="none" stroke="#00763d" d="M 40,2 A40,39,0,0,1,80,40" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                  </svg>
                </td>
                <td style="padding-right:20px;" width="25%">
                  <div class="fs_14">Переаттестация более<br />чем через месяц</div>
                  <i class="br1"></i>
                  <b class="fs_30">1/5</b>
                </td>
                <td width="1" style="padding-right:20px;">
                  <svg width="82" height="82">
                    <circle cx="41" cy="41" r="40" fill="rgb(255,255,255)" stroke-width="1" stroke="rgb(219,225,234)"/>
                    <text x="50%" y="48" text-anchor="middle"><tspan style="font-weight: bold;">100%</tspan></text>
                    <path fill="none" stroke="#969696" d="M 40,3 A38,38,0,1,1,39.9,3.003" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                  </svg>
                </td>
                <td style="padding-right:20px;" width="25%">
                  <div class="fs_14">Переаттестация<br />не требуется</div>
                  <i class="br1"></i>
                  <b class="fs_30">2/2</b>
                </td>
              </tr>
            </table>
            <i class="br40"></i>
            <table cellspacing="0" cellpadding="0" width="100%" class="fs_14 whoami_detail">
              <tr>
                <td width="1"><b>СИЗ</b></td>
                <td width="100%"><div class="whoami_detail__wrap"><div class="whoami_detail__wrap__line whoami_detail__wrap__line__low"></div></div></td>
                <td width="1"><b>4/12</b></td>
              </tr>
              <tr>
                <td width="1"><b>Медосмотр</b></td>
                <td width="100%"><div class="whoami_detail__wrap"><div class="whoami_detail__wrap__line whoami_detail__wrap__line__gray"></div></div></td>
                <td width="1"><b>4/12</b></td>
              </tr>
              <tr>
                <td width="1"><b>Инструктаж</b></td>
                <td width="100%"><div class="whoami_detail__wrap"><div class="whoami_detail__wrap__line whoami_detail__wrap__line__mid"></div></div></td>
                <td width="1"><b>4/12</b></td>
              </tr>
              <tr>
                <td width="1"><b>Тесты</b></td>
                <td width="100%"><div class="whoami_detail__wrap"><div class="whoami_detail__wrap__line whoami_detail__wrap__line__hight"></div></div></td>
                <td width="1"><b>4/12</b></td>
              </tr>
            </table>
            
            <?php
            */
            ?>
            
          </div>
        </div>
      </div>
    </td>
  </tr>
</table>
