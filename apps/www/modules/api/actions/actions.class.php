<?php
class apiActions extends sfActions
{
    public function executeIs_auth($request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        $myUser = $this->getUser()->getAccount();
        if ($this->getUser()->isAuthenticated())
        {
            if($this->isSpecialist($myUser->getId())){
                $specialist = $this->getSpecialist($myUser->getId());

                $response = array(
                    "response" => true,
                    "userData" => array(
                        "auth" => true,
                        "isSpecialist" => $this->isSpecialist($myUser->getId()),
                        "first_name" => $myUser->getFirst_name(),
                        "second_name" => $myUser->getSecond_name(),
                        "middle_name" => $myUser->getMiddle_name(),
                        "username" => $myUser->getUsername(),
                        "gender" => $myUser->getGender(),
                        "birth_date" => $myUser->getBirth_date(),
                        "email" => $myUser->getEmail(),
                        "phone" => $myUser->getPhone(),
                        "photo" => $myUser->getPhoto(),
                        "rating" => $specialist["rating"],
                        "answers_count" => $specialist["answers_count"],
                    ),
                );
            } else {
                $response = array(
                    "response" => true,
                    "userData" => array(
                        "auth" => true,
                        "isSpecialist" => $this->isSpecialist($myUser->getId()),
                        "first_name" => $myUser->getFirst_name(),
                        "second_name" => $myUser->getSecond_name(),
                        "middle_name" => $myUser->getMiddle_name(),
                        "username" => $myUser->getUsername(),
                        "gender" => $myUser->getGender(),
                        "birth_date" => $myUser->getBirth_date(),
                        "email" => $myUser->getEmail(),
                        "phone" => $myUser->getPhone(),
                        "photo" => $myUser->getPhoto(),
                    ),
                );
            }
        } else {
            $response = array(
                "response" => false,
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

    public function executeSign_vk(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');

        if (!$request->isMethod('post')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }

        $data = $this->getPostData();
        if ($data) {
            $user_id = $data["user_id"];
            $access_token = $data["access_token"];
            $email = $data["email"];
            $agreement = (boolean) $data["agreement"];
        } else {
            $user_id = $request->getPostParameter("user_id");
            $access_token = $request->getPostParameter("access_token");
            $email = $request->getPostParameter("email");
            $agreement = (boolean) $request->getPostParameter("agreement");
        }
        if(!$user_id || !$access_token || !$email){
            return $this->renderText(json_encode(array(
                "error" => "Введите параметры 'user_id', 'access_token', 'email'"
            )));
        }

        $str = 'https://api.vk.com/method/users.get?user_ids=' . $user_id .
            '&fields=first_name,last_name,photo,sex,email,bdate' .
            '&access_token=' . $access_token .
            '&v=5.131';
        $s = file_get_contents($str);
        $json = json_decode($s, true);

        if ($json['error']) {
            return $this->renderText(json_encode(array(
                "error" => $json['error']['error_msg'],
            )));
        }
        $json = $json['response'][0];

        $identitys = array(
            'http://vk.com/id' . $json["id"],
            'https://vk.com/id' . $json["id"],
        );

        $user = Doctrine_Query::create()
            ->select("u.*")
            ->from("User u")
            ->whereIn("u.username", $identitys)
            ->orderBy("(SELECT COUNT(s.id) FROM Specialist s WHERE s.user_id = u.id) DESC")
            ->fetchOne();

        if (!$user) {
            $user = new User();
            $user->setUsername($identitys[1]);
            $user->setFirstName($json['first_name'] ? $json['first_name'] : "");
            $user->setSecondName($json['last_name'] ? $json['last_name'] : "");
            $user->setMiddleName("");
            if ($json['sex'] != 0 && $json['sex'] != '') {
                $user->setGender($json['sex'] == 1 ? 'ж' : 'м');
            }
            $user->setBirthDate(isset($json['bdate']) ? implode(".", array_reverse(explode(".", $json['bdate']))) : rand(1960, date('Y')) . ':' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT) . ' 00:00:00');
            $email != "null" ? $user->setEmail($email) : '';

            $user->save();
        }
        if (Agreement::agreementCheck($user->getId()) && !$agreement) {
            return $this->renderText(json_encode(array(
                "agreement" => "Нужно принять соглашения"
            )));
        }

        if (Agreement::agreementCheck($user->getId())) {
            // Принятие соглашений
            $agreements = Doctrine_Query::create()
                ->select('a.id')
                ->from('Agreement a')
                ->fetchArray();

            for($i = 0;$i < count($agreements);$i++){
                $a_complete = new AgreementComplete();
                $a_complete->setUserId($user->get('id'))
                    ->setAgreementId($agreements[$i]['id'])
                    ->save();
            }
        }

        $this->getUser()->signIn($user, 1);

        if($this->isSpecialist($user->getId())){
            $specialist = $this->getSpecialist($user->getId());

            return $this->renderText(json_encode(array(
                "auth" => true,
                "userData" => array(
                    "auth" => true,
                    "isSpecialist" => $this->isSpecialist($user->getId()),
                    "first_name"   => $user->getFirst_name(),
                    "second_name"  => $user->getSecond_name(),
                    "middle_name"  => $user->getMiddle_name(),
                    "username"     => $user->getUsername(),
                    "gender"       => $user->getGender(),
                    "birth_date"   => $user->getBirth_date(),
                    "email"        => $user->getEmail(),
                    "phone"        => $user->getPhone(),
                    "photo"        => $user->getPhoto(),
                    "rating"       => $specialist["rating"],
                    "answers_count" => $specialist["answers_count"],
                ),
            )));
        } else {
            return $this->renderText(json_encode(array(
                "auth" => true,
                "userData" => array(
                    "auth" => true,
                    "isSpecialist" => $this->isSpecialist($user->getId()),
                    "first_name"   => $user->getFirst_name(),
                    "second_name"  => $user->getSecond_name(),
                    "middle_name"  => $user->getMiddle_name(),
                    "username"     => $user->getUsername(),
                    "gender"       => $user->getGender(),
                    "birth_date"   => $user->getBirth_date(),
                    "email"        => $user->getEmail(),
                    "phone"        => $user->getPhone(),
                    "photo"        => $user->getPhoto(),
                ),
            )));
        }
    }

    public function executeSignIn(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('post')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }

        $data = $this->getPostData();
        if($data) {
            $username = $data["user"];
            $password = $data["password"];
        } else {
            $username = $request->getPostParameter("user");
            $password = $request->getPostParameter("password");
        }

        $usernameDoctrine = Doctrine::getTable('User')->findOneByUsernameOrEmail($username, $username);

        if ($usernameDoctrine){
//            var_dump("нашел пользователя");
            $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
            $encrypted = call_user_func_array($algorithm, array($usernameDoctrine->get("salt") . $password));

            if ($encrypted == $usernameDoctrine->get("password")) {
                $this->getUser()->signIn($usernameDoctrine, 1);

                if($this->isSpecialist($usernameDoctrine->get("id"))) {
                    $specialist = $this->getSpecialist($usernameDoctrine->get("id"));

                    $response = array(
                        "auth" => true,
                        "isSpecialist" => $this->isSpecialist($usernameDoctrine->get("id")),
                        "first_name" => $usernameDoctrine->get("first_name"),
                        "second_name" => $usernameDoctrine->get("second_name"),
                        "middle_name" => $usernameDoctrine->get("middle_name"),
                        "username" => $usernameDoctrine->get("username"),
                        "gender" => $usernameDoctrine->get("gender"),
                        "birth_date" => $usernameDoctrine->get("birth_date"),
                        "email" => $usernameDoctrine->get("email"),
                        "phone" => $usernameDoctrine->get("phone"),
                        "photo" => $usernameDoctrine->get("photo"),
                        "rating" => $specialist["rating"],
                        "answers_count" => $specialist["answers_count"],

                    );
                } else {
                    $response = array(
                        "auth" => true,
                        "isSpecialist" => $this->isSpecialist($usernameDoctrine->get("id")),
                        "first_name" => $usernameDoctrine->get("first_name"),
                        "second_name" => $usernameDoctrine->get("second_name"),
                        "middle_name" => $usernameDoctrine->get("middle_name"),
                        "username" => $usernameDoctrine->get("username"),
                        "gender" => $usernameDoctrine->get("gender"),
                        "birth_date" => $usernameDoctrine->get("birth_date"),
                        "email" => $usernameDoctrine->get("email"),
                        "phone" => $usernameDoctrine->get("phone"),
                        "photo" => $usernameDoctrine->get("photo"),
                    );
                }

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
            $params = $this->getPostData();
            if(!$params){
                $params = $request->getPostParameters();
            }

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
            // Принятие соглашений
            $agreements = Doctrine_Query::create()
                ->select('a.id')
                ->from('Agreement a')
                ->fetchArray();

            for($i = 0;$i < count($agreements);$i++){
                $a_complete = new AgreementComplete();
                $a_complete->setUserId($newUser->get('id'))
                    ->setAgreementId($agreements[$i]['id'])
                    ->save();
            }

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
            ->select("u.username, uq.id, uq.body, uq.updated_at, uq.created_at, uq.user_id, uq.closed_by, uq.is_anonymous,
                    uqs.title, qs.id, qs.user_id as specialist_id, qsu.first_name, qsu.second_name, qsu.middle_name, qsu.photo")
            ->from("User u")
            ->innerJoin("u.Question uq")
            ->innerJoin("uq.Specialtys uqs")
            ->innerJoin("uq.Specialists qs")
            ->innerJoin("qs.User qsu")
            ->where("u.id = $myUserId")
            ->orderBy("uq.id DESC")
            ->fetchArray();

        if(!$question_user){
            return $this->renderText(json_encode(array(
                "error" => "Нет чатов"
            )));
        }

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
            ->select("s.user_id,
                sq.body, sq.user_id, sq.closed_by, sq.updated_at, sq.created_at, sq.is_anonymous,
                squ.first_name, squ.second_name, squ.middle_name, squ.photo")
            ->from("Specialist s")
            ->innerJoin("s.Questions sq")
            ->innerJoin("sq.User squ")
            ->where("s.user_id = $myUserId")
            ->orderBy("sq.id DESC")
            ->fetchArray();

        if(!$question_user){
            return $this->renderText(json_encode(array(
                "error" => "Нет чатов"
            )));
        }

        return $this->renderText(json_encode(array(
            "response" => $question_user
        )));
    }

    public function executeGet_answers_by_questionid(sfWebRequest $request)
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

        $questionId = $request->getGetParameter("question_id");

        $question_user = Doctrine_Query::create()
            ->select("q.id, q.user_id, q.is_anonymous, q.body, q.closed_by, q.created_at, 
            qa.user_id, qa.body, qa.attachment, qa.created_at")
            ->from("Question q")
            ->leftJoin("q.Answer qa")
            ->where("q.id = $questionId")
            ->orderBy("qa.created_at DESC")
            ->fetchArray();
        $question_user[0]['my_id'] = $myUserId;

        // Очищение уведомлений чата
        $notice = Doctrine_Query::create()
            ->select("n.*")
            ->from("Notice n")
            ->where("n.user_id = " . $myUserId . " AND n.type = 'dialog' AND n.inner_id = " . $questionId)
            ->execute();
        if(count($notice) > 0)
        {
            foreach ($notice as $n)
            {
                $n->delete();
            }
        }

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
        $user_id = $this->getUser()->getAccount()->getId();

        $data = $this->getPostData();
        if($data){
            $question_id = $data['question_id'];
            $body        = $data['body'];
            if($data['attachment'])
                $attachment  = $data['attachment'];
            else
                $attachment = "";
        } else {
            $question_id = $request->getPostParameter('question_id');
            $body        = $request->getPostParameter('body');
            if($request->getPostParameter('attachment'))
                $attachment = $request->getPostParameter('attachment');
            else
                $attachment = "";
        }

        $question = Doctrine::getTable('Question')->findOneBy("id", $question_id);
        if($question["closed_by"]){
            return $this->renderText(json_encode(array(
                "error" => "Вопрос уже закрыт"
            )));
        }

        if(!$question_id || !$body){
            return $this->renderText(json_encode(array(
                "error" => "Введите параметры 'question_id', 'body', при желании 'attachment'"
            )));
        }

        $answer = new Answer();
        $answer->setUserId($user_id)
            ->setQuestionId($question_id)
            ->setBody($body)
            ->setType("")
            ->setAttachment($attachment)
            ->save();

        $isSpecialist = $this->isSpecialist($user_id);

        if(!$question["approved"] && $isSpecialist){
            $question["approved"] = true;
            $question->save();
        }

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
            ->select('s.specialty_id, s.rating, s.answers_count, s.about, u.first_name, u.second_name, u.middle_name, u.photo')
            ->from("Specialist s")
            ->innerJoin("s.User u ON u.id = s.user_id and s.specialty_id = $specialtyId")
            ->fetchArray();

        return $this->renderText(json_encode(
            array(
                "response" => $specialists
            )
        ));
    }

    public function executeGetSpecialists(sfWebRequest $request)
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
                ->select('s.specialty_id, s.rating, s.answers_count, s.about, u.first_name, u.second_name, u.middle_name, u.photo')
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

    public function executeGetAgreements(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if (!$request->isMethod('get'))
        {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }

        $agreements = Doctrine_Query::create()
            ->select('a.id, a.description, a.in_documentation')
            ->from('Agreement a')
            ->fetchArray();

        $host = $request->getHost();
        for($i = 0;$i < count($agreements);$i++){
            if($agreements[$i]['in_documentation'] == true){
                $agreements[$i]['url'] = "http://{$host}/agreement/{$agreements[$i]["id"]}/";
            }
        }

        return $this->renderText(json_encode(
            $response = array(
                "response" => $agreements
            )
        ));
    }

    public function executeGet_anamnes(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        if (!$request->isMethod('get')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
//        $data = json_decode(file_get_contents('php://input'), true);
//        $spec_id = $data["spec_id"];
        $spec_id = $request->getGetParameter('spec_id');
        if (!$spec_id) {
            return $this->renderText(json_encode(array(
                "error" => "Введите параметр 'spec_id'"
            )));
        }

        $anamneses = Doctrine_Query::create()
            ->select('sh.*, shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required')
            ->from("SheetHistory sh")
            ->innerJoin("sh.SheetHistorySpecialty shs")
            ->innerJoin("sh.SheetHistoryField shf")
            ->where("shs.specialty_id = $spec_id")
            ->orderBy("shf.order_field ASC")
            ->fetchArray();
        $anamneses = $anamneses[0]["SheetHistoryField"];

        if ($anamneses) {
            $response = array(
                "response" => $this->unserializeAnamneses($anamneses)
            );
        } else {
            $common = Doctrine_Query::create()
                ->select('shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required')
                ->from("SheetHistoryField shf")
                ->where("shf.sheet_history_id = 1")
                ->orderBy("shf.order_field ASC")
                ->fetchArray();
            $response = array(
                "common" => "Общий",
                "response" => $this->unserializeAnamneses($common)
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeGet_question_anamnes(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        if (!$request->isMethod('get')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
        $question_id = $request->getGetParameter('question_id');
        if (!$question_id) {
            return $this->renderText(json_encode(array(
                "error" => "Введите параметр 'question_id'"
            )));
        }

        $anamneses = Doctrine_Query::create()
            ->select('q.id, qsh.value, shf.title')
            ->from("Question q")
            ->innerJoin("q.QuestionSheetHistory qsh")
            ->innerJoin("qsh.SheetHistoryField shf")
            ->where("q.id = $question_id")
            ->fetchArray();
        $anamneses = $anamneses[0]["QuestionSheetHistory"];

        for($i = 0; $i < count($anamneses);$i++){
            $anamneses[$i]['title'] = $anamneses[$i]['SheetHistoryField']['title'];
            unset($anamneses[$i]['SheetHistoryField']);
        }

        $response = array(
            "response" => $anamneses
        );

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeGet_patient_card(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        if (!$request->isMethod('get')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только GET запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }
        $user_id = $request->getGetParameter('user_id');
        if (!$user_id) {
            return $this->renderText(json_encode(array(
                "error" => "Введите параметр 'user_id'"
            )));
        }

        $patient_card = Doctrine_Query::create()
            ->select("q.*, s.*, sp.*, s.id AS specialist_id, s.user_id AS specialist_user_id, u.first_name, u.middle_name, u.second_name, u.photo AS specialist_photo, s.about AS specialist_about, last_answer")
            ->from("Question q")
            ->innerJoin("q.Specialists s")
            ->innerJoin("s.Specialty sp")
            ->innerJoin("s.User u")
            ->where("q.user_id = " . $user_id . " AND q.closed_by != ''")
            ->addSelect(" (SELECT CONCAT_WS(':division:', a.body, a.user_id) FROM answer a WHERE a.question_id = q.id LIMIT 1 ORDER BY a.id DESC) AS last_answer")
            ->orderBy("q.closing_date DESC")
            ->fetchArray();

        if(!$patient_card){
            $response = array(
                "error" => "Нет карты"
            );
        } else {
            $response = array(
                "response" => $patient_card
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeRecover_password(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');
        if ($request->isMethod('post')) {
            $data = $this->getPostData();
            if($data)
                $email = $data["email"];
            else
                $email = $request->getPostParameter('email');

            if ($email != '') {
                $user = Doctrine::getTable('User')->findOneByEmail($email);
                if ($user) {
                    if ($user->getIsActive() == 1) {
                        $sha = ProjectUlils::generateUuid();

                        $user->setPasswordCheck($sha);
                        $user->save();

                        $message = Swift_Message::newInstance()
                            ->setFrom('noreply@vrachrb.ru') //. str_replace('www.', '', $request->getHost()))
                            ->setContentType('text/html; charset=UTF-8')
                            ->setTo($user->getEmail())
                            ->setSubject('Восстановление пароля на сайте ' . $request->getHost())
                            ->setBody($this->getPartial('user/recover_password', array('param' => array('password_check' => $sha, 'email_sha' => substr(sha1($user->getEmail()), 0, 20)))));

                        $this->getMailer()->send($message);

                        $response = array("response" => "На указанный вами адрес отправлено письмо для восстановления пароля");
                    } else $response = array("error" => "Пользователь не активен");
                } else $response = array("error" => "Пользователя с таким email нет");
            } else $response = array("error" => "email пустой");
        } else $response = array("error" => "Поддерживается только POST запрос");

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeAdd_review(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        if (!$request->isMethod('post')) {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }
        if (!$this->getUser()->isAuthenticated()) {
            return $this->renderText(json_encode(array(
                "error" => "Для использования метода нужно авторизоваться"
            )));
        }

        $data = $this->getPostData();
        if ($data) {
            $question_id = (integer) json_decode($data['question_id'], true);
            $body        = json_decode($data["body"], false);
            $informative = json_decode($data['informative'], true);
            $courtesy    = json_decode($data['courtesy'], true);
        } else {
            $question_id = (integer) $request->getPostParameter('question_id');
            $body        = $request->getPostParameter('body');
            $informative = $request->getPostParameter('informative');
            $courtesy    = $request->getPostParameter('courtesy');
        }
        if (!$question_id || !$informative || !$courtesy) {
            return $this->renderText(json_encode(array(
                "error" => "Введите все параметры: 'question_id','body','informative','courtesy'"
            )));
        }

        $myUser = $this->getUser()->getAccount();
        $question_user_id = Doctrine_Query::create()
            ->select("q.*, qu.*, qs.*")
            ->from("Question q")
            ->innerJoin("q.User qu")
            ->innerJoin("q.Specialists qs")
            ->where("q.id = " . $question_id . " AND q.user_id = " . $this->getUser()->getAccount()->getId())
            ->fetchArray();
        $specialist_id = $question_user_id[0]['Specialists'][0]['id'];

        $review_by_question_id = Doctrine_Query::create()
            ->select("r.*")
            ->from("Review r")
            ->where("r.question_id = " . $question_id)
            ->fetchArray();
        // Проверка был ли отзыв по вопросу
        if($review_by_question_id){
            // Перезапись отзыва
            $review = Doctrine::getTable('Review')->findOneBy("question_id", $question_id);
            $review["body"] = $body;
            $review["informative"] = $informative;
            $review["courtesy"] = $courtesy;
            $review->save();
        } else {
            // Создание нового отзыва
            $review = new Review();
            $review->setQuestionId($question_id)
                ->setUserId($myUser->getId())
                ->setSpecialistId($specialist_id)
                ->setBody($body)
                ->setInformative($informative)
                ->setCourtesy($courtesy)
                ->save();
        }
        Page::noticeAdd('s', 'review', $review->getId(), 'review');

        return $this->renderText(json_encode(
            $response = array(
                "response" => "Отзыв успешно добавлен, рейтинг доктора изменен",
            )
        ));
    }

    public function executeClose_question(sfWebRequest $request)
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
        $user_id = $this->getUser()->getAccount()->getId();
        if(!$this->isSpecialist($user_id)){
            return $this->renderText(json_encode(array(
                "error" => "Вы не специалист"
            )));
        }

        $data = $this->getPostData();
        if($data) {
            $question_id = json_decode($data['question_id'], true);
        } else {
            $question_id = $request->getPostParameter('question_id');
        }
        if(!$question_id){
            return $this->renderText(json_encode(array(
                "error" => "Введите все параметры: 'question_id'"
            )));
        }

        $question = Doctrine::getTable('Question')->findOneById($question_id);
        $question->setClosedBy($this->getUser()->getAccount()->getId())
            ->setClosingDate(date('Y-m-d' . ' ' . 'H:i:s'))
            ->save();

        Page::noticeAdd('u', 'dialog', $question_id, 'closed');

        return $this->renderText(json_encode(
            $response = array(
                "response" => 'Вопрос успешно закрыт'
            )
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
        $q_user_id = $this->getUser()->getAccount()->getId();

        $data = $this->getPostData();
        if($data) {
            $qsh_anamnes     = json_decode($data['qsh_anamnes'], true);
            $q_body          = $data['q_body'];
            $q_specialist_id = $data['q_specialist_id'];
            $q_specialty_id  = $data['q_specialty_id'];
            $anonymous       = $data['anonymous'];
            $user_about      = json_decode($data['user_about'], true);
        } else {
            // Пример json в qsh_anamnes: [{"sh_field":"137","val":"test"},{"sh_field":"138","val":"test"},{"sh_field":"139","val":"test","file":""},{"sh_field":"140","bool":"Нет","val":""},{"sh_field":"141","bool":"Нет","val":"ТЕКСТ и да нет"},{"sh_field":"142","choices":["список"]},{"sh_field":"143","choices":{"1":"без","2":"выбора"}}]
            $qsh_anamnes     = json_decode($request->getPostParameter('qsh_anamnes'), true);
            $q_body          = $request->getPostParameter('q_body');
            $q_specialist_id = $request->getPostParameter('q_specialist_id');
            $q_specialty_id  = $request->getPostParameter('q_specialty_id');
            $anonymous       = $request->getPostParameter('anonymous');
            $user_about      = json_decode($request->getPostParameter('user_about'), true);
        }

        if(!$q_body || !$qsh_anamnes || !$q_specialist_id || !$q_specialty_id ){
            return $this->renderText(json_encode(array(
                "error" => "Введите все параметры: 'q_body', 'qsh_anamnes', 'q_specialist_id', 'q_specialty_id', 'anonymous'"
            )));
        }
        if(!($anonymous == 1 || $anonymous == 0)){
            return $this->renderText(json_encode(array(
                "error" => "anonymous должен быть либо 1 либо 0"
            )));
        }

        $question = new Question();
        $question->setUserId($q_user_id)
            ->setBody($q_body)
            ->setVkNotice(false)
            ->setIsAnonymous($anonymous);
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
                $afu = new Attached_family_users();
                $afu->setUserId($q_user_id)
                    ->setUserAboutId($newUser->get('id'))
                    ->save();
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
        unset($values['isRequired']);
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

    public function isSpecialist($user_id){
        $isSpecialist = Doctrine_Query::create()
            ->select("s.specialty_id, s.rating, s.answers_count, s.about")
            ->from("Specialist s")
            ->where("s.user_id = {$user_id}")
            ->fetchArray();
        return ($isSpecialist != []);
    }

    public function getSpecialist($user_id){
        //$specialist = Doctrine::getTable('Specialist')->findOneBy("id", "118");
        $isSpecialist = Doctrine_Query::create()
            ->select("s.specialty_id, s.rating, s.answers_count, s.about")
            ->from("Specialist s")
            ->where("s.user_id = {$user_id}")
            ->fetchArray();
        if($isSpecialist != [])
            return $isSpecialist[0];
        else
            return null;
    }

    public function getPostData(){
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getUserIdByQuestionId($question_id){
        $user_id_by_q_id = Doctrine_Query::create()
            ->select("qs.*, qss.*, qssu.*")
            ->from("QuestionSpecialist qs")
            ->innerJoin("qs.Specialist qss")
            ->innerJoin("qss.User qssu")
            ->where("qs.question_id = {$question_id}")
            ->fetchArray()[0]["Specialist"]["User"]["id"];

        return $user_id_by_q_id;
    }

    public function pushNotifications($tokens = array(""), $title = "", $message = "", $data = array()){
        $method = 'http://192.168.2.7:8103/gorush/api/push';
        $params = array(
            "notifications" => array(
                array(
                    "tokens" => $tokens,
                    "data" => $data,
                    "platform" => 1,
                    "title" => "IOS::" . $title,
                    "message" => $message,
                    "topic" => "company.atma.vrachrb",
                    "priority" => "high",
                    "content_available" => true,
                    "push_type" => "background",
                ),
                array(
                    "tokens" => $tokens,
                    "data" => $data,
                    "platform" => 2,
                    "title" => "ANDROID::" . $title,
                    "message" => $message,
                    "topic" => "company.atma.vrachrb",
                    "priority" => "high",
                    "content_available" => true,
                    "push_type" => "background",
                ),
                array(
                    "tokens" => $tokens,
                    "huawei_data" => json_encode($data),
                    "platform" => 3,
                    "title" => "HUAWEI::" . $title,
                    "message" => $message,
                    "topic" => "company.atma.vrachrb",
                    "priority" => "high",
                    "content_available" => true,
                    "push_type" => "background",
                )
            )
        );
//        return $params;
        return json_decode(ProjectUtils::post($method, json_encode($params)), true);
    }

    public function executeTest(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        $json = $this->pushNotifications(array("fB_0viatS_a5DSD83ywkuJ:APA91bF60tuio25--LJx-leKlcuccbhNkZePDsIC94G-Ej5btKmYP8kn3J3YCUh9GZr2v7g0Z6ikKNyH3em2fDj_DmKBNJkqAXZUUt5iLdQlJGSW7qg2E9dBcGw9hn5dD-WIODhq3_Al"),
            "TITLE","MESSAGE",array(
                "kek" => "kek1"
            ));

        return $this->renderText(json_encode(
            $response = array(
                "json" => $json
            )
        ));
    }
//    var_dump($this->getUser()->getAccount()->getUsername());
}