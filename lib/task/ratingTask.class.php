<?php

class ratingTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = '';
    $this->name             = 'rating';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [rating|INFO] task does things.
Call it with:

  [php symfony rating|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $specialists = Doctrine_Query::create()
      ->select("s.*, q_count, p_count")
      ->from("Specialist s")
      ->addSelect("(SELECT COUNT(*) FROM QuestionSpecialist qs WHERE s.id = qs.specialist_id) AS q_count")
      ->addSelect("(SELECT COUNT(*) FROM Prompt p WHERE s.id = p.specialist_id) AS p_count")
      ->addSelect("(SELECT COUNT(*) FROM Article a WHERE s.id = a.specialist_id) AS a_count")
      ->where("s.id != 51")
      ->fetchArray()
    ;

    $data_arr = array();

    foreach ($specialists as $specialist_key => $specialist)
    {
      $data_arr[] = array(
        'id' => $specialist['id'],
        'q_count' => $specialist['q_count'],
        'p_count' => $specialist['p_count'],
        'a_count' => $specialist['a_count']
      );
      $sum = $specialist['q_count'] + $specialist['p_count'] + $specialist['a_count'];
      if($specialist_key == 0)
      {
        $max = $sum;
      }
      if($sum > $max)
      {
        $max = $sum;
      }
    }
    $max = $max != 0 ? $max : 9.1;
    $one = 9.1 / $max;
    foreach ($data_arr as $data_elem)
    {
      $rating = number_format(($one * ($data_elem['q_count'] + $data_elem['p_count'] + $data_elem['a_count'])), 1, '.', '');
      $specialist_change = Doctrine::getTable("Specialist")->find($data_elem['id']);
      if($specialist_change)
      {
        $specialist_change->setQuestionCount($data_elem['q_count']);
        $specialist_change->setPromptCount($data_elem['p_count']);
        $specialist_change->setArticleCount($data_elem['a_count']);
        $specialist_change->setAnswersCount($data_elem['q_count']);
        $specialist_change->setRating($rating);
        $specialist_change->save();
      }
    }
  }
}
