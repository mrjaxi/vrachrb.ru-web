<div class="inf_panel">
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td class="inf_panel__consultations" align="center" width="33%">
        <img src="/i/n.gif" class="inf_panel__img" width="86" height="85" />
        <div class="inf_panel__item ff_roboto_l">
          <?php
          echo '<span class="inf_panel__num">' . number_format($question_count, 0, ',', ' ') . '</span><br /><span class="fs_20">консультац' . Page::niceRusEnds($question_count,'ия','ии','ий') . '</span>';
          ?>
        </div>
      </td>
      <td class="inf_panel__users" align="center" width="33%">
        <img src="/i/n.gif" class="inf_panel__img" width="101" height="85" />
        <div class="inf_panel__item ff_roboto_l">
          <?php
          echo '<span class="inf_panel__num">' . number_format($user_count, 0, ',', ' ') .'</span><br /><span class="fs_20">пользовател' . Page::niceRusEnds($user_count,'ь','я','ей') .'</span>';
          ?>
        </div>
      </td>
      <td class="inf_panel__doctors" align="center" width="33%">
        <img src="/i/n.gif" class="inf_panel__img" width="82" height="85" />
        <div class="inf_panel__item ff_roboto_l">
          <?php
          echo '<span class="inf_panel__num">' .number_format($specialist_count, 0, ',', ' ') .'</span><br /><span class="fs_20">врач' . Page::niceRusEnds($specialist_count,'','а','ей') .'</span>';
          ?>
        </div>
      </td>
    </tr>
  </table>
</div>
<i class="br30"></i>