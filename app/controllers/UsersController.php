<?php
declare(strict_types=1);

use Phalcon\Security;

/**
 * Users Controller
 * MIT
 */ 
class UsersController extends ControllerBase
{

    public function indexAction(){}

    /**
     * Edits user
     *
     * @param String $id user id
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Http\Response::redirect
     * @return Phalcon\Mvc\View
     */ 
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user = Usersdev::findFirstByid($id);
            if (!$user) {
                $this->flashSession->error('User does not exist');
                return $this->response->redirect("scout_app");
            }

            $this->view->setVars(
                [
                    'user' =>  $user
                ]
            );
            return $this->view;
        }
    }

    /**
     * Creates user
     *
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Http\Response::redirect
     * @return Phalcon\Mvc\Controller\ControllerBase->insertUpdate
     */ 
    public function createAction()
    {
        $unique = $this->unique(
            $this->request->getPost('email'), 
            $this->request->getPost('username'), 
            $this->request->getPost('mobile')
        );

        $unique_error = '';

        if($unique['email'] || $unique['username'] || $unique['mobile']){
            $unique_error .= $unique['username'] ? "Username exists" : '';
            $unique_error .= $unique['email'] ? ",Email exists" : '';
            $unique_error .= $unique['mobile'] ? ",Mobile exists" : '';
   
            $this->flashSession->error(nl2br($unique_error));
            return $this->response->redirect("scout_app");
        }

        $this->security->hash((string)rand());
        return $this->insertUpdate(
            $this->request, 
            new UsersDev(), 
            [
                'password' => $this->security->hash($this->request->getPost('password'))
            ],
            'scout_app',
            'scout_app'
        );
    }

    /**
     * Saves updated user
     *
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Http\Response::redirect
     * @return Phalcon\Mvc\Controller\ControllerBase->insertUpdate
     */ 
    public function saveAction()
    {
        $user = UsersDev::findFirst($this->request->getPost('id'));
        $unique_string = $this->uniqueUpdate($user);

        if($unique_string != ''){
            $this->flashSession->error($unique_string);

            return $this->response->redirect('scout_app'); 
        }

        return $this->insertUpdate($this->request, 
            $user,
            NULL,
            'scout_app',
            'scout_app'
        );
    }

    /**
     * Determines if unique: email, username, mobile is 
     * provided on updating of user.
     *
     * @param \Phalcon\Mvc\Model\UsersDev\findFirst $user
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return String (Empty if all values are unique)
     */ 
    public function uniqueUpdate($user){
        $unique_error = '';

        if($user->email != $this->request->getPost('email')){
            (UsersDev::findFirst(
                [
                    'conditions' =>
                    'email=:email:',
                    'bind' => [ 'email' => $this->request->getPost('email') ]
                ]
            )) ? $unique_error .= 'Email exists' : ''; 
        } 

        if($user->username != $this->request->getPost('username')){
            (UsersDev::findFirst(
                [
                    'conditions' =>
                    'username=:username:',
                    'bind' => [ 'username' => $this->request->getPost('username') ]
                ]
            )) ? $unique_error .= ',Username exists' : ''; 
        }

        if($user->mobile != $this->request->getPost('mobile')){
            (UsersDev::findFirst(
                [
                    'conditions' =>
                    'mobile=:mobile:',
                    'bind' => [ 'mobile' => $this->request->getPost('mobile') ]
                ]
            )) ? $unique_error .= ',Mobile exists' : ''; 
        }

        return $unique_error;
    }

    /**
     * Determines if unique: email, username, mobile is 
     * provided on inserting new user.
     *
     * @param String $email
     * @param String $username
     * @param String $mobile
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Array: 
        [
            'email' => $email,
            'username' => $username,
            'mobile' => $mobile,
        ]
     */ 
    public function unique($email, $username, $mobile){
        $email = (UsersDev::findFirst(
            [
                'conditions' =>
                'email=:email:',
                'bind' => [ 'email' => $email ]
            ]
        )) ? true : false;

        $username = (UsersDev::findFirst(
            [
                'conditions' =>
                'username=:username:',
                'bind' => [ 'username' => $username ]
            ]
        )) ? true : false;

        $mobile = (UsersDev::findFirst(
            [
                'conditions' =>
                'mobile=:mobile:',
                'bind' => [ 'mobile' => $mobile ]
            ]
        )) ? true : false;

        return [
            'email' => $email,
            'username' => $username,
            'mobile' => $mobile,
        ];
    }
}

