<?php
class apiActions extends sfActions
{
    public function executeSignin(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        $username = $request->getPostParameter("user");
        $password = $request->getPostParameter("password");

        $usernameDoctrine = Doctrine::getTable('User')->findOneByUsernameOrEmail($username, $username);

        $response = array();

        if ($usernameDoctrine){
            $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
            $encrypted = call_user_func_array($algorithm, array($usernameDoctrine->get("salt") . $password));

            if ($encrypted == $usernameDoctrine->get("password")){
                $this->getUser()->signIn($usernameDoctrine, 1);

                $response = array(
                    "first_name" => $usernameDoctrine->get("first_name"),
                    "second_name" => $usernameDoctrine->get("second_name"),
                    "middle_name" => $usernameDoctrine->get("middle_name"),
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

    public function executeSendMessage(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

    }

    public function executeSpecialist(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type','application/json');

        if ($this->getUser()->isAuthenticated()){
            $allSpeciality = Doctrine::getTable('Specialist');

            $response = array(
                "response" => $allSpeciality
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

    public function executeApi_register(sfWebRequest $request)
    {
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        $this->setLayout('json');
        $this->setTemplate('json', 'main');

        if ($request->isMethod('post')) {
            try {
                $params['name'] = $request->getPostParameter("name");
                $params['familia'] = $request->getPostParameter("familia");
                $params['last_name'] = $request->getPostParameter("last_name");
                $params['gender'] = $request->getPostParameter("gender");
                $params['birth_date'] = $request->getPostParameter("birth_date");
                $params['phone'] = $request->getPostParameter("phone");
                $params['email'] = $request->getPostParameter("email");
                $params['password'] = $request->getPostParameter("password");

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
                $user = $newUser;

                return $this->renderText(json_encode(array(
                    'salt'          => $user->get('salt'),
                )));
            } catch (Exception $e) {
                return $this->renderText(json_encode(array(
                    "error" => $e->getMessage(),
                )));
            }
        } else {
            return $this->renderText(json_encode(array(
                "error" => "Поддерживается только POST запрос.",
            )));
        }
    }
}