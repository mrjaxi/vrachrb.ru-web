<?php
class partnerActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->partner = Doctrine_Query::create()
      ->select("pr.*")
      ->from("Partner pr")
      ->orderBy("order_field DESC")
      ->execute()
    ;
  }
}
