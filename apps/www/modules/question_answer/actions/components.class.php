<?php
class question_answerComponents extends sfComponents
{
  public function executeQuestion_index(sfWebRequest $request)
  {
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = ' AND (';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . ' s.id = ' . $lpu_specialist;
      }
      $lpu_str .= ')';
    }

    $q = Doctrine_Query::create()
      ->select("q.*, u.*, qsc.*, s.*, su.*, a_count, a_last_date")
      ->from("Question q")
      ->innerJoin("q.User u")
      ->leftJoin("q.Specialists s")
      ->leftJoin("s.User su")
      ->leftJoin("s.Specialty qsc")
      ->addSelect("(SELECT COUNT(*) FROM Answer a WHERE a.question_id = q.id AND (a.type IS NULL OR a.type LIKE '' OR a.type LIKE '0')) AS a_count")
      ->addSelect("(SELECT an.created_at FROM Answer an WHERE an.question_id = q.id ORDER BY an.created_at DESC LIMIT 1) AS a_last_date")
      ->orderBy("q.created_at DESC")
      ->where("q.approved = 1" . $lpu_str)
    ;

    if($this->specialty_id && is_numeric($this->specialty_id))
    {
      $q->andWhere('s.specialty_id = ' . $this->specialty_id);
    }

    if($this->page)
    {
      $page_element_offset = ($this->page - 1) * $this->show_el;
      $q->offset($page_element_offset)->limit($this->show_el);
    }
    else
    {
      $q->limit(10);
    }

    if($this->specialty_id && is_array($this->specialty_id))
    {
      $filter = '';
      foreach ($this->specialty_id as $key => $item)
      {
        $filter .= $key != 0 ? ' OR ' : '';
        $filter .= 'qsc.id=' . $item;
      }
      $q->andWhere($filter);
    }
    if($this->categories == 'y')
    {
      $q->limit($this->sp_limit == 'no' ? 1000 : 2);
    }

    $this->questions = $q->fetchArray();
  }
}