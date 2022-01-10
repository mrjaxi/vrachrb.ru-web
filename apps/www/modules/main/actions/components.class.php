<?php
class mainComponents extends sfComponents
{
  public function executeMenu(sfWebRequest $request)
  {
    $menu = array(
      '@question_answer_index' => array('title' => 'Вопросы и ответы', 'disabled' => false),
      '@specialist_index' => array('title' => 'Специалисты', 'disabled' => false),
      '@tip_index' => array('title' => 'Советы', 'disabled' => false),
      '@categories_index' => array('title' => 'Рубрикатор', 'disabled' => false),
      '@article_index' => array('title' => 'Статьи', 'disabled' => false),
      '@news_index' => array('title' => 'Новости', 'disabled' => false),
      '@partner_index' => array('title' => 'Партнёры', 'disabled' => false),
      '@documentation_index' => array('title' => 'Документация', 'disabled' => false),
      '@help' => array('title' => 'Как пользоваться?', 'disabled' => false),
      '@about' => array('title' => 'О проекте', 'disabled' => false)
    );
    $this->items = $menu;
  }
  public function executeStat(sfWebRequest $request)
  {
    $specialists = 0;
    $specialists_q = Doctrine_Query::create()
      ->select("COUNT(s.id) AS s_count")
      ->from('Specialist s')
    ;

    if($this->getUser()->getAttribute('lpu'))
    {
      $specialists_q->innerJoin('s.LpuSpecialist ls');
      $specialists_q->where('ls.lpu_id = ' . $this->getUser()->getAttribute('lpu'));
    }
    $specialists = $specialists_q->fetchArray();
    $this->specialist_count = $specialists[0]['s_count'];

    $users = Doctrine::getTable('User')->findByIsActive(1)->count('*') + csSettings::get('add_users');
    $this->user_count = $users - $this->specialist_count;
    $question_count_q = Doctrine_Query::create()
      ->select('COUNT(q.id) AS q_count')
      ->from('Question q')
      ->where('q.approved = 1')
    ;
    
    if($this->getUser()->getAttribute('lpu'))
    {
      $question_count_q->innerJoin('q.QuestionSpecialist qs');
      $question_count_q->innerJoin('qs.Specialist s');
      $question_count_q->innerJoin('s.LpuSpecialist ls');
      $question_count_q->andWhere('ls.lpu_id = ' . $this->getUser()->getAttribute('lpu'));
    }
    $question_count = $question_count_q->fetchArray();
    $this->question_count = $question_count[0]['q_count'];
  }
  public function executeSpecialty(sfWebRequest $request)
  {
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = '';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 'sp.id = ' . $lpu_specialist;
      }
    }
    if($this->table == 'Specialist')
    {
      $el = Doctrine_Query::create()
        ->select('el.*,sc.*')
        ->from($this->table . ' el')
        ->innerJoin('el.Specialty sc')
        ->orderBy('sc.title ASC')
      ;
      if($lpu_str)
      {
        $el->where(str_replace('sp.id', 'el.id', $lpu_str));
      }
      $elements = $el->fetchArray();
    }
    else
    {
      if($this->table == 'general')
      {
        $el = Doctrine_Query::create()
          ->select("sc.*,sp.*, q_count, p_count, a_count")
          ->from("Specialty sc")
          ->innerJoin("sc.Specialist sp")
          ->orderBy("sc.title ASC")
          ->addSelect("(SELECT COUNT(*) FROM QuestionSpecialty qs WHERE qs.specialty_id = sc.id LIMIT 1) AS q_count")
          ->addSelect("(SELECT COUNT(*) FROM Prompt p WHERE p.specialty_id = sc.id LIMIT 1) AS p_count")
          ->addSelect("(SELECT COUNT(*) FROM Article a WHERE a.specialist_id = sp.id LIMIT 1) AS a_count")
        ;
        if($lpu_str)
        {
          $el->where($lpu_str);
        }
        $elements = $el->fetchArray();
      }
      elseif($this->table == 'qa')
      {
        $el = Doctrine_Query::create()
          ->select('sp.*,qs.*')
          ->from('Specialty sp')
          ->innerJoin('sp.QuestionSpecialty qs')
        ;
        if($this->getUser()->getAttribute('lpu'))
        {
          $el->leftJoin('sp.Specialist s')->where(str_replace('sp.id', 's.id', $lpu_str));
        }
        $elements = $el->fetchArray();
      }
      else
      {
        if($this->categories == 'y')
        {
          $el = Doctrine_Query::create()
            ->select('sc.*, s.*')
            ->from('Specialty sc')
            ->innerJoin('sc.Specialist s')
            ->orderBy('sc.title ASC')
          ;
          if($this->getUser()->getAttribute('lpu'))
          {
            $el->where(str_replace('sp.id', 's.id', $lpu_str));
          }
          $elements = $el->fetchArray();
        }
        else
        {
          $el = Doctrine_Query::create()
            ->select('el.*,sp.*,sc.*')
            ->from($this->table . ' el')
            ->innerJoin('el.Specialist sp')
            ->innerJoin('sp.Specialty sc')
            ->orderBy('sc.title ASC')
          ;
          if($this->getUser()->getAttribute('lpu'))
          {
            $el->where($lpu_str);
          }
          $elements = $el->fetchArray();
        }
      }
    }
    $el_array = array();
    
    $this->categories_close = array();

    foreach ($elements as $element)
    {
      if($this->table == 'Specialist')
      {
        $el_array[($element['specialty_id'])] = $element['Specialty']['title'];
      }
      elseif($this->table == 'general')
      {
        $el_array[($element['id'])] = $element['title'];
        
        if($element['q_count'] > 0 || $element['a_count'] > 0 || $element['p_count'] > 0)
        {
          $this->categories_close[$element['id']] = 'open';
        }
      }
      elseif($this->table == 'qa')
      {
        $el_array[$element['id']] = $element['title'];
      }
      elseif($this->categories == 'y')
      {
        $el_array[($element['id'])] = $element['title'];
      }
      else
      {
        $el_array[($element['Specialist']['specialty_id'])] = $element['Specialist']['Specialty']['title'];
      }
    }
    
    $this->result = array_unique($el_array);
  }
  public function executeBanner(sfWebRequest $request)
  {
    $this->banners = Doctrine_Query::create()
      ->select('b.*')
      ->from('Banner b')
      ->orderBy('order_field DESC')
      ->execute();
  }
  public function executeComment(sfWebRequest $request)
  {
    $this->form_comment = new AddCommentForm();

    $cc = Doctrine::getTable('Comment');
    if($this->type == 'Prompt')
    {
      $this->comments_count = $cc->findByPromptId($this->id)->count('*');
    }
    elseif($this->type == 'Article')
    {
      $this->comments_count = $cc->findByArticleId($this->id)->count('*');
    }
    $this->comment_limit = 5;

    $c = Doctrine_Query::create()
      ->select('c.*,u.*')
      ->from('Comment c')
      ->innerJoin('c.User u')
      ->where('c.' . $this->type . '_id = ' . $this->id)
      ->orderBy('c.created_at DESC')
      ->limit($this->comment_limit)
    ;

    if($this->sp)
    {
      $this->comments = $c->offset($this->sp)->execute();
    }
    else
    {
      $this->comments = $c->execute();
    }
  }

  public function executeNotice(sfWebRequest $request)
  {
    if($this->profile == 's')
    {
      $this->notice_count = Doctrine::getTable("Notice")->findByUserId($this->getUser()->getAccount()->getId())->count("*");
      if($this->notice_count > 0)
      {
        $this->notice = Doctrine_Query::create()
          ->select("n.*, nu.*, s.*, sq.*, u.*, c.*")
          ->from("Notice n")
          ->innerJoin("n.User nu")
          ->innerJoin("nu.Specialist s")
          ->leftJoin("s.Questions sq")
          ->leftJoin("s.Consiliums c")
          ->innerJoin("sq.User u")
          ->where("n.user_id = " . $this->getUser()->getAccount()->getId())
          ->andWhere("(n.type = 'dialog' AND n.inner_id = sq.id) OR (n.type = 'consilium') OR n.type = 'review'")
//          ->andWhere("(n.type = 'dialog' AND n.inner_id = sq.id) OR (n.type = 'consilium' AND n.inner_id = c.id)")
          ->orderBy("n.created_at DESC")
          ->limit(10)
          ->fetchArray()
        ;
      }
    }
    else
    {
      $this->notice = Doctrine_Query::create()
        ->select("n.*, nu.*, q.*, qs.*, qsu.*")
        ->from("Notice n")
        ->innerJoin("n.User nu")
        ->innerJoin("nu.Question q")
        ->innerJoin("q.Specialists qs")
        ->innerJoin("qs.User qsu")
        ->where("n.user_id = " . $this->getUser()->getAccount()->getId())
        ->andWhere("n.inner_id = q.id")
        ->orderBy("n.created_at DESC")
        ->limit(10)
        ->fetchArray()
      ;
    }
  }
  public function executeNow_analysis(sfWebRequest $request)
  {
    if(!$this->user_id)
    {
      $this->user_id = $this->getUser()->getAccount()->getId();
    }
    $this->analysis = Doctrine_Query::create()
      ->select("a.*, at.*")
      ->from("Analysis a")
      ->innerJoin("a.Analysis_type at")
      ->where("a.user_id = " . $this->user_id)
      ->orderBy("a.created_at DESC")
      ->limit(4)
      ->fetchArray()
    ;
  }
  public function executeHistory_test(sfWebRequest $request)
  {
    $this->analysis_type = Doctrine_Query::create()
      ->select("at.*, a.*")
      ->from("Analysis_type at")
      ->innerJoin("at.Analysis a")
      ->where("a.user_id = " . $request->getParameter('user_id'))
      ->orderBy("a.created_at DESC")
      ->fetchArray()
    ;
  }
  public function executeMessage_error(sfWebRequest $request)
  {
    $this->message_error_form = new CreateMessage_errorForm();
  }
  public function executeAttachment(sfWebRequest $request)
  {

  }
  public function executeMain(sfWebRequest $request)
  {
    $this->partner = Doctrine_Query::create()
      ->select('pr.*')
      ->from('partner pr')
      ->limit(4)
      ->orderBy('order_field DESC')
      ->fetchArray()
    ;
  }
  public function executeLive_band(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();

    $lpu_str = '';
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = ' AND (';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 's.id = ' . $lpu_specialist;
      }
      $lpu_str .= ')';
      $qs_lpu_str = str_replace('s.id', 'qs.specialist_id', $lpu_str);
    }

    $specialty_id_str = strip_tags($request->getPostParameter('specialty_id_str'));
    if($specialty_id_str)
    {
      $sp_id_arr = (substr_count($specialty_id_str, 'and') > 0 ? explode('and', $specialty_id_str) : array($specialty_id_str));

      $prompt_condition_arr = array();
      $question_condition_arr = array();
      foreach ($sp_id_arr as $sp_id_arr_item)
      {
        if(is_numeric($sp_id_arr_item))
        {
          $prompt_condition_arr[] = 'p.specialty_id = ' . $sp_id_arr_item;
          $question_condition_arr[] = 's.specialty_id = ' . $sp_id_arr_item;
        }
      }
      if(count($prompt_condition_arr) > 0 && count($question_condition_arr) > 0)
      {
        $prompt_condition = implode(' OR ', $prompt_condition_arr);
        $question_condition = implode(' OR ', $question_condition_arr);
      }
    }

    $this->live_ribbons = $conn->execute("(SELECT null AS sp_title, null AS a_last_date, NULL AS a_count, NULL AS q_closed_by, p.id AS id, p.specialist_id AS sp_id, NULL AS spy_id, p.title_url AS p_title_url, s.title_url AS s_title_url, p.title AS title, p.description AS p_description_q_body, CONCAT_WS(' ', u.first_name, u.middle_name, u.second_name) AS sp_name, s.about AS sp_about, NULL AS q_gender, NULL AS q_birth_date, p.created_at, p.updated_at, NULL AS q_name, 'p' AS type
  FROM prompt p 
  INNER JOIN specialist s 
  INNER JOIN user u
  WHERE 
    s.id = p.specialist_id AND 
    s.user_id = u.id " . $lpu_str . "
    
    " . ($prompt_condition ? " AND (" . $prompt_condition . ")" : "") . "
    
   ORDER BY updated_at DESC)

  UNION

  (SELECT sp.title AS sp_title, a.created_at AS a_last_date, COUNT(a.id) AS a_count, q.closed_by AS q_closed_by, q.id AS id, qs.specialist_id AS sp_id, sp.id AS spy_id, NULL AS p_title_url, s.title_url AS s_title_url, NULL AS title, q.body AS p_description_q_body, NULL AS sp_name, s.about AS sp_about, u.gender AS q_gender, u.birth_date AS q_birth_date, q.created_at, q.updated_at, CONCAT_WS(' ', su.first_name, su.middle_name, su.second_name) AS q_name, 'q' AS type
    FROM question q 
    LEFT JOIN answer a ON q.id = a.question_id AND (a.type IS NULL OR a.type LIKE '')
    INNER JOIN question_specialist qs 
    INNER JOIN user u 
    INNER JOIN specialist s 
    INNER JOIN user su 
    LEFT JOIN specialty sp ON s.specialty_id = sp.id
  WHERE 
    q.id = qs.question_id AND 
    u.id = q.user_id AND 
    su.id = s.user_id AND 
    qs.specialist_id = s.id AND 
    q.approved = 1 " . $qs_lpu_str . "
    
    " . ($question_condition ? " AND (" . $question_condition . ")" : "") . "
    
  GROUP BY q.id
  ORDER BY updated_at DESC)
  
  ORDER BY updated_at DESC LIMIT 5;");


    $this->live_ribbons_clone = array();

    $lr_str = '';
    $c = 0;
    foreach ($this->live_ribbons as $lr)
    {
      $this->live_ribbons_clone[] = $lr;
      $lr_str .= $lr['updated_at'] . '_' . $lr['type'];
      $c ++;
    }
    $this->sha_str = sha1($lr_str);

    $specialist_online = Doctrine_Query::create()
      ->select("s.*")
      ->from("Specialist_online s")
      ->fetchArray()
    ;

    $specialist_online_arr = array();
    foreach ($specialist_online as $specialist_online_item)
    {
      if(Page::specialistOnline($specialist_online_item['date']))
      {
        $specialist_online_arr[$specialist_online_item['specialist_id']] = '' . $specialist_online_item['specialist_id'];
      }
    }

    $this->specialist_on = implode(', ', $specialist_online_arr);
    
    $update = strip_tags($request->getParameter('update'));
    if($update)
    {
      echo 'update_specialist_start' . implode('and', $specialist_online_arr) . 'update_specialist_end';
      if($this->sha_str == $update)
      {
        echo '-:update_none:-';
        return sfView::NONE;
      }
      else
      {
        echo 'update_key_start' . $this->sha_str . 'update_key_end';
      }
    }
    if($c == 0)
    {
      echo 'Нет вопросов и советов в выбранном кабинете<br>';
    }
  }
  public function executeOne_question_tip(sfWebRequest $request)
  {
    
  }
  public function executeSimilar_post(sfWebRequest $request)
  {
    if(!is_numeric($this->element_id))
    {
      return sfView::NONE;
    }
    $sort_type = ($this->sort_type ? $this->sort_type : 'DESC');
    $el = Doctrine_Query::create()
      ->select("e.*")
      ->from($this->module . " e")
    ;
    if($this->where_sql)
    {
      $el->andWhere($this->where_sql);
    }
    $el->orderBy("e.created_at " . $sort_type);
    $this->elements = $el->fetchArray();
  }
}