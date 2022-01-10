<?php
class specialistActions extends sfActions
{
  private function getMonths(sfWebRequest $request)
  {

  }
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeShow(sfWebRequest $request)
  {
    $specialist_title_url = strip_tags($request->getParameter('title_url'));
    if($specialist_title_url != '')
    {
      $this->specialists = Doctrine::getTable('Specialist')->findOneByTitleUrl($specialist_title_url);
      $this->forward404Unless($this->specialists);

      $this->specialist_work_place = Doctrine_Query::create()
        ->select("swp.*")
        ->from("Specialist_work_place swp")
        ->where("swp.specialist_id = " . $this->specialists->getId() . " AND swp.hidden != 1")
        ->fetchArray()
      ;
      $this->user = $this->specialists->getUser();
      $this->prompts = Doctrine_Query::create()
        ->select('pr.*')
        ->from('Prompt pr')
        ->where('pr.specialist_id = ' . $this->specialists->getId())
        ->execute()
      ;
      $this->reviews = Doctrine_Query::create()
        ->select('rv.*,us.*, q.*')
        ->from('Review rv')
        ->innerJoin('rv.Question q')
        ->innerJoin('q.User us')
        ->where('rv.specialist_id = ' . $this->specialists->getId())
//        ->andWhere('rv.approved = 1')
        ->fetchArray()
      ;

      $this->question_answer = Doctrine_Query::create()
        ->select("q.*, s.*, u.*, sp.*, qu.*, a_count, a_last_date")
        ->from("Question q")
        ->innerJoin("q.Specialists s")
        ->innerJoin("s.User u")
        ->innerJoin("s.Specialty sp")
        ->innerJoin("q.User qu")
        ->where("s.id = " . $this->specialists->getId() . " AND q.approved = 1")
        ->addSelect("(SELECT COUNT(*) FROM Answer a WHERE a.question_id = q.id) AS a_count")
        ->addSelect("(SELECT an.created_at FROM Answer an WHERE an.question_id = q.id ORDER BY an.created_at DESC LIMIT 1) AS a_last_date")
        ->fetchArray()
      ;
      
      

//      echo '<pre>';
//      print_r($this->question_answer);
//      echo '</pre>';
//      die();



    }
    $this->forward404Unless($this->specialists);
  }
  public function executeFilter(sfWebRequest $request)
  {
    $this->param_ajax = $request->getParameter('param_ajax');
    $sort_type_arr = array('asc', 'desc');

    $sort_type = strip_tags($request->getParameter('sort_type'));
    $sort_name = strip_tags($request->getParameter('sort_name'));
    $sp_arr = strip_tags($request->getParameter('sp_arr'));

    if($sort_name && $sort_type && in_array($sort_type, $sort_type_arr))
    {
      $sort_d = $sort_name . ':' . $sort_type . ($sp_arr == 'clear' || $sp_arr == '' ? '' : ':' . $sp_arr);
      $this->getResponse()->setCookie('sort_doc', $sort_d);

      $this->sort_doc = $sort_d;
    }
  }
}
