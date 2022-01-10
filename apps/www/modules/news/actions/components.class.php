<?php
class newsComponents extends sfComponents
{
  public function executeNews(sfWebRequest $request)
  {
    $n = Doctrine_Query::create()
      ->select('n.*')
      ->from('News n')
      ->orderBy('created_at DESC')
    ;
    $this->newss_years = $n->execute();
    if($this->year && is_numeric($this->year) && $this->year != '')
    {
      $n->where("n.created_at LIKE '" . $this->year . "%'");
    }
    if($this->general == 'y')
    {
      $n->limit(1);
    }
    $this->newss = $n->execute();
  }
}