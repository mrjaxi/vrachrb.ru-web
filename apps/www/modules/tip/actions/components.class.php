<?php
class tipComponents extends sfComponents
{
  public function executeTip(sfWebRequest $request)
  {
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = '';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 'sp.id = ' . $lpu_specialist;
      }
      $lpu_str .= '';
    }

    if($this->page)
    {
      $page = $this->page;
      $this->page = $page;
      $show_el = $this->show_el;
      $this->show_el = $show_el;
      $page_element_offset = ($page - 1) * $show_el;
    }
    else
    {
      $page = 1;
      $this->page = $page;
      $show_el = 2;
      $this->show_el = $show_el;
      $page_element_offset = 0;
      if($this->specialty_id)
      {
        $show_el = 0;
        $this->show_el = $show_el;
      }
      else
      {
        $show_el = 10;
        $this->show_el = $show_el;
      }
    }

    $pr = Doctrine_Query::create()
      ->select('pt.*,sp.*,us.*,sc.*')
      ->from('Prompt pt')
      ->offset($page_element_offset)
      ->limit($show_el)
      ->innerJoin('pt.Specialist sp')
      ->innerJoin('sp.User us')
      ->innerJoin('sp.Specialty sc')
      ->orderBy('created_at DESC')
//      ->where('pt.specialist_id = sp.id')
//      ->andWhere('sp.user_id = us.id')
      ;


    if($this->getUser()->getAttribute('lpu'))
    {
      $pr->where($lpu_str);
    }
    $this->prompts = $pr->execute();

    $this->specialty_id;
  }
}