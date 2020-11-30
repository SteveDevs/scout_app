<?php
declare(strict_types=1);

/**
 * Index Controller
 * MIT
 */ 
class IndexController extends ControllerBase
{
    /**
     * The initial default routing call, gets all Users
     *
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Mvc\View
     */ 
    public function indexAction()
    {
        $users = UsersDev::find();
        
        $this->view->setVars(
            [
                'users' =>  $users,
            ]
        );
        return $this->view;
    }
}

