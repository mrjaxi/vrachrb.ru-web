  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    if ($this->getRoute()->getObject()->delete())
    {
      if(!$request->isXmlHttpRequest())
      {
        $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
      }
    }
    if(!$request->isXmlHttpRequest())
    {
      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }
    else
    {
      return sfView::NONE;
    }
  }
