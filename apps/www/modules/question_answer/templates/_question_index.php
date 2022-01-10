<?php
if(count($questions) > 0)
{
  $valid_arr = array(url_for('@question_answer_index'), url_for('@question_answer_filter'), url_for('@question_answer_show?id=' . $sf_request->getParameter('id')), url_for('@question_answer_page?id=' . $sf_request->getParameter('id')));
  foreach ($questions as $question)
  {

    /*
    $age = date('Y') - substr($question['User']['birth_date'], 0, 4);
    ?>
    <div class="live_band__item">
      <?php
      if(!in_array($sf_request->getPathInfo(), $valid_arr))
      {
        ?>
        <div class="live_band__item__tags clearfix">
          <a href="<?php echo url_for('@question_answer_index');?>" class="b_blue_btn tags__link fl_l">вопросы и ответы</a>
        </div>
        <?php
      }
      ?>
      <i class="live_band__item__date"><?php echo Page::rusDate($question['created_at']);?></i>
      <i class="br5"></i>
      <b><?php echo Page::nameAge($age, $question['User']['gender']) .  ($age != date('Y') && $age != 0 ? ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет') : '');?></b>
      <i class="br10"></i>
      <?php
      $question_raw = $question->getRawValue();
      echo '<' . ($question['Specialists'][0]['id'] ? 'a href="' . url_for('@question_answer_show?id=' . $question['id']) . '"' : 'div') . ' class="live_band__item__link">' . strip_tags($question_raw['body']) . ($question['Specialists'][0]['id'] ? '</a' : '</div') . '>';
      ?>
      <div class="live_band__item__author">
        <?php
        if($question['Specialists'][0]['id'])
        {
          ?>
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr valign="top">
              <td style="padding-right: 10px;" width="1">отвечает</td>
              <td>
                <?php
                $author_link = '<div>Врач РБ</div>';
                $author_about = 'Сервис медицинской консультации';
                if($question['Specialists'][0]['id'] != 51)
                {
                  $author_link = '<a href="' . url_for('@specialist_index') . $question['Specialists'][0]['title_url'] . '/">' . $question['Specialists'][0]['User']['first_name'] . ' ' . ($question['Specialists'][0]['User']['middle_name'] ? $question['Specialists'][0]['User']['middle_name'] . ' ' : '') . $question['Specialists'][0]['User']['second_name'] . '</a>';
                  $author_about = $question['Specialists'][0]['about'];
                }
                echo $author_link;
                echo '<i class="br1"></i>';
                echo $author_about;
                ?>
              </td>
            </tr>
          </table>
          <?php
        }
        else
        {
          echo 'Ожидает ответа<br>';
        }
        ?>
      </div>
    </div>



    <?php
    */
    ?>



    <div class="live_band__item new_live_band__item">
      <table cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
        <tr>
          <td colspan="2" class="live_band__item__author live_band__item__block" height="30">

            <div class="live_band__item__author__div">
              <table class="live_band__item__author__div__table" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tbody><tr valign="middle">
                  <td class="live_band__item__author__name" width="1">отвечает:</td>
                  <td>
                    <?php
                    $s_name = 'Врач РБ';
                    $s_about = 'Сервис медицинской консультации';
                    if($question['Specialists'][0]['id'] != 51)
                    {
                      $s_name = '<a class="live_band__item__author_link" href="' . url_for('@specialist_index') . $question['Specialists'][0]['title_url'] . '/">' . $question['Specialists'][0]['User']['first_name'] . ' ' . ($question['Specialists'][0]['User']['middle_name'] ? $question['Specialists'][0]['User']['middle_name'] . ' ' : '') . $question['Specialists'][0]['User']['second_name'] . '</a>';
                      $s_about = $question['Specialists'][0]['about'];
                    }
                    $str_about = Page::strCut(strip_tags($s_about), 200);
                    echo $s_name . '. <span ' . ($str_about[1] == 'full' ? 'title="' . $s_about . '"' : '') . '>' . $str_about[0] . '</span></td>';
                    ?>
                  <td class="live_band__item__author__name" width="1">кабинет:</td>
                  <td width="1">
                    <?php
                    $sp_about = $question['Specialists'][0]['Specialty']['title'];
                    $sp_about_str = Page::strCut($sp_about, 50);
                    echo '<a href="' . url_for('@categories_transition_filter?id=' . $question['Specialists'][0]['Specialty']['id']) . '" class="live_band__item__author_link" ' . ($sp_about_str[1] == 'full' ? 'title="' . $sp_about . '"' : '') . '>' . str_replace(' -', '&nbsp;-', $sp_about_str[0]) . '</a>';
                    ?>
                  </td>
                </tr></tbody>
              </table>
            </div>

          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block live_band__item__author_patient" height="40">
            <?php
            $age = date('Y') - substr($question['User']['birth_date'], 0, 4);
            echo '<b>' . Page::nameAge($age, $question['User']['gender']) . ($age == date('Y') || $age == 0 ? '' : ', ' . $age . ' ' . Page::niceRusEnds($age, 'год', 'года', 'лет')) . '</b>';
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block">
            <?php
            $question_raw = $question->getRawValue();
            echo '<a ' . (sfConfig::get('app_question_quick_open') ? 'onclick="questionAnswerQuickOpen.open(this, ' . $question['id'] . ');return false;"' : '') . ' href="' . url_for('@question_answer_show?id=' . $question['id']) . '" class="live_band__item__body">';
            echo '<div class="live_band__item__body__text">' . $question_raw['body'] . '</div>';
            echo '</a>';
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="live_band__item__block">
            <div class="live_band__item__bottom_line"></div>
          </td>
        </tr>
        <tr class="live_band__item__bottom">
          <td colspan="2" class="live_band__item__block" height="30">
            <table cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr>
                <td align="left" width="200"><?php echo Page::rusDate($question['created_at']);?></td>
                <td align="center">
                  <?php
                  $live_ribbon_answer_value = 'Без ответа';
                  if($question['a_count'] > 0)
                  {
                    $a_last_date = strtotime($question['a_last_date']);
                    $q_last_date = strtotime($question['created_at']);
                    if(isset($a_last_date) && isset($q_last_date) && $a_last_date > $q_last_date)
                    {
                      $time = $a_last_date - $q_last_date;
                      $live_ribbon_answer_value = 'Ответ получен (' . Page::timeUnit($time, array(), 'cut') . ')';
                    }
                  }
                  echo $live_ribbon_answer_value;
                  ?>
                </td>
                <td width="200" align="right">
                  <?php
                  echo (isset($question['closed_by']) ? '✓&nbsp;Вопрос закрыт' : 'В обсуждении') . ' (' . $question['a_count'] . '&nbsp;сообщ.)';
                  ?>
                </td>
              </tr></tbody>
            </table>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
<?php
  }
}
else
{
  echo '<p>Нет вопросов к выбранному специалисту</p>';
}
?>