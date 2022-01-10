<?php
class pageComponents extends sfComponents
{
  public function executeMain(sfWebRequest $request)
  {
    $this->pages = Doctrine::getTable('Page')->createQuery('a')->where('is_activated = 1')->andWhere("folder != 'about'")->andWhere("id != 9")->limit(9)->execute();
  }
  public function executeSponsor_list(sfWebRequest $request)
  {
    $this->donate_sponsors = Doctrine_Query::create()
      ->select("ds.*")
      ->from("Donate_sponsors ds")
      ->where("ds.anonymous = 0")
      ->orderBy("ds.created_at ASC")
      ->fetchArray()
    ;
  }
}
