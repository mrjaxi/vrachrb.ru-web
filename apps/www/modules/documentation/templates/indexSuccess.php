<?php
  slot('title', 'Документация');
?>
<div class="breadcrumbs">
  <a href="/">Главная</a>
</div>
<h1><?php echo ($sf_user->getAttribute('seoH') ? $sf_user->getAttribute('seoH') : 'Документация');?></h1>
<table cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td>
      <div class="white_box">
        <?php
          foreach ($document as $document_idx => $document_item) {
            if($document_idx != 0)
            {
              echo '<i class="br10"></i>';
            }
            $file_type = substr($document_item['file'],strrpos($document_item['file'],'.'));
            echo '<a class="document__item download_icons" href="' . url_for('documentation/?id=' . $document_item['id']) . '' . $document_item['title'] . $file_type . '">';
              echo $document_item['title'] . '<br /><i class="download_icons__inf">' . Page::formatSize(filesize(sfConfig::get('sf_upload_dir') . '/i/' . $document_item['file'])) . '</i>';
            echo '</a>';
          }
        ?>
      </div>
    </td>
    <td width="1" style="padding-left: 20px;">
      <?php
      if(count($agreement) > 0)
      {
        foreach ($agreement as $a)
        {
          echo '<a target="_blank" href="' . url_for('@agreement_show?id=' . $a['id']) . '">' . $a['title'] . '</a><i class="br10"></i>';
        }
        echo '<i class="br10"></i>';
      }
      include_component('main', 'banner', array('show_location' => 'document'));
      ?>
    </td>
  </tr>
</table>