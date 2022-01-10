<?php
class printActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->setTemplate($request->getParameter('template'));
    $this->setLayout('layout_print');
  }
}
