<?php

class answer_checkTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = '';
    $this->name             = 'answer_check';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [answer_check|INFO] task does things.
Call it with:

  [php symfony answer_check|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    sfContext::createInstance($this->configuration);

    $questions = Doctrine_Query::create()
      ->select("q.*, sp.*, s.*, answer_count")
      ->from("Question q")
      ->innerJoin("q.Specialists s")
      ->innerJoin("q.Specialtys sp")
      ->addSelect("(SELECT COUNT(*) FROM answer a WHERE a.question_id = q.id) AS answer_count")
      ->where("q.approved = 0")
      ->andWhere("q.closed_by IS NULL")
      ->andWhere("q.comment_id IS NULL OR q.comment_id = ''")
      ->andWhere("s.id != 51")
      ->groupBy("q.id")
      ->having("answer_count = 0")
      ->fetchArray()
    ;
    if(count($questions) > 0)
    {
      foreach ($questions as $question)
      {
        echo "\n" . $question['body'] . "\n";
        $new_trust_specialist = false;
        $black_list = Doctrine::getTable("Question_black_list")->findByQuestionId($question['id']);
        $difference = time() - strtotime($question['created_at'] != $question['updated_at'] ? $question['updated_at'] : $question['created_at']);
        if($difference > sfConfig::get('app_answer_check_time'))
        {







          $specialists = Doctrine::getTable("Specialist")->findBySpecialtyId($question['Specialtys'][0]['id']);






          $qs_change = Doctrine::getTable("QuestionSpecialist")->findOneByQuestionId($question['id']);
          if(!$qs_change)
          {
            $qs_change = new QuestionSpecialist();
            $qs_change->setQuestionId($question['id']);
            $qs_change->setSpecialistId($new_trust_specialist);
            $qs_change->save();
          }
          foreach ($black_list as $bl)
          {
            if($bl->getSpecialistId() == $question['Specialists'][0]['id'])
            {
              $repeat = true;
              break;
            }
          }
          if(!$repeat)
          {
            $new_question_bl = new Question_black_list();
            $new_question_bl->setQuestionId($question['id']);
            $new_question_bl->setSpecialistId($question['Specialists'][0]['id']);
            $new_question_bl->save();
          }
          if(count($specialists) > 0 && !$new_trust_specialist)
          {
            foreach ($specialists as $specialist)
            {
              if($qs_change->getSpecialistId() != $specialist->getId())
              {
                if(count($black_list) > 0)
                {
                  foreach ($black_list as $bl)
                  {
                    if($specialist->getId() != $bl->getSpecialistId())
                    {
                      $bl_specialist = true;
                    }
                  }
                  if(!$bl_specialist)
                  {
                    $new_trust_specialist = $specialist->getId();
                    break;
                  }
                }
                else
                {
                  $new_trust_specialist = $specialist->getId();
                  break;
                }
              }
            }
          }
          $new_trust_specialist = !$new_trust_specialist ? 51 : $new_trust_specialist;
          if($new_trust_specialist)
          {
            echo $new_trust_specialist . "\n";

            $qs_change->setSpecialistId($new_trust_specialist);
            $qs_change->save();


            $link = sfConfig::get('app_lp_host') . ($new_trust_specialist == 51 ? '/arm/question/' . $question['id'] . '/edit' : '/doctor-account/now-dialog/' . $question['id'] . '/');
            $message = 'Новый вопрос<br>' . $question['body'];
//            Notice::noticeSent($qs_change->getSpecialist()->getUserId(), array('email'), $message, $link);
          }
        }
      }
    }
  }
}
