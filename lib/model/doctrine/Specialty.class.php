<?php

/**
 * Specialty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Specialty extends BaseSpecialty
{
  static function map($param = false)
  {
    $map = array(
     '32514126' => 1,
     '32465817' => 6,
     '32465850' => 18,
     '32465833' => 10,
     '32588816' => 13,
     '32535136' => 14,
     '32517894' => 15,
     '32483610' => array(16, 107),
     /*'32483610' => 16,*/
     '32587890' => 17,
     '32466002' => 19,
     '32487351' => 20,
     '32515609' => 22,
     '32516261' => 23,
     '32731691' => 25,
     '32487348' => 26,
     '32702032' => 27,
     '32552047' => 29,
     '32668811' => 30,
     '32559704' => 32,
     '32566102' => 33,
     '32465840' => 34,
     '32587869' => 35,
     '32515770' => 37,
     '34629925' => 33372797,
     '34782314' => 33372789,
     '35182248' => 33372801,
     '35797289' => 33372802
    );
    $result = $map;
    if($param)
    {
      $result = $map[$param];
    }
    return $result;
  }
  static function fullMap()
  {
    $map = array(


      '33433000',
      '33433001'


//      '32514126',
//      '32628939',
//      '32588920',
//      '32731691',
//      '32465850',
//      '32466002',
//      '32587988',
//      '32552047',
//      '32465833',
//      '32587869',
//      '32517894',
//      '32613981',
//      '32465817',
//      '32552070',
//      '32483610',
//      '32588816',
//      '32487355',
//      '32566102',
//      '32539897',
//      '32487351',
//      '32516261',
//      '32587768',
//      '32959254',
//      '32587890',
//      '32515770',
//      '32487348',
//      '32587749',
//      '32634617',
//      '32914320',
//      '32702032',
//      '32514176',
//      '32465867',
//      '32515609',
//      '32559704',
//      '32535136',
//      '32587838',
//      '32587801',
//      '33372773',
//      '32668811',
//      '32465840'
    );

    return $map;
  }
}
