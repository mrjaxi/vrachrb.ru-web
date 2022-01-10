<?php
class tipActions extends sfActions
{
  private function getMonths(sfWebRequest $request)
  {

  }
  public function executeIndex(sfWebRequest $request)
  {
    
  }
  public function executeShow(sfWebRequest $request)
  {
    $prompt_title_url = strip_tags($request->getParameter('title_url'));
    if($prompt_title_url != '')
    {
      $this->prompt = Doctrine::getTable('Prompt')->findOneByTitleUrl($prompt_title_url);
      $this->forward404Unless($this->prompt);
      $this->specialist = $this->prompt->getSpecialist();
      $this->user = $this->prompt->getSpecialist()->getUser();
    }
    $this->forward404Unless($this->prompt);
  }
  public function executeFilter(sfWebRequest $request)
  {
    $this->specialty_id = $request->getParameter('sc');
  }
  public function executeTip_page(sfWebRequest $request)
  {
    $this->page = $request->getParameter('id');
    $this->show_el = 10;
    $page_element_offset = ($this->page - 1) * $this->show_el;

    $this->prompts = Doctrine_Query::create()
      ->select('pt.*,sp.*,us.*,sc.*')
      ->from('Prompt pt')
      ->offset($page_element_offset)
      ->limit($this->show_el)
      ->innerJoin('pt.Specialist sp')
      ->innerJoin('sp.User us')
      ->innerJoin('sp.Specialty sc')
      ->orderBy('created_at DESC')
      ->where('pt.specialist_id = sp.id')
      ->andWhere('sp.user_id = us.id')
      ->execute();
  }
}
