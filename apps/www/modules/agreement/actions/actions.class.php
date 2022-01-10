<?php
class agreementActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->isAuthenticated());
    if($this->getUser()->isAuthenticated())
    {
      $this->agreement = Doctrine_Query::create()
        ->select("a.*")
        ->from("Agreement a")
        ->execute()
      ;
      if($request->isMethod('post') && $request->getParameter('agreement'))
      {
        foreach ($request->getParameter('agreement') as $ag)
        {
          if(is_numeric($ag))
          {
            $ag_check = Doctrine::getTable("Agreement")->find($ag);
            $agc_check = Doctrine_Query::create()
              ->select("agc.*")
              ->from("AgreementComplete agc")
              ->where("agc.user_id = " . $this->getUser()->getAccount()->getId() . " AND agc.agreement_id = " . $ag)
              ->execute()
            ;
            if($ag_check && count($agc_check) == 0)
            {
              $agreement_complete_new = new AgreementComplete();
              $agreement_complete_new->setUserId($this->getUser()->getAccount()->getId());
              $agreement_complete_new->setAgreementId($ag);
              $agreement_complete_new->save();
            }
          }
        }
      }
      else
      {
        $this->agreement_complete = Doctrine_Query::create()
          ->select("ac.agreement_id")
          ->from("AgreementComplete ac")
          ->where("ac.user_id = " . $this->getUser()->getAccount()->getId())
          ->fetchArray()
        ;
      }
      $add_agreement = Doctrine::getTable("AgreementComplete")->findByUserId($this->getUser()->getAccount()->getId())->count("*");

      if(count($this->agreement) == $add_agreement)
      {
        $prefix = count($this->getUser()->getAccount()->getSpecialist()) > 0 ? 'doctor' : 'personal';
        $this->redirect('/' . $prefix . '-account/');
      }
    }
  }
  public function executeShow(sfWebRequest $request)
  {
    if($request->getParameter('id'))
    {
      $this->agreement = Doctrine::getTable("Agreement")->find($request->getParameter('id'));
      $this->forward404Unless($this->agreement->getBody() != '');
    }
  }
}