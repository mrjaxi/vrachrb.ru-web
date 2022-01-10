<?php
class mainActions extends sfActions
{
  public function executeEvent(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeUploader(sfWebRequest $request)
  {
    echo Page::uploader($request->getFiles('file'), $request->getParameter('key'));
    return sfView::NONE;
  }
  public function executeEvent_elem(sfWebRequest $request)
  {
    $type = $request->getParameter('type');
    $event = $request->getParameter('event');
    $advanced_id = $request->getParameter('advanced_id');
    $id = $request->getParameter('id');

    $valid_type = array('LpuSpecialist');
    $valid_event = array('add', 'delete');

    if(in_array($type, $valid_type) && in_array($event, $valid_event) && is_numeric($id) && is_numeric($advanced_id))
    {
      if($event == 'delete')
      {
        $lpu_specialist = Doctrine_Query::create()
          ->select("ls.*")
          ->from("LpuSpecialist ls")
          ->where("ls.lpu_id = " . $advanced_id)
          ->andWhere("ls.specialist_id = " . $id)
          ->execute()
        ;
        if($lpu_specialist)
        {
          $lpu_specialist->delete();
        }
      }
      elseif($event == 'add')
      {
        $lpu_specialist_new = new LpuSpecialist();
        $lpu_specialist_new->setLpuId($advanced_id);
        $lpu_specialist_new->setSpecialistId($id);
        $lpu_specialist_new->save();
      }
    }
    echo 'ok';
    return sfView::NONE;
  }
  public function executeJcrop(sfWebRequest $request)
  {
    if($request->isMethod('get'))
    {
      $c = $request->getParameter('c');
      $key = $request->getParameter('key');

      if(is_array($cfg_sizes = sfConfig::get('app_' . $key . '_sizes')))
      {
        if($cfg_sizes['min'])
        {
          $min_width = $cfg_sizes['min']['width'];
          $min_height = $cfg_sizes['min']['height'];

          if(($request->getParameter('w') * $c) > $min_width && ($request->getParameter('h') * $c) > $min_height)
          {
            $img = new sfImage(sfConfig::get('sf_upload_dir') . '/i/' . $request->getParameter('src'));
            $img->crop(($request->getParameter('x') * $c), ($request->getParameter('y') * $c), ($request->getParameter('w') * $c), ($request->getParameter('h') * $c));
            $img->thumbnail($min_width, $min_height, true);
            $image_src = sfConfig::get('sf_upload_dir') . '/i/' . str_replace('.', '-M.', $request->getParameter('src'));
            $img->saveAs($image_src);

            if($cfg_sizes['M']['watermark'])
            {
              Page::watermarkAdd($image_src);
            }

            $result = 'ok';
          }
          else
          {
            $result = 'small';
          }
        }
      }
    }
    echo $result;
    return sfView::NONE;
  }
  public function executeError404(sfWebRequest $request)
  {
    $this->forward404();
  }
}
