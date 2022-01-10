<?php
class sfValidatorInn extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }
  protected function doClean($value)
  {
    $c = parent::doClean($value);
    if(strlen($c) == 10)
    {
      $cs = (2 * $c[0] + 4 * $c[1] + 10 * $c[2] + 3 * $c[3] + 5 * $c[4] + 9 * $c[5] + 4 * $c[6] + 6 * $c[7] + 8 * $c[8]);
      $cs = $cs % 11;
      $cs = $cs % 10;
      if($cs == $c[9])
      {
        return $c;
      }
    }
    elseif(strlen($c) == 12)
    {
      $cs = (7 * $c[0] + 2 * $c[1] + 4 * $c[2] + 10 * $c[3] + 3 * $c[4] + 5 * $c[5] + 9 * $c[6] + 4 * $c[7] + 6 * $c[8] + 8 * $c[9]);
      $cs = $cs % 11;
      $cs = $cs % 10;
      if($cs == $c[10])
      {
        $cs = (3 * $c[0] + 7 * $c[1] + 2 * $c[2] + 4 * $c[3] + 10 * $c[4] + 3 * $c[5] + 5 * $c[6] + 9 * $c[7] + 4 * $c[8] + 6 * $c[9] + 8 * $c[10]);
        $cs = $cs % 11;
        $cs = $cs % 10;
        if($cs == $c[11])
        {
          return $c;
        }
      }
    }

    throw new sfValidatorError($this, 'invalid', array('value' => $value));
  }
}
