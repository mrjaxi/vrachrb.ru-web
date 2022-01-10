<?php
class vkImportTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace = 'vk';
    $this->name = 'import';
    
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'arm'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));
  }


  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);


//    $specialist = Doctrine_Query::create()
//      ->select("s.*")
//      ->from("Specialist s")
//      ->fetchArray()
//    ;
//    $specialist_user_arr = array();
//    foreach ($specialist as $item)
//    {
//      $specialist_user_arr[$item['id']] = $item['user_id'];
//    }

    $question = Doctrine_Query::create()
      ->select("q.*")
      ->from("Question q")
      ->where("q.approved != 1")
//      ->addSelect("(SELECT COUNT(*) FROM question_specialist qs WHERE q.id = qs.question_id) AS sp_count")
//      ->where("q.closed_by IS NULL")
      ->fetchArray()
    ;

    $count = 0;
    foreach ($question as $q)
    {



//      if(substr_count($q['body'], '[club98747038'))
//      {
//        $q_exp = explode('],', $q['body']);


        $q_change = Doctrine::getTable("Question")->find($q['id']);
        $q_change->setApproved(1);
        $q_change->save();

        $count ++;
//      }




//          $question_specialist = Doctrine::getTable("QuestionSpecialist")->findOneByQuestionId($q['id']);
//          if(!$question_specialist)
//          {
//            $question_specialist = new QuestionSpecialist();
//            $question_specialist->setQuestionId($q['id']);
//            $question_specialist->setSpecialistId($search);
//            $question_specialist->save();
//
//            $question_change = Doctrine::getTable("Question")->find($q['id']);
//            $question_change->setClosedBy($question_specialist->getSpecialist()->getUserId());
//            $question_change->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'));
//            $question_change->save();
//          }
//
//          $question_specialty = Doctrine::getTable("QuestionSpecialty")->findOneByQuestionId($q['id']);
//          if(!$question_specialty)
//          {
//            $question_specialty_new = new QuestionSpecialty();
//            $question_specialty_new->setQuestionId($q['id']);
//            $question_specialty_new->setSpecialtyId($question_specialist->getSpecialist()->getSpecialtyId());
//            $question_specialty_new->save();
//          }




    }
    echo $count . "\n";



    $map = array(
      '32514126' => 1,
      '32465817' => 6,
      '32465850' => 18,
      '32465833' => 10,
      '32588816' => 13,
      '32535136' => 14,
      '32517894' => 15,
      '32483610' => 16,
      '32587890' => 17,
      '32466002' => 19,
      '32487351' => 20,
      '32515609' => 22,
      '32516261' => 23,
      '32731691' => 25,
      '32487348' => 26,
      '32702032' => 27,
      '32552047' => 29,
      '32668811' => 30,
      '32559704' => 32,
      '32566102' => 33,
      '32465840' => 34,
      '32587869' => 35,
      '32515770' => 37
    );

    $map_specialist = array(
      '32514126' => '1069_and_1203',
      '32628939' => '1190',
      '32588920' => '1',
      '32731691' => '1076',
      '32465850' => '6_and_1078',
      '32466002' => '7',
      '32587988' => '1',
      '32552047' => '1191',
      '32465833' => '1192_and_3_and_1193',
      '32587869' => '1',
      '32517894' => '1194',
      '32613981' => '1',
      '32465817' => '1040_and_1171',
      '32552070' => '1',
      '32483610' => '1039_and_5_and_1083',
      '32588816' => '1195',
      '32487355' => '1',
      '32566102' => '1086',
      '32539897' => '1196',
      '32487351' => '1070',
      '32516261' => '1170_and_1074',
      '32587768' => '1',
      '32959254' => '1',
      '32587890' => '1082_and_1041_and_1197',
      '32515770' => '1174',
      '32487348' => '1077',
      '32587749' => '1198',
      '32634617' => '1199',
      '32914320' => '1200',
      '32702032' => '1079',
      '32514176' => '4',
      '32465867' => '1',
      '32515609' => '1087_and_1201',
      '32559704' => '1085',
      '32535136' => '1072_and_1202',
      '32587838' => '1',
      '32587801' => '1',
      '33372773' => '1',
      '32668811' => '1081',
      '32465840' => '1204_and_1096'
    );

/*
    $specialist_user_id_arr = array();
    $specialist_user_id = Doctrine_Query::create()
      ->select("s.*")
      ->from("Specialist s")
      ->fetchArray()
    ;

    foreach ($specialist_user_id as $s_user_id)
    {
      $specialist_user_id_arr[$s_user_id['user_id']] = $s_user_id['id'];
    }
*/
//    specialty__user_id


/*
    $specialty = Doctrine::getTable('Specialty')->findAll();
    foreach ($specialty as $sp_key => $sp)
    {
      $specialist_count = Doctrine::getTable("Specialist")->findBySpecialtyId($sp->getId())->count("*");
      $prompt_count = Doctrine::getTable("Prompt")->findBySpecialtyId($sp->getId())->count("*");
      $prompt_count = Doctrine::getTable("QuestionSpecialty")->findBySpecialtyId($sp->getId())->count("*");

      if($specialist_count == 0 && $prompt_count == 0 && $prompt_count == 0)
      {
        $specialty[$sp_key]->delete();
      }
    }
*/

/*
//    cabinet
    $json = json_decode(file_get_contents('https://api.vk.com/method/board.getTopics.json?group_id=98747038'), true);

    foreach($json['response']['topics'] as $topic_key => $topic)
    {
      if(is_array($topic))
      {
        $specialty = Doctrine::getTable('Specialty')->find($map[$topic['tid']]);
        $specialtyTid = Doctrine::getTable('Specialty')->find($topic['tid']);
        if(isset($map[$topic['tid']]))
        {
          if($specialty)
          {
            $specialty->setTitle($topic['title']);
            $specialty->save();
          }
        }
        elseif(!$specialty && !$specialtyTid)
        {
          $new_specialty = new Specialty();
          $new_specialty->setId($topic['tid']);
          $new_specialty->setTitle($topic['title']);
          $new_specialty->save();
        }
      }
      usleep(500);
    }
    */


    /*
    //    question

        $users_questions = array();

        $topic_id = array();

        $specialty = Doctrine::getTable("Specialty")->findAll();
        foreach ($specialty as $sp_key => $sp)
        {
          $topic_id[$sp_key] = $sp->getId();
          foreach ($map as $m_key => $m)
          {
            if($sp->getId() == $m)
            {
            $topic_id[$sp_key] = $m_key;
            }
          }
        }



        $all_question = Doctrine_Query::create()
          ->select("q.*, a.*")
          ->from("Question q")
          ->leftJoin("q.Answer a")
          ->where("q.approved != 1")
          ->fetchArray()
        ;

        $all_answer = array();
        foreach ($all_question as $all_q_key => $all_q)
        {
          foreach ($all_q['Answer'] as $a)
          {
            $all_answer[] = $a;
          }
        }









          foreach ($topic_id as $topic_id_key => $t_id)
          {

            if($topic_id_key != 0)
            {
              sleep(10);
              echo $topic_id_key . '_iter' . "\n";
            }

            echo "\n \n" . 'topic_id__' . $t_id . "\n \n";

            $file_url_count = file_get_contents('http://144.76.222.228/r/https://api.vk.com/method/board.getComments.json?group_id=98747038&extended=1&topic_id=' . $t_id . '&lang=ru');

          if(!$file_url_count)
          {
            echo 'error!-count-sleep!';
            sleep(30);
            $file_url_count = file_get_contents('http://144.76.222.228/r/https://api.vk.com/method/board.getComments.json?group_id=98747038&extended=1&topic_id=' . $t_id . '&lang=ru');
          }

          $json_count = json_decode($file_url_count, true);

          $comment_count = ceil($json_count['response']['comments'][0]  / 100);

          echo 'topic_comments_count__' . $comment_count . "\n";

          sleep(20);

          for($i = 0; $i < $comment_count; $i++)
          {
            if(($i % 10) == 0)
            {
              sleep(30);
              echo "\n" . 'count__' . $i . ' / ' . $comment_count . "\n";
            }

            if($i > 0)
            {
              $offset = '&offset=' . (100 * $i);
            }

            $file_url = file_get_contents('http://144.76.222.228/r/https://api.vk.com/method/board.getComments.json?group_id=98747038&extended=1&topic_id=' . $t_id . '&count=100' . $offset . '&lang=ru');
            if(!$file_url)
            {
              echo 'error!-comment-sleep!';
              sleep(60);
              $file_url = file_get_contents('http://144.76.222.228/r/https://api.vk.com/method/board.getComments.json?group_id=98747038&extended=1&topic_id=' . $t_id . '&count=100' . $offset . '&lang=ru');
            }

            $json = json_decode($file_url, true);

            foreach($json['response']['comments'] as $comment_key => $comment)
            {
              if(is_array($comment))
              {


                foreach ($all_question as $all_q)
                {
                  if($all_q['body'] == $comment['text'])
                  {
                    $q_save = Doctrine::getTable("Question")->find($all_q['id']);
                    $q_save->setCreatedAt(date('Y-m-d' . ' ' . 'H:i:s', $comment['date']));
                    $q_save->save();

                    echo "q_save__ \n";
                  }
                }
                foreach ($all_answer as $all_a)
                {
                  if($all_a['body'] == $comment['text'])
                  {
                    $a_save = Doctrine::getTable("Answer")->find($all_a['id']);
                    $a_save->setCreatedAt(date('Y-m-d' . ' ' . 'H:i:s', $comment['date']));
                    $a_save->save();

                    echo "a_save__ \n";
                  }
                }







                /*
                if($comment['from_id'] != 101 && substr($comment['text'], 0, 3) != '[id')
                {


                  $user = Doctrine::getTable('User')->findOneByUsername('http://vk.com/id' . $comment['from_id']);
                  if(!$user)
                  {
                    $user = new User();
                    $user->setUsername('http://vk.com/id' . $comment['from_id']);
                    $user->setFirstName('user_first_name');
                    $user->setSecondName('user_second_name');
                    $user->setGender('ж');
                    $user->save();
                  }
                  if(!$users_questions[$comment['from_id'] . '_' . $t_id])
                  {
                    $question[] = $comment['from_id'];
                    $question_new = new Question();
                    $question_new->setUserId($user->getId());
                    $question_new->setBody($comment['text']);
                    $question_new->setCreatedAt(date('Y-m-d' . ' ' . 'H:i:s', $comment['date']));
                    $question_new->save();

                    $users_questions[$comment['from_id'] . '_' . $t_id] = array('q_id' => $question_new->getId(), 'u_id' => $user->getId());
                  }



                  elseif($users_questions[$comment['from_id'] . '_' . $t_id])
                  {
                    $answer_new = new Answer();
                    $answer_new->setUserId($users_questions[$comment['from_id'] . '_' . $t_id]['u_id']);
                    $answer_new->setQuestionId($users_questions[$comment['from_id'] . '_' . $t_id]['q_id']);
                    $answer_new->setBody($comment['text']);
                    $answer_new->save();
                  }


                }
                else
                {

                  $q_body_str = mb_substr($comment['text'], 3, 9, 'utf-8');

                  if($users_questions[$q_body_str . '_' . $t_id])
                  {
                    $answer_user_id = $users_questions[$q_body_str . '_' . $t_id]['sp_user_id'];
                    if(!$users_questions[$q_body_str . '_' . $t_id]['sp_user_id'])
                    {
                      $map_s_exp = explode('_and_', $map_specialist[$t_id]);
                      $answer_user_id = $map_specialist[$t_id];

                      if(count($map_s_exp) > 1)
                      {
                        $rand_key = array_rand($map_s_exp);
                        $answer_user_id = $map_s_exp[$rand_key];
                      }
                    }



                    $question_specialist_check = Doctrine::getTable("QuestionSpecialist")->findOneByQuestionId($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                    if(!$question_specialist_check)
                    {
                      $question_specialist_new = new QuestionSpecialist();
                      $question_specialist_new->setQuestionId($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                      $question_specialist_new->setSpecialistId($specialist_user_id_arr[$answer_user_id]);
                      $question_specialist_new->save();
                    }

                    $question_specialty_check = Doctrine::getTable("QuestionSpecialty")->findOneByQuestionId($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                    if(!$question_specialty_check)
                    {
                      $question_specialty_new = new QuestionSpecialty();
                      $question_specialty_new->setQuestionId($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                      $question_specialty_new->setSpecialtyId(($map[$t_id] ? $map[$t_id] : $t_id));
                      $question_specialty_new->save();
                    }

                    $answer_new = new Answer();
                    $answer_new->setUserId($answer_user_id);
                    $answer_new->setQuestionId($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                    $answer_new->setBody($comment['text']);
                    $answer_new->setCreatedAt(date('Y-m-d' . ' ' . 'H:i:s', $comment['date']));
                    $answer_new->save();


                    $question_closed = Doctrine::getTable("Question")->find($users_questions[$q_body_str . '_' . $t_id]['q_id']);
                    $question_closed->setClosedBy($answer_user_id);
                    $question_closed->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'));
                    $question_closed->save();

                    $users_questions[$q_body_str . '_' . $t_id]['sp_user_id'] = $answer_user_id;
                  }
                }
                */

    /*
              }
            }
/*
            foreach($json['response']['profiles'] as $profile_key => $profile)
            {
              if(is_array($profile))
              {
                $user = Doctrine::getTable('User')->findOneByUsername('http://vk.com/id' . $profile['uid']);
                if($user)
                {
                  $user->setFirstName($profile['first_name']);
                  $user->setSecondName($profile['last_name']);
                  $user->setGender($profile['sex'] == 1 ? 'ж' : 'м');
                  $user->save();
                }
              }
            }


            sleep(10);
          }
        }

*/











    
  }
}
