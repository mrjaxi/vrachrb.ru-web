<?php
class newsActions extends sfActions
{
  private function getMonths(sfWebRequest $request)
  {

  }
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeShow(sfWebRequest $request)
  {
    $news_title = strip_tags($request->getParameter('title_url'));
    if($news_title != '')
    {
      $this->news = Doctrine::getTable('News')->findOneByTitleUrl($news_title);
    }
    $this->forward404Unless($this->news);
  }
  public function executeNews_page(sfWebRequest $request)
  {
    $this->year = $request->getParameter('id');
  }
}
