<?php
if ($form->isNew() == false)
{
  ?>

  <style>
    .comment_item_field{
      margin: 20px 0;
    }
  </style>

  <i class="br20"></i>
  <div class="fs_18">Место и время приёма</div>
  <i class="br20"></i>
  <?php
  $location = $form->getObject()->getLocation();
  $datetime = $form->getObject()->getReceive_datetime();
  if(count($datetime) > 0)
  {
  ?>
    <div class="wrap_comments">
      <div class="comment_item">
        <table cellspacing="0" cellpadding="">
          <tr align="left">
            <td>
              <b>Место</b>
            </td>
            <td>
              <b>Дата и время</b>
            </td>
          </tr>
          <tr valign="top" align="left">
            <td style="padding-right: 40px">
              <?php
              echo '<div class="comment_item_field">' . $location[0]->getTitle() . '</div>';
              ?>
            </td>
            <td>
              <?php
              foreach($datetime as $k_dt => $dt)
              {
                ?>
                <div class="comment_item_field">
                  <div style="display: inline-block;vertical-align: top;">
                    <?php echo Page::rusDate($dt->getDatetime()) . ' ' . mb_substr($dt->getDatetime(), 11, 5, 'utf-8');?>
                  </div>

                  <div style="display: inline-block;padding-left:20px;">
                    <a href="<?php echo url_for('receive_datetime_edit', array('id' => $dt->getId())) ?>">Редактировать</a>
                    <i class="br3"></i>
                    <a href="<?php echo url_for('receive_datetime') . '/' . $dt->getId() . '/delete' ?>" onclick="receptionContract.remove($(this), event)" class="red_link">Удалить</a>
                  </div>
                </div>
                <?php
              }
              ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  <?php
  }
  ?>
  <i class="br5"></i>
  <a class="lui_pseudo" href="<?php echo url_for('receive_datetime_new') ?>?id=<?php echo $form->getObject()->getId() ?>">Добавить дату приёма</a>
<?php
}
?>