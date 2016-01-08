<?php

class UserModel extends Model
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db)
    {
        parent::__construct($db);
    }

    public function loginValidate()
    {
        // we do negative-first checks here
        if (!isset($_POST['log']) OR empty($_POST['log'])) {
            $_SESSION["fb_error"][] = ERR_USERNAME_EMPTY;
            return false;
        }
        if (!isset($_POST['pwd']) OR empty($_POST['pwd'])) {
            $_SESSION["fb_error"][] = ERR_PASSWORD_EMPTY;
            return false;
        }
        return true;
    }

    public function getUserByUserLogin($user_login)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERS . "
                                   WHERE  " . TB_USERS_COL_USER_LOGIN . " = :user_login");

        $sth->execute(array(':user_login' => $user_login));
        $count = $sth->rowCount();
        if ($count != 1) {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        if (hash('sha1', $_POST['pwd']) == $result->user_pass) {
            if ($result->user_status == USER_STATUS_ACTIVE) {
                $userBO = $this->getBO('user');
                $userBO->setUserInfo($result);
                $userMetaInfoArray = $this->getMetaInfoUser($result->ID);
                $userBO->setUserMetaInfo($userMetaInfoArray);
                //write user info into session
                Session::init();
                Session::set('userInfo', json_encode($userBO));
                Session::set('user_logged_in', true);
                if (isset($_POST['rememberme']) && $_POST['rememberme'] == 'forever') {
                    $this->saveCookieLogin();
                }
                return true;
            } elseif ($result->user_status == USER_STATUS_NOT_ACTIVE) {
                $_SESSION["fb_error"][] = ERR_USER_NOT_ACTIVE;
                return false;
            } elseif ($result->user_status == USER_STATUS_BLOCKED) {
                $_SESSION["fb_error"][] = ERR_USER_BLOCKED;
                return false;
            } elseif ($result->user_status == USER_STATUS_DELETEED) {
                $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
    }

    public function getMetaInfoUser($user_id)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERMETA . "
                                   WHERE  " . TB_USERMETA_COL_USER_ID . " = :user_id");

        $sth->execute(array(':user_id' => $user_id));
        $count = $sth->rowCount();
        if ($count > 0) {
            return $sth->fetchAll();
        } else {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
    }

//    public function saveCookieLogin()
//    {
//        
//    }
//
//    public function detroyCookieLogin()
//    {
//        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
//    }

    public function logout()
    {
        $this->detroyCookieLogin();
        // delete the session
        Session::destroy();
    }

    public function executeAction($para)
    {
        $action = NULL;
        if (isset($para->type)) {
            if ($para->type == "action") {
                if (isset($para->action) && in_array($para->action, array("delete"))) {
                    $action = $para->action;
                }
            } elseif ($para->type == "action2") {
                if (isset($para->action2) && in_array($para->action2, array("delete"))) {
                    $action = $para->action2;
                }
            }
        }
        if ($action != NULL) {
            switch ($action) {
                case "delete":
                    $this->executeActionDelete($para);
                    break;
            }
        }
    }

    public function executeActionDelete($para)
    {
        if (isset($para->users) && is_array($para->users)) {
            foreach ($para->users as $user_id) {
                try {
                    $user_id = (int) $user_id;
                } catch (Exception $ex) {
                    return;
                }
                if (!is_int($user_id)) {
                    return;
                }
            }
            $user_ids = join(',', $para->users);

            $userBO = json_decode(Session::get('userInfo'));
            $user_id = $userBO->user_id;

            $sql = "UPDATE " . TABLE_USERS . " 
                    SET " . TB_USERS_COL_USER_STATUS . " = " . USER_STATUS_DELETED . "
                    WHERE " . TB_USERS_COL_ID . " IN (" . $user_ids . ")
                    AND " . TB_USERS_COL_ID . " != 1
                    AND " . TB_USERS_COL_ID . " != " . $user_id . " ;";

            $sth = $this->db->prepare($sql);
            $sth->execute();
        }
    }

    public function executeChangeRole($para, $role)
    {
        if (isset($para->users) && is_array($para->users) && 
            in_array($role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            foreach ($para->users as $user_id) {
                try {
                    $user_id = (int) $user_id;
                } catch (Exception $ex) {
                    return;
                }
                if (!is_int($user_id)) {
                    return;
                }
            }
            $user_ids = join(',', $para->users);

            $userBO = json_decode(Session::get('userInfo'));
            $user_id = $userBO->user_id;

            $sql = "UPDATE " . TABLE_USERMETA . " 
                    SET " . TB_USERMETA_COL_META_VALUE . " = '" . $role . "'
                    WHERE " . TB_USERMETA_COL_USER_ID . " IN (" . $user_ids . ")
                    AND " . TB_USERMETA_COL_USER_ID . " != 1
                    AND " . TB_USERMETA_COL_USER_ID . " != " . $user_id . ";
                    AND " . TB_USERMETA_COL_META_KEY . " = '" . WP_CAPABILITIES . "' ;";

            $sth = $this->db->prepare($sql);
            $sth->execute();
        }
    }

    public function changeRole($para)
    {
        $role = NULL;
        if (isset($para->type)) {
            if ($para->type == "new_role") {
                if (isset($para->new_role) && in_array($para->new_role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
                    $role = $para->new_role;
                }
            } elseif ($para->type == "new_role2") {
                if (isset($para->new_role2) && in_array($para->new_role2, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
                    $role = $para->new_role2;
                }
            }
        }
        if ($role != NULL) {
            $this->executeChangeRole($para, $role);
        }
    }

    public function prepareEditPage($view, $para)
    {
        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT u." . TB_USERS_COL_ID . " as '" . USER_ID . "', u." . TB_USERS_COL_USER_LOGIN . ", u." . TB_USERS_COL_DISPLAY_NAME . ", 
            u." . TB_USERS_COL_USER_EMAIL . ", m." . TB_USERMETA_COL_META_VALUE . " AS '" . WP_CAPABILITIES . "'";
            $sqlSelectCount = "SELECT COUNT(*) as countUser ";
            //para: orderby, order, page, s, paged, users, new_role, new_role2, action, action2
            $sqlFrom = " FROM " . TABLE_USERS . " AS u, " . TABLE_USERMETA . " AS m ";
            $sqlWhere = " WHERE m." . TB_USERMETA_COL_USER_ID . " = u." . TB_USERS_COL_ID . " 
            AND m." . TB_USERMETA_COL_META_KEY . " = '" . WP_CAPABILITIES . "'
            AND user_status != " . USER_STATUS_DELETED;

            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= "  AND (u." . TB_USERS_COL_USER_LOGIN . " like :s OR
                                u." . TB_USERS_COL_DISPLAY_NAME . " like :s OR
                                u." . TB_USERS_COL_USER_EMAIL . " like :s ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "login";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("login", "name", "email"))) {
                switch ($para->orderby) {
                    case "login":
                        $para->orderby = TB_USERS_COL_USER_LOGIN;
                        $view->orderby = "login";
                        break;
                    case "name":
                        $para->orderby = TB_USERS_COL_DISPLAY_NAME;
                        $view->orderby = "name";
                        break;
                    case "email":
                        $para->orderby = TB_USERS_COL_USER_EMAIL;
                        $view->orderby = "email";
                        break;
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $view->order = $para->order;
                } else {
                    $para->order = "asc";
                    $view->order = "asc";
                }
                $sqlOrderby = " ORDER BY u." . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY u." . TB_USERS_COL_USER_LOGIN . " ASC";
            }

            $view->count = array(
                FILTER_USERS_LIST_ALL_TITLE => 0,
                CAPABILITY_ADMINISTRATOR => 0,
                CAPABILITY_SUBSCRIBER => 0,
                CAPABILITY_CONTRIBUTOR => 0,
                CAPABILITY_AUTHOR => 0,
                CAPABILITY_EDITOR => 0,
            );


            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countUser = (int) $sth->fetch()->countUser;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countUser > 0) {
                $view->count[FILTER_USERS_LIST_ALL_TITLE] = $countUser;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_USERMETA_COL_META_VALUE . " = '" . CAPABILITY_ADMINISTRATOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_ADMINISTRATOR] = (int) $sth->fetch()->countUser;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_USERMETA_COL_META_VALUE . " = '" . CAPABILITY_EDITOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_EDITOR] = (int) $sth->fetch()->countUser;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_USERMETA_COL_META_VALUE . " = '" . CAPABILITY_AUTHOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_AUTHOR] = (int) $sth->fetch()->countUser;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_USERMETA_COL_META_VALUE . " = '" . CAPABILITY_CONTRIBUTOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_CONTRIBUTOR] = (int) $sth->fetch()->countUser;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_USERMETA_COL_META_VALUE . " = '" . CAPABILITY_SUBSCRIBER . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_SUBSCRIBER] = (int) $sth->fetch()->countUser;

                if (!isset($_SESSION['options'])) {
                    $_SESSION['options'] = new stdClass();
                    $_SESSION['options']->users_per_page = 10;
                } elseif (!isset($_SESSION['options']->users_per_page)) {
                    $_SESSION['options']->users_per_page = 10;
                }

                $view->count[NUMBER_SEARCH_USER] = $view->count[FILTER_USERS_LIST_ALL_TITLE];
                $view->role = "-1";
                if (isset($para->role) && in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                        CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
                    $sqlWhere .= " AND m." . TB_USERMETA_COL_META_VALUE . "= '" . $para->role . "' ";
                    $view->count[NUMBER_SEARCH_USER] = $view->count[$para->role];
                    $view->role = $para->role;
                }

                if ($view->count[NUMBER_SEARCH_USER] > 0) {
                    $view->pageNumber = floor($view->count[NUMBER_SEARCH_USER] / $_SESSION['options']->users_per_page);
                    if ($view->count[NUMBER_SEARCH_USER] % $_SESSION['options']->users_per_page != 0) {
                        $view->pageNumber++;
                    }

                    if (isset($para->page)) {
                        try {
                            $page = (int) $para->page;
                            if ($para->page <= 0) {
                                $page = 1;
                            }
                        } catch (Exception $e) {
                            $page = 1;
                        }
                    } else {
                        $page = 1;
                    }
                    if ($page > $view->pageNumber) {
                        $page = $view->pageNumber;
                    }

                    $view->page = $page;
                    $startUser = ($page - 1) * $_SESSION['options']->users_per_page;
                    $sqlLimit = " LIMIT " . $_SESSION['options']->users_per_page . " OFFSET " . $startUser;

                    $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                    $sth = $this->db->prepare($sqlAll);
                    $sth->execute($paraSQL);
                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $view->userList = $sth->fetchAll();
                    } else {
                        $view->userList = NULL;
                    }
                } else {
                    $view->userList = NULL;
                    $view->page = 0;
                }
            } else {
                $view->userList = NULL;
            }
        } catch (Exception $e) {
            $view->userList = NULL;
        }
    }
}
