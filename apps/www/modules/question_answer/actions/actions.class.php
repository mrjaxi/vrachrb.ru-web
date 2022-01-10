<?php
class question_answerActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $error = true;
    if($request->isMethod('post') && is_numeric($request->getParameter('question')) && $this->getUser()->isAuthenticated())
    {
      $specialist = $this->getUser()->getAccount()->getSpecialist();
      if($specialist[0]->getId())
      {
        $qa_id = $request->getParameter('question');
        $this->redirect('/question-answer/' . $qa_id . '/');
        $error = false;
      }
      $this->forward404Unless(!$error);
    }
  }
  public function executeShow(sfWebRequest $request)
  {
    $q_id = strip_tags($request->getParameter('id'));

    $this->forward404Unless($q_id && is_numeric($q_id));

    $this->questions = Doctrine_Query::create()
      ->select('q.*, u.*, qs.*, a.*, ua.*, s.*, su.*')
      ->from('Question q')
      ->innerJoin('q.User u')
      ->leftJoin('q.QuestionSpecialist qs')
      ->leftJoin('q.Answer a')
      ->leftJoin('a.User ua')
      ->leftJoin('ua.Specialist s')
      ->leftJoin('s.User su')
      ->orderBy('q.created_at DESC, a.created_at ASC')
      ->where('q.id = ' . $q_id)
      ->andWhere('q.approved = 1')
      ->fetchArray()
    ;

    $this->forward404Unless(count($this->questions) > 0);

    if($request->isMethod('post') && strip_tags($request->getParameter('quick_open')) == 1)
    {
      $this->quick_open = true;
    }
  }
  public function executeFilter(sfWebRequest $request)
  {
    if($request->isMethod('post'))
    {
      $this->param = $request->getParameter('specialty_array');

      if(!$this->param)
      {
        $this->param = 'all';
//        $this->categories = 'y';
      }
    }
    else
    {
      $this->forward404();
    }
  }
  public function executeQa_page(sfWebRequest $request)
  {
//    $this->page = $request->getParameter('id');
//    $this->show_el = 10;
//    $page_element_offset = ($this->page - 1) * $this->show_el;
//
//    $this->prompts = Doctrine_Query::create()
//      ->select('pt.*,sp.*,us.*,sc.*')
//      ->from('Prompt pt')
//      ->offset($page_element_offset)
//      ->limit($this->show_el)
//      ->innerJoin('pt.Specialist sp')
//      ->innerJoin('sp.User us')
//      ->innerJoin('sp.Specialty sc')
//      ->orderBy('created_at DESC')
//      ->where('pt.specialist_id = sp.id')
//      ->andWhere('sp.user_id = us.id')
//      ->execute();
  }
}
