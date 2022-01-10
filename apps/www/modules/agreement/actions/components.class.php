<?php
class agreementComponents extends sfComponents
{
  public function executeAgreement(sfWebRequest $request)
  {
    $agreement = Doctrine_Query::create()
      ->select("a.*" . ($this->login ? ", ac_count" : ''))
      ->from("agreement a")
    ;
    if($this->login)
    {
      $agreement->addSelect("(SELECT COUNT(*) FROM AgreementComplete ac WHERE ac.agreement_id = a.id AND ac.user_id = " . $this->getUser()->getAccount()->getId() . ") AS ac_count");
    }
    $this->agreement = $agreement->fetchArray();
  }
}