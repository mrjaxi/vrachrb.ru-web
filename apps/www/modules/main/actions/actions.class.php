<?php

class mainActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
//    https://oauth.vk.com/authorize?client_id=5396457&display=page&redirect_uri=http://vrachrb.ru.atmadev.ru&scope=groups,offline&response_type=code&v=5.50
//    https://oauth.vk.com/access_token?client_id=5396457&client_secret=dha0afTSVkQd2GycgfZu&redirect_uri=http://vrachrb.ru.atmadev.ru&code=6a9d8e7704cc6a64cd

//    https://oauth.vk.com/authorize?client_id=5367670&display=page&redirect_uri=http://vrachrb.ru&scope=groups,offline&response_type=code&v=5.50
//    https://oauth.vk.com/access_token?client_id=5367670&client_secret=jryleKXkh3BNYQBqfhpH&redirect_uri=http://vrachrb.ru&code=d2a6f99181110e6da4

    if($this->getUser()->hasAttribute('token'))
    {
      $this->setTemplate('index', 'frame');
    }
  }
  public function executePhoto(sfWebRequest $request)
  {
    if($request->isMethod('get') && is_numeric($request->getParameter('dataId')) && is_numeric($request->getParameter('dataValue')))
    {
      $error = true;
      $valid_arr = array('Certificate', 'Analysis');

      $this->data_table = $request->getParameter('dataTable');
      $data_value = $request->getParameter('dataValue');
      $this->data_id = $request->getParameter('dataId');

      if(in_array($this->data_table, $valid_arr))
      {
        $t = Doctrine_Query::create()
          ->select('el.*')
          ->from($this->data_table . ' el')
        ;
        if($this->data_table == 'Certificate')
        {
          $t->where('el.specialist_id = ' . $data_value);
          $error = false;
        }
        elseif($this->data_table == 'Analysis')
        {
          if($this->getUser()->isAuthenticated())
          {
            $t->where('el.answer_id = ' . $data_value);
            $specialist = $this->getUser()->getAccount()->getSpecialist();
            if(!$specialist)
            {
              $t->andWhere("el.user_id = " . $this->getUser()->getAccount()->getId());
            }
            $error = false;
          }
        }
        $this->table_collection = $t->fetchArray();
      }
    }
    $this->forward404Unless(!$error);
  }
  public function executeUploader(sfWebRequest $request)
  {
    echo Page::uploader($request->getFiles('file'), $request->getParameter('key'));
    return sfView::NONE;
  }
  public function executeComment(sfWebRequest $request)
  {
    $this->form_comment = new AddCommentForm();

    if ($request->isMethod('post'))
    {
      $sp = $request->getParameter('show_prev');
      if($sp && is_numeric($sp))
      {
        $this->show_prev = $sp;
      }
      $request_comment = $request->getParameter('comment');
      if($this->getUser()->isAuthenticated())
      {
        $request_comment['user_id'] = $this->getUser()->getAccount()->getId();
      }
      $comment_type_arr = explode(':', $request->getParameter('comment_type_id'));
      if(in_array($comment_type_arr[0], array('Prompt', 'Article')))
      {
        $this->c_type = $comment_type_arr[0];
        $this->c_type_id = $comment_type_arr[1];

        $request_comment[strtolower($comment_type_arr[0]) . '_id'] =  $comment_type_arr[1];

        if(!$sp && $this->getUser()->isAuthenticated())
        {
          $this->form_comment->bind($request_comment);
          if ($this->form_comment->isValid())
          {
            $this->form_comment->save();
            $this->save_true = true;
          }
        }
      }
    }
  }
  public function executeComment_restart(sfWebRequest $request)
  {
    if($request->isMethod('post') && $request->getParameter('restart'))
    {
      $request_restart = $request->getParameter('restart');
      $this->type = $request_restart['type'];
      $this->id = $request_restart['id'];
    }
  }
  public function executeComment_check(sfWebRequest $request)
  {

  }
  public function executeNotice_update(sfWebRequest $request)
  {
    if($request->isMethod('post') && $this->getUser()->isAuthenticated())
    {
      if($request->getParameter('notice_delete') && is_numeric($request->getParameter('notice_delete')))
      {
        $notice_delete_id = $request->getParameter('notice_delete');

        $notice_delete = Doctrine_Query::create()
          ->select("n.*")
          ->from("Notice n")
          ->where("n.user_id = " . $this->getUser()->getAccount()->getId())
          ->andWhere("n.id = " . $notice_delete_id)
          ->execute()
        ;

        if($notice_delete)
        {
          $notice_delete->delete();
          echo 'ok';
          return sfView::NONE;
        }
      }
      else
      {
        $this->notice = $request->getParameter('notice');
        $location_valid_arr = array('dialog_show', 'dialog_index', 'consilium_show', 'consilium_index', 'review', 'patient_card');

        $specialist_check = $this->getUser()->getAccount()->getSpecialist();
        $this->notice['profile'] = $specialist_check ? 's' : 'u';
        if($this->notice && in_array($this->notice['location'], $location_valid_arr))
        {
          $notice_exp = explode('_', $this->notice['location']);

          $n = Doctrine_Query::create()
            ->select("n.*")
            ->from("Notice n")
            ->where("n.user_id = " . $this->getUser()->getAccount()->getId())
          ;

          if($notice_exp[1] == 'show' && $this->notice['type'] == $notice_exp[0] && $this->notice['show_id'] == $this->notice['inner_id'])
          {
            $n->andWhere("n.type = '" . $notice_exp[0] . "' AND n.inner_id = " . $this->notice['show_id']);
            $n_true = true;
          }
          elseif($this->notice['location'] == 'review')
          {
            $n->andWhere("n.type = 'review'");
            $n_true = true;
          }

          $notice_all = $n->execute();

          if($n_true)
          {
            foreach ($notice_all as $notice_a)
            {
              $notice_a->delete();
            }
          }
        }
        else
        {
          $this->forward404();
        }
      }
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeHistory_test(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated())
    {
      $this->profile = 'u';
      if($this->getUser()->getAccount()->getSpecialist())
      {
        $this->profile = 's';
      }
      elseif($request->getParameter('user_id') != $this->getUser()->getAccount()->getId())
      {
        $this->forward404();
      }
      $analysis = Doctrine_Query::create()
        ->select("a.*")
        ->from("Analysis a")
        ->where("a.user_id = " . $request->getParameter('user_id'))
        ->fetchArray()
      ;
    }
    $this->forward404Unless(count($analysis) > 0);
  }
  public function executeHistory_test_show(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated() && is_numeric($request->getParameter('id')))
    {
      $a = Doctrine_Query::create()
        ->select("a.*")
        ->from("Analysis a")
        ->where("a.id = " . $request->getParameter('id'))
      ;
      $this->profile = 's';
      if(!$this->getUser()->getAccount()->getSpecialist())
      {
        $a->andWhere("a.user_id = " . $this->getUser()->getAccount()->getId());
        $this->profile = 'u';
      }
      $analysis = $a->fetchArray();
    }
    $this->forward404Unless(count($analysis) > 0);
  }
  public function executeMessage_error_add(sfWebRequest $request)
  {
    $request_message_error = $request->getParameter('message_error');
    if($request_message_error && $request->isMethod('post'))
    {
      $message_error_form = new CreateMessage_errorForm();
      $request_message_error['user_id'] = NULL;
      $user_name = 'Не авторизован';
      if($this->getUser()->isAuthenticated())
      {
        $user = $this->getUser()->getAccount();
        $request_message_error['user_id'] = $user->getId();
        $user_email = $user->getEmail();
        $user_name = $user->getFirstName() . ($user->getMiddleName() ? ' ' . $user->getMiddleName() : '') . ' ' . $user->getSecondName();
        $advanced_info = '';
      }
      else
      {
        $user_email = 'Эл.почта: ' . strip_tags($request->getParameter('message_error_email'));
        $advanced_info = 'Пользователь: ' . $user_name . ', ' . $user_email . ';';
        $request_message_error['body'] = $advanced_info . $request_message_error['body'];
      }
      $message_error_form->bind($request_message_error);

      if($message_error_form->isValid())
      {
        $message_error_form->save();

        if($request_message_error['body'] != 'testRus55q177')
        {
          $method = 'http://bitrix24.atmadev.ru/do_not_delete/support.php';
          $param = array(
            'code' => 'vrb',
            'message' => $advanced_info . $request_message_error['body']
          );
          ProjectUlils::post($method, $param);

          if(csSettings::get('admin_email'))
          {
            $message = Swift_Message::newInstance()
              ->setFrom('noreply@' . $request->getHost())
              ->setContentType('text/html; charset=UTF-8')
              ->setTo(csSettings::get('admin_email'))
              ->setSubject('Сервис Врач РБ - Новое сообщение об ошибке')
              ->setBody('<b>Сообщение:</b> ' . ($user_name != 'Не авторизован' ? $request_message_error['body'] : str_replace($advanced_info, '', $request_message_error['body'])) . '<br><b>Пользователь:</b> ' . ($user_name != 'Не авторизован' ? $user_name : str_replace('Пользователь: ', '', $advanced_info)));
            $this->getMailer()->send($message);
          }
        }
      }
    }
    echo 'ok';
    return sfView::NONE;
  }
  public function executeYam(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $request_post = $_POST;
    $str_check = $request_post['notification_type'] . '&' . $request_post['operation_id'] . '&' . $request_post['amount'] . '&' . $request_post['currency'] . '&' . $request_post['datetime'] . '&' . $request_post['sender'] . '&' . $request_post['codepro'] . '&KWVSYalYwdIKw1XN8Mt3h6l5&' . $request_post['label'];

    if($request_post['sha1_hash'] == sha1($str_check))
    {
      $sponsor_new = new Donate_sponsors();

      $f_name = strip_tags($request_post['firstname']);
      $s_name = strip_tags($request_post['lastname']);
      $m_name = strip_tags($request_post['fathersname']);

      if(!$request_post['firstname'])
      {
        $f_name = 'Неизвестно';
        $s_name = 'Неизвестно';
        $m_name = 'Неизвестно';

        $sponsor_new->setAnonymous(1);
      }
      $sponsor_new->setFirstName($f_name);
      $sponsor_new->setSecondName($s_name);
      $sponsor_new->setMiddleName($m_name);
      $sponsor_new->setAmount(strip_tags($request_post['amount']));
      $sponsor_new->setSender(strip_tags($request_post['sender']));
      $sponsor_new->setJson(json_encode($request_post));
      $sponsor_new->save();
    }
    return sfView::NONE;
  }
  public function executeLive_band_update(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
  }
  public function executeError404(sfWebRequest $request)
  {
//    $this->redirect($request->getPathInfo(), 301);
  }
}