<?php

/**
 * Notice
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Notice extends BaseNotice
{
  public function save(Doctrine_Connection $conn = null)
  {
    $specialist = $this->getUser()->getSpecialist();
    $profile = $specialist ? 's' : 'u';

    $data = array(
      'id' => 1,
      'text' => array(
        'user_id' => $this->getUserId(),
        'type' => $this->getType(),
        'inner_id' => $this->getInnerId(),
        'event' => $this->getEvent(),
        'profile' => $profile
      )
    );
    $specialist = Doctrine::getTable("Specialist")->findOneByUserId($this->getUserId());
    $param = array('email');
    $q = Doctrine_Query::create()
      ->select("q.*, a.*")
      ->from("Question q")
      ->leftJoin("q.Answer a")
      ->orderBy("a.created_at DESC")
      ->where("q.id = " . $this->getInnerId())
    ;
    $question = $q->fetchArray();
    if($this->getType() == 'dialog')
    {
      $body = $question[0]['body'];

      if($this->getEvent() == 'message' && count($question) > 0)
      {
        $body = $question[0]['Answer'][0]['body'];
      }
      $body_result = Notice::noticeType($body) ? '' : "\n" . $body;
      if($this->getEvent() == 'email_reminder')
      {
        $body_result = "Здравствуйте!\nВы задали вопрос нашему специалисту и довольно долгое время не появлялись у нас. Быть может, вы сдали анализы или у вас нет времени. Отпишитесь по поводу вашего вопроса. Наши специалисты всегда готовы помочь.";
      }
      elseif($this->getEvent() == 'specialist_reception' || $this->getEvent() == 'closed' || $this->getEvent() == 'resume' || $this->getEvent() == 'user_reception' || $this->getEvent() == 'give_analysis' || $this->getEvent() == 'reception_yes')
      {
        $body_result = '';
      }
      elseif($this->getEvent() == 'reception_no')
      {
        $body_result = "\n" . 'Причина: ';
        $reception_contract = Doctrine_Query::create()
          ->select("rc.*")
          ->from("Reception_contract rc")
          ->where("rc.question_id = " . $this->getInnerId())
          ->andWhere("rc.reject_reason IS NOT NULL")
          ->orderBy("rc.created_at DESC")
          ->limit(1)
          ->fetchOne()
        ;
        $body_result .= $reception_contract->getRejectReason();
      }
    }
    if($specialist)
    {
      $param[] = 'vk';
      $link = sfConfig::get('app_lp_host') . ($specialist->getId() == 51 ? '/arm/question/' . $this->getInnerId() . '/edit' : '/doctor-account/now-dialog/' . $this->getInnerId() . '/');
    }
    else
    {
      $link = sfConfig::get('app_lp_host') . '/personal-account/now-dialog/' . $this->getInnerId() . '/';
    }
    $message = Notice::noticeType($this->getEvent());
    if($this->getEvent() == 'email_reminder')
    {
      $message = '';
    }
    elseif($this->getEvent() == 'please_analysis')
    {
      $message = Notice::noticeType($this->getEvent());
      $body_result = "\n" . 'Вам необходимо сдать следующие анализы:';
      $please_analysis = json_decode($question[0]['Answer'][0]['body'], true);
      foreach ($please_analysis as $pa_key => $pa)
      {
        $pa_exp = explode(':', $pa);
        $body_result .= ($pa_key != 0 ? ';' : '') . "\n" . ($pa_key + 1) . '___' . $pa_exp[1];
      }
      $body_result .= '.';
    }


    Notice::noticeSent($this->getUserId(), $param, $message . $body_result, $link);
    ProjectUtils::wsPub('private-' . $this->getUserId(), $data);


    
    $user_tokens = $this->getUser()->getToken();

    file_put_contents(sfConfig::get('sf_log_dir') . '/push_test.txt', $user_tokens . '__');



    if(count($user_tokens) > 0)
    {
      foreach ($user_tokens as $user_token)
      {
        $param = array(
          'type' => $this->getEvent(),
          'token' => $user_token->getValue(),
          'body' => $body_result,
          'profile' => $profile,
          'id' => $this->getInnerId()
        );
        ProjectUtils::pushPost($param);
      }
    }

    return parent::save($conn);
  }
  static function userStatus($user_id)
  {
    if($user_id != 1)
    {
      $json = @file_get_contents('http://' . sfConfig::get('app_lp_host') . '/whoishere/?channel=private-' . $user_id);
      $json = @json_decode($json, true);
      $status = isset($json['subscribers']) && $json['subscribers'] > 0;
    }
    return $status;
  }
  static function noticeSent($user_id, $param, $message, $link = false)
  {
    if(true)
//    if(!Notice::userStatus($user_id))
    {
      $user = Doctrine::getTable("User")->find($user_id);
      foreach ($param as $param_item)
      {
        if($param_item == 'vk' && sfConfig::get('app_vk_notice'))
        {
          $username = ($user_id == 1 ? csSettings::get('admin_vk_link') : $user->getUsername());
          if(substr_count($username, 'vk.com') > 0)
          {
            $user_exp = explode('id', $username);
            $method = 'https://api.vk.com/method/messages.send';
            $params = array(
              'user_id' => $user_exp[1],
              'message' => $message . ($link ? "\n" . $link : ''),
              'access_token' => sfConfig::get('app_vk_access_token')
            );
            $json = json_decode(ProjectUtils::post($method, $params), true);
            file_put_contents(sfConfig::get('sf_log_dir') . '/vk_notice.txt', print_r($params, true) . "\n" . print_r($json, true) . "\n", FILE_APPEND);
          }
        }
        elseif($param_item == 'email')
        {
          $email = $user_id == 1 ? csSettings::get('admin_email') : $user->getEmail();
          if($email && preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', $email))
          {
            $message_r = str_replace("\n", '<br>', $message);
            $mail_message = Swift_Message::newInstance()
              ->setFrom('noreply@' . sfConfig::get('app_lp_host'))
              ->setContentType('text/html; charset=UTF-8')
              ->setTo($email)
              ->setSubject('Сервис Врач РБ - новое уведомление')
              ->setBody($message_r . ($link ? '<br><a href="' . sfConfig::get('app_protocol') . '://' . $link . '">' . $link . '</a>' : ''))
            ;
            sfContext::getInstance()->getMailer()->send($mail_message);
          }
        }
      }
    }
  }
  static function noticeType($view)
  {
    $valid_arr = array(
      'message' => 'Новое сообщение',
      'closed' => 'Беседа закрыта',
      'resume' => 'Беседа возобновлена',
      'specialist_reception' => 'Приглашение на очный приём',
      'user_reception' => 'Просьба очного приёма',
      'please_analysis' => 'Добавлена анкета для анализов',
      'give_analysis' => 'Пациент прислал анализы',
      'reception_yes' => 'Согласие на очный приём',
      'reception_no' => 'Отказ от очного приёма',
      'review' => 'Новый отзыв',
      'consilium_specialist_add' => 'Вас добавили в консилиум',
      'consilium_specialist_delete' => 'Вас удалили из консилиума',
      'consilium_message' => 'Новое сообщение в консилиуме',
      'consilium_closed' => 'Консилиум закрыт',
      'consilium_resume' => 'Консилиум возобновлён',
      'question' => 'Новый вопрос',
      'email_reminder' => 'Напоминание на эл. почту'
    );

    $result = $valid_arr;
    if($view != 'list')
    {
      $result = $valid_arr[$view];
    }
    return $result;
  }
}
