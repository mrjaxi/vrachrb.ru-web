<?php

/**
 * search actions.
 *
 * @package    sf
 * @subpackage search
 * @author     SyLord
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {

//    $questions = Doctrine::getTable('Question')
//      ->createQuery('q')
//      ->leftJoin('q.Answer a')
//      ->execute()
//    ;



    $limit = 30;

    $this->res = array();
    $this->rs = array();
    $pages = $articles = $docs = $courses = array();
    $this->query = $this->getRequestParameter('q');
    $this->page = $this->getRequestParameter('p', 1);
    $options = array(
      'limit'   => $limit,
      'offset'  => ($this->page - 1) * $limit,
      'weights' => array(100, 1),
      'sort'    => sfSphinxClient::SPH_SORT_EXTENDED,
      'sortby'  => '@weight DESC',
      'host' => '127.0.0.1'
    );
    if (!empty($this->query))
    {
      $this->sphinx = new sfSphinxClient($options);
      $this->res[0] = $this->sphinx->Query($this->query, 'vrachrb_ru_index');

      $search_cfg = sfConfig::get('app_search_objects');

      $this->search_cfg = $search_cfg;
      
      $pos_length = 200;
      
      $change_url = array('news', 'article', 'tip');
      
      if($this->res[0]['total_found'] > 0)
      {
        foreach($this->res[0]['matches'] as $matches)
        {
          $where = intval($matches['attrs']['where2']);
          $id = ($matches['id'] - $where) / 10;
          $search_cfg_item = $search_cfg[$where];

          $query = Doctrine::getTable($search_cfg_item['model'])
            ->createQuery('a')
          ;

          if (isset($search_cfg_item['leftJoin']))
          {
            foreach($search_cfg_item['leftJoin'] as $left_join)
            {
              $query->leftJoin($left_join);
            }
          }

          if (isset($search_cfg_item['innerJoin']))
          {
            foreach($search_cfg_item['innerJoin'] as $inner_join)
            {
              $query->innerJoin($inner_join);
            }
          }

          $query->where('a.id = ?', $id);
          $object = $query->fetchOne();

          if($object)
          {
            $desc = false;

            $title = array();

            if (isset($search_cfg_item['title']))
            {
              foreach($search_cfg_item['title'] as $content_field)
              {
                $title[] = $object[$content_field];
              }
            }

            if (isset($search_cfg_item['title_methods']))
            {
              foreach($search_cfg_item['title_methods'] as $title_methods)
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
                $title[] = htmlspecialchars(strip_tags($ob));
              }
            }

            $title = implode(' ', $title);


            $content = array();
            if ($search_cfg_item['content'])
            {
              foreach($search_cfg_item['content'] as $content_field)
              {
                $content[] = strip_tags($object[$content_field], '<a>');
              }
            }

            if (isset($search_cfg_item['content_methods']))
            {
              foreach($search_cfg_item['content_methods'] as $content_methods)
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

            if(count($content) > 0)
            {
              $desc = implode(' ', $content);
            }

            if ($desc == $title)
            {
              $desc = false;
            }
            
            foreach($this->res[0]['words'] as $word => $info)
            {
              $pos = mb_stripos($title, $word);
              if($pos !== false)
              {
                $title = mb_substr($title, 0, $pos) . '<span class="txt_color_yellow">' . mb_substr($title, $pos, mb_strlen($word)) . '</span>' . mb_substr($title, $pos + mb_strlen($word));
                $desc = false;
              }
              elseif($desc != '')
              {
                $pos = mb_stripos($desc, $word);
                if($pos !== false)
                {
                  $desc = ($pos <= $pos_length ? mb_substr($desc, 0, $pos) : mb_substr($desc, $pos - $pos_length, $pos_length)) 
                    . '<span class="txt_color_yellow">' . mb_substr($desc, $pos, mb_strlen($word)) 
                    . '</span>' . mb_substr($desc, $pos + mb_strlen($word), $pos_length);
                  $ex = explode(' ', $desc);
                  if(mb_strlen($desc) > $pos_length)
                  {
                    $ex[count($ex) - 1] = '...';
                  }
                  if($pos > $pos_length)
                  {
                    $ex[0] = '...';
                  }
                  $desc = implode(' ', $ex);
                }
              }
            }

            $key = 'id';

            if(isset($search_cfg_item['key'])){
                $key = $search_cfg_item['key'];                
            }
            
            $key_value_method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            
            $key_value = $object->$key_value_method();
                      
            $link = isset($search_cfg_item['global_link']) ? $search_cfg_item['global_link'] : ($search_cfg_item['module'] . '/' . (isset($search_cfg_item['show_action']) ? $search_cfg_item['show_action'] : 'show') . '?' . $key . '=' . $key_value . (isset($search_cfg_item['show_with_year']) ? '&year=' . date('Y', strtotime($object->getCreatedAt())) : '')) . '';

            $this->rs[$where][] = array(
              'title' => $title,
              'desc' => $desc,
              'link' => $link
            );
          }
        }
      }
    }
  }
}