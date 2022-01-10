<?php
class articleActions extends sfActions
{
  private function getMonths(sfWebRequest $request)
  {

  }
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeShow(sfWebRequest $request)
  {
    $article_title_url = strip_tags($request->getParameter('title_url'));
    if($article_title_url != '')
    {
      $this->article = Doctrine::getTable('Article')->findOneByTitleUrl($article_title_url);
      $this->forward404Unless($this->article);

      $this->specialist = $this->article->getSpecialist();
      $this->user = $this->article->getSpecialist()->getUser();
    }
    $this->forward404Unless($this->article);
  }
  public function executeArticle_page(sfWebRequest $request)
  {
    $this->year = $request->getParameter('id');
  }
}
