<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class User extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index() {
        if (Auth::handleLogin()) {
            header('location: ' . URL . 'admin');
        }        
    }

    public function login() {
        if (empty($_POST['log'])) {
            if (Auth::handleLogin()) {
                header('location: ' . URL . 'admin');
            }            
        } else {
            //Thực hiện đăng nhập
            // run the login() method in the login-model, put the result in $login_successful (true or false)
            $this->model = $this->loadModel('User');

            if ($this->model->loginValidate()) {
                if ($this->model->getUserByUserLogin($_POST['log'])) {
                    header('location: ' . URL . 'admin');
                }
            } else {
                $this->view->renderAdmin('user/login', TRUE);
            }
        }
    }

}
