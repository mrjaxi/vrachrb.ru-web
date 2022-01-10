<?php
class frameActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->setLayout('layout_frame');
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $this->live_ribbons = $conn->execute("(SELECT p.id AS id, p.specialist_id AS sp_id, p.title AS title, p.description AS p_description_q_body, CONCAT_WS(' ', u.first_name, u.middle_name, u.second_name) AS sp_name, s.about AS sp_about, NULL AS q_gender, NULL AS q_birth_date, p.created_at, NULL AS q_name, 'p' AS type
      FROM prompt p INNER JOIN specialist s INNER JOIN user u
      WHERE s.id = p.specialist_id AND s.user_id = u.id ORDER BY created_at DESC)

      UNION

      (SELECT q.id AS id, qs.specialist_id AS sp_id, NULL AS title, q.body AS p_description_q_body, NULL AS sp_name, s.about AS sp_about, u.gender AS q_gender, u.birth_date AS q_birth_date, q.created_at, CONCAT_WS(' ', su.first_name, su.middle_name, su.second_name) AS q_name, 'q' AS type
        FROM question q INNER JOIN question_specialist qs INNER JOIN user u INNER JOIN specialist s INNER JOIN user su
      WHERE q.id = qs.question_id AND u.id = q.user_id AND su.id = s.user_id AND qs.specialist_id = s.id AND q.approved = 1 ORDER BY created_at DESC)
      ORDER BY created_at DESC LIMIT 4;");
  }
}
