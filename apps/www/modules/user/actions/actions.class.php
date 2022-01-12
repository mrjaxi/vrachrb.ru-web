<?php

class userActions extends sfActions
{
    public function executeLpAuth($request)
    {
        $status_code = 403;
        if ($this->getUser()->isAuthenticated()) {
            $channel = explode('?', $_SERVER['X-Original-URI']);
            $channel = intval(str_replace('/lp/private-', '', $channel[0]));
            if ($channel == $this->getUser()->getUserId()) {
                $status_code = 200;
            }
        }
        $this->getResponse()->setStatusCode($status_code);
        return sfView::NONE;
    }

    public function executeSignout($request)
    {
        $this->getUser()->signOut();
        $this->redirect('/');
//    $this->redirect($request->getReferer());
        return sfView::NONE;
    }

    public function executeSignin(sfWebRequest $request)
    {
        $this->signin_form = new wwwSigninForm();
        if ($request->isMethod('post')) {
            $replace_simple_user = array(
                'romanchenko87@mail.ru' => 'http://vk.com/id99478393',
                'abdulmanova' => 'http://vk.com/id9652170',
                'user1' => 'http://vk.com/id4611267',
                'doctor1' => 'http://vk.com/id14807881',
                'bezrukova' => 'http://vk.com/id256494869',
                'anyuser' => 'samarastudent@mail.ru',
                'absdina886611q' => 'http://vk.com/id249013808'
            );

            $this->signin_form->bind($request->getParameter($this->signin_form->getName()));

            if ($this->signin_form->isValid()) {
                $a = $request->getParameter($this->signin_form->getName());
                $username = $replace_simple_user[$a['username']] ? $replace_simple_user[$a['username']] : $a['username'];
                $b = Doctrine::getTable('User')->findOneByUsernameOrEmail($username, $username);
                $this->getUser()->signIn($b, 1);
                if (Agreement::agreementCheck($this->getUser()->getAccount()->getId())) {
                    $this->agreement = true;
                }
                $this->r = true;
            }
        }
    }

    public function executeCheck_token(sfWebRequest $request)
    {
        if ($request->getParameter('token')) {
            $s = file_get_contents('https://ulogin.ru/token.php?token=' . $request->getParameter('token') . '&host=' . $request->getHost());
            $json = json_decode($s, true);

            $replace_user = array(
                'http://vk.com/id3664302' => 'http://vk.com/id337071653',
                'http://vk.com/id70520912' => 'http://vk.com/knopka_margo',
                'http://vk.com/id40578836' => 'https://vk.com/nailya_84',
                'http://vk.com/id54037275' => 'http://vk.com/lisenoklisichka',
                'https://vk.com/azizuseinov' => 'https://vk.com/id37272438',
                'http://vk.com/id9654467' => 'https://vk.com/ernest_settarov',
                'http://vk.com/id14807881' => 'https://vk.com/gaptrakipov',
            );

            $identity = $json['identity'];

            file_put_contents(sfConfig::get('sf_log_dir') . '/signinVks.txt', $s . "\n\n", FILE_APPEND);
            file_put_contents(sfConfig::get('sf_log_dir') . '/signinVk.txt', $identity . "__\n", FILE_APPEND);

            if ($replace_user[$identity]) {
                $identity = $replace_user[$json['identity']];
            }

            $identity_ = explode('://', $identity);

            $identitys = array(
                'http://' . $identity_[1],
                'https://' . $identity_[1],
            );

            $user = Doctrine_Query::create()
                ->select("u.*")
                ->from("User u")
                ->whereIn("u.username", $identitys)
                ->orderBy("(SELECT COUNT(s.id) FROM Specialist s WHERE s.user_id = u.id) DESC")
                ->fetchOne();

            if (!$user) {
                $user = new User();
                $user->setUsername($identity);
                $user->setFirstName($json['first_name']);
                $user->setSecondName($json['last_name']);
                if ($json['sex'] != 0 && $json['sex'] != '') {
                    $user->setGender($json['sex'] == 1 ? 'ж' : 'м');
                }
                $json['email'] ? $user->setEmail($json['email']) : '';
                $user->setBirthDate(isset($json['bdate']) ? $json['bdate'] : rand(1960, date('Y')) . ':' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT) . ' 00:00:00');

                $user->save();
            }
            $this->getUser()->signIn($user, 1);

            if (Agreement::agreementCheck($user->getId())) {
                $this->login = true;
            } else {
                return $this->redirect('@login_index?authorization=1');
            }
        }
    }

    public function executeLogin(sfWebRequest $request)
    {
        $this->register_form = new RegisterUserForm();
    }

    public function executeRegister(sfWebRequest $request)
    {
        $this->form = new RegisterUserForm();
        if ($request->isMethod('post')) {
            $request_user = $request->getParameter('user');
            $request_user['username'] = $request_user['email'];

            $this->form->bind($request_user);

            if ($this->form->isValid()) {
                if ($request->getParameter('agreement')) {
                    $user = $this->form->save();
                    $agreement = Doctrine::getTable("Agreement")->findAll();
                    foreach ($agreement as $ag) {
                        $agreement_complete_new = new AgreementComplete();
                        $agreement_complete_new->setUserId($user->getId());
                        $agreement_complete_new->setAgreementId($ag->getId());
                        $agreement_complete_new->save();
                    }
                    $rnd = Page::generateUuid(32);
                    $user->setIsActive(false);
                    $user->setPasswordCheck($rnd);
                    $user->save();
                    $message = Swift_Message::newInstance()
                        ->setFrom('noreply@' . $request->getHost())
                        ->setContentType('text/html; charset=UTF-8')
                        ->setTo($user->getEmail())
                        ->setSubject('Активация акаунта на сайте ' . $request->getHost())
                        ->setBody($this->getPartial('user/mail', array('user' => $user)));

                    $this->getMailer()->send($message);
                }
                $this->r = true;
            }
        }
    }


    public function executeActivate(sfWebRequest $request)
    {
        $user = Doctrine::getTable('User')
            ->createQuery('u')
            ->where('u.username = ?', base64_decode($request->getParameter('username')))
            ->andWhere('u.password_check = ?', $request->getParameter('password_check'))
            ->fetchOne();

        $this->forward404Unless($user);

        if ($user->getIsActive() == false) {
            $user->setIsActive(1);
            $user->save();
            $this->getUser()->signIn($user, 1);
        }
    }

    public function executeChange(sfWebRequest $request)
    {
        if ($this->getUser()->isAuthenticated()) {
            $this->change_answer = true;
            $account = $this->getUser()->getAccount();

            $this->register_form = new ChangeUserForm($account);

            $is_soc = $account->getUsername() != $account->getEmail();

            if (!$is_soc) {
                $this->change_form = new ChangePasswordForm($account);
            }

            if ($request->isMethod('post')) {
                $request_user = $request->getParameter('user');

                $this->register_form->bind($request_user);

                if ($this->register_form->isValid()) {
                    $user = $this->register_form->save();
                    $user->setIsActive(1);
                    $user->save();
                    if (!$is_soc) {
                        $user->setUsername($user->getEmail());
                        $user->save();
                    }
                    $this->save_true = true;
                }
            }
        }
    }

    public function executeChange_password(sfWebRequest $request)
    {
        $account = $this->getUser()->getAccount();
        $this->change_password_form = new ChangePasswordForm($account);
        if ($request->isMethod('post')) {
            $request_user = $request->getParameter('user_password');
            $this->change_password_form->bind($request_user);

            if ($this->change_password_form->isValid()) {
                $user = $this->change_password_form->save();
                $user->setIsActive(1);
                $user->save();
                $this->save_true = true;
            }
        }
    }

    public function executeSignin_check(sfWebRequest $request)
    {
        if ($request->isMethod('post')) {
            if ($request->getParameter('signin_check') == 1) {
                if ($this->getUser()->isAuthenticated()) {
                    echo 'y';
                } else {
                    $this->redirect('@login_index');
                }
            }
        }
        return sfView::NONE;
    }

    public function executeSecure(sfWebRequest $request)
    {

    }

    public function executeRecover_password(sfWebRequest $request)
    {
        if ($request->isMethod('post')) {
            $email = strip_tags($request->getParameter('email'));
            if ($email != '') {
                $user = Doctrine::getTable('User')->findOneByEmail($email);
                if ($user) {
                    if ($user->getIsActive() == 1) {
                        $sha = ProjectUlils::generateUuid();

                        $user->setPasswordCheck($sha);
                        $user->save();

                        $message = Swift_Message::newInstance()
                            ->setFrom('noreply@' . str_replace('www.', '', $request->getHost()))
                            ->setContentType('text/html; charset=UTF-8')
                            ->setTo($user->getEmail())
                            ->setSubject('Восстановление пароля на сайте ' . $request->getHost())
                            ->setBody($this->getPartial('user/recover_password', array('param' => array('password_check' => $sha, 'email_sha' => substr(sha1($user->getEmail()), 0, 20)))));

                        $this->getMailer()->send($message);
                    }
                }

                $this->getUser()->setFlash('recover_message', 'На указанный вами адрес отправлено письмо для восстановления пароля');
            }
        }
    }

    public function executeRecover_password_active(sfWebRequest $request)
    {
        $email_sha = $request->getParameter('email_sha');
        $password_check = $request->getParameter('password_check');
        if (strip_tags($email_sha) != '' && strip_tags($password_check) != '') {
            $user = Doctrine::getTable("User")->findOneByPasswordCheck($password_check);
            if ($user) {
                if ($email_sha == substr(sha1($user->getEmail()), 0, 20) && $user->getIsActive() == 1) {
                    $access = true;

                    if ($request->isMethod('post')) {
                        $new_pass = strip_tags($request->getParameter('new_password'));
                        $repeat_pass = strip_tags($request->getParameter('new_password'));
                        if ($new_pass == $repeat_pass && (strlen($new_pass) >= 8 && strlen($new_pass) <= 12)) {
                            if (!$salt = $user->getSalt()) {
                                $salt = sha1(uniqid(mt_rand(), true));
                                $user->setSalt($salt);
                            }
                            $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
                            $algorithmAsStr = is_array($algorithm) ? $algorithm[0] . '::' . $algorithm[1] : $algorithm;
                            if (!is_callable($algorithm)) {
                                throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
                            }
                            $user->setPassword(call_user_func_array($algorithm, array($salt . $new_pass)));
                            $user->setPasswordCheck(NULL);
                            $user->save();

                            $this->change_pass = true;
                        }
                    }
                }
            }
        }
        if (!$access) {
            $this->redirect('/recover-password/');
        }
    }
}
