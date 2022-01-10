<div class="overlay__close">×</div>
<table width="100%" height="100%" onclick="event.stopPropagation();" style="position: relative;top: -100%;">
  <tr>
    <td valign="middle" align="center">
      <div class="fotorama" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-min-width="300" data-max-width="90%" data-min-height="300" data-max-height="90%" data-hash="false">
        <?php
        foreach ($table_collection as $item)
        {
          // $file_ex = file_exists(sfConfig::get('sf_upload_dir') . '/i/' . Page::replaceImageSize($item['photo'],'M')) ? true : false;
          $file_ex = file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $item['photo']) ? true : false;
          if($item['id'] == $data_id && $file_ex)
          {
            $result_elem = '<img src="/u/i/' . (file_exists(sfConfig::get('sf_upload_dir') . '/i/' . Page::replaceImageSize($item['photo'],'M')) ? Page::replaceImageSize($item['photo'],'M') : $item['photo']) . '" />';
          }
          elseif($file_ex)
          {
            $all_elem .= '<img src="/u/i/' . $item['photo'] . '" />';
          }
        }
        $result_elem .= $all_elem;
        echo $result_elem;
        ?>
      </div>
    </td>
  </tr>
</table>
<?php
if($data_table == 'Certificate')
{
  ?>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.fotorama').fotorama();
    });
  </script>
  <?php
}
?>