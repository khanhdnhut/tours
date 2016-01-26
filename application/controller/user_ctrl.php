<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class UserCtrl extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if (empty($_POST['log'])) {
            if (Auth::isLogged()) {
                header('location: ' . URL . CONTEXT_PATH_ADMIN);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_LOGIN, TRUE);
            }
        } else {
            //Thực hiện đăng nhập
            // run the login() method in the login-model, put the result in $login_successful (true or false)
            Model::autoloadModel('User');
            $this->model = new UserModel($this->db);

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

    public function logout()
    {
        Model::autoloadModel('User');
            $this->model = new UserModel($this->db);
        $this->model->logout();
        // redirect user to base URL
        header('location: ' . URL . CONTEXT_PATH_USER_LOGIN);
    }

    public function editInfo($user_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('User');
            $this->model = new UserModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['user'])) {
                $this->para->user_id = $_POST['user'];
            } elseif (isset($user_id) && !is_null($user_id)) {
                $this->para->user_id = $user_id;
            }
            if (isset($_POST['action']) && $_POST['action'] == "update") {
                $this->para->action = $_POST['action'];

                if (isset($_POST['role'])) {
                    $this->para->role = $_POST['role'];
                }
                if (isset($_POST['first_name'])) {
                    $this->para->first_name = $_POST['first_name'];
                }
                if (isset($_POST['last_name'])) {
                    $this->para->last_name = $_POST['last_name'];
                }
                if (isset($_POST['nickname'])) {
                    $this->para->nickname = $_POST['nickname'];
                }
                if (isset($_POST['display_name'])) {
                    $this->para->display_name = $_POST['display_name'];
                }
                if (isset($_POST['email'])) {
                    $this->para->email = $_POST['email'];
                }
                if (isset($_POST['url'])) {
                    $this->para->url = $_POST['url'];
                }
                if (isset($_POST['description'])) {
                    $this->para->description = $_POST['description'];
                }
                if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) && 
                    $_FILES['avatar']['name'] != '') {
                    $this->para->avatar = $_FILES['avatar'];
                }
                if (isset($_POST['pass1'])) {
                    $this->para->pass1 = $_POST['pass1'];
                }
                if (isset($_POST['pass1-text'])) {
                    $this->para->pass1_text = $_POST['pass1-text'];
                }
                if (isset($_POST['pw_weak'])) {
                    $this->para->pw_weak = $_POST['pw_weak'];
                }
                $result = $this->model->updateInfoUser($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }                
            }
            $this->view->userBO = $this->model->getUserByUserId($this->para->user_id);
            if (isset($user_id) && !is_null($user_id)) {
                $this->view->renderAdmin(RENDER_VIEW_USER_EDIT);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_EDIT, TRUE);
            }
        }
    }

    public function info($user_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('User');
            $this->model = new UserModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['user'])) {
                $this->para->user_id = $_POST['user'];
            } elseif (isset($user_id) && !is_null($user_id)) {
                $this->para->user_id = $user_id;
            }
            $this->view->userBO = $this->model->getUserByUserId($this->para->user_id);
            if (isset($user_id) && !is_null($user_id)) {
                $this->view->renderAdmin(RENDER_VIEW_USER_INFO);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_INFO, TRUE);
            }
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('User');
            $this->model = new UserModel($this->db);
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

            if (count((array) $this->para) > 0) {
                $this->view->renderAdmin(RENDER_VIEW_USER_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_USER_INDEX);
            }
        } else {
            header('location: ' . URL . CONTEXT_PATH_ADMIN);
        }
    }
}
