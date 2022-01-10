<?php

class testActions extends sfActions
{
  public function executeTest_form(sfWebRequest $request)
  {
    $this->form = new scForm();
  }
  public function executeTest(sfWebRequest $request)
  {
    if ($request->isMethod('post')) {
      $this->form = new scForm();
      $this->form->bind($request->getParameter('specialty'));

      if ($this->form->isValid()) {
        $this->form->save();
        $sc = $this->form->getObject();
        $sc->save();
        $this->result = 'yes';
      }
    }
  }
  public function executeError404(sfWebRequest $request)
  {
    $this->redirect($request->getPathInfo(), 301);
  }
}