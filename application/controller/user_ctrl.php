<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class UserCtrl extends Controller {

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
            header('location: ' . URL . CONTEXT_PATH_ADMIN);
        }
    }

    public function login() {
        if (empty($_POST['log'])) {
            if (Auth::isLogged()) {
                header('location: ' . URL . CONTEXT_PATH_ADMIN);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_LOGIN, TRUE);
            }
        } else {
            //Thực hiện đăng nhập
            // run the login() method in the login-model, put the result in $login_successful (true or false)
            $this->model = $this->loadModel('User');

            if ($this->model->loginValidate()) {
                if ($this->model->getUserByUserLogin($_POST['log'])) {
                    header('location: ' . URL . CONTEXT_PATH_ADMIN);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_USER_LOGIN, TRUE);
                }
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_LOGIN, TRUE);
            }
        }
    }

    public function logout() {
        $this->model = $this->loadModel('User');
        $this->model->logout();
        // redirect user to base URL
        header('location: ' . URL . CONTEXT_PATH_USER_LOGIN);
    }

    public function edit() {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            $this->model = $this->loadModel('User');
            $this->para = new stdClass();
            
            if (isset($_POST['type'])) {
                $this->para->type = $_POST['type'];
            }
            if (isset($_POST['role'])) {
                $this->para->role = $_POST['role'];
            }
            if (isset($_POST['orderby'])) {
                $this->para->orderby = $_POST['orderby'];
            }
            if (isset($_POST['order'])) {
                $this->para->order = $_POST['order'];
            }
            if (isset($_POST['page'])) {
                $this->para->page = $_POST['page'];
            }
            if (isset($_POST['s'])) {
                $this->para->s = $_POST['s'];
            }
            if (isset($_POST['paged'])) {
                $this->para->paged = $_POST['paged'];
            }
            if (isset($_POST['users'])) {
                $this->para->users = $_POST['users'];
            }
            if (isset($_POST['new_role'])) {
                $this->para->new_role = $_POST['new_role'];
            }
            if (isset($_POST['new_role2'])) {
                $this->para->new_role2 = $_POST['new_role2'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            
            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2", "new_role", "new_role2")) && isset($this->para->users)) {
                if (in_array($this->para->type, array("action", "action2"))) {
                    $this->model->executeAction($this->para);
                } else {
                    $this->model->changeRole($this->para);
                }
            }
                        
            $this->model->prepareEditPage($this->view, $this->para);

            if (count((array) $this->para) > 0 ) {
                $this->view->renderAdmin(RENDER_VIEW_USER_EDIT, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_EDIT);
            }
        } else {
            header('location: ' . URL . CONTEXT_PATH_ADMIN);
        }
    }

}
