<?php
class vkLpTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace = 'vk';
    $this->name = 'lp';
    
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));
  }
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $connect_lp_server = 'https://api.vk.com/method/messages.getLongPollServer?use_ssl=1&need_pts=1&access_token=' . sfConfig::get('app_vk_access_token');
    $lp_server = json_decode(file_get_contents($connect_lp_server), true);
    
    print_R($lp_server);
    
    $r = $lp_server['response'];
    $ts = $r['ts'];
    $pts = $r['pts'];

    while(true)
    {
      $connect = 'https://' . $r['server'] . '?act=a_check&key=' . $r['key'] . '&ts=' . $ts . '&wait=25&mode=8';
      $wait = json_decode(file_get_contents($connect), true);

      if($wait['failed'])
      {
        if($wait['failed'] == 1 || $wait['failed'] == 2 || $wait['failed'] == 3)
        {
          $method = 'https://api.vk.com/method/messages.getLongPollHistory';
          $params = array(
            'ts' => $ts,
            'pts' => $pts,
            'access_token' => sfConfig::get('app_vk_access_token')
          );

          $history = json_decode(ProjectUtils::post($method, $params), true);

          if($history['response'])
          {
            $element_arr = array(
              'Question',
              'Answer'
            );
            $element_result_arr = array();

            foreach ($element_arr as $element)
            {
              $element_max = Doctrine_Query::create()
                ->select("el.*")
                ->from($element . " el")
                ->where("el.comment_id IS NOT NULL")
                ->limit(1)
                ->fetchOne()
              ;
              $element_result_arr[] = $element_max ? $element_max->getCommentId() : 0;
            }

            $max_comment_id = max($element_result_arr);

            foreach ($history['response']['messages'] as $message)
            {
              if($message['mid'] > $max_comment_id && $message['out'] == 0)
              {
                $user_check = Doctrine::getTable("User")->findOneByUsername("http://vk.com/id" . $message['uid']);
                if($user_check)
                {
                  $user_question_open = Doctrine_Query::create()
                    ->select("q.*")
                    ->from("Question q")
                    ->where("q.closed_by IS NULL")
                    ->andWhere("q.user_id = " . $user_check->getId())
                    ->orderBy("q.created_at DESC")
                    ->limit(1)
                    ->fetchArray()
                  ;

                  if(count($user_question_open) > 0)
                  {
                    Answer::AnswerNew(array(
                      'user_id' => $user_check->getId(),
                      'question_id' => $user_question_open[0]['id'],
                      'body' => $message['body'],
                      'mid' => $message['mid'],
                      'date' => $message['date']
                    ));
                  }
                  elseif(count($user_question_open) == 0)
                  {
                    Question::QuestionNew(array(
                      'user_id' => $user_check->getId(),
                      'body' => $message['body'],
                      'mid' => $message['mid'],
                      'date' => $message['date']
                    ));
                  }
                }
                else
                {
                  $user = new User();
                  $user->setUsername('http://vk.com/id' . $message['uid']);
                  $user->save();
                  Question::QuestionNew(array(
                    'user_id' => $user->getId(),
                    'body' => $message['body'],
                    'mid' => $message['mid'],
                    'date' => $message['date']
                  ));
                }
              }
            }

            $pts = $history['response']['new_pts'];

            print_r($history['response']['messages']);
            print_r($history['response']['profiles']);
          }


          $lp_server = json_decode(file_get_contents($connect_lp_server), true);
          $r = $lp_server['response'];
          $ts = $r['ts'];
          $pts = $r['pts'];

          $connect = 'https://' . $r['server'] . '?act=a_check&key=' . $r['key'] . '&ts=' . $ts . '&wait=25&mode=8';
          echo "\n ____reconnect_server____ \n";
        }
        else
        {
          echo "\n ____reconnect_simple____ \n";
        }
        $wait = json_decode(file_get_contents($connect), true);
      }

      foreach($wait['updates'] as $event)
      {
        if($event[0] == 4 && $event[2] != 3)
        {
          $read = file_get_contents('https://api.vk.com/method/messages.markAsRead?peer_id=' . $event[3] . '&start_message_id=' . $event[1] . '&access_token=' . sfConfig::get('app_vk_access_token'));

          $user = Doctrine::getTable("User")->findOneByUsername('http://vk.com/id' . $event[3]);

          if(!$user)
          {
            $method = 'https://api.vk.com/method/users.get';

            $params = array(
              'user_ids' => $event[3],
              'fields' => 'sex, bdate'
            );

            $user_info = json_decode(ProjectUtils::post($method, $params), true);

            $user = new User();
            $user->setUsername('http://vk.com/id' . $event[3]);

            $user_info_response = $user_info['response'][0];

            if($user_info_response)
            {
              $user_info_response['first_name'] ? $user->setFirstName($user_info_response['first_name']) : '';
              $user_info_response['last_name'] ? $user->setSecondName($user_info_response['last_name']) : '';
              $user->setGender(($user_info_response['sex'] == 1 ? 'ж' : 'м'));

              if($user_info_response['bdate'])
              {
                $b_date_exp = explode('.', $user_info_response['bdate']);
                if(count($b_date_exp) == 3)
                {
                  $user->setBirthDate(date($b_date_exp[2] . '-' . $b_date_exp[1] . '-' . $b_date_exp[0]));
                }
              }
            }
            $user->save();
          }

          $user_questions = Doctrine_Query::create()
            ->select("q.*")
            ->from("Question q")
            ->where("q.user_id = " . $user->getId())
            ->andWhere("q.closed_by IS NULL")
            ->andWhere("q.comment_id IS NOT NULL")
            ->orderBy("q.created_at DESC")
            ->fetchArray()
          ;

          $mail_subject = 'Сервис Врач РБ - Нов';

          if(count($user_questions) > 0)
          {
            $answer_check = Doctrine::getTable("Answer")->findOneByCommentId($event[1]);
            if(!$answer_check)
            {
              Answer::AnswerNew(array(
                'user_id' => $user->getId(),
                'question_id' => $user_questions[0]['id'],
                'body' => $event[6],
                'mid' => $event[1],
                'date' => $event[4]
              ));

              $mail_subject .= 'ое сообщение пользователя';
            }
          }
          elseif(count($user_questions) == 0)
          {
            $question_check = Doctrine::getTable("Question")->findOneByCommentId($event[1]);
            if(!$question_check)
            {
              Question::QuestionNew(array(
                'user_id' => $user->getId(),
                'body' => $event[6],
                'mid' => $event[1],
                'date' => $event[4]
              ));

              $mail_subject .= 'ый вопрос пользователя';
            }
          }
          $mail_subject .= $user->getFirstName() . ' ' . $user->getSecondName();
          $mail_body = $event[6];


          if(false)
          {
//            $param_mailer = array(
//              'host' => 'vrachrb.ru.atmadev.ru',
//              'subject' => $mail_subject,
//              'body' => $mail_body
//            );
//
//            Question::vlLpMailer($param_mailer);

//            $message = sfContext::getInstance()->getMailer()->compose(
//              array('noreply@vrachrb.ru.atmadev.ru' => 'Jobeet Bot'),
//              csSettings::get('admin_email'),
//              'test122222222222',
//              'testMessage333333333333333'
//            );
//
//            sfContext::getInstance()->getMailer()->send($message);



//            $message = Swift_Message::newInstance()
//              ->setFrom('noreply@vrachrb.ru.atmadev.ru')
//              ->setContentType('text/html; charset=UTF-8')
//              ->setTo(csSettings::get('admin_email'))
//              ->setSubject($mail_subject)
//              ->setBody($mail_body)
//            ;
//
//            sfContext::getInstance()->getMailer()->send($message);
          }



        }
      }
      print_r($wait);
      $ts = $wait['ts'];





















    }

    /*
    $params = array();
      
    $params['access_token'] = sfConfig::get('app_vk_access_token');
    $params['group_id'] = sfConfig::get('app_vk_group_id');
    
    $params['topic_id'] = 33338821;
    //$params['user_id'] = 125262662;
    $params['text'] = $object['body'];
    //$params['message'] = $object['body'];
    $params['version'] = '5.50';
    
    /*
    if(get_class($object) == 'Question')
    {
      
    }
    elseif(get_class($object) == 'Answer')
    {
      
    }
    */
    
    //ProjectUtils::post($method, $params);
    
  }
}
