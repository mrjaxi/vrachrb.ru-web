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

        if ($request->isMethod('post')) {
            try {
                $params['name']      = $request->getPostParameter("name");
                $params['familia']   = $request->getPostParameter("familia");
                $params['last_name'] = $request->getPostParameter("last_name");
                $params['gender']    = $request->getPostParameter("gender");
                $params['birth_date']= $request->getPostParameter("birth_date");
                $params['phone']     = $request->getPostParameter("phone");
                $params['email']     = $request->getPostParameter("email");
                $params['password']  = $request->getPostParameter("password");

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
                    'username'   => $params['email'],
                    'first_name' => $params['name'],
                    'second_name'=> $params['familia'],
                    'middle_name'=> $params['last_name'],
                    'gender'     => $params['gender'],
                    'birth_date' => $params['birth_date'],
                    'email'      => $params['email'],
                    'phone'      => $params['phone'],
                    'salt'       => $salt,
                    'password'   => $encryptedPass,
//                'photo' => '',
//                'last_login' => '',
                    'password_check'=> $rndPasswordCheck,
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
        } else {
            $response = array(
                "error" => "Поддерживается только POST запрос.",
            );
        }

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function executeSendMessage(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

    }

    public function executeGetSpecialistBySpecialtyID(sfWebRequest $request)
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

        $specialists = Doctrine_Query::create()
            ->select("s.rating, s.answers_count, s.about, u.first_name, u.second_name, u.middle_name")
            ->from("Specialist s")
            ->innerJoin("s.User u ON u.id = s.user_id and s.specialty_id = 10")
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
//        var_dump($this->getUser()->getAccount()->getUsername());

        $this->getResponse()->setHttpHeader('Content-type','application/json');

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

        $spec_id = $request->getPostParameter('spec_id');
        if(!$spec_id){
            return $this->renderText(json_encode(array(
                "error" => "Введите параметр 'spec_id'"
            )));
        }

        if ($this->getUser()->isAuthenticated()){
            $CurrentConnection = Doctrine_Manager::getInstance()->getCurrentConnection();
            $anamneses = $CurrentConnection->execute("select shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required
                from sheet_history_specialty as shs
                inner join sheet_history as sh ON sh.id = (select sheet_history_id from sheet_history_specialty as shs where shs.specialty_id = {$spec_id})
                inner join sheet_history_field as shf ON shf.sheet_history_id = sh.id
                where shs.sheet_history_id = (select sheet_history_id from sheet_history_specialty as shs where shs.specialty_id = {$spec_id})")
                ->fetchAll();

            if($anamneses){
                $response = array(
                    "response" => $this->unserializeAnamneses($anamneses)
                );
            } else {
                $common = $CurrentConnection->execute("select shf.id, shf.title, shf.field_type, shf.field_options, shf.order_field, shf.is_required
                    from sheet_history_field as shf
                    where shf.sheet_history_id = 1")
                    ->fetchAll();
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

        $q_user_id    = $request->getPostParameter('q_user_id');
        $q_body       = $request->getPostParameter('q_body');
        $qsh_anamnes  = json_decode($request->getPostParameter('qsh_anamnes'));
        $q_specialist = $request->getPostParameter('q_specialist');
        $q_specialty  = $request->getPostParameter('q_specialty');
//        if(!$spec_id){
//            return $this->renderText(json_encode(array(
//                "error" => "Введите параметр 'spec_id'"
//            )));
//        }

        $idQuestion = Doctrine_Query::create()
            ->select('q.id')
            ->from('Question q')
            ->orderBy('q.id DESC')
            ->limit(1)
            ->fetchArray()[0]["id"];


        $value = array(
            'question_id'            => $idQuestion,
            'sheet_history_field_id' => '137',
            'values'                 => '{"choices":["svkljsdjnk"]}'
        );
        $this->insertFromTable('QuestionSheetHistory',$value);

//        $values = $this->buildValue($idQuestion, $qsh_anamnes);
//        for($i = 0; $i < count($values); $i++){
//            $this->insertFromTable('QuestionSheetHistory',$values[$i]);
//        }
//        $this->insertFromTable('QuestionSheetHistory',$values[0]);

        $response = array(
            "val" => $values[0]
        );

        return $this->renderText(json_encode(
            $response
        ));
    }

    public function buildValue($idQuestion, $val){
        $values = array();
        for($i = 0; $i < count($val); $i++){
            $values[$i] = array(
                'question_id'            => $idQuestion,
                'sheet_history_field_id' => $val[$i]['sh_field'],
                'values'                 => $this->clearValues($val[$i])
            );
        }
        return $values;
    }

    public function clearValues($values){
        unset($values['sh_field']);
        return json_encode($values);
    }

    public function insertFromTable($tableName, array $values){
        $CurrentConnection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $Table = Doctrine_Core::getTable($tableName);

        $CurrentConnection->insert($Table,$values);
    }

    public function unserializeAnamneses($anamneses){
        for($i = 0;$i < count($anamneses);$i++){
            $anamneses[$i]["field_options"] = unserialize($anamneses[$i]["field_options"]);
            $anamneses[$i]["3"] = unserialize($anamneses[$i]["3"]);
        }
        return $anamneses;
    }
}