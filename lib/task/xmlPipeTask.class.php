<?php
class xmlPipeTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace = 'xml';
    $this->name = 'pipe';
    
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'arm'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));
  }

  protected function execute($arguments = array(), $options = array())
  {
//    file_put_contents(sfConfig::get('sf_log_dir') . '/xml_pipe_' . time(), time());
    ini_set('display_errors', 'Off');
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $this->log('<?xml version="1.0" encoding="utf-8"?>');
    $this->log('<sphinx:docset>');
    $this->log('<sphinx:schema>');
    $this->log('<sphinx:field name="title"/>');
    $this->log('<sphinx:field name="content"/>');
    $this->log('<sphinx:attr name="where2" type="int"/>');
    $this->log('</sphinx:schema>');
    
    $search_cfg = sfConfig::get('app_search_objects');


    foreach($search_cfg as $so_idx => $search_object)
    {
      $q = Doctrine_Query::create()
        ->select("*")
        ->from($search_object['model'] . ' a')
      ;

      if (isset($search_object['leftJoin']))
      {
        foreach($search_object['leftJoin'] as $left_join)
        {
          $q->leftJoin($left_join);
        }
      }

      if (isset($search_object['innerJoin']))
      {
        foreach($search_object['innerJoin'] as $inner_join)
        {
          $q->innerJoin($inner_join);
        }
      }

      if(isset($search_object['where']))
      {
        $q->where($search_object['where']);
      }
      $objects = $q->execute();
      foreach($objects as $object)
      {
        $this->log('<sphinx:document id="' . (($object['id'] * 10) + $so_idx)  . '">');
        $this->log('<where2>' . $so_idx . '</where2>');

        $title_arr = array();

        if (isset($search_object['title']))
        {
          foreach($search_object['title'] as $content_field)
          {
            $title_arr[] = htmlspecialchars(strip_tags($object[$content_field]));
          }
        }

        if (isset($search_object['title_methods']))
        {
          foreach($search_object['title_methods'] as $title_methods)
          {
            $ob = $object;
            foreach($title_methods as $title_method)
            {
              if ($title_method == 'get')
              {
                $ob = $ob->$title_method(0);
              }
              else
              {
                $ob = $ob->$title_method();
              }
            }
            $title_arr[] = htmlspecialchars(strip_tags($ob));
          }
        }




        $this->log('<title>' . implode(" ", $title_arr) . '</title>');




        $content = array();
        if (isset($search_object['content']))
        {
          foreach($search_object['content'] as $content_field)
          {
            $content[] = htmlspecialchars(strip_tags($object[$content_field]));
          }
        }

        if (isset($search_object['content_methods']))
        {
          foreach($search_object['content_methods'] as $content_methods)
          {
            $ob = $object;
            foreach($content_methods as $content_method)
            {
              if ($content_method == 'get')
              {
                $ob = $ob->$content_method(0);
              }
              else
              {
                $ob = $ob->$content_method();
              }
            }
            $content[] = htmlspecialchars(strip_tags($ob));
          }
        }

        $this->log('<content>' . implode("\n", $content) . '</content>');
        $this->log('</sphinx:document>');
      }
    }

    $this->log('</sphinx:docset>');
  }
}
