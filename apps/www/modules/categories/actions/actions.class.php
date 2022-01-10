<?php
class categoriesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeFilter(sfWebRequest $request)
  {
    $request->getParameter('sc') ? $this->sp_param = $request->getParameter('sc') : $this->forward404();
    $this->sp_limit = $request->getParameter('limit');
  }
  public function executeError404(sfWebRequest $request)
  {
    $this->forward404();
  }
  public function executeTransition_filter(sfWebRequest $request)
  {
    if($request->getParameter('id') && is_numeric($request->getParameter('id')))
    {
      $this->forward404Unless(Doctrine::getTable('Specialty')->find($request->getParameter('id')));
      $this->setTemplate('filter', 'categories');
      $this->sp_param = $request->getParameter('id');
    }
  }
  public function executeMore(sfWebRequest $request)
  {
    if($request->isMethod('post'))
    {
      echo 'ok';
    }
    return sfView::NONE;
  }
}
