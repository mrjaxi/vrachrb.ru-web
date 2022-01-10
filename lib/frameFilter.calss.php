<?php 
class frameFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $context = $this->getContext();
    $request = $context->getRequest();
    $user = $context->getUser();
    
    $user->setAttribute('lpu_title', 'Все ЛПУ');
    
    if($request->hasParameter('token') && $request->getParameter('token') == 'null')
    {
      $user->setAttribute('lpu', false);
      $user->setAttribute('lpu_title', 'Все ЛПУ');
      $user->setAttribute('lpu_specialists', array());
      $user->setAttribute('token', false);
    }
    else
    {
      if($request->hasParameter('token') || $user->hasAttribute('token'))
      {
        if(!$request->isXmlHttpRequest() && strpos($request->getHost(), 'frame.') === 0)
        {
          $context->getController()->getActionStack()->getFirstEntry()->getActionInstance()->setLayout('layout_frame');
        }
        if($request->hasParameter('token'))
        {
          $user->setAttribute('token', $request->getParameter('token'));
        }
      }
      if($user->hasAttribute('token') && $user->getAttribute('token') !== false)
      {
        $lpu = Doctrine::getTable('Lpu')->findOneByToken($user->getAttribute('token'));
        $user->setAttribute('lpu', $lpu ? $lpu->getId() : false);
        $user->setAttribute('lpu_title', $lpu ? $lpu->getTitle() : '');
        
        
        if($lpu)
        {
          $lpu_specialists = array();
          foreach($lpu->getSpecialist() as $specialist)
          {
            $lpu_specialists[] = $specialist->getId();
          }
          $user->setAttribute('lpu_specialists', $lpu_specialists);
        }
        
      }
    }
    
    $filterChain->execute();
  }
}
?>