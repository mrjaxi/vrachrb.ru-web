<?php
class ask_questionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->analysis_form = new CreateMultipleAnalysisForm();

    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = '';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ' OR s.id = ' . $lpu_specialist;
      }
    }

    $this->no_specialty_id = true;
    
    $specialists_q = Doctrine_Query::create()
      ->select('s.*,u.*, so.*')
      ->from('Specialist s')
      ->innerJoin('s.User u')
      ->leftJoin("s.Specialist_online so")
      ->orderBy('s.rating DESC')
      ->where('s.user_id != 1' . $lpu_str)
    ;
    
    if($this->getUser()->getAttribute('lpu'))
    {
      $specialists_q->innerJoin('s.LpuSpecialist ls');
      $specialists_q->where('ls.lpu_id = ' . $this->getUser()->getAttribute('lpu'));
    }

    if($request->getCookie('sort_ask_specialist') && !$request->getParameter('id'))
    {
      $valid_type = array('rating', 'symbol');
      $valid_values  = array('desc', 'asc');
      $cookie_exp = explode(':', $request->getCookie('sort_ask_specialist'));
      if(in_array(substr($cookie_exp[0], 2, 6), $valid_type) && in_array(substr($cookie_exp[0], -4), $valid_values) && substr_count($cookie_exp[1], ' AND s.specialty_id = ') >= 1)
      {
        $this->no_specialty_id = false;
        $sp_id_resolution = substr($cookie_exp[1], 4);
        $specialists_q->andWhere($sp_id_resolution);
        if(substr_count($sp_id_resolution, '=') > 0)
        {
          $this->specialty_visible_id = trim(str_replace('s.specialty_id = ', '', $sp_id_resolution));
        }
        $this->specialist_cookie_sort = $sp_id_resolution;
      }
    }

    if($request->getParameter('id') && is_numeric($request->getParameter('id')))
    {
      $this->no_specialty_id = false;
      $specialty = Doctrine::getTable("Specialist")->find($request->getParameter('id'));
      $specialists_q->andWhere("s.specialty_id = " . $specialty->getSpecialtyId());
    }
    
    $this->specialists = $specialists_q->execute();

    $sp = Doctrine_Query::create()
      ->select('s.*')
      ->from('Specialty s')
    ;

    if($this->getUser()->getAttribute('lpu'))
    {
      $sp->innerJoin('s.Specialist sp')->where(str_replace('s.id', 'sp.id', substr($lpu_str, 4)));
    }

    $sp->orderBy('s.title ASC');

    $this->specialty = $sp->execute();
    
    $this->sheet_history = Doctrine::getTable('SheetHistory')->findOneById(1);
    
    $user = $this->getUser();

    $specialist = $this->getUser()->getAccount();

    if($user->isAuthenticated() && !$specialist->getSpecialist())
    {
      $this->attached_family_users = Doctrine::getTable("Attached_family_users")->findByUserId($this->getUser()->getAccount()->getId());
    }

    if($user->isAuthenticated() && $request->isMethod('post'))
    {
      if(!$specialist->getSpecialist())
      {
        if($request->getParameter('who_does') == 'family')
        {
          $request_user_about = $request->getParameter('user_about');

          if($request_user_about['user_about_id'] && is_numeric($request_user_about['user_about_id']))
          {
            $check_family_user = Doctrine::getTable("Attached_family_users")->findByUserAboutId($request_user_about['user_about_id']);
            if($check_family_user[0])
            {
              $about_user_id = $request_user_about['user_about_id'];
            }
          }
          elseif($request_user_about['gender'] == 1 || $request_user_about['gender'] == 0)
          {
            $f_name = strip_tags($request_user_about['first_name']);
            $m_name = strip_tags($request_user_about['middle_name']);
            $s_name = strip_tags($request_user_about['second_name']);
            $g = ($request_user_about['gender'] == 1 ? 'ж' : 'м');
            $bd = strip_tags($request_user_about['birth_date']);
            if($f_name != '' && $m_name != '' && $s_name != '')
            {
              $about_user_param = array(
                'username' => $f_name . ' ' . $m_name . ' ' . $s_name,
                'first_name' => $f_name,
                'second_name' => $m_name,
                'middle_name' => $s_name,
                'gender' => $g,
                'birth_date' => $bd,
              );

              $about_user_form = new UserForm();

              $about_user_param['_csrf_token'] = $about_user_form->getCSRFToken();
              $about_user_form->bind($about_user_param);

              if($about_user_form->isValid())
              {
                $about_user_form->save();
                $about_user_id = $about_user_form->getObject()->getId();
              }
            }
          }
          if($about_user_id)
          {
            $create_af_user = Doctrine::getTable("Attached_family_users")->findOneByUserAboutId($about_user_id);
            if(!$create_af_user)
            {
              $create_af_user = new Attached_family_users();
              $create_af_user->setUserId($this->getUser()->getAccount()->getId());
              $create_af_user->setUserAboutId($about_user_id);
              $create_af_user->save();
            }
          }
        }

        $p_question = $request->getParameter('question');
        $p_sheet_fields = $request->getParameter('sheet_field');
        $p_sheet_field_files = $request->getFiles('sheet_field');

        $question = new Question();

        if($about_user_id && $create_af_user->getId())
        {
          $question->setUserAboutId($about_user_id);
        }

        $question->setUserId($user->getUserId());
        $question->setBody(trim(strip_tags($p_question['body'])));
        if(isset($p_question['is_anonymous']))
        {
          $question->setIsAnonymous(true);
        }
        $question->save();

        $q_specialist = $request->getParameter('q_specialist');

        if((isset($p_question['specialist_id']) && is_numeric($p_question['specialist_id'])) || (isset($q_specialist) && is_numeric($q_specialist)))
        {
          $specialist_id = $p_question['specialist_id'] ? $p_question['specialist_id'] : $q_specialist;
        }
        elseif(isset($p_question['specialty_id']) && $p_question['specialty_id'] != 'undefined' && is_numeric($p_question['specialty_id']))
        {
          $specialty_specialist = Doctrine_Query::create()
            ->select("s.*, sp.*")
            ->from("Specialist s")
            ->innerJoin("s.Specialty sp")
            ->where("sp.id = " . $p_question['specialty_id'])
            ->fetchArray()
          ;

          if(count($specialty_specialist) > 0)
          {
            $specialty_specialist_arr = array();
            foreach ($specialty_specialist as $sp_specialist)
            {
              $specialty_specialist_arr[] = $sp_specialist['id'];
            }
            $specialist_id = $specialty_specialist_arr[array_rand($specialty_specialist_arr)];
          }
          else
          {
            $specialty_id = $p_question['specialty_id'];
          }
        }

        if(!$specialist_id)
        {
          $specialist_id = 51;
        }

        if($specialist_id)
        {
          $question_specialist = new QuestionSpecialist();
          $question_specialist->setQuestionId($question->getId());
          $question_specialist->setSpecialistId($specialist_id);
          $question_specialist->save();
          if($question_specialist)
          {
            $question_specialty = new QuestionSpecialty();
            $question_specialty->setQuestionId($question->getId());
            $question_specialty->setSpecialtyId($question_specialist->getSpecialist()->getSpecialtyId());
            $question_specialty->save();
          }
          Page::noticeAdd('s', 'dialog', $question->getId(), 'question');
        }
        elseif($specialty_id)
        {
          $check_specialty_id = Doctrine::getTable("Specialty")->find($specialty_id);
          if($check_specialty_id)
          {
            $question_specialty = new QuestionSpecialty();
            $question_specialty->setQuestionId($question->getId());
            $question_specialty->setSpecialtyId($specialty_id);
            $question_specialty->save();
          }
        }

        if(is_array($p_sheet_field_files))
        {
          foreach($p_sheet_field_files as $p_sheet_field_file_id => $p_sheet_field_file)
          {
            print_R($p_sheet_field_file);
            if(is_uploaded_file($p_sheet_field_file['tmp_name']))
            {
              $fn = sha1_file($p_sheet_field_file['tmp_name']);
              if(!is_dir(sfConfig::get('sf_upload_dir') . '/p/' . $user->getUserId()))
              {
                mkdir(sfConfig::get('sf_upload_dir') . '/p/' . $user->getUserId());
              }
              if(move_uploaded_file($p_sheet_field_file['tmp_name'], sfConfig::get('sf_upload_dir') . '/p/' . $user->getUserId() . '/' . $fn))
              {
                $p_sheet_fields[$p_sheet_field_file_id]['file'] = array(
                  'name' => $p_sheet_field_file['name'],
                  'path' => $user->getUserId() . '/' . $fn
                );
              }
            }
          }
        }
        foreach($p_sheet_fields as $p_sheet_field_id => $p_sheet_field)
        {
          $question_sheet_history = new QuestionSheetHistory();
          $question_sheet_history->setQuestionId($question->getId());
          $question_sheet_history->setSheetHistoryFieldId($p_sheet_field_id);
          $question_sheet_history->setValue(json_encode($p_sheet_field));
          $question_sheet_history->save();
        }

//        $this->redirect('personal_account_index');
//      $this->redirect('question_answer_show', array('id' => $question->getId()));
      $this->forward('ask_question', 'complete');
      }
    }
  }
  public function executeComplete(sfWebRequest $request)
  {

  }
  public function executeTip(sfWebRequest $request)
  {
    if($request->isMethod('post'))
    {
      $limit = 1000;

      $this->res = array();
      $this->rs = array();
      $pages = $articles = $docs = $courses = array();
      $this->query = $request->getParameter('q');
      $this->page = 1;
      $options = array(
        'limit'   => $limit,
        'offset'  => ($this->page - 1) * $limit,
        'weights' => array(100, 1),
        'sort'    => sfSphinxClient::SPH_SORT_EXTENDED,
        'sortby'  => '@weight DESC',
        'host' => '192.168.2.7'
//        'host' => '127.0.0.1'
      );
      if (!empty($this->query))
      {
        $this->sphinx = new sfSphinxClient($options);
        $this->res[0] = $this->sphinx->Query($this->query, 'vrachrb_ru');

        $search_cfg = sfConfig::get('app_question_tip_objects');

        $this->search_cfg = $search_cfg;

        $pos_length = 200;

        if($this->res[0]['total_found'] > 0)
        {
          foreach($this->res[0]['matches'] as $matches)
          {
            $where = intval($matches['attrs']['where']);
            $id = ($matches['id'] - $where) / 10;
            $search_cfg_item = $search_cfg[$where];

            $query = Doctrine::getTable('Question')
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

              $this->rs[$where][] = array(
                'title' => $title,
                'desc' => $desc,
                'link' => $key_value
              );
            }
          }
        }
      }
    }
  }
  public function executeSpecialist_filter(sfWebRequest $request)
  {
    if($request->isMethod('post'))
    {
      $error = true;
      $lpu_str = '';
      if($this->getUser()->getAttribute('lpu'))
      {
        $lpu_str = ' AND (';
        foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
        {
          $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 's.id = ' . $lpu_specialist;
        }
        $lpu_str .= ')';
      }

      if(is_numeric($request->getParameter('select_specialty_id')) && $request->getParameter('select_specialty_id'))
      {
        $advanced_filter = ' AND s.specialty_id = ' . $request->getParameter('select_specialty_id');
      }
      else
      {
        $this->select_admin = true;
      }

      $exp = explode(':', $request->getParameter('param'));
      $check_arr1 = array('rating', 'symbol');
      $check_arr2 = array('asc', 'desc');

      if(in_array($exp[0], $check_arr1) && in_array($exp[1], $check_arr2))
      {
        $cookie_ask_specialists = '';

        $s = Doctrine_Query::create()
          ->select('s.*,u.*, so.*')
          ->from('Specialist s')
          ->innerJoin('s.User u')
          ->leftJoin('s.Specialist_online so')
          ->where('u.id != 1' . $advanced_filter . $lpu_str)
        ;
        if($exp[0] == 'symbol')
        {
          $s->orderBy('u.first_name ' . $exp[1]);
          $cookie_ask_specialists .= 'u.first_name ' . $exp[1];
        }
        else
        {
          $s->orderBy('s.' . $exp[0] . ' ' . $exp[1]);
          $cookie_ask_specialists .= 's.' . $exp[0] . ' ' . $exp[1];
        }
        $this->specialists = $s->fetchArray();

        $cookie_ask_specialists .= ':' . $advanced_filter;
        $this->getResponse()->setCookie('sort_ask_specialist', $cookie_ask_specialists);

        if(count($this->specialists) == 0)
        {
          echo 0;
          return sfView::NONE;
        }

        $error = false;
      }
    }

    $this->forward404Unless(!$error);
  }
  public function executeSelect_specialist(sfWebRequest $request)
  {
    $specialist = Doctrine::getTable("Specialist")->find($request->getParameter('id'));
    if($specialist)
    {
      $this->forward('ask_question', 'index', array('select_specialist_id' => $request->getParameter('id')));
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeSheet_history_update(sfWebRequest $request)
  {
    $specialty_id = $request->getParameter('specialty_id');
    $specialist_id = $request->getParameter('specialist_id');
    if(is_numeric($specialist_id))
    {
      $specialist = Doctrine::getTable("Specialist")->find($specialist_id);
      if($specialist)
      {
        $verified_specialty_id = $specialist->getSpecialtyId();
      }
    }
    else
    {
      $specialty = Doctrine::getTable("Specialty")->find($specialty_id);
      if($specialty)
      {
        $verified_specialty_id = $specialty_id;
      }
    }
    if($verified_specialty_id)
    {
      $sh = Doctrine_Query::create()
        ->select("sh.*, s.*")
        ->from("SheetHistory sh")
        ->leftJoin("sh.Specialtys s")
        ->where("s.id = " . $verified_specialty_id)
        ->fetchOne()
      ;
    }
    $sh_id = $sh ? $sh->getId() : 1;
    $this->sheet_history = Doctrine::getTable("SheetHistory")->find($sh_id);
  }
}
