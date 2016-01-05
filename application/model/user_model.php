<?php
/*
 * UserModel 
 */
/**
 * Description of UserModel
 *
 * @author khanh_000
 */
use Gregwar\Captcha\CaptchaBuilder;

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

    public function getUserList($view)
    {
//        $view->a = 1;
        try {
            $sth = $this->db->prepare("
            SELECT u." . TB_USERS_COL_ID . " as '" . USER_ID . "', u." . TB_USERS_COL_USER_LOGIN . ", u." . TB_USERS_COL_DISPLAY_NAME . ", 
            u." . TB_USERS_COL_USER_EMAIL . ", m." . TB_USERMETA_COL_META_VALUE . " AS '" . WP_CAPABILITIES . "'
            FROM " . TABLE_USERS . " AS u, " . TABLE_USERMETA . " AS m 
            WHERE m." . TB_USERMETA_COL_USER_ID . " = u." . TB_USERS_COL_ID . " 
            AND m." . TB_USERMETA_COL_META_KEY . " = '" . WP_CAPABILITIES . "'
            AND user_status != " . USER_STATUS_DELETED . ";");

            $sth->execute();
            $count = $sth->rowCount();
            if ($count > 0) {
                $view->userList = $sth->fetchAll();
                foreach ($view->userList as $userInfo) {
                    $userInfo->wp_capabilities = Auth::getRole($userInfo->wp_capabilities);
                    $userInfo->wp_capabilities_title = ucfirst($userInfo->wp_capabilities);
                }
            } else {
                $view->userList = NULL;
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
            $view->userList = NULL;
        }
    }
}
