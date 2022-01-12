<?php
class apiActions extends sfActions
{
    public function executeIndex(sfWebRequest $request) {}

    public function executeApi_signin(sfWebRequest $request)
    {
        $response = $request->getGetParameter("test");
        $this->getResponse()->setHttpHeader('Content-type','application/json');
        $this->setLayout('json');
        $this->setTemplate('json','main');

        return $this->renderText(json_encode(array(
            "test" => $response
        )));
    }
}