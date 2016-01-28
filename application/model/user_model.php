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

    public function login($user_login)
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
                $this->autoloadBO('user');
                $userBO = new UserBO();
                $userBO->setUserInfo($result);
                $userMetaInfoArray = $this->getMetaInfoUser($result->ID);
                $userBO->setUserMetaInfo($userMetaInfoArray);
                //write user info into session
                Session::init();
                Session::set('userInfo', json_encode($userBO));
                Session::set('user_id', $result->ID);
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
            } elseif ($result->user_status == USER_STATUS_DELETED) {
                $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
    }

    public function validateUpdateInfoUser($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER;
            return false;
        }
        if (!isset($para->user_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER;
            return false;
        } else {
            try {
                $para->user_id = (int) $para->user_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER;
                return false;
            }
        }

        if (isset($para->email) && $para->email != "") {
            if (!filter_var($para->email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["fb_error"][] = ERROR_USER_EMAIL_INVALID;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_USER_EMAIL;
            return false;
        }
        if (isset($para->role) && !in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            $_SESSION["fb_error"][] = ERROR_ROLE_OF_USER;
            return false;
        }
        if (isset($para->pass1) && $para->pass1 != "") {
            if (!isset($para->pass1_text) || $para->pass1 != $para->pass1_text) {
                $_SESSION["fb_error"][] = ERROR_USER_PASS;
                return false;
            }
            if (!in_array($this->analyzePassword($para->pass1), array("Better", "Medium", "Strong", "Strongest"))) {
                if (!(isset($para->pw_weak) && $para->pw_weak == "confirm")) {
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER_CONFIRM_WEAK_PASS;
                    return false;
                }
            }
        }
        return true;
    }

    public function validateAddNewUser($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_USER;
            return false;
        }
        if (isset($para->role) && !in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            $_SESSION["fb_error"][] = ERROR_ROLE_OF_USER;
            return false;
        }
        if (isset($para->user_login) && $para->user_login != "") {
            if ($this->hasUserWithUserLogin($para->user_login)) {
                $_SESSION["fb_error"][] = ERROR_USER_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_USER_LOGIN;
            return false;
        }
        if (isset($para->email) && $para->email != "") {
            if (!filter_var($para->email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["fb_error"][] = ERROR_USER_EMAIL_INVALID;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_USER_EMAIL;
            return false;
        }
        if (isset($para->pass1) && $para->pass1 != "") {
            if (!isset($para->pass1_text) || $para->pass1 != $para->pass1_text) {
                $_SESSION["fb_error"][] = ERROR_USER_PASS;
                return false;
            }
            if (!in_array($this->analyzePassword($para->pass1), array("Better", "Medium", "Strong", "Strongest"))) {
                if (!(isset($para->pw_weak) && $para->pw_weak == "confirm")) {
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER_CONFIRM_WEAK_PASS;
                    return false;
                }
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_USER_PASSWORD_EMPTY;
            return false;
        }
        return true;
    }

    public function analyzePassword($txtpass)
    {
        $desc = array("Very Weak", "Weak", "Better", "Medium", "Strong", "Strongest");
        $resultCheck;

        $score = 0;

        //if $txtpass bigger than 6 give 1 point
        if (strlen($txtpass) > 8) {
            $score++;

            //if $txtpass has both lower and uppercase characters give 1 point
            if (preg_match('/[a-z]/', $txtpass) && preg_match('/[A-Z]/', $txtpass))
                $score++;

            //if $txtpass has at least one number give 1 point
            if (preg_match('/\d+/', $txtpass))
                $score++;

            //if $txtpass has at least one special caracther give 1 point              
            if (preg_match('/.[!,@,#,$,%,^,&,*,?,_,~,-,+,`,|,},{,(,),\],\[,\\,\/,<,>,=,:,;,\,]/', $txtpass))
                $score++;

            //if $txtpass bigger than 12 give another 1 point
            if (strlen($txtpass) > 12)
                $score++;
            $resultCheck = $desc[$score];
        } else {
            $resultCheck = "Password Should be Minimum 8 Characters";
        }
        return $resultCheck;
    }

    public function updateInfoUser($para)
    {
        try {
            if ($this->validateUpdateInfoUser($para)) {
                BO::autoloadBO("user");
                $userBO = new UserBO();

                if (isset($para->user_id)) {
                    $userBO->user_id = $para->user_id;
                }
                if (isset($para->role)) {
                    $userBO->wp_capabilities = $para->role;
                }
                if (isset($para->first_name)) {
                    $userBO->first_name = $para->first_name;
                }
                if (isset($para->last_name)) {
                    $userBO->last_name = $para->last_name;
                }
                if (isset($para->nickname)) {
                    $userBO->nickname = $para->nickname;
                }
                if (isset($para->display_name)) {
                    $userBO->display_name = $para->display_name;
                }
                if (isset($para->email)) {
                    $userBO->user_email = $para->email;
                }
                if (isset($para->url)) {
                    $userBO->user_url = $para->url;
                }
                if (isset($para->description)) {
                    $userBO->description = $para->description;
                }
                if (isset($para->pass1_text)) {
                    $userBO->user_pass = $para->pass1_text;
                }

                $this->db->beginTransaction();
                if (isset($para->avatar)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
                    $imageModel->slider_thumb_height = SIZE_WITH_SLIDER_THUMB;
                    $avatar_array_id = $imageModel->uploadImages("avatar");

                    if (!is_null($avatar_array_id) && is_array($avatar_array_id) && sizeof($avatar_array_id) != 0) {
                        $avatar_id = $avatar_array_id[0];
                        $userBO->avatar = $avatar_id;
                        $is_change_avatar = true;
                        $userOld = $this->getUserByUserId($para->user_id);
                        $avatar_old = $userOld->avatar;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPDATE_AVATAR_FAILED;
                    }
                }

                if ($this->updateUser($userBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = UPDATE_USER_SUCCESS;
                    if (isset($is_change_avatar) && $is_change_avatar && isset($imageModel) && isset($avatar_old)) {
                        $imageModel->deletePost($avatar_old);
                    }
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER;
                    if ($is_change_avatar && isset($imageModel) && isset($avatar_id)) {
                        $imageModel->deletePost($avatar_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_USER;
        }
        return FALSE;
    }

    public function addNewUser($para)
    {
        try {
            if ($this->validateAddNewUser($para)) {
                BO::autoloadBO("user");
                $userBO = new UserBO();

                if (isset($para->user_login)) {
                    $userBO->user_login = $para->user_login;
                }
                if (isset($para->role)) {
                    $userBO->wp_capabilities = $para->role;
                }
                if (isset($para->first_name)) {
                    $userBO->first_name = $para->first_name;
                }
                if (isset($para->last_name)) {
                    $userBO->last_name = $para->last_name;
                }
                if (isset($para->nickname)) {
                    $userBO->nickname = $para->nickname;
                }
                if (isset($para->display_name)) {
                    $userBO->display_name = $para->display_name;
                }
                if (isset($para->email)) {
                    $userBO->user_email = $para->email;
                }
                if (isset($para->url)) {
                    $userBO->user_url = $para->url;
                }
                if (isset($para->description)) {
                    $userBO->description = $para->description;
                }
                if (isset($para->pass1_text)) {
                    $userBO->user_pass = $para->pass1_text;
                }

                $this->db->beginTransaction();
                if (isset($para->avatar)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
                    $avatar_array_id = $imageModel->uploadImages("avatar");

                    if (!is_null($avatar_array_id) && is_array($avatar_array_id) && sizeof($avatar_array_id) != 0) {
                        $avatar_id = $avatar_array_id[0];
                        $userBO->avatar = $avatar_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_AVATAR_FAILED;
                    }
                }

                if ($this->addUserIntoDB($userBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_USER_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_USER_SUCCESS;
                    if (isset($is_change_avatar) && $is_change_avatar && isset($imageModel) && isset($avatar_id)) {
                        $imageModel->deletePost($avatar_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_USER;
        }
        return FALSE;
    }

    public function getUserMeta($user_id, $meta_key)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERMETA . "
                                   WHERE  " . TB_USERMETA_COL_USER_ID . " = :user_id AND " . TB_USERMETA_COL_META_KEY . " = :meta_key; ");

        $sth->execute(array(':user_id' => $user_id, ':meta_key' => $meta_key));
        $count = $sth->rowCount();
        if ($count != 1) {
            return NULL;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        return $result->meta_value;
    }

    public function setUserMeta($user_id, $meta_key, $meta_value)
    {
        try {
            if (!is_null($this->getUserMeta($user_id, $meta_key))) {
                //update
                $sql = "UPDATE " . TABLE_USERMETA . " 
                    SET " . TB_USERMETA_COL_META_VALUE . " = :meta_value
                    WHERE " . TB_USERMETA_COL_USER_ID . " = :user_id AND " . TB_USERMETA_COL_META_KEY . " = :meta_key;";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':user_id' => $user_id, ':meta_key' => $meta_key));
                $count = $sth->rowCount();
                if ($count != 1) {
                    return false;
                }
            } else {
                //insert
                $sql = "INSERT INTO " . TABLE_USERMETA . " 
                                (" . TB_USERMETA_COL_USER_ID . ",
                                 " . TB_USERMETA_COL_META_KEY . ",
                                 " . TB_USERMETA_COL_META_VALUE . ")
                    VALUES (:user_id, :meta_key, :meta_value);";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':user_id' => $user_id, ':meta_key' => $meta_key));
                $count = $sth->rowCount();
                if ($count != 1) {
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function updateUser($userBO)
    {
        if (is_a($userBO, "UserBO")) {
            if (isset($userBO->user_id)) {
                $sql = "UPDATE " . TABLE_USERS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_USERS_COL_ID . " = :user_id;";

                $para_array = [];
                $para_array[":user_id"] = $userBO->user_id;

                if (isset($userBO->user_login)) {
                    $set .= " " . TB_USERS_COL_USER_LOGIN . " = :user_login,";
                    $para_array[":user_login"] = $userBO->user_login;
                }
                if (isset($userBO->user_pass) && $userBO->user_pass != "") {
                    $set .= " " . TB_USERS_COL_USER_PASS . " = :user_pass,";
                    $para_array[":user_pass"] = hash('sha1', $userBO->user_pass);
                }
                if (isset($userBO->user_nicename)) {
                    $set .= " " . TB_USERS_COL_USER_NICENAME . " = :user_nicename,";
                    $para_array[":user_nicename"] = $userBO->user_nicename;
                }
                if (isset($userBO->user_email)) {
                    $set .= " " . TB_USERS_COL_USER_EMAIL . " = :user_email,";
                    $para_array[":user_email"] = $userBO->user_email;
                }
                if (isset($userBO->user_url)) {
                    $set .= " " . TB_USERS_COL_USER_URL . " = :user_url,";
                    $para_array[":user_url"] = $userBO->user_url;
                }
                if (isset($userBO->user_registered)) {
                    $set .= " " . TB_USERS_COL_USER_REGISTERED . " = :user_registered,";
                    $para_array[":user_registered"] = $userBO->user_registered;
                }
                if (isset($userBO->user_activation_key)) {
                    $set .= " " . TB_USERS_COL_USER_ACTIVATION_KEY . " = :user_activation_key,";
                    $para_array[":user_activation_key"] = $userBO->user_activation_key;
                }
                if (isset($userBO->user_status)) {
                    $set .= " " . TB_USERS_COL_USER_STATUS . " = :user_status,";
                    $para_array[":user_status"] = $userBO->user_status;
                }
                if (isset($userBO->display_name)) {
                    $set .= " " . TB_USERS_COL_DISPLAY_NAME . " = :display_name,";
                    $para_array[":display_name"] = $userBO->display_name;
                }

                if (count($para_array) != 0) {
                    $set = substr($set, 0, strlen($set) - 1);
                    $sql .= $set . $where;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);
//                    $count = $sth->rowCount();
//                    if ($count == 1) {
//                        
//                    }
                    $user_id = $userBO->user_id;
                    if (isset($userBO->first_name) && $userBO->first_name != NULL) {
                        $this->setUserMeta($user_id, "first_name", $userBO->first_name);
                    }
                    if (isset($userBO->last_name) && $userBO->last_name != NULL) {
                        $this->setUserMeta($user_id, "last_name", $userBO->last_name);
                    }
                    if (isset($userBO->description) && $userBO->description != NULL) {
                        $this->setUserMeta($user_id, "description", $userBO->description);
                    }
                    if (isset($userBO->avatar) && $userBO->avatar != NULL) {
                        $this->setUserMeta($user_id, "avatar", $userBO->avatar);
                    }
                    if (isset($userBO->wp_capabilities) && $userBO->wp_capabilities != NULL) {
                        $this->setUserMeta($user_id, "wp_capabilities", $userBO->wp_capabilities);
                    }
                    if (isset($userBO->session_tokens) && $userBO->session_tokens != NULL) {
                        $this->setUserMeta($user_id, "session_tokens", $userBO->session_tokens);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function addUserIntoDB($userBO)
    {
        try {
            if (is_a($userBO, "UserBO")) {
                $sql = "INSERT INTO " . TABLE_USERS . " ";
                $column = " ( ";
                $value = " VALUES ( ";

                $para_array = [];

                if (isset($userBO->user_login)) {
                    $column .= " " . TB_USERS_COL_USER_LOGIN . ",";
                    $value .= " :user_login,";
                    $para_array[":user_login"] = $userBO->user_login;
                }
                if (isset($userBO->user_pass) && $userBO->user_pass != "") {
                    $column .= " " . TB_USERS_COL_USER_PASS . ",";
                    $value .= " :user_pass,";
                    $para_array[":user_pass"] = hash('sha1', $userBO->user_pass);
                }
                if (isset($userBO->user_nicename)) {
                    $column .= " " . TB_USERS_COL_USER_NICENAME . ",";
                    $value .= " :user_nicename,";
                    $para_array[":user_nicename"] = $userBO->user_nicename;
                }
                if (isset($userBO->user_email)) {
                    $column .= " " . TB_USERS_COL_USER_EMAIL . ",";
                    $value .= " :user_email,";
                    $para_array[":user_email"] = $userBO->user_email;
                }
                if (isset($userBO->user_url)) {
                    $column .= " " . TB_USERS_COL_USER_URL . ",";
                    $value .= " :user_url,";
                    $para_array[":user_url"] = $userBO->user_url;
                }
                if (isset($userBO->user_registered)) {
                    $column .= " " . TB_USERS_COL_USER_REGISTERED . ",";
                    $value .= " :user_registered,";
                    $para_array[":user_registered"] = $userBO->user_registered;
                }
                if (isset($userBO->user_activation_key)) {
                    $column .= " " . TB_USERS_COL_USER_ACTIVATION_KEY . ",";
                    $value .= " :user_activation_key,";
                    $para_array[":user_activation_key"] = $userBO->user_activation_key;
                }
                if (isset($userBO->user_status)) {
                    $column .= " " . TB_USERS_COL_USER_STATUS . ",";
                    $value .= " :user_status,";
                    $para_array[":user_status"] = $userBO->user_status;
                }
                if (isset($userBO->display_name)) {
                    $column .= " " . TB_USERS_COL_DISPLAY_NAME . ",";
                    $value .= " :display_name,";
                    $para_array[":display_name"] = $userBO->display_name;
                }

                if (count($para_array) != 0) {
                    $column = substr($column, 0, strlen($column) - 1) . " ) ";
                    $value = substr($value, 0, strlen($value) - 1) . " ) ";
                    $sql .= $column . $value;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);
                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $user_id = $this->db->lastInsertId();
                        if (isset($userBO->first_name) && $userBO->first_name != NULL) {
                            $this->setUserMeta($user_id, "first_name", $userBO->first_name);
                        }
                        if (isset($userBO->last_name) && $userBO->last_name != NULL) {
                            $this->setUserMeta($user_id, "last_name", $userBO->last_name);
                        }
                        if (isset($userBO->description) && $userBO->description != NULL) {
                            $this->setUserMeta($user_id, "description", $userBO->description);
                        }
                        if (isset($userBO->avatar) && $userBO->avatar != NULL) {
                            $this->setUserMeta($user_id, "avatar", $userBO->avatar);
                        }
                        if (isset($userBO->wp_capabilities) && $userBO->wp_capabilities != NULL) {
                            $this->setUserMeta($user_id, "wp_capabilities", $userBO->wp_capabilities);
                        }
                        if (isset($userBO->session_tokens) && $userBO->session_tokens != NULL) {
                            $this->setUserMeta($user_id, "session_tokens", $userBO->session_tokens);
                        }
                        return true;
                    }
                }
            }
        } catch (Exception $e) {
            
        }
        return false;
    }

    public function insertUser($userBO)
    {
        try {
            if (is_a($userBO, "UserBO")) {

                //insert
                $sql = "INSERT INTO " . TABLE_USERS . " ";
                $column = " ( ";
                $value = " VALUES ( ";
                $para_array = [];
                if (isset($userBO->user_pass)) {
                    $column .= " " . TB_USERS_COL_USER_PASS . ",";
                    $value .= " :user_pass,";
                    $para_array[":user_pass"] = hash('sha1', $userBO->user_pass);
                }
                if (isset($userBO->user_nicename)) {
                    $column .= " " . TB_USERS_COL_USER_NICENAME . ",";
                    $value .= " :user_nicename,";
                    $para_array[":user_nicename"] = $userBO->user_nicename;
                }
                if (isset($userBO->user_email)) {
                    $column .= " " . TB_USERS_COL_USER_EMAIL . ",";
                    $value .= " :user_email,";
                    $para_array[":user_email"] = $userBO->user_email;
                }
                if (isset($userBO->user_url)) {
                    $column .= " " . TB_USERS_COL_USER_URL . ",";
                    $value .= " :user_url,";
                    $para_array[":user_url"] = $userBO->user_url;
                }
                if (isset($userBO->user_registered)) {
                    $column .= " " . TB_USERS_COL_USER_REGISTERED . ",";
                    $value .= " :user_registered,";
                    $para_array[":user_registered"] = $userBO->user_registered;
                }
                if (isset($userBO->user_activation_key)) {
                    $column .= " " . TB_USERS_COL_USER_ACTIVATION_KEY . ",";
                    $value .= " :user_activation_key,";
                    $para_array[":user_activation_key"] = $userBO->user_activation_key;
                }
                if (isset($userBO->user_status)) {
                    $column .= " " . TB_USERS_COL_USER_STATUS . ",";
                    $value .= " :user_status,";
                    $para_array[":user_status"] = $userBO->user_status;
                }
                if (isset($userBO->display_name)) {
                    $column .= " " . TB_USERS_COL_DISPLAY_NAME . ",";
                    $value .= " :display_name,";
                    $para_array[":display_name"] = $userBO->display_name;
                }

                if (count($para_array) != 0) {
                    $column = substr($column, 0, strlen($column) - 1) . ") ";
                    $value = substr($value, 0, strlen($value) - 1) . "); ";

                    $sql .= $column . $value;

                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);
                    $count = $sth->rowCount();
                    if ($count == 1) {
                        $user_id = $this->db->lastInsertId();

                        if (isset($userBO->first_name)) {
                            $this->setUserMeta($user_id, "first_name", $userBO->first_name);
                        }
                        if (isset($userBO->last_name)) {
                            $this->setUserMeta($user_id, "last_name", $userBO->last_name);
                        }
                        if (isset($userBO->description)) {
                            $this->setUserMeta($user_id, "description", $userBO->description);
                        }
                        if (isset($userBO->avatar)) {
                            $this->setUserMeta($user_id, "avatar", $userBO->avatar);
                        }
                        if (isset($userBO->wp_capabilities)) {
                            $this->setUserMeta($user_id, "wp_capabilities", $userBO->wp_capabilities);
                        }
                        if (isset($userBO->session_tokens)) {
                            $this->setUserMeta($user_id, "session_tokens", $userBO->session_tokens);
                        }
                        return $user_id;
                    }
                }
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function getUserByUserId($user_id)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERS . "
                                   WHERE  " . TB_USERS_COL_ID . " = :user_id");

        $sth->execute(array(':user_id' => $user_id));
        $count = $sth->rowCount();
        if ($count != 1) {
            $_SESSION["fb_error"][] = ERR_USER_INFO_NOT_FOUND;
            return null;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        if ($result->user_status != USER_STATUS_DELETED) {
            $this->autoloadBO('user');
            $userBO = new UserBO();
            $userBO->setUserInfo($result);
            $userMetaInfoArray = $this->getMetaInfoUser($result->ID);
            $userBO->setUserMetaInfo($userMetaInfoArray);
            return $userBO;
        }
        return null;
    }

    public function hasUserWithUserLogin($user_login)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERS . "
                                   WHERE  " . TB_USERS_COL_USER_LOGIN . " = :user_login");

        $sth->execute(array(':user_login' => $user_login));
        $count = $sth->rowCount();
        if ($count != 0) {
            return true;
        }
        return false;
    }

    public function getMetaInfoUser($user_id)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_USERMETA . "
                                   WHERE  " . TB_USERMETA_COL_USER_ID . " = :user_id");

        $sth->execute(array(':user_id' => $user_id));
        $count = $sth->rowCount();
        if ($count > 0) {
            $userMetaInfoArray = $sth->fetchAll();
            foreach ($userMetaInfoArray as $userMeta) {
                if (isset($userMeta->meta_key) && $userMeta->meta_key == "manage_users_columns_show") {
                    try {
                        $userMeta->meta_value = json_decode($userMeta->meta_value);
                    } catch (Exception $e) {
                        $userMeta->meta_value = NULL;
                    }
                }
                if (isset($userMeta->meta_key) && $userMeta->meta_key == "avatar") {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $avatar_object = new stdClass();
                    $avatar_object->umeta_id = $userMeta->umeta_id;
                    $avatar_object->user_id = $userMeta->user_id;
                    $avatar_object->meta_key = 'avatar_object';
                    $avatar_object->meta_value = $imageModel->getPost($userMeta->meta_value);
                    $userMetaInfoArray[] = $avatar_object;
                }
            }
            return $userMetaInfoArray;
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
//        $this->detroyCookieLogin();
        // delete the session
        Session::destroy();
    }

    public function updateUsersPerPages($users_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "users_per_page";
        $meta_value = $users_per_page;
        $this->setUserMeta($user_id, $meta_key, $meta_value);
    }

    public function updateShowHideColumn($email_show, $role_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_users_columns_show";
        $meta_value = new stdClass();
        $meta_value->email_show = $email_show;
        $meta_value->role_show = $role_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        $this->setUserMeta($user_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->users_per_page) && is_numeric($para->users_per_page)) {
            $this->updateUsersPerPages($para->users_per_page);
        }
        $email_show = false;
        $role_show = false;
        $tours_show = false;
        if (isset($para->email_show) && $para->email_show == "email") {
            $email_show = true;
        }
        if (isset($para->role_show) && $para->role_show == "role") {
            $role_show = true;
        }
        if (isset($para->tours_show) && $para->tours_show == "tours") {
            $tours_show = true;
        }
        $this->updateShowHideColumn($email_show, $role_show, $tours_show);
        $userBO = $this->getUserByUserId(Session::get("user_id"));
        $this->setNewSessionUser($userBO);
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
        if (!is_null($action)) {
            switch ($action) {
                case "delete":
                    $this->executeActionDelete($para);
                    break;
            }
        }
    }

    public function setNewSessionUser($userBO)
    {
        if (is_a($userBO, "UserBO") && isset($userBO->user_id) && Session::get('user_id') == $userBO->user_id) {
            Session::init();
            Session::set('userInfo', json_encode($userBO));
            Session::set('user_id', $userBO->user_id);
            Session::set('user_logged_in', true);
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

            if (in_array("1", $para->users)) {
                $user_delete = $this->getUserByUserId("1");
                if (!is_null($user_delete)) {
                    $_SESSION["fb_error"][] = ERROR_DELETE_USER_NOT_PERMISSION . " <strong>" . $user_delete->user_login . "</strong>";
                } else {
                    $_SESSION["fb_error"][] = ERR_USER_INFO_NOT_FOUND;
                }
            }
            if (in_array($user_id, $para->users) && $user_id != "1") {
                $_SESSION["fb_error"][] = ERROR_DELETE_USER_NOT_PERMISSION . " <strong>" . $userBO->user_login . "</strong>";
            }

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
            $user_id = Session::get('user_id');

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
        if (!is_null($role)) {
            $this->executeChangeRole($para, $role);
        }
    }

    public function prepareIndexPage($view, $para)
    {
        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT u.* ";
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
                $userLoginBO = json_decode(Session::get("userInfo"));
                
                $users_per_page = USERS_PER_PAGE_DEFAULT;
                if ($userLoginBO != NULL) {
                    if (isset($userLoginBO->users_per_page) && is_numeric($userLoginBO->users_per_page)) {
                        $users_per_page = (int) $userLoginBO->users_per_page;
                    }
                }

                if (!isset($users_per_page)) {
                    if (!isset($_SESSION['options'])) {
                        $_SESSION['options'] = new stdClass();
                        $_SESSION['options']->users_per_page = USERS_PER_PAGE_DEFAULT;
                        $users_per_page = USERS_PER_PAGE_DEFAULT;
                    } elseif (!isset($_SESSION['options']->users_per_page)) {
                        $_SESSION['options']->users_per_page = USERS_PER_PAGE_DEFAULT;
                        $users_per_page = USERS_PER_PAGE_DEFAULT;
                    }
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
                    $view->pageNumber = floor($view->count[NUMBER_SEARCH_USER] / $users_per_page);
                    if ($view->count[NUMBER_SEARCH_USER] % $users_per_page != 0) {
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
                    $startUser = ($page - 1) * $users_per_page;
                    $sqlLimit = " LIMIT " . $users_per_page . " OFFSET " . $startUser;

                    $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                    $sth = $this->db->prepare($sqlAll);
                    $sth->execute($paraSQL);
                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $userList = $sth->fetchAll();
                        for ($i = 0; $i < sizeof($userList); $i++) {
                            $userInfo = $userList[$i];
                            $this->autoloadBO('user');
                            $userBO = new UserBO();
                            $userBO->setUserInfo($userInfo);
                            $userMetaInfoArray = $this->getMetaInfoUser($userInfo->ID);
                            $userBO->setUserMetaInfo($userMetaInfoArray);
                            $userList[$i] = $userBO;
                        }
                        $view->userList = $userList;
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
