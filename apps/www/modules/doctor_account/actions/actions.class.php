<?php
class doctor_accountActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeFilter_view(sfWebRequest $request)
  {
    if ($request->isMethod('post') && $request->isXmlHttpRequest())
    {
      $this->filter_da_list = strip_tags($request->getParameter('filter'));
    }
    else
    {
      $this->forward404Unless(false);
    }
  }
  public function executeData(sfWebRequest $request)
  {
    $account_user = $this->getUser()->getAccount();
    $this->form_user = new ChangeUserSpecialistForm($account_user);
    $this->change_password_form = new ChangePasswordForm($account_user);

    $specialist = $this->getUser()->getAccount()->getSpecialist();
    $account_specialist = Doctrine::getTable('Specialist')->findOneByUserId($this->getUser()->getAccount()->getid());

    $this->form_specialist = new ChangeSpecialistForm($account_specialist);

    $this->specialist_id = $specialist[0]->getId();

    $this->specialist_work_place = Doctrine::getTable("Specialist_work_place")->findBySpecialistId($specialist[0]->getId());

    if ($request->isMethod('post'))
    {
      $request_user = $request->getParameter('user');
      $request_specialist = $request->getParameter('specialist');

      $specialty_id = Doctrine::getTable("Specialist")->findOneByUserId($this->getUser()->getAccount()->getId())->getSpecialtyId();

      $request_specialist['user_id'] = $this->getUser()->getAccount()->getId();
      $request_specialist['specialty_id'] = $specialty_id;

      $this->form_user->bind($request_user);
      $this->form_specialist->bind($request_specialist);

      if ($this->form_user->isValid() && $this->form_specialist->isValid())
      {
        $this->form_user->save();
        $this->form_specialist->save();
        $wp_save_arr = array();
        $request_work_place = explode(':division:', $request->getParameter('work_place'));
        $work_places = Doctrine::getTable("Specialist_work_place")->findBySpecialistId($specialist[0]->getId());
        foreach ($work_places as $wp)
        {
          $wp->delete();
        }
        foreach ($request_work_place as $rwp)
        {
          $hidden = substr($rwp, -1);
          if(($hidden == 1 || $hidden == 0) && substr($rwp, 0, -1) != '')
          {
            $rwp_value = array(
              'specialist_id' => $specialist[0]->getId(),
              'title' => substr($rwp, 0, -1),
              'hidden' => $hidden
            );
            $swp_new = new CreateSpecialist_work_placeForm();
            $swp_new->bind($rwp_value);
            if($swp_new->isValid())
            {
              $swp_new->save();
            }
          }
        }
        $this->save_true = true;
      }
    }

    $this->specialist_work_place = Doctrine::getTable("Specialist_work_place")->findBySpecialistId($specialist[0]->getId());
  }
  public function executePosting(sfWebRequest $request)
  {
    $this->prompt_form = new CreatePromptForm();

    $sp_id = $this->getUser()->getAccount()->getSpecialist();

    if ($request->isMethod('post'))
    {
      $request_prompt = $request->getParameter('prompt');
      $request_prompt['specialist_id'] = $sp_id[0]['id'];

      $this->prompt_form->bind($request_prompt);

      if ($this->prompt_form->isValid())
      {
        $this->prompt_form->save();
        $this->message_true = true;
      }
      else
      {
        $this->message_error = true;
      }
    }
  }
  public function executePosting_article(sfWebRequest $request)
  {
    $this->article_form = new CreateArticleForm();

    $sp_id = $this->getUser()->getAccount()->getSpecialist();
    if ($request->isMethod('post'))
    {
      $request_article = $request->getParameter('article');
      $request_article['specialist_id'] = $sp_id[0]['id'];

      $this->article_form->bind($request_article);
      if ($this->article_form->isValid())
      {
        $this->article_form->save();
        $this->message_true = true;
      }
    }
  }
  public function executeConsilium(sfWebRequest $request)
  {
    $specialist = $this->getUser()->getAccount()->getSpecialist();

    $this->councils = Doctrine_Query::create()
      ->select('c.*, q.*, ca.*, qu.*, cas.*, casu.*, cs.*, csc.*, sp.*, spu.*, qs.*, qsu.*')
      ->from('Consilium c')
      ->innerJoin('c.Question q')
      ->innerJoin('q.User qu')
      ->leftJoin('c.Consilium_answer ca')
      ->innerJoin('ca.Specialist cas')
      ->innerJoin('cas.User casu')
      ->innerJoin('q.Specialists qs')
      ->innerJoin('qs.User qsu')
      ->leftJoin('c.ConsiliumSpecialist cs')
      ->leftJoin('cs.Consilium csc')
      ->leftJoin('csc.Specialists sp')
      ->innerJoin('sp.User spu')
      ->where('cs.specialist_id = ' . $specialist[0])
      ->orderBy('ca.created_at ASC, c.created_at DESC')
      ->limit(1)
      ->fetchArray()
    ;

    $councils = $this->councils;
    $consilium_id = $councils[0]['id'];

    if($request->isMethod('post'))
    {
      if($request->getParameter('add_info'))
      {
        $request_add_info = $request->getParameter('add_info');

        if($request_add_info != 'all')
        {
          $add_where = 's.id != ' . $specialist[0]->getId() . ' AND s.id != 51 AND ';
          $add_exp = explode('/', $request_add_info);
          foreach ($add_exp as $ae)
          {
            if($ae && is_numeric($ae))
            {
              $add_where .= 's.id != ' . $ae . ' AND ';
            }
          }
        }

        $s = Doctrine_Query::create()
          ->select('sp.*, s.*, u.*')
          ->from('Specialty sp')
          ->leftJoin('sp.Specialist s')
          ->innerJoin('s.User u')
        ;

        if($request_add_info != 'all')
        {
          $s->where(mb_substr($add_where, 0, -5));
        }
        else
        {
          $s->where('s.id != ' . $specialist[0]->getId() . ' AND s.id != 51');
        }

        $specialty = $s->fetchArray();

        $add_info_obj = array();

        foreach ($specialty as $specialty_key => $specialty_item)
        {
          $add_info_obj[$specialty_key] = array('id' => $specialty_item['id'], 'title' => $specialty_item['title']);
          foreach ($specialty_item['Specialist'] as $specialist_key => $specialist)
          {
            array_push($add_info_obj[$specialty_key], array(
              'id' => $specialist['id'],
              'about' => $specialist['about'],
              'name' => $specialist['User']['first_name'] ? $specialist['User']['first_name'] . ' ' .$specialist['User']['middle_name'] . ' ' . $specialist['User']['second_name'] : $specialist['User']['username']
            ));
          }
        }
        echo json_encode($add_info_obj);
      }
      elseif($request->getParameter('cons_id_and_sp_id_delete'))
      {

//        consilium_specialist_delete

        $cons_id_and_sp_id_delete = $request->getParameter('cons_id_and_sp_id_delete');
        $el = explode('_', $cons_id_and_sp_id_delete);
        $result = 'problem';

        if($el[0] && $el[1] && is_numeric($el[0]) && is_numeric($el[1]))
        {
          $ca = Doctrine::getTable('Consilium')->find($el[0]);
          if($ca)
          {
            $check_author = $ca->getQuestion()->getSpecialists();
            if($check_author[0] == $specialist[0] && $el[1] != $specialist[0])
            {
              $delete_sp = Doctrine_Query::create()
                ->select("cs.*")
                ->from("ConsiliumSpecialist cs")
                ->where("cs.specialist_id = " . $el[1] . " AND cs.consilium_id = " . $el[0])
                ->execute()
              ;
              if($delete_sp)
              {
                $delete_sp->delete();
                $result = 'ok';

                $consilium_specialist_delete_user_id = Doctrine::getTable("Specialist")->find($el[1])->getUserId();
                Page::noticeAdd('s', 'consilium', $el[0], 'consilium_specialist_delete', false, $consilium_specialist_delete_user_id);
              }
            }
          }
        }
        echo $result;
      }
      elseif($request->getParameter('specialist_id') && is_numeric($request->getParameter('consilium_id')))
      {
        $request_specialist_id = $request->getParameter('specialist_id');
        $ca = Doctrine::getTable('Consilium')->find($request->getParameter('consilium_id'));
        if($ca)
        {
          $check_author = $ca->getQuestion()->getSpecialists();

          if($check_author[0] == $specialist[0])
          {
            $request_consilium_id = $request->getParameter('consilium_id');

            $consilium_specialists = Doctrine_Query::create()
              ->select('cs.specialist_id')
              ->from('ConsiliumSpecialist cs')
              ->where('cs.consilium_id = ' . $request_consilium_id)
              ->fetchArray()
            ;
            $check_specialist = true;

            foreach ($consilium_specialists as $consilium_specialist)
            {
              if(in_array($consilium_specialist['specialist_id'], $request_specialist_id))
              {
                $check_specialist = false;
              }
            }

            foreach ($request_specialist_id as $rs_id)
            {
              if(!is_numeric($rs_id))
              {
                $check_specialist = false;
              }
            }

            if($check_specialist)
            {
              $result = 'ok';
              foreach ($request_specialist_id as $specialist_id)
              {
                $add_specialist = new ConsiliumSpecialist();
                $add_specialist
                  ->setSpecialistId($specialist_id)
                  ->setConsiliumId($request_consilium_id)
                ;
                $add_specialist->save();

                $specialist_user_id = Doctrine::getTable("Specialist")->find($specialist_id)->getUserId();
                Page::noticeAdd('s', 'consilium', $request_consilium_id, 'consilium_specialist_add', false, $specialist_user_id);
              }

              $add_arr = array();
              $c_sp = Doctrine_Query::create()
                ->select('cs.*, s.*, u.*')
                ->from('ConsiliumSpecialist cs')
                ->innerJoin('cs.Specialist s')
                ->innerJoin('s.User u')
                ->where('cs.consilium_id = ' . $request_consilium_id . ' AND cs.specialist_id != ' . $specialist[0])
                ->fetchArray()
              ;

              foreach ($c_sp as $item)
              {
                array_push($add_arr, array('specialist_id' => $item['specialist_id'], 'about' => $item['Specialist']['about'], 'name' => $item['Specialist']['User']['first_name'] ? $item['Specialist']['User']['first_name'] . ' ' . $item['Specialist']['User']['middle_name'] . ' ' . $item['Specialist']['User']['second_name'] : $item['Specialist']['User']['username'], 'consilium_id' => $item['consilium_id']));
              }
              echo json_encode($add_arr);
            }
          }
        }
      }
      return sfView::NONE;
    }
  }
  public function executeConsilium_show(sfWebRequest $request)
  {

//    $this->consilium_form = new CreateConsiliumForm();

    $consilium_id = $request->getParameter('id');
    if($consilium_id && is_numeric($consilium_id))
    {
      if(is_numeric($consilium_id))
      {
        $specialist = $this->getUser()->getAccount()->getSpecialist();
        $this->councils = Doctrine_Query::create()
          ->select('c.*, q.*, qua.*, ca.*, qu.*, cas.*, casu.*, cs.*, csc.*, sp.*, spu.*, qs.*, qsu.*')
          ->from('Consilium c')
          ->innerJoin('c.Question q')
          ->innerJoin('q.User qu')
          ->leftJoin('q.UserAbout qua')
          ->leftJoin('c.Consilium_answer ca')
          ->leftJoin('ca.Specialist cas')
          ->leftJoin('cas.User casu')
          ->innerJoin('q.Specialists qs')
          ->innerJoin('qs.User qsu')
          ->leftJoin('c.ConsiliumSpecialist cs')
          ->leftJoin('cs.Consilium csc')
          ->leftJoin('csc.Specialists sp')
          ->innerJoin('sp.User spu')
          ->where('cs.specialist_id = ' . $specialist[0] . ' AND c.id = ' . $consilium_id)
          ->orderBy('ca.created_at ASC')
          ->limit(1)
          ->fetchArray()
        ;
      }

      $notice = Doctrine_Query::create()
        ->select("n.*")
        ->from("Notice n")
        ->where("n.user_id = " . $this->getUser()->getAccount()->getId() . " AND n.type = 'consilium' AND n.inner_id = " . $request->getParameter('id'))
        ->execute()
      ;

      if(count($notice) > 0)
      {
        foreach ($notice as $n)
        {
          $n->delete();
        }
      }

      $this->forward404Unless($this->councils);
    }
  }
  public function executeHistory_appeal(sfWebRequest $request)
  {
    $specialist = $this->getUser()->getAccount()->getSpecialist();
    if($specialist[0]->getId())
    {
      $this->reviews_count = Doctrine::getTable("Review")->findBySpecialistId($specialist[0]->getId())->count("*");

      $notice = Doctrine_Query::create()
        ->select("n.*")
        ->from("Notice n")
        ->where("n.user_id = " . $this->getUser()->getAccount()->getId())
        ->andWhere("n.event = 'review'")
        ->execute()
      ;

      if($notice[0]->getId())
      {
        foreach ($notice as $n)
        {
          $n->delete();
        }
      }
    }
  }
  public function executeHa_filter(sfWebRequest $request)
  {
    $sort = $request->getParameter('sort');
    $patient = $request->getParameter('patient');

    $valid_arr = array('asc', 'desc');

    if($request->isMethod('post') && in_array($sort, $valid_arr) && $sort)
    {
      $this->sort = $sort;
      $this->patient = $patient;
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeConsilium_answer(sfWebRequest $request)
  {
    $this->form = new CreateConsilium_answerForm();

    if ($request->isMethod('post'))
    {
      $specialist = $this->getUser()->getAccount()->getSpecialist();
      $request_answer = $request->getParameter('consilium_answer');

      $save_val = 1;
      $r_param = $request->getParameter('close');
      if($request->getParameter('open'))
      {
        $r_param = $request->getParameter('open');
        $save_val = 0;
      }

      if($request_answer)
      {
        $request_answer['specialist_id'] = $specialist[0];
        $closed = Doctrine::getTable('Consilium')->findOneById($request_answer['consilium_id'])->getClosed();

        $this->form->bind($request_answer);

        if ($this->form->isValid())
        {
          $this->result = false;
          if($closed != 1)
          {
            $this->form->save();
            $result_answer = true;


            $consilium_specialists = Doctrine::getTable("ConsiliumSpecialist")->findByConsiliumId($request_answer['consilium_id']);
            foreach ($consilium_specialists as $c_specialist)
            {
              if($c_specialist->getSpecialist()->getUser()->getId() != $this->getUser()->getAccount()->getId())
              {
                Page::noticeAdd('s', 'consilium', $request_answer['consilium_id'], 'consilium_message', false, $c_specialist->getSpecialist()->getUser()->getId());
              }
            }
          }
          else
          {
            $this->result = 'closed';
          }
        }
      }

      if($r_param && is_numeric($r_param))
      {
        $c = Doctrine::getTable('Consilium')->find($r_param);
        if($c)
        {
          $c
            ->setClosed($save_val)
            ->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'))
            ->save();

          $consilium_specialists = Doctrine::getTable("Consilium")->find($r_param);
          foreach ($consilium_specialists->getSpecialists() as $c_specialist)
          {
            if($c_specialist->getUserId() != $this->getUser()->getAccount()->getId())
            {
              Page::noticeAdd('s', 'consilium', $r_param, ($c == 1 ? 'consilium_closed' : 'consilium_resume'), false, $c_specialist->getUserId());
            }
          }
        }
        $this->forward404Unless($c);
        $result_close = true;
      }

      if($result_close || $result_answer)
      {
        $r_param = $r_param ? $r_param : $request_answer['consilium_id'];

        $this->councils = Doctrine_Query::create()
          ->select('c.*, q.*, ca.*, qu.*, cas.*, casu.*, cs.*, csc.*, sp.*, spu.*, qs.*, qsu.*')
          ->from('Consilium c')
          ->innerJoin('c.Question q')
          ->innerJoin('q.User qu')
          ->leftJoin('c.Consilium_answer ca')
          ->leftJoin('ca.Specialist cas')
          ->leftJoin('cas.User casu')
          ->innerJoin('q.Specialists qs')
          ->innerJoin('qs.User qsu')
          ->leftJoin('c.ConsiliumSpecialist cs')
          ->leftJoin('cs.Consilium csc')
          ->leftJoin('csc.Specialists sp')
          ->innerJoin('sp.User spu')
          ->where('cs.specialist_id = ' . $specialist[0] . ' AND c.id = ' . $r_param)
          ->orderBy('ca.created_at ASC')
          ->limit(1)
          ->fetchArray()
        ;
      }
      else
      {
        $this->forward404();
      }
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeConsilium_close(sfWebRequest $request)
  {
    if($request->isMethod('get'))
    {
      echo 'ok';
      return sfView::NONE;
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeNow_dialog_show(sfWebRequest $request)
  {
    $this->complaint_form = new CreateComplaintForm();
    $this->reception_form = new CreateReception_contractForm();

    if($request->getParameter('id') && is_numeric($request->getParameter('id')))
    {
      $question_check = Doctrine::getTable("Question")->find($request->getParameter('id'));
      $this->forward404Unless($question_check);

      $this->analysis_type = Doctrine::getTable("Analysis_type")->findAll();

      $notice = Doctrine_Query::create()
        ->select("n.*")
        ->from("Notice n")
        ->where("n.user_id = " . $this->getUser()->getAccount()->getId() . " AND n.type = 'dialog' AND n.inner_id = " . $request->getParameter('id'))
        ->execute()
      ;

      if(count($notice) > 0)
      {
        foreach ($notice as $n)
        {
          $n->delete();
        }
      }
    }
  }
  public function executeNow_dialog_answer(sfWebRequest $request)
  {
    $this->form = new CreateAnswerForm();
    if($request->isMethod('post'))
    {
      $specialist = $this->getUser()->getAccount()->getSpecialist();
      $request_answer = $request->getParameter('answer');
      $request_complaint = $request->getParameter('complaint');

      if($request->getParameter('close') && is_numeric($request->getParameter('close')))
      {
        $q_id = $request->getParameter('close');
      }
      elseif($request->getParameter('question_id') && is_numeric($request->getParameter('question_id')))
      {
        $q_id = $request->getParameter('question_id');
      }
      elseif($request_answer['question_id'] && is_numeric($request_answer['question_id']))
      {
        $q_id = $request_answer['question_id'];
      }
      elseif($request_complaint['question_id'] && is_numeric($request_complaint['question_id']))
      {
        $q_id = $request_complaint['question_id'];
      }
      elseif($request->getParameter('open') && is_numeric($request->getParameter('open')))
      {
        $q_id = $request->getParameter('open');
      }
      elseif($request->getParameter('email') && is_numeric($request->getParameter('email')))
      {
        $q_id = $request->getParameter('email');
      }
      elseif(($request->getParameter('consilium_specialist') && is_numeric($request->getParameter('consilium_specialist'))) || ($request->getParameter('consilium') && is_numeric($request->getParameter('consilium'))))
      {
        $q_id = $request->getParameter('consilium') ? $request->getParameter('consilium') : $request->getParameter('consilium_specialist');
      }

      if($q_id)
      {
        $question_specialist_id = Doctrine::getTable('QuestionSpecialist')->findOneByQuestionId($q_id)->getSpecialistId();
      }

      if($specialist[0]->getId() && $question_specialist_id == $specialist[0]->getId())
      {

        //      add_answer

        if(!$request->getParameter('redirect_admin') && !$request->getParameter('email') && !$request->getParameter('please_analysis'))
        {
          $request_answer['user_id'] = $this->getUser()->getAccount()->getId();
          $request_answer['question_id'] = $q_id;
          $form = new CreateAnswerForm();
          $form->bind($request_answer);

          if($form->isValid())
          {
            $form->save();
            $this->result = true;
            $this->question_id = $q_id;
          }
            $question = Doctrine::getTable('Question')->findOneBy("id", $q_id);
          $userMessage = Doctrine::getTable('User')->findOneBy("id", $request_answer['user_id']);

            $deviceToken = Doctrine_Query::create()
                ->select("dt.*")
                ->from("DeviceTokens dt")
                ->where("dt.user_id = " . $question["user_id"])
                ->fetchArray();
            if ($deviceToken && !$request->getParameter('open') && !$request->getParameter('close')) {
                $tokens = array();
                for ($i = 0; $i < count($deviceToken); $i++) {
                    array_push($tokens, $deviceToken[$i]["token"]);
                }
                $json = ProjectUtils::pushNotifications($tokens,
                    array(
                        "type" => "message",
                        "id" => 172,
                        "user_id" => $request_answer['user_id'],
                        "chat_id" => $question["id"],
                        "created_at" => $question["created_at"],
                        "message" => $request_answer["body"],
                        "title" => "Новое сообщение",
                        "name" => $userMessage["first_name"] . " " . $userMessage["second_name"] . " " . $userMessage["middle_name"],
                        "images" => $request_answer["attachment"],
                        "isSpecialist" => true
                    )
                );
            }
        }

        //      open_dialog

        if($request->getParameter('open'))
        {
          $question = Doctrine::getTable('Question')->find($q_id);
          $question
            ->setClosedBy(NULL)
            ->setClosingDate(NULL)
            ->save()
          ;

          $this->question_id = $q_id;
          $this->result = true;

          Page::noticeAdd('u', 'dialog', $q_id, 'resume');
        }

        //      close_dialog

        if($request->getParameter('close'))
        {
          $question = Doctrine::getTable('Question')->findOneById($q_id);
          $question
            ->setClosedBy($this->getUser()->getAccount()->getId())
            ->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'))
            ->save()
          ;

          $this->question_id = $q_id;
          $this->result = true;

          Page::noticeAdd('u', 'dialog', $q_id, 'closed');
        }

//        add_consilium_specialist_info

        if($request->getParameter('consilium_specialist'))
        {
          $specialty = Doctrine_Query::create()
            ->select('sp.*, s.*, u.*')
            ->from('Specialty sp')
            ->leftJoin('sp.Specialist s')
            ->innerJoin('s.User u')
            ->where('s.id != ' . $specialist[0] . ' AND s.id != 51')
            ->fetchArray()
          ;

          $add_info_obj = array();

          foreach ($specialty as $specialty_key => $specialty_item)
          {
            $add_info_obj[$specialty_key] = array('id' => $specialty_item['id'], 'title' => $specialty_item['title']);
            foreach ($specialty_item['Specialist'] as $specialist_key => $specialist)
            {
              array_push($add_info_obj[$specialty_key], array('id' => $specialist['id'], 'about' => $specialist['about'], 'name' => $specialist['User']['first_name'] ? $specialist['User']['first_name'] . ' ' .$specialist['User']['middle_name'] . ' ' . $specialist['User']['second_name'] : $specialist['User']['username']));
            }
          }
          echo json_encode($add_info_obj);

          $this->question_id = $q_id;
          return sfView::NONE;
        }

//        add_consilium & add_consilium_specialist

        if($request->getParameter('consilium'))
        {
          $this->question_id = $q_id;
          $check_consilium_question = Doctrine::getTable('Consilium')->findOneByQuestionId($request->getParameter('consilium'));
          if(!$check_consilium_question)
          {
            $consilium_form = new CreateConsiliumForm();
            $consilium_value = array('question_id' => $request->getParameter('consilium'));
            $consilium_form->bind($consilium_value);

            if($consilium_form->isValid())
            {
              $consilium_save = true;
              $consilium_form->save();

              $consilium_specialist_form = new CreateConsilium_specialistForm();
              $consilium_specialist_value = array('consilium_id' => $consilium_form->getObject()->getId(), 'specialist_id' => $specialist[0]->getId());
              $consilium_specialist_form->bind($consilium_specialist_value);

              if($consilium_specialist_form->isValid())
              {
                $consilium_specialist_form->save();
                $add_consilium_id = $consilium_form->getObject()->getId();
              }
            }

            if($consilium_save && $request->getParameter('specialist_id'))
            {
              $notice_str = '';
              foreach ($request->getParameter('specialist_id') as $s_id_key => $specialist_id)
              {
                $consilium_specialist_form = new CreateConsilium_specialistForm();
                $consilium_specialist_value = array('consilium_id' => $consilium_form->getObject()->getId(), 'specialist_id' => $specialist_id);
                $consilium_specialist_form->bind($consilium_specialist_value);
                if($consilium_specialist_form->isValid())
                {
                  $consilium_specialist_form->save();



                  if($s_id_key != 0)
                  {
                    $notice_str .= ' OR ';
                  }
                  $notice_str .= 's.id = ' . $specialist_id;
                }
              }

              if($notice_str != '')
              {
                $user_specialists_id = Doctrine_Query::create()
                  ->select("s.*")
                  ->from("Specialist s")
                  ->where($notice_str)
                  ->fetchArray()
                ;
                foreach ($user_specialists_id as $user_s_id)
                {
                  Page::noticeAdd('s', 'consilium', $consilium_form->getObject()->getId(), 'consilium_specialist_add', false, $user_s_id['user_id']);
                }
              }
            }

            if($add_consilium_id)
            {
              echo $add_consilium_id;
              return sfView::NONE;
            }
          }
        }

//        redirect_admin

        if($request->getParameter('redirect_admin'))
        {
          $question_specialist_delete = Doctrine::getTable("QuestionSpecialist")->findOneByQuestionId($q_id);
          $question_specialist_delete->delete();

          $question_specialist_form = new CreateQuestionSpecialistForm();
          $question_specialist_form_value = array('question_id' => $q_id, 'specialist_id' => 51);

          $question_specialist_form->bind($question_specialist_form_value);
          if($question_specialist_form->isValid())
          {
            $question_specialist_form->save();

            $this->getUser()->setFlash('question_redirect_admin', 'Ваш вопрос перенаправлен администратору.');
            $this->redirect('/doctor-account/');
          }
        }

//        add_complaint

        if($request->getParameter('complaint'))
        {
          $complaint_form = new CreateComplaintForm();
          $request_complaint['question_id'] = $q_id;
          $request_complaint['specialist_id'] = $specialist[0]->getId();
          $complaint_form->bind($request_complaint);

          if($complaint_form->isValid())
          {
            $complaint_form->save();

            $question_closed = Doctrine::getTable('Question')->findOneById($q_id);
            $question_closed
              ->setClosedBy(1)
              ->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'))
              ->save()
            ;

            $this->getUser()->setFlash('question_complaint', 'Ваша жалоба отправлена администратору. Беседа закрыта.');

            Page::noticeAdd('u', 'dialog', $q_id, 'closed');

            $this->redirect('/doctor-account/');
          }
        }

//        email_reminder

        if($request->getParameter('email'))
        {
          $question_user = Doctrine::getTable("Question")
            ->findOneById($q_id)
            ->getUser()
          ;
          if($question_user->getEmail())
          {
            $request_answer['body'] = 'email_reminder';
            $request_answer['type'] = 'email_reminder';
            $request_answer['user_id'] = $this->getUser()->getAccount()->getId();
            $request_answer['question_id'] = $q_id;
            $form = new CreateAnswerForm();
            $form->bind($request_answer);
            if($form->isValid())
            {
              $form->save();
              $this->result = true;
              $this->question_id = $q_id;
            }
          }
        }

//        live_reception_info

        if($request->getParameter('live_reception_info'))
        {
          $specialist_work_place = Doctrine::getTable("Specialist_work_place")->findBySpecialistId($specialist[0]->getId());
          if(count($specialist_work_place) > 0)
          {
            $swk_arr = array();
            foreach ($specialist_work_place as $swk_key => $swk)
            {
              $swk_arr[$swk_key] = array('id' => $swk['id'], 'title' => $swk['title']);
            }
            echo json_encode($swk_arr);
          }
          return sfView::NONE;
        }

//        add_live_reception

        if($request->getParameter('live_reception'))
        {
          if($request->getParameter('work_place') && $request->getParameter('inp_0'))
          {
            $request_work_place = $request->getParameter('work_place');

            $reception_form = new CreateReception_contractForm();
            $request_reception = $request->getParameter('reception_contract');
            $request_reception['user_id'] = Doctrine::getTable("Question")->find($q_id)->getUser()->getId();
            $request_reception['specialist_id'] = $specialist[0]->getId();
            $request_reception['question_id'] = $q_id;

            $request_reception['price'] = 0;
            if($request->getParameter('price_admission') == 'money')
            {
              $request_reception['price'] = 1;
              if($request->getParameter('price_admission_money_number') != '')
              {
                $request_reception['price'] = $request->getParameter('price_admission_money_number');
              }
            }

            $reception_form->bind($request_reception);

            if($reception_form->isValid())
            {
              $reception_form->save();

//              add_live_reception__add_location

              $specialist_work_place = Doctrine_Query::create()
                ->select("qwp.*")
                ->from("Specialist_work_place qwp")
                ->where("qwp.specialist_id = " . $specialist[0]->getId())
                ->fetchArray()
              ;
              foreach($specialist_work_place as $swp)
              {
                if(in_array($swp['id'], $request_work_place))
                {
                  $receive_location_form_value = array('reception_id' => $reception_form->getObject()->getId(), 'work_place_id' => $swp['id']);
                  $receive_location_form = new CreateReceiveLocationForm();
                  $receive_location_form->bind($receive_location_form_value);

                  if($receive_location_form->isvalid())
                  {
                    $receive_location_form->save();
                  }
                }
              }

//              add_live_reception__add_datetime

              for($i = 0; $i < 100; $i ++)
              {
                if($request->getParameter('inp_' . $i) && $request->getParameter('hh_' . $i) && $request->getParameter('mm_' . $i))
                {
                  $year = mb_substr($request->getParameter('inp_' . $i), -4);
                  $month = mb_substr($request->getParameter('inp_' . $i), 3, 2);
                  $day = mb_substr($request->getParameter('inp_' . $i), 0, 2);
                  $hour = str_pad($request->getParameter('hh_' . $i), 2, "0", STR_PAD_LEFT);
                  $minute = str_pad($request->getParameter('mm_' . $i), 2, "0", STR_PAD_LEFT);

                  $receive_datetime_form_value = array('reception_id' => $reception_form->getObject()->getId(), 'datetime' => date($year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':00'));
                  $receive_datetime_form = new CreateReceive_datetimeForm();
                  $receive_datetime_form->bind($receive_datetime_form_value);

                  if($receive_datetime_form->isValid())
                  {
                    $receive_datetime_form->save();
                  }
                }
                else
                {
                  break;
                }
              }

              $this->result = true;
              $this->question_id = $q_id;

              Page::noticeAdd('u', 'dialog', $q_id, 'specialist_reception');
            }
          }
        }

//        please_analysis

        if($request->getParameter('please_analysis') == 1 && !$request->getParameter('redirect_admin') && !$request->getParameter('email'))
        {
          $analysis_type = Doctrine::getTable("Analysis_type")->findAll();
          $analysis_valid_arr = array();
          $result_arr = array();

          foreach ($analysis_type as $a_type_key => $a_type)
          {
            for($i = 0; $i < 30; $i ++ )
            {
              $request_inp = $request->getParameter('inp_' . $i);
              if(is_numeric($request_inp) && $request_inp == $a_type->getId())
              {
                $result_arr[] = $request_inp . ':' . $a_type->getTitle();
              }
            }
          }

          if(count($result_arr) > 0)
          {
            $request_answer['body'] = json_encode(array_unique($result_arr));
            $request_answer['type'] = 'please_analysis';
            $request_answer['user_id'] = $this->getUser()->getAccount()->getId();
            $request_answer['question_id'] = $q_id;
            $analysis_answer_form = new CreateAnswerForm();
            $analysis_answer_form->bind($request_answer);
            if($analysis_answer_form->isValid())
            {
              $analysis_answer_form->save();
              $this->result = true;
              $this->question_id = $q_id;
            }
          }
        }
      }
      if(!$this->result)
      {
        $this->forward404();
      }
    }
  }
  public function executeSheet_history(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') && is_numeric($request->getParameter('sheet_history_q_id')));

    $specialist = $this->getUser()->getAccount()->getSpecialist();

    $check_str = '';
    if($request->getParameter('in_consilium') != 1)
    {
      $check_str = " AND qs.specialist_id = " . $specialist[0]->getId();
    }

    $this->question_sheet_history = Doctrine_Query::create()
      ->select("q.*, qsh.*, shf.*, qs.*")
      ->from("Question q")
      ->innerJoin("q.QuestionSheetHistory qsh")
      ->leftJoin("qsh.SheetHistoryField shf")
      ->leftJoin("q.QuestionSpecialist qs")
      ->where("q.id = " . $request->getParameter('sheet_history_q_id') . $check_str)
      ->fetchArray()
    ;

    $this->forward404Unless(count($this->question_sheet_history) > 0);
  }
  public function executePatient_card(sfWebRequest $request)
  {
    if(is_numeric($request->getParameter('id')))
    {
      $this->patient_card = Doctrine_Query::create()
        ->select("q.*, s.*, sp.*, s.id AS specialist_id, s.user_id AS specialist_user_id, CONCAT_WS(' ', u.first_name, u.middle_name, u.second_name) AS specialist_name, u.photo AS specialist_photo, s.about AS specialist_about, last_answer")
        ->from("Question q")
        ->innerJoin("q.Specialists s")
        ->innerJoin("s.Specialty sp")
        ->innerJoin("s.User u")
        ->where("q.user_id = " . $request->getParameter('id') . " AND q.closed_by != ''")
        ->addSelect(" (SELECT CONCAT_WS(':division:', a.body, a.user_id) FROM answer a WHERE a.question_id = q.id LIMIT 1 ORDER BY a.id DESC) AS last_answer")
        ->orderBy("q.closing_date DESC")
        ->fetchArray()
      ;
    }
  }
  public function executePatient_card_show(sfWebRequest $request)
  {

  }
  public function executeNow_dialog_edit(sfWebRequest $request)
  {
    $type = $request->getParameter('type');
    $id = $request->getParameter('id');
    $body = $request->getParameter('body');

    $user = $this->getUser();

    if($user->isAuthenticated() && $type == 'answer')
    {
      $specialist = $user->getAccount()->getSpecialist();
      if(count($specialist) > 0)
      {
        $answer = Doctrine::getTable("Answer")->find($id);
        if($answer)
        {
          if($answer->getUserId() == $user->getAccount()->getId() && (is_null($answer->getType()) || $answer->getType() == ''))
          {
            if($answer->getBody() != $body && $body != '')
            {
              $answer->setBody(strip_tags($body));
              $answer->save();
              echo 'ok';
            }
          }
        }
      }
    }
    return sfView::NONE;
  }
}
