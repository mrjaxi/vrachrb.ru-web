<?php

/**
 * Page
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    chrisal
 * @subpackage model
 * @author     SyLord
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Page extends BasePage
{
  static public function niceRusEnds($c, $e1, $e2, $e3)
  {
    $s = substr(strval($c), -1);
    $s2 = substr(strval($c), -2);
    $e = '';
    if($c == 0)
    {
      return $e3;
    }
    elseif($s2 == '11' || $s2 == '12' || $s2 == '13' || $s2 == '14')
    {
      $e = $e3;
    }
    elseif($s == '1')
    {
      $e = $e1;
    }
    elseif($s == '2' || $s == '3' || $s == '4')
    {
      $e = $e2;
    }
    else
    {
      $e = $e3;
    }
    return sprintf($e, $c);
  }
  static function findImages($content)
  {
    preg_match_all("/src=\"\/u\/(\S*)\" width=/iS", $content, $m);
    foreach($m[1] as $im)
    {
      if(file_exists(sfConfig::get('sf_upload_dir') . '/' . $im) && file_exists(sfConfig::get('sf_upload_dir') . '/' . str_replace('/s1/', '/', $im)))
      {
        $sz1 = getimagesize(sfConfig::get('sf_upload_dir') . '/' . $im);
        $sz2 = getimagesize(sfConfig::get('sf_upload_dir') . '/' . str_replace('/s1/', '/', $im));
        $content = str_replace($im, $im . '" name="' . $sz1[0] . ';' . $sz1[1] . ';' . $sz2[0] . ';' . $sz2[1] . '" class="full_image', $content);
      }
    }
    return $content;
  }
  static function formatSize($bytes)
  {
    $ex = array('кб', 'Мб', 'Гб', 'Тб', 'Пб', 'Еб');
    $i = -1;
    do
    {
      $bytes = $bytes / 1024;
      $i++;  
    }
    while ($bytes > 999);
    return round($bytes) . ' ' . $ex[$i];
  }

  static function replaceImageSize($str, $need_size)
  {
    $replace_arr = array('png', 'jpg', 'jpeg');
    $replace_str = $str;
    foreach ($replace_arr as $r)
    {
      $replace_str = str_replace('.' . $r,'-' . $need_size . '.' . $r, $replace_str);
    }
    return $replace_str;
  }

  static function watermarkAdd($image_src)
  {
//    $image = new Imagick($image_src);
//    $overlay = new Imagick(sfConfig::get('sf_web_dir') . '/i/watermark_small.png');
    $image_size = getimagesize($image_src);
    for($item = 0; $item < $image_size[0] / 150; $item ++)
    {
      for($i = 0; $i < $image_size[1] / 150; $i ++)
      {
//        $image->compositeimage($overlay, Imagick::COMPOSITE_DEFAULT, $item * 150, $i * 150 + ($item % 2 == 0 ? 0 : 150));
      }
    }
//    $image->writeImage($image_src);
  }

  static function noticeAdd($who, $type, $inner_id, $event, $csrf = false, $user_id = false)
  {
    $notice_form = !$csrf ? new CreateNoticeForm() : new CreateNoticeCsrfForm();
    $notice_form_value = array();
    if($type == 'dialog')
    {
      $n = Doctrine::getTable("Question")->find($inner_id);
      if($who == 'u')
      {
        $user_id = $n->getUserId();
      }
      elseif($who == 's')
      {
        if($n)
        {
          $notice_user_id = $n->getSpecialists();
          $user_id = $notice_user_id[0]->getUser()->getId();
        }
      }
      if($csrf)
      {
        $notice_form_value['_csrf_token'] = $csrf;
      }
    }
    elseif($type == 'review')
    {
      $user_id = Doctrine::getTable("Review")->find($inner_id)->getSpecialist()->getUser()->getId();
    }

    $notice_form_value['user_id'] = $user_id;
    $notice_form_value['type'] = $type;
    $notice_form_value['inner_id'] = $inner_id;
    $notice_form_value['event'] = $event;

    $notice_form->bind($notice_form_value);
    if($notice_form->isValid())
    {
      $notice_form->save();
    }
  }

  static function notice($type, $number = false, $event = false, $need_value, $profile = false)
  {
    $valid_arr = Notice::noticeType('list');

    $result = $valid_arr[$event];

    if($need_value == 'link')
    {
      if($type == 'dialog')
      {
        $profile_prefix = $profile == 's' ? 'doctor' : 'personal';
        $url = $event == 'review' ? 'doctor_account_history_appeal' : $profile_prefix . '_account_now_dialog_show?id=' . $number;
      }
      elseif($type == 'consilium')
      {
        $url = 'doctor_account_consilium_show?id=' . $number;
      }
      elseif($type == 'review')
      {
        $url = 'doctor_account_history_appeal';
      }
      $result = url_for('@' . $url);
    }

    return $result;
  }

  static function authorizedUserName($account)
  {
    $profile_name = '';
    if($account->getFirstName())
    {
      $profile_name .= $account->getFirstName() . ' ' . ($account->getMiddleName() ? '<br>' . $account->getMiddleName() : '<br>' . $account->getSecondName());
    }
    else
    {
      $profile_name = $account->getUsername();
    }
    return $profile_name;
  }

  static function whoIsDoctor($id, $need_value)
  {
    $elem = false;
    if(Doctrine::getTable('Specialist')->findOneByUserId('' . $id))
    {
      $elem = true;
    }

    if($need_value == 'url')
    {
      return $elem ? 'doctor' : 'personal';
    }
    elseif('doctor')
    {
      return $elem;
    }
  }

  static function nameAge($age = false, $gender)
  {
    $male = $gender == 'м' ? true : false;

    if($age < 17 && $age != 0)
    {
      $people = $male ? 'Мальчик' : 'Девочка';
    }
    elseif($age < 21 && $age != 0)
    {
      $people = $male ? 'Юноша' : 'Девушка';
    }
    else
    {
      $people = $male ? 'Мужчина' : 'Женщина';
    }
    return $people;
  }

  static function tipCountPage($show_el, $page, $table, $lpu_specialist = false)
  {
    $pc = Doctrine_Query::create()
      ->select('COUNT(*)')
      ->from($table . ' el');

    if($table == 'Question')
    {
      if($lpu_specialist)
      {
        $pc->innerJoin("el.QuestionSpecialist qs");
      }
      $pc->where('el.approved = 1' . $lpu_specialist);
    }
    elseif($table == 'Prompt' && sfContext::getInstance()->getUser()->getAttribute('lpu'))
    {
      $lpu_str = '';
      foreach (sfContext::getInstance()->getUser()->getAttribute('lpu_specialists') as $lpu_specialist_key => $lpu_specialist)
      {
        $lpu_str .= ($lpu_specialist_key != 0 ? ' OR ' : '') . 'el.specialist_id = ' . $lpu_specialist;
      }
      $pc->where($lpu_str);
    }

    $page_count = $pc->fetchArray();

    if($page_count[0]['COUNT'] > 10)
    {
      if(($page_count[0]['COUNT'] % $show_el) != 0)
      {
        $page_count_val = ($page_count[0]['COUNT'] + $show_el) / $show_el;
      }
      else
      {
        $page_count_val = $page_count[0]['COUNT'] / $show_el;
      }

      $html = '<div class="pagination_page">';
      $i = 1;
      $url_name = $table == 'Question' ? 'question_answer' : 'tip';
      while($i <= $page_count_val)
      {
        if($i == $page)
        {
          $html .= '<span class="pagination_page__el">' . $i . '</span>';
          $html .= $page != 1 ? '<a href="' . url_for('@' . $url_name . '_page?id=' . ($i - 1)) . '" class="pagination_page__el prev_page"></a>' : '';
          $html .= $page != intval($page_count_val) ? '<a href="' . url_for('@' . $url_name . '_page?id=' . ($i + 1)) . '" class="pagination_page__el next_page"></a>' : '';
        }
        else
        {
          if(($i >= $page - 2 && $i <= $page + 2))
          {
            $html .= '<a href="' . url_for('@' . $url_name . '_page?id=' . $i) . '" class="pagination_page__el">' . $i . '</a>';
          }
        }
        $i++;
      }
      $html .= '</div>';
      return $html;
    }
  }

  static function endPointReplace($str)
  {
    if(substr($str,-1) != '.' && substr($str,-1) != ' ')
    {
      $f_str = $str . '...';
    }
    else
    {

      $f_str = substr($str, 0, -1) . '...';
    }
    return $f_str;
  }

  private function cc()
  {
    /*
    try
    {
      $configuration = ProjectConfiguration::getApplicationConfiguration('www', sfConfig::get('sf_environment'), false);
      sfContext::createInstance($configuration, 'www');
      $cacheManager = sfContext::getInstance('www')->getViewCacheManager();
      //$cacheManager->remove('page/about');
      
    }
    catch(Exception $e)
    {
      
    }
    */
  }
  public function save(Doctrine_Connection $conn = null)
  {
    $this->cc();
    return parent::save($conn);
  }
  public function delete(Doctrine_Connection $conn = null)
  {
    $this->cc();
    return parent::delete($conn);
  }
  static public function rndName($length)
  {
    $alf = array_merge(range('a', 'z'), range(1, 9));
    $s = '';
    for($i = 0; $i < $length; $i++)
    {
      $s .= $alf[mt_rand(0, count($alf) - 1)];
    }
    return $s;
  }
  static public function generateUuid()
  {
    mt_srand((double)microtime()*10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid, 12, 4).$hyphen
            .substr($charid, 16, 4).$hyphen
            .substr($charid, 20, 12);
    return $uuid;
  }
  static public function rusDate($date, $time = false, $skip_year = false)
  {
    $a = array(
      '01' => 'января',
      '02' => 'февраля',
      '03' => 'марта',
      '04' => 'апреля',
      '05' => 'мая',
      '06' => 'июня',
      '07' => 'июля',
      '08' => 'августа',
      '09' => 'сентября',
      '10' => 'октября',
      '11' => 'ноября',
      '12' => 'декабря',
    );
    $ex = explode(' ', $date);
    if(count($ex) == 2)
    {
      $timeex = explode(':', $ex[1]);
    }
    $ex = explode('-', $ex[0]);
    return intval($ex[2]) . ' ' . $a[$ex[1]] . ($skip_year && $ex[0] == date('Y') ? '' : ' ' . $ex[0]) . ($time ? ' ' . $timeex[0] . ':' . $timeex[1] : '');
  }
  static public function rusMonthR($date)
  {
    $a = array(
      '01' => 'январе',
      '02' => 'Феврале',
      '03' => 'марте',
      '04' => 'апреле',
      '05' => 'мае',
      '06' => 'июне',
      '07' => 'июле',
      '08' => 'августе',
      '09' => 'сентябре',
      '10' => 'октябре',
      '11' => 'ноябре',
      '12' => 'декабре',
    );
    
    $ex = explode(' ', $date);
    $ex = explode('-', $ex[0]);
    if($only_month)
    {
      return $a[$ex[1]];
    }
    return $a[$ex[1]] . ($skip_year && $ex[0] == date('Y') ? '' : ' ' . $ex[0]);
  }
  static public function rusMonth($date, $skip_year = true, $only_month = false)
  {
    $a = array(
      '01' => 'Январь',
      '02' => 'Февраль',
      '03' => 'Март',
      '04' => 'Апрель',
      '05' => 'Май',
      '06' => 'Июнь',
      '07' => 'Июль',
      '08' => 'Август',
      '09' => 'Сентябрь',
      '10' => 'Октябрь',
      '11' => 'Ноябрь',
      '12' => 'Декабрь',
    );
    
    $ex = explode(' ', $date);
    $ex = explode('-', $ex[0]);
    if($only_month)
    {
      return $a[$ex[1]];
    }
    return $a[$ex[1]] . ($skip_year && $ex[0] == date('Y') ? '' : ' ' . $ex[0]);
  }
  static public function rusMonthOnly($month)
  {
    $a = array(
      '1' => 'Январь',
      '2' => 'Февраль',
      '3' => 'Март',
      '4' => 'Апрель',
      '5' => 'Май',
      '6' => 'Июнь',
      '7' => 'Июль',
      '8' => 'Август',
      '9' => 'Сентябрь',
      '10' => 'Октябрь',
      '11' => 'Ноябрь',
      '12' => 'Декабрь',
    );

    return $a[$month];
  }
  static public function resize($request, $return = false)
  {
    $mime_exts = array(
      'image/png' => '.png', 
      'image/gif' => '.gif', 
      'image/jpeg' => '.jpg'
    );
    $error = 'Файл не загрузился [0]';
    $success = array();
    if($request->hasParameter('file') && $request->getParameter('file') != '' && file_exists('../upload/' . $request->getParameter('file')))
    {
      $sourse_file = '../upload/' . $request->getParameter('file');
      $file = $request->getParameter('file');
      $key = $request->getParameter('key');
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $sourse_file);
      finfo_close($finfo);
      $is_image = in_array($mime, array('image/png', 'image/gif', 'image/jpeg'));
      $dest_dir = sfConfig::get('sf_upload_dir') . '/' . ($request->hasParameter('p') ? 'p' : ($is_image ? 'i' : 'f'));
      $error = 'Папка назначения не найдена [1]';
      if($key != '' && is_dir($dest_dir))
      {
        $error = 'Папка назначения защищена от записи [2]';
        if(is_writeable($dest_dir))
        {
          $error = false;
          $tryes = 0;
          $ext = mb_strtolower(substr($file, strrpos($file, '.')));
          if($ext == $file)
          {
            $ext = $mime_exts[$mime];
          }
          do
          {
            if($tryes++ > 10)
            {
              $error = 'Слишком много попыток сгенерировать имя [3]';
              break;
            }
            $filename = Page::generateUuid();
            $predir = substr($filename, 0, 3);
          }
          while(file_exists($dest_dir . '/' . $predir . '/' . $filename . $ext));
          if(!$error)
          {
            $error = 'Не удалось переименовать файл [4]';
            if(!is_dir($dest_dir . '/' . $predir))
            {
              mkdir($dest_dir . '/' . $predir);
            }
            if(rename($sourse_file, $dest_dir . '/' . $predir . '/' . $filename . $ext))
            {
              $error = false;
              $dest_file = $dest_dir . '/' . $predir . '/' . $filename;
              $success['filename'] = $predir . '/' . $filename . $ext;
              $success['filelink'] = '/u/i/' . $success['filename'];
              if($is_image)
              {
                $is = $orig_is = getimagesize($dest_file . $ext);
                $success['sizes']['orig'] = array('width' => $is[0], 'height' => $is[1]);
                if(is_array($cfg_sizes = sfConfig::get('app_' . $key . '_sizes')))
                {
                  if($is[0] < $cfg_sizes['min']['width'] || $is[1] < $cfg_sizes['min']['height'])
                  {
                    $error = 'Слишком маленькое изображение [5]';
                  }
                  else
                  {
                    unset($cfg_sizes['min']);
                    foreach($cfg_sizes as $sk => $sv)
                    {
                      $copy = false;
                      if($sv['thumbstyle'] == 'scale')
                      {
                        if($orig_is[0] < $sv['width'] && $orig_is[1] < $sv['height'])
                        {
                          $copy = true;
                        }
                      }
                      else
                      {
                        $max_key_cfg = $sv['width'] < $sv['height'] ? 'width' : 'height';
                        $max_key = $sv['width'] < $sv['height'] ? '0' : '1';
                        if($orig_is[$max_key] < $sv[$max_key_cfg])
                        {
                          $copy = true;
                        }
                      }
                      if($copy)
                      {
                        copy($dest_file . $ext, $dest_file . '-' . $sk . $ext);
                      }
                      else
                      {
                        $img = new sfImage($dest_file . $ext);
                        $img->setQuality(100);
                        $img->thumbnail($sv['width'], $sv['height'], $sv['thumbstyle']);
                        $img->saveAs($dest_file . '-' . $sk . $ext, $mime);
                      }
                      if(isset($sv['blur']))
                      {
                        $img = new sfImage();
                        $overlay = new sfImage($dest_file . '-' . $sk . $ext);
                        $img->resize($overlay->getWidth() + $sv['border'] * 2, $overlay->getHeight() + $sv['border'] * 2);
                        $img->fill(0, 0, $sv['fill']);
                        $img->overlay($overlay, 'middle-center');
                        $img->blur($sv['radius'], $sv['sigma']);
                        $img->saveAs($dest_file . '-' . $sk . $ext, $mime);
                      }
                      $is = getimagesize($dest_file . '-' . $sk . $ext);
                      $success['sizes'][$sk] = array('width' => $is[0], 'height' => $is[1]);
                    }
                  }
                }
              }
              else
              {
                $success['filesize'] = filesize($dest_dir . '/' . $predir . '/' . $filename . $ext);
              }
            }
          }
        }
      }
    }
    $r = $error ? array('success' => false, 'error' => $error) : array_merge(array('success' => true), $success);
    if($return)
    {
      return $r;
    }
    echo $request->getParameter('key') == 'redactor' && !$error ? '<img class="redactor__img" src="' . str_replace('.', '-M.', $success['filelink']) . '" width="' . $success['sizes']['M']['width'] . '" height="' . $success['sizes']['M']['height'] . '" />' : json_encode($r);
  }
  static public function uploader($file, $key, $type = false)
  {
    $json = array(
      'state' => 'error',
      'errorTxt' => 'Not uploaded',
    );
    if($type)
    {
      if(file_exists(sfConfig::get('sf_upload_dir') . '/' . $file))
      {
        $file_exist = true;
        $this_file_dir = sfConfig::get('sf_upload_dir') . '/' . $file;
        $this_filename = $this_file_dir;
      }
    }
    elseif(file_exists($file['tmp_name']))
    {
      $file_exist = true;
      $this_file_dir = $file['tmp_name'];
      $this_filename = $file['name'];
    }

    if($file_exist)
    {
      $sha = ProjectUlils::generateUuid();
      //sha1_file($file['tmp_name']);
      $sha_array = str_split($sha);
      $subdir = '';

      $filename = '';

      foreach($sha_array as $i => $dir)
      {
        if($i > 2)
        {
          $filename .= mb_substr($sha, $i);
          break;
        }
        if(!is_dir(sfConfig::get('sf_upload_dir') . '/i/' . $filename . $dir))
        {
          mkdir(sfConfig::get('sf_upload_dir') . '/i/' . $filename . $dir);
        }
        $filename .= $dir . '/';
      }
      $ext_post = strrpos($this_filename, '.');
      $ext = strtolower(substr($this_filename, $ext_post));
      $name = substr($this_filename, 0, $ext_post);
      $filename_we = $filename;
      $filename .= $ext;

      if(rename($this_file_dir, sfConfig::get('sf_upload_dir') . '/i/' . $filename))
      {
        $json = array(
          'state' => 'success',
          'filename' => $filename,
          'name' => $name,
          'preview' => false
        );
        $file_path = sfConfig::get('sf_upload_dir') . '/i/' . $filename;
        if($file['type'] == 'application/pdf' || $file['type'] == 'application/x-download')
        {
          /*
          exec('pdftoppm ' . $file_path . ' ' . $file_path . '.ppm');
          foreach(glob($file_path . '.ppm-*.ppm') as $ppm)
          {
            exec('ppmtojpeg --quality=80 ' . $ppm . ' > ' . $ppm . '.jpg');
          }
          $image_height = 0;
          $image_width = 0;
          foreach(glob($file_path . '.ppm-*.ppm.jpg') as $jpg)
          {
            $is = getimagesize($jpg);
            $image_width = max(array($image_width, $is[0]));
            $image_height += $is[1];
          }

          $image = new Imagick();
          $pixel = new ImagickPixel( 'gray' );
          $image->newImage($image_width, $image_height, $pixel);

          $y = 0;
          foreach(glob($file_path . '.ppm-*.ppm.jpg') as $jpg)
          {
            $jpgimage = new Imagick($jpg);
            $image->compositeImage($jpgimage, Imagick::COMPOSITE_DEFAULT, 0, $y);
            $y += $jpgimage->getImageHeight();
          }
          if($image_width > 1000)
          {
            $image->scaleImage(1000, 0);
          }
          $image->writeImage($file_path . '.jpg');
          $json['preview'] = '<img src="/u/i/' . $filename . '.jpg" style="max-width:100%" />';
*/
        }
        elseif(substr($file['type'], 0, 5) == 'image')
        {
          $dest_file = sfConfig::get('sf_upload_dir') . '/i/' . $filename_we;
          $is = $orig_is = getimagesize($file_path);

          $prew = false;

          if(is_array($cfg_sizes = sfConfig::get('app_' . $key . '_sizes')))
          {
            $cfg_sizes_full = $cfg_sizes;
            if($is[0] < $cfg_sizes['min']['width'] || $is[1] < $cfg_sizes['min']['height'])
            {
              $error = 'Слишком маленькое изображение [5]';
              $json['state'] = $error;
              $json['errorTxt'] = $error;
              unlink(sfConfig::get('sf_upload_dir') . '/i/' . $filename);
            }
            else
            {
              unset($cfg_sizes['min']);
              foreach($cfg_sizes as $sk => $sv)
              {
                $copy = false;
                  $copy = true;
                if($sv['thumbstyle'] == 'scale')
                {
                  if($orig_is[0] < $sv['width'] && $orig_is[1] < $sv['height'])
                  {
                    $copy = true;
                  }
                }
                else
                {
                  $max_key_cfg = $sv['width'] < $sv['height'] ? 'width' : 'height';
                  $max_key = $sv['width'] < $sv['height'] ? '0' : '1';
                  if($orig_is[$max_key] < $sv[$max_key_cfg])
                  {
                    $copy = true;
                  }
                }
                if($copy)
                {
                  copy($dest_file . $ext, $dest_file . '-' . $sk . $ext);
                }
                else
                {
                  $img = new sfImage($dest_file . $ext);
                  $img->setQuality(100);
                  $img->thumbnail($sv['width'], $sv['height'], $sv['thumbstyle']);
                  $img->saveAs($dest_file . '-' . $sk . $ext, $file['type']);
                }
                if($sv['watermark'])
                {
                  $image_src = sfConfig::get('sf_upload_dir') . '/i/' . Page::replaceImageSize($filename, $sk);
//                  Page::watermarkAdd($image_src);
                }
                $is = getimagesize($dest_file . '-' . $sk . $ext);
                if(!$prew)
                {
                  $json['preview'] = '<img src="/u/i/' . $filename_we . '-' . $sk . $ext . '" style="max-width:100%" />';
                  $prew = true;
                }
              }
            }
          }
          else
          {
            $json['preview'] = '<img src="/u/i/' . $filename . '" style="max-width:100%" />';
          }

          if(file_exists(sfConfig::get('sf_upload_dir') . '/i/' . $filename))
          {
            chmod(sfConfig::get('sf_upload_dir') . '/i/' . $filename, 0644);
          }
        }
        elseif($ext == '.webm')
        {
          $json['preview'] = '<video src="/u/i/' . $filename . '" controls></video>';
        }
      }
    }
    return $key == 'redactor' && $json['state'] == 'success' ? '<img src="/u/i/' . $filename . '" />' : json_encode($json);
  }
  static function getVkId($str)
  {
    if(substr_count($str, '[id') > 0)
    {
      $id = '';
      $explode = explode('[id', $str);
      for($i = 0; $i < 12; $i++)
      {
        if(is_numeric($explode[1][$i]))
        {
          $id .= $explode[1][$i];
        }
        else
        {
          break;
        }
      }
      return $id;
    }
  }
  static function translitUrl($str)
  {
    $tr = array(
      "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d",
      "Е"=>"e","Ё"=>"yo","Ж"=>"j","З"=>"z","И"=>"i",
      "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
      "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
      "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"c","Ч"=>"ch",
      "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
      "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
      "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"j",
      "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
      "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
      "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
      "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
      "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
      " "=> "_", "."=> "", "/"=> "_"
    );
    $str = strtr(strip_tags(trim($str)),$tr);
    $str = preg_replace('/[^A-Za-z0-9_\-]/', '', $str);
    $str = preg_replace('/\W+/', '-', $str);
    $str = strtolower(trim($str, '-\_'));

    return $str;
  }
  static public function timeUnit($value, $type = false, $view = 'full')
  {
    $res = $value;
    $unit = array(
      'секунд',
      'а',
      'ы',
      '',
      'сек.'
    );
    if($value >= 60)
    {
      $unit = array(
        'минут',
        'а',
        'ы',
        '',
        'мин.'
      );
      $m = intval($value / 60);
      $res = $m;
      if($m >= 60)
      {
        $unit = array(
          'час',
          '',
          'а',
          'ов',
          ''
        );
        $h = intval($m / 60);
        $res = $h;
        if($h > 24)
        {
          $unit = array(
            '',
            'день',
            'дня',
            'дней',
            ''
          );
          $d = intval($h / 24);
          $res = $d;
        }
      }
    }
    
    $nice_end = Page::niceRusEnds($res, $unit[1], $unit[2], $unit[3]);

    if($view == 'cut')
    {
      $unit_name = ($unit[4] != '' ? $unit[4] : $unit[0] . $nice_end);
    }
    else
    {
      $unit_name = ($unit[0] != '' ? $unit[0] : '') . $nice_end;
    }

    $result = $res . ' ' . $unit_name;

    return $result;
  }
  static public function specialistOnline($last_login_date)
  {
    $result = '_';
    if(isset($last_login_date))
    {
      $time_difference = strtotime(date('Y-m-d' . ' ' . 'H:i:s')) - strtotime($last_login_date);
      $result = $time_difference < sfConfig::get('app_waiting_time_online') ? true : false;
    }
    return $result;
  }
  static public function strCut($str, $max)
  {
    $result = array();
    if(strlen($str) <= $max)
    {
      $result[0] = $str;
      $result[1] = 'not_full';
    }
    else
    {
      $exp = explode(' ', $str);
      $res = '';
      for($i = 0; $i < count($exp); $i ++)
      {
        $length = $res . ($res != '' ? ' ' : '') . $exp[$i];
        if(strlen($length) <= $max)
        {
          $res .= ' ' . $exp[$i];
        }
        else
        {
          if(substr($res, -1) == ',')
          {
            $res = substr($res, 0, -1);
          }
          $res .= '...';
          break;
        }
      }
      $result[0] = $res;
      $result[1] = 'full';
    }

    return $result;
  }
  static public function messageErrorStatus($status_name, $view)
  {
    $result = false;
    if(strlen($status_name) > 0)
    {
      $valid_arr = array(
      'no_answer' => 'Без ответа',
      'detail' => 'Уточнение деталей',
      'in_work' => 'В работе',
      'completed' => 'Решено',
      'defect' => 'Брак');

      if($view == 'name')
      {
        $result = ($valid_arr[$status_name] ? $valid_arr[$status_name] : false);
      }
      else
      {
        $result = $valid_arr;
      }
    }
    return $result;
  }
}