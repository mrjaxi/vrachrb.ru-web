<?php
class userComponents extends sfComponents
{
  public function executeUsers_list(sfWebRequest $request)
  {
    $this->users = Doctrine_Query::create()
      ->select("u.*")
      ->from('User u')
      ->where("LENGTH(u.username) = 24")
      ->orderBy("u.username ASC")
      ->execute();
  }
  public function executeUser_info(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated())
    {
      $this->user_info = $this->getUser()->getAccount()->getUserInfo();
    }
  }
  public function executeLogin(sfWebRequest $request)
  {
    $this->signin_form = new wwwSigninForm();
  }
  public function executeRegister(sfWebRequest $request)
  {
    $this->register_form = new RegisterUserForm();
  }
}
