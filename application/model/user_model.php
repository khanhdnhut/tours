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

    public function saveCookieLogin()
    {
        
    }

    public function detroyCookieLogin()
    {
        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
    }

    public function logout()
    {
        $this->detroyCookieLogin();
        // delete the session
        Session::destroy();
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

            if (isset($para->orderby) && in_array($para->orderby, array("login", "name", "email"))) {
                switch ($para->orderby) {
                    case "login":
                        $para->orderby = TB_USERS_COL_USER_LOGIN;
                        break;
                    case "name":
                        $para->orderby = TB_USERS_COL_DISPLAY_NAME;
                        break;
                    case "email":
                        $para->orderby = TB_USERS_COL_USER_EMAIL;
                        break;
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $paraSQL['order'] = $para->order;
                } else {
                    $para->order = "asc";
                }
                $sqlOrderby = " ORDER BY u." . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY u." . TB_USERS_COL_USER_REGISTERED . " ASC";
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
                $view->pageNumber = $countUser / $_SESSION['options']->users_per_page;
                if ($countUser % $_SESSION['options']->users_per_page != 0) {
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
            }

            $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
            $sth = $this->db->prepare($sqlAll);
            $sth->execute($paraSQL);
            $count = $sth->rowCount();
            if ($count > 0) {
                $view->userList = $sth->fetchAll();
            } else {
                $view->userList = NULL;
            }
        } catch (Exception $e) {
            $view->userList = NULL;
        }
    }
}
