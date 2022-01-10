<?php
class doctor_accountComponents extends sfComponents
{
  public function executeMenu(sfWebRequest $request)
  {
    $menu = array(
      '@doctor_account_index' => array('title' => 'Текущие беседы'),
      '@doctor_account_consilium' => array('title' => 'Консилиумы'),
      '@doctor_account_history_appeal' => array('title' => 'История обращений'),
      '@doctor_account_data' => array('title' => 'Личные данные'),
      '@doctor_account_posting' => array('title' => 'Публикации')
    );
    $this->items = $menu;

    $specialist = $this->getUser()->getAccount()->getSpecialist();

    $this->count_arr = Doctrine_Query::create()
      ->select('s.*')
      ->addSelect("(SELECT COUNT(cs.consilium_id) FROM ConsiliumSpecialist cs WHERE cs.specialist_id = s.id) AS consiliums_count")
      ->addSelect("(SELECT COUNT(q.question_id) FROM QuestionSpecialist q WHERE q.specialist_id = s.id) AS questions_count")
      ->addSelect("(SELECT COUNT(r.id) FROM Review r WHERE r.specialist_id = s.id) AS reviews_count")
      ->addSelect("(SELECT COUNT(p.id) FROM Prompt p WHERE p.specialist_id = s.id) AS prompts_count")
      ->addSelect("(SELECT COUNT(a.id) FROM Article a WHERE a.specialist_id = s.id) AS articles_count")
      ->from('Specialist s')
      ->where('s.id = ' . $specialist[0])
      ->fetchArray()
    ;
  }
  public function executeDoctor_posting(sfWebRequest $request)
  {
    $user = $this->getUser()->getAccount()->getSpecialist();

    $table = $this->type == 'tip' ? 'Prompt' : 'Article';

    $this->doctor_posting = Doctrine_Query::create()
      ->select('t.*')
      ->from($table . ' t')
      ->where('t.Specialist_id = ' . $user[0]['id'])
      ->orderBy('created_at DESC')
      ->execute()
    ;
  }
  public function executeHa_patient_filter(sfWebRequest $request)
  {
    $user = $this->getUser()->getAccount()->getSpecialist();

    $this->patients = Doctrine_Query::create()
      ->select('r.*, q.*, u.*')
      ->from('Review r')
      ->innerJoin('r.Question q')
      ->innerJoin('q.User u')
      ->where('r.specialist_id = ' . $user[0])
      ->fetchArray()
    ;
  }
  public function executeReview(sfWebRequest $request)
  {
    $user = $this->getUser()->getAccount()->getSpecialist();

    $valid_arr = array('asc', 'desc');

    $r = Doctrine_Query::create()
      ->select('r.*, s.*, q.*, u.*, c.*')
      ->from('Review r')
      ->innerJoin('r.Specialist s')
      ->innerJoin('r.Question q')
      ->innerJoin('q.User u')
      ->leftJoin('q.Consilium c')
    ;

    if($this->sort && in_array($this->sort, $valid_arr))
    {
      $r->orderBy('r.created_at ' . strtoupper($this->sort));
    }
    else
    {
      $r->orderBy('r.created_at DESC');
    }

    if($this->patient)
    {
      $order_str = '';
      foreach ($this->patient as $patient_key => $item)
      {
        $order_str .= 'q.user_id = ' . $patient_key . ' OR ';
      }
      $r->where('' . substr($order_str, 0, (strlen($order_str) - 4)));
    }
    $r->andWhere('r.specialist_id = ' . $user[0]);
    $this->reviews = $r->fetchArray();
  }
  public function executeConsilium(sfWebRequest $request)
  {
    $consilium_id = $request->getParameter('id') ? $request->getParameter('id') : $this->consilium_id;
    if(is_numeric($consilium_id))
    {
      $specialist = $this->getUser()->getAccount()->getSpecialist();
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
        ->where('cs.specialist_id = ' . $specialist[0] . ' AND c.id = ' . $consilium_id)
        ->orderBy('ca.created_at ASC')
        ->limit(1)
        ->fetchArray()
      ;
    }
  }
  public function executeNow_councils(sfWebRequest $request)
  {
    if($this->consilium_id && is_numeric($this->consilium_id))
    {
      $specialist = $this->getUser()->getAccount()->getSpecialist();
      $this->now_councils = Doctrine_Query::create()
        ->select('cs.*, c.*, q.*, s.*, u.*')
        ->from('ConsiliumSpecialist cs')
        ->innerJoin('cs.Consilium c')
        ->innerJoin('c.Question q')
        ->innerJoin('q.Specialists s')
        ->innerJoin('s.User u')
        ->where('cs.specialist_id = ' . $specialist[0] . ' AND cs.consilium_id != ' . $this->consilium_id)
        ->fetchArray()
      ;
    }
  }
  public function executeNow_dialog(sfWebRequest $request)
  {
    $this->form = new CreateAnswerForm();
  }
  public function executeNow_dialog_list(sfWebRequest $request)
  {
    
  }
}