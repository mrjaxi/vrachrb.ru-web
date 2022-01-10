<?php
class specialistComponents extends sfComponents
{
  public function executeSpecialist(sfWebRequest $request)
  {
    $valid_sort_name = array(
      'rating',
      'first_name'
    );
    $valid_sort_type = array(
      'asc',
      'desc'
    );

    $lpu_str = '';
    if($this->getUser()->getAttribute('lpu'))
    {
      $lpu_str = ' AND (';
      foreach ($this->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 's.id = ' . $lpu_specialist;
      }
      $lpu_str .= ')';
    }
    $q = Doctrine_Query::create()
      ->select('s.*,u.*,so.*')
      ->from('Specialist s')
      ->innerJoin('s.User u')
      ->leftJoin('s.Specialist_online so')
      ->where('s.user_id != 1 ' . $lpu_str)
    ;

    if($this->view == 'vertical')
    {
      if($this->categories_id && is_numeric($this->categories_id))
      {
        $q->andWhere('s.specialty_id = ' . $this->categories_id);
      }
      $q->orderBy('s.rating DESC');
    }
    else
    {
      if($this->param_ajax == 'y')
      {
        $sort_value = $this->sort_doc;
      }
      else
      {
        $sort_value = $request->getCookie('sort_doc');
      }

      $ex = explode(':', $sort_value);

      if($this->specialty_array && $this->specialty_array != 'clear' || $ex[2])
      {
        if($ex[2])
        {
          $ex_sp_arr = explode('&', $ex[2]);
          $sp_elem = $ex_sp_arr;
          $ab = false;
        }
        else
        {
          $sp_elem = $this->specialty_array;
          $ab = true;
        }

        foreach ($sp_elem as $item_key => $item)
        {
          $item_key == 0 ? $or = '' : $or = ' OR ';
          if($ab)
          {
            $a .=  $or . 's.specialty_id = ' . $item;
          }
          else
          {
            $a .=  $or . 's.specialty_id = ' . substr($item, (strrpos($item, '=') + 1));
          }
        }
        $q =  $q->andWhere($a);
        $this->a = $a;
      }

      if($sort_value && in_array($ex[0], $valid_sort_name) && in_array($ex[1], $valid_sort_type))
      {
        $q->orderBy($ex[0] . ' ' . $ex[1]);
      }
    }

    $this->specialists = $q->execute();
  }
  public function executeCertificate(sfWebRequest $request)
  {
    $specialist = Doctrine::getTable("Specialist")->find($this->specialist_id);
    $this->certificates = $specialist->getCertificate();
  }
}