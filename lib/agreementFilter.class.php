<?php
class agreementFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $context = $this->getContext();
    $request = $context->getRequest();
    $user = $context->getUser();

    if($user->isAuthenticated())
    {
      if(substr_count($request->getPathInfo(), 'personal-account') > 0 || substr_count($request->getPathInfo(), 'login') > 0)
      {
        if(Agreement::agreementCheck($user->getAccount()->getId()))
        {
          header("Location: /agreement/");
        }
      }
    }
    $filterChain->execute();
  }
}
?>