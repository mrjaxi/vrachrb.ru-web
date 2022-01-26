<?php
class apiActions extends sfActions
{
    public function executeIs_auth($request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if ($this->getUser()->isAuthenticated())
        {
            $response = array(
                "response" => "Вы авторизованы"
            );
        } else {
            $response = array(
                "response" => "Вы не авторизованы"
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeSignOut($request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        $this->getUser()->signOut();
        if (!$this->getUser()->isAuthenticated())
        {
            $response = array(
                "response" => "Вы вышли из аккаунта"
            );
        } else {
            $response = array(
                "error" => "Что-то пошло не так..."
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeSignIn(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('post'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }

        $username = $request->getPostParameter("user");
        $password = $request->getPostParameter("password");

        $usernameDoctrine = Doctrine::getTable('User')->findOneByUsernameOrEmail($username, $username);

        if ($usernameDoctrine){
            $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
            $encrypted = call_user_func_array($algorithm, array($usernameDoctrine->get("salt") . $password));

            if ($encrypted == $usernameDoctrine->get("password")){
                $this->getUser()->signIn($usernameDoctrine, 1);

                $response = array(
                    "first_name" => $usernameDoctrine->get("first_name"),
                    "second_name"=> $usernameDoctrine->get("second_name"),
                    "middle_name"=> $usernameDoctrine->get("middle_name"),
                    "username"   => $usernameDoctrine->get("username"),
                    "gender"     => $usernameDoctrine->get("gender"),
                    "birth_date" => $usernameDoctrine->get("birth_date"),
                    "email"      => $usernameDoctrine->get("email"),
                    "phone"      => $usernameDoctrine->get("phone"),
                    "photo"      => $usernameDoctrine->get("photo"),
                );

            } else {
                $response = array(
                    "error" => "Неверное имя пользовтеля или пароль"
                );
            }
        } else {
            $response = array(
                "error" => "Неверное имя пользовтеля или пароль"
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeRegister(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');

        if (!$request->isMethod('post'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }

        try {
            $params = $request->getPostParameters();

            $checkUser = Doctrine::getTable('User')->findOneBy('email', $params['email']);
            // Если нашел такого пользователя
            if ($checkUser) {
                return $this->renderText(json_encode(array(
                    "error" => "Такой пользователь уже существует",
                )));
            }

            $rndPasswordCheck = Page::generateUuid(32);
            $salt = sha1(uniqid(mt_rand(), true));
            $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
            $encryptedPass = call_user_func_array($algorithm, array($salt . $params['password']));

            $user = [
                'username' => $params['email'],
                'first_name' => $params['name'],
                'second_name' => $params['familia'],
                'middle_name' => $params['last_name'],
                'gender' => $params['gender'],
                'birth_date' => $params['birth_date'],
                'email' => $params['email'],
                'phone' => $params['phone'],
                'salt' => $salt,
                'password' => $encryptedPass,
//                'photo' => '',
//                'last_login' => '',
                'password_check' => $rndPasswordCheck,
            ];

            $newUser = Doctrine::getTable('User')->create($user);
            $newUser->save();

            $this->getUser()->signIn($newUser, 1);

            $response = array(
                'response' => "Успешная регистрация",
            );
        } catch (Exception $e) {
            $response = array(
                "error" => $e->getMessage(),
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeGet_user_questions(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated())
        {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
        $myUserId = $this->getUser()->getAccount()->getId();

        $question_user = Doctrine_Query::create()
            ->select("u.username, uq.id, uq.body, uq.closed_by, qa.*, qs.*, uqs.*, qsu.*")
            ->from("User u")
            ->innerJoin("u.Question uq")
            ->innerJoin("uq.Answer qa")
            ->innerJoin("uq.Specialists qs")
            ->innerJoin("qs.User qsu")
            ->innerJoin("uq.Specialtys uqs")
            ->where("u.id = $myUserId")
            ->fetchArray();

        return $this->renderText(json_encode(array(
            "response" => $question_user
        )));
    }

    public function executeGet_specialist_questions(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated())
        {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
        $myUserId = $this->getUser()->getAccount()->getId();

        $question_user = Doctrine_Query::create()
            ->select("s.*, su.*, sq.*")
            ->from("Specialist s")
            ->innerJoin("s.User su")
            ->innerJoin("s.Questions sq")
            ->innerJoin("sq.Answer sqa")
            ->where("su.id = $myUserId")
            ->fetchArray();

        return $this->renderText(json_encode(array(
            "response" => $question_user
        )));
    }

    public function executeSendMessage(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('post'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }

        if (!$this->getUser()->isAuthenticated())
        {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }

        $user_id = $request->getPostParameter('user_id');
        $question_id = $request->getPostParameter('question_id');
        $body = $request->getPostParameter('body');
        if(!$user_id || !$question_id || !$body){
            return $this->renderText(json_encode(array(
                "error" => "Введите параметры 'user_id', 'question_id', 'body'"
            )));
        }

        $answer = new Answer();
        $answer->setUserId($user_id)
            ->setQuestionId($question_id)
            ->setBody($body)
            ->save();

        return $this->renderText(json_encode(array(
            "response" => "Ответ успешно добавлен"
        )));
    }

    public function executeGetSpecialistBySpecialtyID(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }

        if (!$this->getUser()->isAuthenticated())
        {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }

        $specialtyId = $request->getGetParameter('specialtyId');

        $specialists = Doctrine_Query::create()
            ->select("s.rating, s.answers_count, s.about, u.first_name, u.second_name, u.middle_name")
            ->from("Specialist s")
            ->innerJoin("s.User u ON u.id = s.user_id and s.specialty_id = $specialtyId")
            ->fetchArray();

        return $this->renderText(json_encode(
            array(
                "response" => $specialists
            )
        ));
    }

    public function executeSpecialist(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if ($this->getUser()->isAuthenticated()){
            $q = Doctrine_Query::create()
                ->select('s.rating, s.answers_count, s.about, u.first_name, u.second_name, u.middle_name')
                ->from('Specialist s')
                ->leftJoin('s.User u ON u.id = s.user_id')
                ->fetchArray();

            $response = array(
                "response" => $q
            );
        } else {
            $response = array(
                "error" => "Для использования метода нужно авторизоваться"
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeGetCabinet(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if ($this->getUser()->isAuthenticated()){
            $allSpeciality = Doctrine_Core::getTable('Specialty')->findAll();
            $response = array(
                "response" => $allSpeciality->toArray()
            );
        } else {
            $response = array(
                "error" => "Для использования метода нужно авторизоваться"
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeGet_anamnes(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');
        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }

        $spec_id = $request->getGetParameter('spec_id');
        if(!$spec_id){
            return $this->renderText(json_encode(array(
                "error" => "Введите параметр 'spec_id'"
            )));
        }

        if ($this->getUser()->isAuthenticated()){
            $CurrentConnection = Doctrine_Manager::getInstance()->getCurrentConnection();
            $anamneses = Doctrine_Query::create()
                ->select('sh.*, shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required')
                ->from("SheetHistory sh")
                ->innerJoin("sh.SheetHistorySpecialty shs")
                ->innerJoin("sh.SheetHistoryField shf")
                ->where("shs.specialty_id = $spec_id")
                ->fetchArray();
            $anamneses = $anamneses[0]["SheetHistoryField"];

            if($anamneses){
                $response = array(
                    "response" => $this->unserializeAnamneses($anamneses)
                );
            } else {
                $common = Doctrine_Query::create()
                    ->select('shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required')
                    ->from("SheetHistoryField shf")
                    ->where("shf.sheet_history_id = 1")
                    ->fetchArray();
                $response = array(
                    "common" => "Общий",
                    "response" => $this->unserializeAnamneses($common)
                );
            }

        } else {
            $response = array(
                "error" => "Для использования метода нужно авторизоваться"
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeAsk_question(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');
        if (!$request->isMethod('post'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
        $q_user_id       = $this->getUser()->getAccount()->getId();

        // Пример json в qsh_anamnes: [{"sh_field":"137","val":"test"},{"sh_field":"138","val":"test"},{"sh_field":"139","val":"test","file":""},{"sh_field":"140","bool":"Нет","val":""},{"sh_field":"141","bool":"Нет","val":"ТЕКСТ и да нет"},{"sh_field":"142","choices":["список"]},{"sh_field":"143","choices":{"1":"без","2":"выбора"}}]
        $qsh_anamnes     = json_decode($request->getPostParameter('qsh_anamnes'), true);
        $q_body          = $request->getPostParameter('q_body');
        $q_specialist_id = $request->getPostParameter('q_specialist_id');
        $q_specialty_id  = $request->getPostParameter('q_specialty_id');
        $user_about      = json_decode($request->getPostParameter('user_about'), true);

        if(!$q_body || !$qsh_anamnes || !$q_specialist_id || !$q_specialty_id){
            return $this->renderText(json_encode(array(
                "error" => "Введите все параметры: 'q_body','qsh_anamnes','q_specialist_id','q_specialty_id'"
            )));
        }

        $question = new Question();
        $question->setUserId($q_user_id)
            ->setBody($q_body)
            ->setVkNotice(false);
        if($user_about){
            $username = implode(' ', array($user_about["first_name"], $user_about["second_name"], $user_about["middle_name"]));
            $checkUser = Doctrine::getTable('User')->findOneByUsernameOrEmail($username, $username);
            if(!$checkUser) {
                $user = [
                    'username' => $username,
                    'first_name' => $user_about["first_name"],
                    'second_name' => $user_about["second_name"],
                    'middle_name' => $user_about["middle_name"],
                    'gender' => $user_about['gender'],
                    'is_active' => 0
                ];
                $newUser = Doctrine::getTable('User')->create($user);
                $newUser->save();
                $question->setUserAboutId($newUser->get('id'));
            } else {
                $question->setUserAboutId($checkUser->get('id'));
            }
        }
        $question->save();

        $idQuestion = Doctrine_Query::create()
            ->select('q.id')
            ->from('Question q')
            ->orderBy('q.id DESC')
            ->limit(1)
            ->fetchArray()[0]["id"];

        $valuesQSH = $this->buildValueQSH($idQuestion, $qsh_anamnes);
        for($i = 0;$i < count($valuesQSH);$i++){
            $this->insertFromTable('QuestionSheetHistory',$valuesQSH[$i]);
        }

        $question_specialist = new QuestionSpecialist();
        $question_specialist->setQuestionId($idQuestion)
            ->setSpecialistId($q_specialist_id)
            ->save();

        $question_specialist = new QuestionSpecialty();
        $question_specialist->setQuestionId($idQuestion)
            ->setSpecialtyId($q_specialty_id)
            ->save();


        return $this->renderText(json_encode(
            $response = array(
                "response" => 'Вопрос успешно задан'
            )
        ));
    }

    public function buildValueQSH($idQuestion, $val){
        $values = array();
        for($i = 0; $i < count($val); $i++){
            $values[$i] = array(
                'question_id'            => $idQuestion,
                'sheet_history_field_id' => $val[$i]['sh_field'],
                'value'                  => $this->clearValues($val[$i])
            );
        }
        return $values;
    }

    public function clearValues($values){
        unset($values['sh_field']);
        return json_encode($values);
    }

    public function unserializeAnamneses($anamneses){
        for($i = 0;$i < count($anamneses);$i++){
            $anamneses[$i]["field_options"] = unserialize($anamneses[$i]["field_options"]);
        }
        return $anamneses;
    }

    public function insertFromTable($tableName, array $values){
        $CurrentConnection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $Table = Doctrine_Core::getTable($tableName);

        $CurrentConnection->insert($Table,$values);
    }

    public function executeTest(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');


        return $this->renderText(json_encode(
            $response = array(
                "test" => "test"
            )
        ));
    }
//    var_dump($this->getUser()->getAccount()->getUsername());
}