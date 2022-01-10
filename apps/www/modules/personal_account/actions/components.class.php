<?php
class personal_accountComponents extends sfComponents
{
  public function executeMenu(sfWebRequest $request)
  {
    $menu = array(
      '@personal_account_index' => array('title' => 'Текущие беседы'),
      '@personal_account_patient_card' => array('title' => 'Амбулаторная карта'),
      '@personal_account_data' => array('title' => 'Личные данные'),
      '@personal_account_feedback' => array('title' => 'Обратная связь')
    );
    $this->items = $menu;

    //$this->question_count = Doctrine::getTable("Question")->findByUserId($this->getUser()->getAccount()->getId())->count("*");
    $this->question_count = Doctrine_Query::create()
      ->select("COUNT(*) AS question_count")
      ->from("Question q")
      ->where("q.user_id = ?", $this->getUser()->getAccount()->getId())
      ->andWhere('q.closing_date >= DATE(?)', gmdate("Y-m-d H:i:s", time() - sfConfig::get('app_waiting_time_patient_card')))
      ->count();
    $this->patient_card_count = Doctrine_Query::create()
      ->select("COUNT(*) AS patient_card_count")
      ->from("Question q")
      ->where("q.closed_by != '' AND q.user_id = " . $this->getUser()->getAccount()->getId())
      ->fetchOne();
  }
  public function executeNow_dialog(sfWebRequest $request)
  {
    if(($request->getParameter('id') && is_numeric($request->getParameter('id'))) || ($this->q_id && is_numeric($this->q_id)))
    {
      $question_id = $request->getParameter('id') ? $request->getParameter('id') : $this->q_id;
      
      $this->form = new CreateAnswerForm();
      $this->notice_form = new CreateNoticeCsrfForm();
      $this->analysis_form = new CreateAnalysisForm();

      $conn = Doctrine_Manager::getInstance()->getCurrentConnection();

      $this->questions = $conn->execute("(SELECT a.attachment AS a_attachment, NULL AS ua_info, CONCAT_WS(':', an.id, an.analysis_type_id, an.photo) AS an_info, a.id AS a_id, a.user_id AS user_id, a.question_id AS question_id, NULL AS r_id, a.body AS body, a.type AS a_type, NULL AS is_anonymous, NULL AS specialist_id, NULL AS price, NULL AS is_activated, NULL AS is_reject, NULL AS reject_reason, 'a' AS mode, a.created_at AS created_at,
  NULL AS user_name, NULL AS user_b_date, NULL AS user_g, NULL AS s_about, NULL AS q_closed_by, NULL AS q_closing_date, NULL AS specialist_user_id, NULL AS s_title_url, NULL AS specialist_name, NULL AS specialist_photo, NULL AS r_location, NULL AS r_datetime, NULL AS review
  FROM answer a INNER JOIN user u ON a.user_id = u.id LEFT JOIN specialist s ON u.id = s.user_id LEFT JOIN analysis an ON a.id = an.answer_id
  WHERE a.question_id = " . $question_id . " AND a.body != 'email_reminder' ORDER BY created_at)

  UNION

  (SELECT NULL AS a_attachment, NULL AS ua_info, NULL AS an_info, NULL AS a_id, rc.user_id AS user_id, rc.question_id AS question_id, rc.id AS r_id, NULL AS body, rc.id AS a_type, NULL AS is_anonymous, rc.specialist_id AS specialist_id, rc.price AS price, rc.is_activated AS is_activated, rc.is_reject AS is_reject, rc.reject_reason AS reject_reason, 'r' AS mode, rc.created_at AS created_at,
  NULL AS user_name, NULL AS user_b_date, NULL AS user_g, NULL AS s_about, NULL AS q_closed_by, NULL AS q_closing_date, NULL AS specialist_user_id, NULL AS s_title_url, NULL AS specialist_name, NULL AS specialist_photo, swp.title AS r_location, rd.datetime AS r_datetime, NULL AS review
  FROM reception_contract rc INNER JOIN receive_datetime rd ON rc.id = rd.reception_id INNER JOIN receive_location rl ON rc.id = rl.reception_id INNER JOIN specialist_work_place swp ON rl.work_place_id = swp.id
  WHERE rc.question_id = " . $question_id . " ORDER BY created_at)

  UNION

  (SELECT NULL AS a_attachment, CONCAT_WS('_', ua.username, ua.birth_date, ua.gender) AS ua_info, NULL AS an_info, NULL AS a_id, q.user_id AS user_id, q.id AS question_id, NULL AS r_id, q.body AS body, NULL AS a_type, q.is_anonymous AS is_anonymous, qs.specialist_id AS specialist_id, NULL AS price, NULL AS is_activated, NULL AS is_reject, NULL AS reject_reason, 'q' AS mode, q.created_at AS created_at,
CONCAT_WS(' ', qu.first_name, qu.second_name) AS user_name, qu.birth_date AS user_b_date, qu.gender AS user_g, s.about AS s_about, q.closed_by AS q_closed_by, q.closing_date AS q_closing_date,
u.id AS specialist_user_id, s.title_url AS s_title_url, CONCAT_WS(' ', u.first_name, u.second_name) AS specialist_name, u.photo AS specialist_photo, NULL AS r_location, NULL AS r_datetime, r.id AS review
  FROM question q INNER JOIN question_specialist qs ON q.id = qs.question_id INNER JOIN specialist s ON qs.specialist_id = s.id INNER JOIN user u ON s.user_id = u.id INNER JOIN user qu ON q.user_id = qu.id LEFT JOIN review r ON r.question_id = q.id LEFT JOIN user ua ON ua.id = q.user_about_id
  WHERE q.id = " . $question_id . "
  ) ORDER BY created_at ASC
");
    }
  }
  public function executeNow_dialog_list(sfWebRequest $request)
  {
    $this->questions = Doctrine_Query::create()
      ->select("q.*, qu.*, qs.*, a.*, review_count, close_question_count")
      ->from("Question q")
      ->innerJoin("q.User qu")
      ->leftJoin("q.Answer a")
      ->leftJoin("q.Specialists qs")
      ->addSelect("(SELECT COUNT(*) FROM review r WHERE r.user_id = " . $this->getUser()->getAccount()->getId() . ") AS review_count")
      ->addSelect("(SELECT COUNT(*) FROM question qr WHERE qr.user_id = " . $this->getUser()->getAccount()->getId() . " AND qr.closed_by != '') AS close_question_count")
      ->orderBy("q.created_at DESC, a.created_at DESC")
      ->where("q.user_id = " . $this->getUser()->getAccount()->getId());
    if (isset($this->filter_pa_list))
    {
      if ($this->filter_pa_list == 'open')
      {
        $this->questions->andWhere('q.closed_by IS NULL');
      }
      if ($this->filter_pa_list == 'close')
      {
        $this->questions->andWhere('q.closed_by IS NOT NULL');
      }
    }
    $this->questions = $this->questions->fetchArray();
  }
  public function executeReview_form(sfWebRequest $request)
  {
    $this->review_form = new CreateReviewForm();
  }
}