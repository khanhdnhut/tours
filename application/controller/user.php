<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class User extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        if (Auth::handleLogin()) {
            header('location: ' . URL . CONTEXT_PATH_ADMIN);
        }
    }

    public function login()
    {
        if (empty($_POST['log'])) {
            if (Auth::isLogged()) {
                header('location: ' . URL . CONTEXT_PATH_ADMIN);
            } else {
                $this->view->renderAdmin('user/login', TRUE);
            }
        } else {
            //Thực hiện đăng nhập
            // run the login() method in the login-model, put the result in $login_successful (true or false)
            $this->model = $this->loadModel('User');

            if ($this->model->loginValidate()) {
                if ($this->model->getUserByUserLogin($_POST['log'])) {
                    header('location: ' . URL . CONTEXT_PATH_ADMIN);
                } else {
                    $this->view->renderAdmin('user/login', TRUE);
                }
            } else {
                $this->view->renderAdmin('user/login', TRUE);
            }
        }
    }

    public function logout()
    {
        $this->model = $this->loadModel('User');
        $this->model->logout();
        // redirect user to base URL
        header('location: ' . URL . CONTEXT_PATH_USER_LOGIN);
    }

    public function edit()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            $this->model = $this->loadModel('User');
            $this->model->prepareEditPage($this->view);

            if (isset($_POST['orderby']) && isset($_POST['order'])) {
                $this->view->renderAdmin('user/edit', TRUE);
            } else {
                $this->view->renderAdmin('user/edit');
            }
        } else {
            header('location: ' . URL . CONTEXT_PATH_ADMIN);
        }
    }
}
