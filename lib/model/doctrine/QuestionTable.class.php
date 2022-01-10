<?php


class QuestionTable extends Doctrine_Table
{

  public static function getInstance()
  {
    return Doctrine_Core::getTable('Question');
  }
  public function retrieveQuestionList(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    $q->leftJoin($rootAlias . ".QuestionSpecialist qs");
    $q->where("qs.specialist_id = 51");
//    $q->groupBy($rootAlias . ".id");
//    $q->having("COUNT(qs.specialist_id) = 0");
    return $q;
  }
}