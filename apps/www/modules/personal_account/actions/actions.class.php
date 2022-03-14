<?php
class personal_accountActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    
  }
  public function executeFilter_view(sfWebRequest $request)
  {
    if ($request->isMethod("post") && $request->isXmlHttpRequest())
    {
      $this->filter_pa_list = strip_tags($request->getParameter("filter"));
    }
    else
    {
      $this->forward404Unless(false);
    }
  }
  public function executeNow_dialog_show(sfWebRequest $request)
  {
    $this->forward404Unless($request->getParameter('id') && is_numeric($request->getParameter('id')));

    $question_check = Doctrine::getTable("Question")->find($request->getParameter('id'));

    if($question_check)
    {
      $this->forward404Unless($question_check->getUserId() == $this->getUser()->getAccount()->getId());

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
      $request_notice = $request->getParameter('notice');
      $request_answer = $request->getParameter('answer');

      if($request->getParameter('question_id') && is_numeric($request->getParameter('question_id')))
      {
        $q_id = $request->getParameter('question_id');

        $question_user_id = Doctrine_Query::create()
          ->select("q.*, qu.*, qs.*")
          ->from("Question q")
          ->innerJoin("q.User qu")
          ->innerJoin("q.Specialists qs")
          ->where("q.id = " . $q_id . " AND q.user_id = " . $this->getUser()->getAccount()->getId())
          ->fetchArray()
        ;

        if(count($question_user_id) > 0)
        {
          $check_closed_author = true;
          if($question_user_id[0]['closed_by'] && $question_user_id[0]['closed_by'] != 0)
          {
            if($question_user_id[0]['Specialists'][0]['user_id'] != $question_user_id[0]['closed_by'])
            {
              $check_closed_author = false;
            }
          }

          if($question_user_id[0]['User'] && $question_user_id[0]['User']['id'] == $this->getUser()->getAccount()->getId() && $check_closed_author)
          {

            //      add_answer

            $request_answer['user_id'] = $this->getUser()->getAccount()->getId();
            $request_answer['question_id'] = $q_id;

            $notice_event = 'message';
            if($request->getParameter('user_reception') == 'user_reception')
            {
              $notice_event = 'user_reception';
              $request_answer['body'] = 'user_reception';
              $request_answer['type'] = 'user_reception';
            }
            elseif($request->getParameter('analysis') && is_numeric($request->getParameter('answer_please_id')))
            {
              $notice_event = 'give_analysis';
              $request_answer['body'] = 'give_analysis';
              $request_answer['type'] = 'give_analysis';
            }

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


              $specialist_user_id = $question->getSpecialists()[0]["user_id"];
              $deviceToken = Doctrine_Query::create()
                  ->select("dt.*")
                  ->from("DeviceTokens dt")
                  ->where("dt.user_id = " . $specialist_user_id)
                  ->fetchArray();
              if ($deviceToken) {
                  $tokens = array();
                  for ($i = 0; $i < count($deviceToken); $i++) {
                      array_push($tokens, $deviceToken[$i]["token"]);
                  }
                  $json = ProjectUtils::pushNotifications($tokens,
                      $request_answer["body"],
                      "Новое сообщение",
                      array(
                          "type" => "message",
                          "user_id" => $request_answer['user_id'],
                          "chat_id" => $question["id"],
                          "created_at" => $question["created_at"],
                          "title" => "Новое сообщение",
                          "message" => $request_answer["body"],
                          "image" => $request_answer["attachment"],
                          "first_name" => $userMessage["first_name"],
                          "second_name" => $userMessage["second_name"],
                          "isSpecialist" => false,
                          "speciality" => "",
                      )
                  );
              }

//          reception_info

            if($request->getParameter('reception_info') && is_numeric($request->getParameter('reception_info')))
            {
              $reception_contract = Doctrine_Query::create()
                ->select("rc.*, l.*, rd.*")
                ->from("Reception_contract rc")
                ->innerJoin("rc.Location l")
                ->innerJoin("rc.Receive_datetime rd")
                ->where("rc.id = " . $request->getParameter('reception_info'))
                ->fetchArray()
              ;

              foreach ($reception_contract[0]['Receive_datetime'] as $r_datetime_key => $r_datetime)
              {
                $reception_contract[0]['Receive_datetime'][$r_datetime_key]['datetime'] = Page::rusDate($r_datetime['datetime']) . ', ' . substr($r_datetime['datetime'], 11, 5);
              }

              echo json_encode($reception_contract);
              return sfView::NONE;
            }

//          reception_answer

            if($request->getParameter('reception_contract_id') && is_numeric($request->getParameter('reception_contract_id')))
            {
              $reception_contract = Doctrine::getTable("Reception_contract")->find($request->getParameter('reception_contract_id'));
              if($request->getParameter('reception_answer') == 'y' && $request->getParameter('time_n_date') && is_numeric($request->getParameter('time_n_date')) && $reception_contract->getIsActivated() != 1 && $reception_contract->getIsReject() != 1)
              {
                $receive_datetimes = Doctrine::getTable("Receive_datetime")->findByReceptionId($request->getParameter('reception_contract_id'));
                if($reception_contract && $receive_datetimes)
                {
                  foreach ($receive_datetimes as $receive_datetime)
                  {
                    if($receive_datetime->getId() != $request->getParameter('time_n_date'))
                    {
                      $receive_datetime->delete();
                    }
                  }
                  $reception_contract
                    ->setIsActivated(1)
                    ->save()
                  ;

                  $this->result = true;
                  $this->question_id = $q_id;

                  Page::noticeAdd('s', 'dialog', $q_id, 'reception_yes', $request_notice['_csrf_token']);
                }
              }
              elseif($request->getParameter('reception_answer') == 'n' && $request->getParameter('reception_answer_reject_reason') && $reception_contract->getIsReject() != 1 && $reception_contract->getIsActivated() != 1)
              {
                if($reception_contract)
                {
                  $reception_contract
                    ->setIsReject(1)
                    ->setRejectReason(strip_tags($request->getParameter('reception_answer_reject_reason')))
                    ->save()
                  ;

                  $this->result = true;
                  $this->question_id = $q_id;

                  Page::noticeAdd('s', 'dialog', $q_id, 'reception_no', $request_notice['_csrf_token']);
                }
              }
            }

//          review

            if($request->getParameter('review'))
            {
              $request_review = $request->getParameter('review');
              $request_review['question_id'] = $q_id;
              $request_review['user_id'] = $this->getUser()->getAccount()->getId();
              $request_review['specialist_id'] = $question_user_id[0]['Specialists'][0]['id'];
              $request_review['body'] = strip_tags($request_review['body']);

              $review_form = new CreateReviewForm();
              $review_form->bind($request_review);
              if($review_form->isValid())
              {
                $review_form->save();
                echo 'ok';

                Page::noticeAdd('s', 'review', $review_form->getObject()->getId(), 'review', $request_notice['_csrf_token']);
              }
              return sfView::NONE;
            }

//            give_analysis

            if($form->getObject()->getId() && $request->getParameter('analysis') && is_numeric($request->getParameter('answer_please_id')))
            {
              $answer = Doctrine::getTable("Answer")->find($request->getParameter('answer_please_id'));
              $request_analysis = $request->getParameter('analysis');

              if($answer && $answer->getType() == 'please_analysis')
              {
                $answer_body = json_decode($answer->getBody());
                if(count($answer_body) == count($request_analysis))
                {
                  foreach ($answer_body as $ab_key => $ab)
                  {
                    $ra_exp = explode(':', strip_tags($request_analysis[$ab_key]));
                    foreach ($answer_body as $a_body)
                    {
                      $a_body_exp = explode(':', $a_body);
                      if($ra_exp[0] == $a_body_exp[0])
                      {
                        $analysis_form = new CreateAnalysisForm();
                        $analysis_arr = array(
                          'answer_id' => $form->getObject()->getId(),
                          'user_id' => $this->getUser()->getAccount()->getId(),
                          'analysis_type_id' => $ra_exp[0],
                          'photo' => $ra_exp[2]
                        );
                        $analysis_form->bind($analysis_arr);

                        if($analysis_form->isValid())
                        {
                          $analysis_form->save();
                          $not_equal = true;
                        }
                      }
                    }
                  }

                  if($not_equal)
                  {
                    $answer
                      ->setType('please_analysis_complete')
                      ->save()
                    ;

                    $this->result = true;
                    $this->question_id = $q_id;
                  }
                }
              }
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
  public function executeData(sfWebRequest $request)
  {
    $this->form = new ChangeUserForm($this->getUser()->getAccount());
    $this->change_password_form = new ChangePasswordForm($this->getUser()->getAccount());
  }
  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new CreateFeedbackForm();
    if ($request->isMethod('post'))
    {
      $user = $this->getUser()->getAccount();
      $elem_id = $user->getId();
      $request_user = $request->getParameter('feedback');
      $request_user['user_id'] = $elem_id;
      $fio = $user->getSecondName() . ' ' . $user->getFirstName() . ' ' . $user->getMiddleName();

      if(isset($request_user['body']))
      {
        $request_user['body'] .= ' (веб-сайт)';
      }

      $this->form->bind($request_user);

      if ($this->form->isValid())
      {
        $this->form->save();
        $this->message_true = true;

        if($admin_email = csSettings::get('admin_email'))
        {
          $message_body = '<b>Пользователь:&nbsp;</b>' . $fio . '<b><br>Эл.почта:&nbsp;</b>' . $user->getEmail() . '<br><b>Сообщение:</b> ' . $request_user['body'];
          $message = Swift_Message::newInstance()
          ->setFrom('noreply@' . $request->getHost())
          ->setContentType('text/html; charset=UTF-8')
          ->setTo($admin_email)
          ->setSubject('Сервис Врач РБ - Новое сообщение обратной связи')
          ->setBody($message_body);
          $this->getMailer()->send($message);
        }
      }
    }
  }
  public function executePatient_card(sfWebRequest $request)
  {
    $pc = Doctrine_Query::create()
      ->select("q.*, s.*, sp.*, r.id, s.id AS specialist_id, s.user_id AS specialist_user_id, CONCAT_WS(' ', u.first_name, u.middle_name, u.second_name) AS specialist_name, u.photo AS specialist_photo, s.about AS specialist_about, last_answer")
      ->from("Question q")
      ->innerJoin("q.Specialists s")
      ->innerJoin("s.Specialty sp")
      ->innerJoin("s.User u")
      ->leftJoin("q.Review r")
      ->where("q.user_id = " . $this->getUser()->getAccount()->getId() . " AND q.closed_by != ''")
    ;

    if($request->isMethod('post'))
    {
      if($request->getParameter('patient_card_filter') && $request->getParameter('patient_card_filter') == 'ok' && count($request->getParameter('p_card_sp_filter')) > 0)
      {
        $request_p_card = array_unique($request->getParameter('p_card_sp_filter'));
        $p_card_specialty_condition = '';
        foreach ($request_p_card as $p_card)
        {
          if(is_numeric($p_card))
          {
            $p_card_specialty_condition .= " OR sp.id = " . $p_card;
          }
        }
        $pc->andWhere(substr($p_card_specialty_condition, 4));
      }

      $this->ajax = 'y';
      if($request->getParameter('patient_card_filter') == 'update')
      {
        $this->ajax = 'update';
      }
    }

    $pc
      ->addSelect(" (SELECT CONCAT_WS(':division:', a.body, a.user_id) FROM answer a WHERE a.question_id = q.id LIMIT 1 ORDER BY a.id DESC) AS last_answer")
      ->orderBy("q.closing_date DESC")
    ;

    $this->patient_card = $pc->fetchArray();
  }
}
