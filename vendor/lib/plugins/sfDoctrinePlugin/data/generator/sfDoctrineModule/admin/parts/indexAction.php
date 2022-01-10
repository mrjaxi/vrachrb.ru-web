  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }
    else
    {
      $this->setPage(1);
    }
    
    //search
    if ($request->getParameter('q'))
    {
      $this->setSearch($request->getParameter('q'), ($request->hasParameter('checked') ? $request->getParameter('checked') : false));
    }
    else
    {
      $this->setSearch(false);
    }
    
    //checked
    if ($request->getParameter('checked'))
    {
      $this->setChecked($request->getParameter('checked'));
    }
    else
    {
      $this->setChecked(false);
    }
<?php if ($this->configuration->hasTabs()): ?>
    //tabs
    if ($request->hasParameter('tab'))
    {
      $this->setTab($request->getParameter('tab'));
    }

<?php endif; ?>

    $this->pager = $this->getPager($request);
    $this->sort = $this->getSort();

    
    
    
    
    if($request->hasParameter('export'))
    {
 
      require_once sfConfig::get('sf_lib_dir') . '/Classes/PHPExcel.php';
      $objPHPExcel = new PHPExcel();
      $sheet = $objPHPExcel->setActiveSheetIndex(0);
      <?php
        $ch = 65;
        foreach ($this->configuration->getValue('list.display') as $name => $field)
        {
?>
          $sheet->setCellValue('<?php echo chr($ch)?>1', '<?php echo $field->getConfig('label', '', true);?>');
          $styleArray = array(
              'font' => array(
                  'bold' => true,
              )
          );


        $sheet->getStyle('<?php echo chr($ch)?>1')->applyFromArray($styleArray);

<?php
          $ch++;
        }
      ?>
    
      foreach ($this->pager->getResults() as $i => $<?php echo $this->getSingularName() ?>)
      {
        <?php
        $ch = 65;
        foreach ($this->configuration->getValue('list.display') as $name => $field)
        {
          ?>
          $value = '';
<?php
          if($field->isPartial())
          {
?>

            ob_start();
            ob_implicit_flush(0);

            try
            {
              require(sfConfig::get('sf_app_module_dir') . '/<?php echo $this->getModuleName();?>/templates/_<?php echo $name;?>.php');
            }
            catch (Exception $e)
            {
              ob_end_clean();
              throw $e;
            }

            $value = strip_tags(ob_get_clean());
            
            
<?php
          }
         
          else
          {
?>
          $method = 'get<?php echo $method = str_replace(' ', '', (ucwords(str_replace('_', ' ', $name))));?>';
   
          if(is_callable(array($<?php echo $this->getSingularName();?>, 'get<?php echo $method;?>')))
          {
            $method = $<?php echo $this->getSingularName();?>->$method();
            if(is_object($method) && count($method) > 0)
            {
              $value = $method->__toString();
            }
            
            else
            {
              $value = $method;
            }
          }
<?php
          }
          if ('Date' == $field->getType())
          {
?>
            $value = date('d.m.Y', strtotime($value));
<?php
          }
          
?>
          

          
          $sheet->setCellValue('<?php echo chr($ch)?>' . ($i + 2), $value);
          $sheet->getColumnDimension('<?php echo chr($ch)?>')->setAutoSize(true);
          
<?php
          $ch++;
        }
        ?>
      }
      $sheet->setAutoFilter('A1:<?php echo chr($ch - 1);?>' . $i);
      $sheet->setTitle('<?php echo $this->configuration->getValue('list.title');?>');
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $uuid = Page::generateUuid();

      $objWriter->save('php://output');
      $this->getResponse()->setHttpHeader('Content-type', 'application/octet-stream');
      $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="<?php echo $this->configuration->getValue('list.title');?>.xlsx"');

      return sfView::NONE;
   
    }
  }

