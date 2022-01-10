<?php

class vk_callbackActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    echo 'ok';

    $json = file_get_contents('php://input');
    $arr = json_decode($json, true);

    if($arr['type'] == 'message_new')
    {
      $id = $arr['object']['message']['id'];
      $date = $arr['object']['message']['date'];
    }
    elseif($arr['type'] == 'board_post_new' || $arr['type'] == 'board_post_edit')
    {
      $id = $arr['object']['id'];
      $date = $arr['object']['date'];
    }
    $write_value = $arr['type'] . '_' . $id . '_' . $date;

    $file = file_get_contents(sfConfig::get('sf_log_dir') . '/vk_callback_last.txt');

    if(substr_count($file, $write_value) == 0)
    {
      $path = sfConfig::get('sf_log_dir') . '/vk_raw/';
      if(!file_exists($path))
      {
        mkdir($path);       
      }
      file_put_contents($path . 'raw_' . time() . '.txt', print_r($arr, true));

      file_put_contents(sfConfig::get('sf_log_dir') . '/vk_callback_last.txt', $write_value . "\n", FILE_APPEND);

      if($arr['group_id'] == sfConfig::get('app_vk_group_id') && $arr['secret'] == sfConfig::get('app_vk_callback_secret'))
      {
        if($arr['type'] == 'message_new')
        {
          $method = 'https://api.vk.com/method/messages.markAsRead';
          $params = array(
            'peer_id' => $arr['object']['message']['user_id'],
            'start_message_id' => $arr['object']['message']['id'],
            'access_token' => sfConfig::get('app_vk_access_token')
          );
          $result = json_decode(ProjectUtils::post($method, $params), true);
          Question::vkQuestionCheck($arr);
        }
        if(($arr['type'] == 'board_post_new' || $arr['type'] == 'board_post_edit') && substr_count($arr['object']['text'], 'Добрый день. Вам ответили на портале http://vrachrb.ru/question-answer') == 0)
        {
          if($arr['object']['from_id'] > 0)
          {
            $specialist_check = Doctrine_Query::create()
              ->select("u.*")
              ->from("User u")
              ->innerJoin("u.Specialist s")
              ->where("u.username LIKE 'http://vk.com/id" . $arr['object']['from_id'] . "'")
              ->limit(1)
              ->fetchArray()
            ;
            /*
            $specialty = Specialty::map($arr['object']['topic_id']);
            if(!$specialty)
            {
              $specialty = $arr['object']['topic_id'];
            }
            */            
            if(count($specialist_check) > 0)
            {
              $specialist_answer_user_id = $specialist_check[0]['id'];
            }
            else
            {
              Question::vkQuestionCheck($arr);
            }
          }
          elseif($arr['object']['from_id'] < 0)
          {
            $specialist_answer_user_id = 1;
          }
          if($specialist_answer_user_id)
          {
            $vk_id = Page::getVkId($arr['object']['text']);
            if($vk_id)
            {
              $question = Doctrine_Query::create()
                ->select("q.*, u.*, a.*")
                ->from("Question q")
                ->innerJoin("q.User u")
                ->leftJoin("q.Answer a")
                ->where("u.username = 'http://vk.com/id" . $vk_id . "'")
                ->andWhere("q.comment_id IS NOT NULL")
                ->andWhere("q.topic_id = " . $arr['object']['topic_id'])
                ->andWhere("q.closed_by IS NULL")
                ->limit(1)
                ->fetchArray()
              ;
              if(count($question) > 0)
              {
                if(count($question[0]['Answer']) > 0)
                {
                  foreach ($question[0]['Answer'] as $answer)
                  {
                    if((substr_count($arr['object']['text'], '[id' . $vk_id) > 0 && substr_count($arr['object']['text'], $answer['body']) > 0) || substr_count($arr['object']['text'], 'Вам необходимо сдать следующие анализы:'))
                    {
                      $repeat = true;
                    }
                  }
                }
                if(is_null($repeat))
                {
                  $text_exp = explode('],', $arr['object']['text']);
                  $body = trim($text_exp[1]);
                  $answer_param = array(
                    'user_id' => $specialist_answer_user_id,
                    'question_id' => $question[0]['id'],
                    'body' => $body,
                    'date' => $arr['object']['date'] - sfConfig::get('app_vk_time_difference'),
                    'mid' => $arr['object']['id']
                  );
                  Answer::AnswerNew($answer_param);
                }
              }
            }
          }
        }
      }
    }
    return sfView::NONE;
  }
}