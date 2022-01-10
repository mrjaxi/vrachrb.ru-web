<?php
class articleComponents extends sfComponents
{
  public function executeArticle(sfWebRequest $request)
  {
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = '';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 'a.specialist_id = ' . $lpu_specialist;
      }
    }

    $a = Doctrine_Query::create()
      ->select('a.*')
      ->from('Article a')
      ->orderBy('created_at DESC');

    $this->articles_years = $a->execute();
    if($this->year && is_numeric($this->year) && $this->year != '')
    {
      $a->where("a.created_at LIKE '" . $this->year . "%'");
      $this->articles = $a->execute();
    }
    else
    {
      if($this->categories_id && is_numeric($this->categories_id))
      {
        $a->innerJoin('a.Specialist s')->innerJoin('s.Specialty sc')->where('sc.id = ' . $this->categories_id);
      }
      if($this->answer)
      {
        $a->limit(5);
      }
      if($this->specialist_id)
      {
        $a->andWhere("a.specialist_id = " . $this->specialist_id);
      }
      if($this->getUser()->getAttribute('lpu'))
      {
        $a->where($lpu_str);
      }
      $this->articles = $a->execute();
    }
  }
}