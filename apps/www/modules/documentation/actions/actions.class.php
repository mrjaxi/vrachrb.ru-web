<?php
class documentationActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->document = Doctrine_Query::create()
      ->select('d.*')
      ->from('documentation d')
      ->orderBy('order_field ASC')
      ->execute()
    ;
    $this->agreement = Doctrine_Query::create()
      ->select("a.*")
      ->from("Agreement a")
      ->where("a.in_documentation = 1")
      ->fetchArray()
    ;
  }
  public function executeDl(sfWebRequest $request)
  {
    $this->documentation = Doctrine::getTable('documentation')->find($request->getParameter('id'));
    $this->forward404Unless($this->documentation);

    $file = $this->documentation->getFile();
    $file_type = substr($file,strrpos($file,'.'));

    $this->getResponse()->setHttpHeader('Content-type', 'application/octet-stream');
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="' . $this->documentation->getTitle() . $file_type . '"');
    $this->getResponse()->setHttpHeader('X-Accel-Redirect', '/u/i/' . $file);

    return sfView::NONE;
  }
}
