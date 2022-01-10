<?php
class utils
{
  public static function post($url, $data)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
  }
  public static function wsPub($channel, $data)
  {
    if(sfConfig::has('app_ws_pub'))
    {
      self::post(sfConfig::get('app_ws_pub') . '?channel=' . $channel, json_encode($data));
    }
  }
}
?>