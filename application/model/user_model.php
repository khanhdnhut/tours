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

class UserModel extends Model {

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db) {
        parent::__construct($db);
    }
    
    public function loginValidate() {
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
    
    public function getUserByUserLogin($user_login) {
        $sth = $this->db->prepare("SELECT *
                                   FROM   ".TABLE_USERS."
                                   WHERE  ".TB_USERS_COL_USER_LOGIN." = :user_login");
        
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
                $userBO = $this->loadBO('user');
                $userBO->setUserInfo($result);
                $userMetaInfoArray = $this->getMetaInfoUser($result->ID);
                $userBO->setUserMetaInfo($userMetaInfoArray);
                //write user info into session
                Session::init();
                Session::set('userInfo', $userBO);
                Session::set('user_logged_in', true);
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
    
    public function getMetaInfoUser($user_id){        
        $sth = $this->db->prepare("SELECT *
                                   FROM   ".TABLE_USERMETA."
                                   WHERE  ".TB_USERMETA_COL_USER_ID." = :user_id");
        
        $sth->execute(array(':user_id' => $user_id));
        $count = $sth->rowCount();
        if ($count > 0) {
            return $sth->fetchAll();            
        } else {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }        
    }
}