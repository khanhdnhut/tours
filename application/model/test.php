<?php

class CountryModel extends Model
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
            $_SESSION["fb_error"][] = ERR_COUNTRYNAME_EMPTY;
            return false;
        }
        if (!isset($_POST['pwd']) OR empty($_POST['pwd'])) {
            $_SESSION["fb_error"][] = ERR_PASSWORD_EMPTY;
            return false;
        }
        return true;
    }

    public function login($country_login)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_COUNTRYS . "
                                   WHERE  " . TB_COUNTRYS_COL_COUNTRY_LOGIN . " = :country_login");

        $sth->execute(array(':country_login' => $country_login));
        $count = $sth->rowCount();
        if ($count != 1) {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        if (hash('sha1', $_POST['pwd']) == $result->country_pass) {
            if ($result->country_status == COUNTRY_STATUS_ACTIVE) {
                $this->autoloadBO('country');
                $countryBO = new CountryBO();
                $countryBO->setCountryInfo($result);
                $countryMetaInfoArray = $this->getMetaInfoCountry($result->ID);
                $countryBO->setCountryMetaInfo($countryMetaInfoArray);
                //write country info into session
                Session::init();
                Session::set('countryInfo', json_encode($countryBO));
                Session::set('country_id', $result->ID);
                Session::set('country_logged_in', true);
                if (isset($_POST['rememberme']) && $_POST['rememberme'] == 'forever') {
                    $this->saveCookieLogin();
                }
                return true;
            } elseif ($result->country_status == COUNTRY_STATUS_NOT_ACTIVE) {
                $_SESSION["fb_error"][] = ERR_COUNTRY_NOT_ACTIVE;
                return false;
            } elseif ($result->country_status == COUNTRY_STATUS_BLOCKED) {
                $_SESSION["fb_error"][] = ERR_COUNTRY_BLOCKED;
                return false;
            } elseif ($result->country_status == COUNTRY_STATUS_DELETED) {
                $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERR_LOGIN_FAILED;
            return false;
        }
    }

    public function validateUpdateInfoCountry($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        }
        if (!isset($para->country_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        } else {
            try {
                $para->country_id = (int) $para->country_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
                return false;
            }
        }

        if (isset($para->email) && $para->email != "") {
            if (!filter_var($para->email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_EMAIL_INVALID;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_EMAIL;
            return false;
        }
        if (isset($para->role) && !in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            $_SESSION["fb_error"][] = ERROR_ROLE_OF_COUNTRY;
            return false;
        }
        if (isset($para->pass1) && $para->pass1 != "") {
            if (!isset($para->pass1_text) || $para->pass1 != $para->pass1_text) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_PASS;
                return false;
            }
            if (!in_array($this->analyzePassword($para->pass1), array("Better", "Medium", "Strong", "Strongest"))) {
                if (!(isset($para->pw_weak) && $para->pw_weak == "confirm")) {
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY_CONFIRM_WEAK_PASS;
                    return false;
                }
            }
        }
        return true;
    }

    public function validateAddNewCountry($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_COUNTRY;
            return false;
        }
        if (isset($para->role) && !in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            $_SESSION["fb_error"][] = ERROR_ROLE_OF_COUNTRY;
            return false;
        }
        if (isset($para->country_login) && $para->country_login != "") {
            if ($this->hasCountryWithCountryLogin($para->country_login)) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_LOGIN;
            return false;
        }
        if (isset($para->email) && $para->email != "") {
            if (!filter_var($para->email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_EMAIL_INVALID;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_EMAIL;
            return false;
        }
        if (isset($para->pass1) && $para->pass1 != "") {
            if (!isset($para->pass1_text) || $para->pass1 != $para->pass1_text) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_PASS;
                return false;
            }
            if (!in_array($this->analyzePassword($para->pass1), array("Better", "Medium", "Strong", "Strongest"))) {
                if (!(isset($para->pw_weak) && $para->pw_weak == "confirm")) {
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY_CONFIRM_WEAK_PASS;
                    return false;
                }
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_PASSWORD_EMPTY;
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

    public function updateInfoCountry($para)
    {
        try {
            if ($this->validateUpdateInfoCountry($para)) {
                BO::autoloadBO("country");
                $countryBO = new CountryBO();

                if (isset($para->country_id)) {
                    $countryBO->country_id = $para->country_id;
                }
                if (isset($para->role)) {
                    $countryBO->wp_capabilities = $para->role;
                }
                if (isset($para->first_name)) {
                    $countryBO->first_name = $para->first_name;
                }
                if (isset($para->last_name)) {
                    $countryBO->last_name = $para->last_name;
                }
                if (isset($para->nickname)) {
                    $countryBO->nickname = $para->nickname;
                }
                if (isset($para->display_name)) {
                    $countryBO->display_name = $para->display_name;
                }
                if (isset($para->email)) {
                    $countryBO->country_email = $para->email;
                }
                if (isset($para->url)) {
                    $countryBO->country_url = $para->url;
                }
                if (isset($para->description)) {
                    $countryBO->description = $para->description;
                }
                if (isset($para->pass1_text)) {
                    $countryBO->country_pass = $para->pass1_text;
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
                        $countryBO->avatar = $avatar_id;
                        $is_change_avatar = true;
                        $countryOld = $this->getCountryByCountryId($para->country_id);
                        $avatar_old = $countryOld->avatar;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPDATE_AVATAR_FAILED;
                    }
                }

                if ($this->updateCountry($countryBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = UPDATE_COUNTRY_SUCCESS;
                    if (isset($is_change_avatar) && $is_change_avatar && isset($imageModel) && isset($avatar_old)) {
                        $imageModel->deletePost($avatar_old);
                    }
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
                    if ($is_change_avatar && isset($imageModel) && isset($avatar_id)) {
                        $imageModel->deletePost($avatar_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
        }
        return FALSE;
    }

    public function addNewCountry($para)
    {
        try {
            if ($this->validateAddNewCountry($para)) {
                BO::autoloadBO("country");
                $countryBO = new CountryBO();

                if (isset($para->country_login)) {
                    $countryBO->country_login = $para->country_login;
                }
                if (isset($para->role)) {
                    $countryBO->wp_capabilities = $para->role;
                }
                if (isset($para->first_name)) {
                    $countryBO->first_name = $para->first_name;
                }
                if (isset($para->last_name)) {
                    $countryBO->last_name = $para->last_name;
                }
                if (isset($para->nickname)) {
                    $countryBO->nickname = $para->nickname;
                }
                if (isset($para->display_name)) {
                    $countryBO->display_name = $para->display_name;
                }
                if (isset($para->email)) {
                    $countryBO->country_email = $para->email;
                }
                if (isset($para->url)) {
                    $countryBO->country_url = $para->url;
                }
                if (isset($para->description)) {
                    $countryBO->description = $para->description;
                }
                if (isset($para->pass1_text)) {
                    $countryBO->country_pass = $para->pass1_text;
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
                        $countryBO->avatar = $avatar_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_AVATAR_FAILED;
                    }
                }

                if ($this->addCountryIntoDB($countryBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_COUNTRY_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_COUNTRY_SUCCESS;
                    if (isset($is_change_avatar) && $is_change_avatar && isset($imageModel) && isset($avatar_id)) {
                        $imageModel->deletePost($avatar_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
        }
        return FALSE;
    }

    public function getCountryMeta($country_id, $meta_key)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_COUNTRYMETA . "
                                   WHERE  " . TB_COUNTRYMETA_COL_COUNTRY_ID . " = :country_id AND " . TB_COUNTRYMETA_COL_META_KEY . " = :meta_key; ");

        $sth->execute(array(':country_id' => $country_id, ':meta_key' => $meta_key));
        $count = $sth->rowCount();
        if ($count != 1) {
            return NULL;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        return $result->meta_value;
    }

    public function setCountryMeta($country_id, $meta_key, $meta_value)
    {
        try {
            if (!is_null($this->getCountryMeta($country_id, $meta_key))) {
                //update
                $sql = "UPDATE " . TABLE_COUNTRYMETA . " 
                    SET " . TB_COUNTRYMETA_COL_META_VALUE . " = :meta_value
                    WHERE " . TB_COUNTRYMETA_COL_COUNTRY_ID . " = :country_id AND " . TB_COUNTRYMETA_COL_META_KEY . " = :meta_key;";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':country_id' => $country_id, ':meta_key' => $meta_key));
                $count = $sth->rowCount();
                if ($count != 1) {
                    return false;
                }
            } else {
                //insert
                $sql = "INSERT INTO " . TABLE_COUNTRYMETA . " 
                                (" . TB_COUNTRYMETA_COL_COUNTRY_ID . ",
                                 " . TB_COUNTRYMETA_COL_META_KEY . ",
                                 " . TB_COUNTRYMETA_COL_META_VALUE . ")
                    VALUES (:country_id, :meta_key, :meta_value);";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':country_id' => $country_id, ':meta_key' => $meta_key));
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

    public function updateCountry($countryBO)
    {
        if (is_a($countryBO, "CountryBO")) {
            if (isset($countryBO->country_id)) {
                $sql = "UPDATE " . TABLE_COUNTRYS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_COUNTRYS_COL_ID . " = :country_id;";

                $para_array = [];
                $para_array[":country_id"] = $countryBO->country_id;

                if (isset($countryBO->country_login)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_LOGIN . " = :country_login,";
                    $para_array[":country_login"] = $countryBO->country_login;
                }
                if (isset($countryBO->country_pass) && $countryBO->country_pass != "") {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_PASS . " = :country_pass,";
                    $para_array[":country_pass"] = hash('sha1', $countryBO->country_pass);
                }
                if (isset($countryBO->country_nicename)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_NICENAME . " = :country_nicename,";
                    $para_array[":country_nicename"] = $countryBO->country_nicename;
                }
                if (isset($countryBO->country_email)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_EMAIL . " = :country_email,";
                    $para_array[":country_email"] = $countryBO->country_email;
                }
                if (isset($countryBO->country_url)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_URL . " = :country_url,";
                    $para_array[":country_url"] = $countryBO->country_url;
                }
                if (isset($countryBO->country_registered)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_REGISTERED . " = :country_registered,";
                    $para_array[":country_registered"] = $countryBO->country_registered;
                }
                if (isset($countryBO->country_activation_key)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_ACTIVATION_KEY . " = :country_activation_key,";
                    $para_array[":country_activation_key"] = $countryBO->country_activation_key;
                }
                if (isset($countryBO->country_status)) {
                    $set .= " " . TB_COUNTRYS_COL_COUNTRY_STATUS . " = :country_status,";
                    $para_array[":country_status"] = $countryBO->country_status;
                }
                if (isset($countryBO->display_name)) {
                    $set .= " " . TB_COUNTRYS_COL_DISPLAY_NAME . " = :display_name,";
                    $para_array[":display_name"] = $countryBO->display_name;
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
                    $country_id = $countryBO->country_id;
                    if (isset($countryBO->first_name) && $countryBO->first_name != NULL) {
                        $this->setCountryMeta($country_id, "first_name", $countryBO->first_name);
                    }
                    if (isset($countryBO->last_name) && $countryBO->last_name != NULL) {
                        $this->setCountryMeta($country_id, "last_name", $countryBO->last_name);
                    }
                    if (isset($countryBO->description) && $countryBO->description != NULL) {
                        $this->setCountryMeta($country_id, "description", $countryBO->description);
                    }
                    if (isset($countryBO->avatar) && $countryBO->avatar != NULL) {
                        $this->setCountryMeta($country_id, "avatar", $countryBO->avatar);
                    }
                    if (isset($countryBO->wp_capabilities) && $countryBO->wp_capabilities != NULL) {
                        $this->setCountryMeta($country_id, "wp_capabilities", $countryBO->wp_capabilities);
                    }
                    if (isset($countryBO->session_tokens) && $countryBO->session_tokens != NULL) {
                        $this->setCountryMeta($country_id, "session_tokens", $countryBO->session_tokens);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function addCountryIntoDB($countryBO)
    {
        try {
            if (is_a($countryBO, "CountryBO")) {
                $sql = "INSERT INTO " . TABLE_COUNTRYS . " ";
                $column = " ( ";
                $value = " VALUES ( ";

                $para_array = [];

                if (isset($countryBO->country_login)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_LOGIN . ",";
                    $value .= " :country_login,";
                    $para_array[":country_login"] = $countryBO->country_login;
                }
                if (isset($countryBO->country_pass) && $countryBO->country_pass != "") {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_PASS . ",";
                    $value .= " :country_pass,";
                    $para_array[":country_pass"] = hash('sha1', $countryBO->country_pass);
                }
                if (isset($countryBO->country_nicename)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_NICENAME . ",";
                    $value .= " :country_nicename,";
                    $para_array[":country_nicename"] = $countryBO->country_nicename;
                }
                if (isset($countryBO->country_email)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_EMAIL . ",";
                    $value .= " :country_email,";
                    $para_array[":country_email"] = $countryBO->country_email;
                }
                if (isset($countryBO->country_url)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_URL . ",";
                    $value .= " :country_url,";
                    $para_array[":country_url"] = $countryBO->country_url;
                }
                if (isset($countryBO->country_registered)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_REGISTERED . ",";
                    $value .= " :country_registered,";
                    $para_array[":country_registered"] = $countryBO->country_registered;
                }
                if (isset($countryBO->country_activation_key)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_ACTIVATION_KEY . ",";
                    $value .= " :country_activation_key,";
                    $para_array[":country_activation_key"] = $countryBO->country_activation_key;
                }
                if (isset($countryBO->country_status)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_STATUS . ",";
                    $value .= " :country_status,";
                    $para_array[":country_status"] = $countryBO->country_status;
                }
                if (isset($countryBO->display_name)) {
                    $column .= " " . TB_COUNTRYS_COL_DISPLAY_NAME . ",";
                    $value .= " :display_name,";
                    $para_array[":display_name"] = $countryBO->display_name;
                }

                if (count($para_array) != 0) {
                    $column = substr($column, 0, strlen($column) - 1) . " ) ";
                    $value = substr($value, 0, strlen($value) - 1) . " ) ";
                    $sql .= $column . $value;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);
                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $country_id = $this->db->lastInsertId();
                        if (isset($countryBO->first_name) && $countryBO->first_name != NULL) {
                            $this->setCountryMeta($country_id, "first_name", $countryBO->first_name);
                        }
                        if (isset($countryBO->last_name) && $countryBO->last_name != NULL) {
                            $this->setCountryMeta($country_id, "last_name", $countryBO->last_name);
                        }
                        if (isset($countryBO->description) && $countryBO->description != NULL) {
                            $this->setCountryMeta($country_id, "description", $countryBO->description);
                        }
                        if (isset($countryBO->avatar) && $countryBO->avatar != NULL) {
                            $this->setCountryMeta($country_id, "avatar", $countryBO->avatar);
                        }
                        if (isset($countryBO->wp_capabilities) && $countryBO->wp_capabilities != NULL) {
                            $this->setCountryMeta($country_id, "wp_capabilities", $countryBO->wp_capabilities);
                        }
                        if (isset($countryBO->session_tokens) && $countryBO->session_tokens != NULL) {
                            $this->setCountryMeta($country_id, "session_tokens", $countryBO->session_tokens);
                        }
                        return true;
                    }
                }
            }
        } catch (Exception $e) {
            
        }
        return false;
    }

    public function insertCountry($countryBO)
    {
        try {
            if (is_a($countryBO, "CountryBO")) {

                //insert
                $sql = "INSERT INTO " . TABLE_COUNTRYS . " ";
                $column = " ( ";
                $value = " VALUES ( ";
                $para_array = [];
                if (isset($countryBO->country_pass)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_PASS . ",";
                    $value .= " :country_pass,";
                    $para_array[":country_pass"] = hash('sha1', $countryBO->country_pass);
                }
                if (isset($countryBO->country_nicename)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_NICENAME . ",";
                    $value .= " :country_nicename,";
                    $para_array[":country_nicename"] = $countryBO->country_nicename;
                }
                if (isset($countryBO->country_email)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_EMAIL . ",";
                    $value .= " :country_email,";
                    $para_array[":country_email"] = $countryBO->country_email;
                }
                if (isset($countryBO->country_url)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_URL . ",";
                    $value .= " :country_url,";
                    $para_array[":country_url"] = $countryBO->country_url;
                }
                if (isset($countryBO->country_registered)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_REGISTERED . ",";
                    $value .= " :country_registered,";
                    $para_array[":country_registered"] = $countryBO->country_registered;
                }
                if (isset($countryBO->country_activation_key)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_ACTIVATION_KEY . ",";
                    $value .= " :country_activation_key,";
                    $para_array[":country_activation_key"] = $countryBO->country_activation_key;
                }
                if (isset($countryBO->country_status)) {
                    $column .= " " . TB_COUNTRYS_COL_COUNTRY_STATUS . ",";
                    $value .= " :country_status,";
                    $para_array[":country_status"] = $countryBO->country_status;
                }
                if (isset($countryBO->display_name)) {
                    $column .= " " . TB_COUNTRYS_COL_DISPLAY_NAME . ",";
                    $value .= " :display_name,";
                    $para_array[":display_name"] = $countryBO->display_name;
                }

                if (count($para_array) != 0) {
                    $column = substr($column, 0, strlen($column) - 1) . ") ";
                    $value = substr($value, 0, strlen($value) - 1) . "); ";

                    $sql .= $column . $value;

                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);
                    $count = $sth->rowCount();
                    if ($count == 1) {
                        $country_id = $this->db->lastInsertId();

                        if (isset($countryBO->first_name)) {
                            $this->setCountryMeta($country_id, "first_name", $countryBO->first_name);
                        }
                        if (isset($countryBO->last_name)) {
                            $this->setCountryMeta($country_id, "last_name", $countryBO->last_name);
                        }
                        if (isset($countryBO->description)) {
                            $this->setCountryMeta($country_id, "description", $countryBO->description);
                        }
                        if (isset($countryBO->avatar)) {
                            $this->setCountryMeta($country_id, "avatar", $countryBO->avatar);
                        }
                        if (isset($countryBO->wp_capabilities)) {
                            $this->setCountryMeta($country_id, "wp_capabilities", $countryBO->wp_capabilities);
                        }
                        if (isset($countryBO->session_tokens)) {
                            $this->setCountryMeta($country_id, "session_tokens", $countryBO->session_tokens);
                        }
                        return $country_id;
                    }
                }
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function getCountryByCountryId($country_id)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_COUNTRYS . "
                                   WHERE  " . TB_COUNTRYS_COL_ID . " = :country_id");

        $sth->execute(array(':country_id' => $country_id));
        $count = $sth->rowCount();
        if ($count != 1) {
            $_SESSION["fb_error"][] = ERR_COUNTRY_INFO_NOT_FOUND;
            return null;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        if ($result->country_status != COUNTRY_STATUS_DELETED) {
            $this->autoloadBO('country');
            $countryBO = new CountryBO();
            $countryBO->setCountryInfo($result);
            $countryMetaInfoArray = $this->getMetaInfoCountry($result->ID);
            $countryBO->setCountryMetaInfo($countryMetaInfoArray);
            return $countryBO;
        }
        return null;
    }

    public function hasCountryWithCountryLogin($country_login)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_COUNTRYS . "
                                   WHERE  " . TB_COUNTRYS_COL_COUNTRY_LOGIN . " = :country_login");

        $sth->execute(array(':country_login' => $country_login));
        $count = $sth->rowCount();
        if ($count != 0) {
            return true;
        }
        return false;
    }

    public function getMetaInfoCountry($country_id)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_COUNTRYMETA . "
                                   WHERE  " . TB_COUNTRYMETA_COL_COUNTRY_ID . " = :country_id");

        $sth->execute(array(':country_id' => $country_id));
        $count = $sth->rowCount();
        if ($count > 0) {
            $countryMetaInfoArray = $sth->fetchAll();
            foreach ($countryMetaInfoArray as $countryMeta) {
                if (isset($countryMeta->meta_key) && $countryMeta->meta_key == "manage_countrys_columns_show") {
                    try {
                        $countryMeta->meta_value = json_decode($countryMeta->meta_value);
                    } catch (Exception $e) {
                        $countryMeta->meta_value = NULL;
                    }
                }
                if (isset($countryMeta->meta_key) && $countryMeta->meta_key == "avatar") {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $avatar_object = new stdClass();
                    $avatar_object->umeta_id = $countryMeta->umeta_id;
                    $avatar_object->country_id = $countryMeta->country_id;
                    $avatar_object->meta_key = 'avatar_object';
                    $avatar_object->meta_value = $imageModel->getPost($countryMeta->meta_value);
                    $countryMetaInfoArray[] = $avatar_object;
                }
            }
            return $countryMetaInfoArray;
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

    public function updateCountrysPerPages($countrys_per_page)
    {
        $country_id = Session::get("country_id");
        $meta_key = "countrys_per_page";
        $meta_value = $countrys_per_page;
        $this->setCountryMeta($country_id, $meta_key, $meta_value);
    }

    public function updateShowHideColumn($email_show, $role_show, $tours_show)
    {
        $country_id = Session::get("country_id");
        $meta_key = "manage_countrys_columns_show";
        $meta_value = new stdClass();
        $meta_value->email_show = $email_show;
        $meta_value->role_show = $role_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        $this->setCountryMeta($country_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->countrys_per_page) && is_numeric($para->countrys_per_page)) {
            $this->updateCountrysPerPages($para->countrys_per_page);
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
        $countryBO = $this->getCountryByCountryId(Session::get("country_id"));
        $this->setNewSessionCountry($countryBO);
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

    public function setNewSessionCountry($countryBO)
    {
        if (is_a($countryBO, "CountryBO") && isset($countryBO->country_id) && Session::get('country_id') == $countryBO->country_id) {
            Session::init();
            Session::set('countryInfo', json_encode($countryBO));
            Session::set('country_id', $countryBO->country_id);
            Session::set('country_logged_in', true);
        }
    }

    public function executeActionDelete($para)
    {
        if (isset($para->countrys) && is_array($para->countrys)) {
            foreach ($para->countrys as $country_id) {
                try {
                    $country_id = (int) $country_id;
                } catch (Exception $ex) {
                    return;
                }
                if (!is_int($country_id)) {
                    return;
                }
            }
            $country_ids = join(',', $para->countrys);

            $countryBO = json_decode(Session::get('countryInfo'));
            $country_id = $countryBO->country_id;

            if (in_array("1", $para->countrys)) {
                $country_delete = $this->getCountryByCountryId("1");
                if (!is_null($country_delete)) {
                    $_SESSION["fb_error"][] = ERROR_DELETE_COUNTRY_NOT_PERMISSION . " <strong>" . $country_delete->country_login . "</strong>";
                } else {
                    $_SESSION["fb_error"][] = ERR_COUNTRY_INFO_NOT_FOUND;
                }
            }
            if (in_array($country_id, $para->countrys) && $country_id != "1") {
                $_SESSION["fb_error"][] = ERROR_DELETE_COUNTRY_NOT_PERMISSION . " <strong>" . $countryBO->country_login . "</strong>";
            }

            $sql = "UPDATE " . TABLE_COUNTRYS . " 
                    SET " . TB_COUNTRYS_COL_COUNTRY_STATUS . " = " . COUNTRY_STATUS_DELETED . "
                    WHERE " . TB_COUNTRYS_COL_ID . " IN (" . $country_ids . ")
                    AND " . TB_COUNTRYS_COL_ID . " != 1
                    AND " . TB_COUNTRYS_COL_ID . " != " . $country_id . " ;";

            $sth = $this->db->prepare($sql);
            $sth->execute();
        }
    }

    public function executeChangeRole($para, $role)
    {
        if (isset($para->countrys) && is_array($para->countrys) &&
            in_array($role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
            foreach ($para->countrys as $country_id) {
                try {
                    $country_id = (int) $country_id;
                } catch (Exception $ex) {
                    return;
                }
                if (!is_int($country_id)) {
                    return;
                }
            }
            $country_ids = join(',', $para->countrys);
            $country_id = Session::get('country_id');

            $sql = "UPDATE " . TABLE_COUNTRYMETA . " 
                    SET " . TB_COUNTRYMETA_COL_META_VALUE . " = '" . $role . "'
                    WHERE " . TB_COUNTRYMETA_COL_COUNTRY_ID . " IN (" . $country_ids . ")
                    AND " . TB_COUNTRYMETA_COL_COUNTRY_ID . " != 1
                    AND " . TB_COUNTRYMETA_COL_COUNTRY_ID . " != " . $country_id . ";
                    AND " . TB_COUNTRYMETA_COL_META_KEY . " = '" . WP_CAPABILITIES . "' ;";

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
            $sqlSelectCount = "SELECT COUNT(*) as countCountry ";
            //para: orderby, order, page, s, paged, countrys, new_role, new_role2, action, action2
            $sqlFrom = " FROM " . TABLE_COUNTRYS . " AS u, " . TABLE_COUNTRYMETA . " AS m ";
            $sqlWhere = " WHERE m." . TB_COUNTRYMETA_COL_COUNTRY_ID . " = u." . TB_COUNTRYS_COL_ID . " 
            AND m." . TB_COUNTRYMETA_COL_META_KEY . " = '" . WP_CAPABILITIES . "'
            AND country_status != " . COUNTRY_STATUS_DELETED;

            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= "  AND (u." . TB_COUNTRYS_COL_COUNTRY_LOGIN . " like :s OR
                                u." . TB_COUNTRYS_COL_DISPLAY_NAME . " like :s OR
                                u." . TB_COUNTRYS_COL_COUNTRY_EMAIL . " like :s ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "login";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("login", "name", "email"))) {
                switch ($para->orderby) {
                    case "login":
                        $para->orderby = TB_COUNTRYS_COL_COUNTRY_LOGIN;
                        $view->orderby = "login";
                        break;
                    case "name":
                        $para->orderby = TB_COUNTRYS_COL_DISPLAY_NAME;
                        $view->orderby = "name";
                        break;
                    case "email":
                        $para->orderby = TB_COUNTRYS_COL_COUNTRY_EMAIL;
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
                $sqlOrderby = " ORDER BY u." . TB_COUNTRYS_COL_COUNTRY_LOGIN . " ASC";
            }

            $view->count = array(
                FILTER_COUNTRYS_LIST_ALL_TITLE => 0,
                CAPABILITY_ADMINISTRATOR => 0,
                CAPABILITY_SUBSCRIBER => 0,
                CAPABILITY_CONTRIBUTOR => 0,
                CAPABILITY_AUTHOR => 0,
                CAPABILITY_EDITOR => 0,
            );


            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countCountry = (int) $sth->fetch()->countCountry;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countCountry > 0) {
                $view->count[FILTER_COUNTRYS_LIST_ALL_TITLE] = $countCountry;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_COUNTRYMETA_COL_META_VALUE . " = '" . CAPABILITY_ADMINISTRATOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_ADMINISTRATOR] = (int) $sth->fetch()->countCountry;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_COUNTRYMETA_COL_META_VALUE . " = '" . CAPABILITY_EDITOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_EDITOR] = (int) $sth->fetch()->countCountry;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_COUNTRYMETA_COL_META_VALUE . " = '" . CAPABILITY_AUTHOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_AUTHOR] = (int) $sth->fetch()->countCountry;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_COUNTRYMETA_COL_META_VALUE . " = '" . CAPABILITY_CONTRIBUTOR . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_CONTRIBUTOR] = (int) $sth->fetch()->countCountry;

                $sqlCountAdmin = $sqlSelectCount . $sqlFrom . $sqlWhere . " AND m." . TB_COUNTRYMETA_COL_META_VALUE . " = '" . CAPABILITY_SUBSCRIBER . "'";
                $sth = $this->db->prepare($sqlCountAdmin);
                $sth->execute($paraSQL);
                $view->count[CAPABILITY_SUBSCRIBER] = (int) $sth->fetch()->countCountry;
                $countryLoginBO = json_decode(Session::get("countryInfo"));

                $countrys_per_page = COUNTRYS_PER_PAGE_DEFAULT;
                if ($countryLoginBO != NULL) {
                    if (isset($countryLoginBO->countrys_per_page) && is_numeric($countryLoginBO->countrys_per_page)) {
                        $countrys_per_page = (int) $countryLoginBO->countrys_per_page;
                    }
                }

                if (!isset($countrys_per_page)) {
                    if (!isset($_SESSION['options'])) {
                        $_SESSION['options'] = new stdClass();
                        $_SESSION['options']->countrys_per_page = COUNTRYS_PER_PAGE_DEFAULT;
                        $countrys_per_page = COUNTRYS_PER_PAGE_DEFAULT;
                    } elseif (!isset($_SESSION['options']->countrys_per_page)) {
                        $_SESSION['options']->countrys_per_page = COUNTRYS_PER_PAGE_DEFAULT;
                        $countrys_per_page = COUNTRYS_PER_PAGE_DEFAULT;
                    }
                }



                $view->count[NUMBER_SEARCH_COUNTRY] = $view->count[FILTER_COUNTRYS_LIST_ALL_TITLE];
                $view->role = "-1";
                if (isset($para->role) && in_array($para->role, array(CAPABILITY_ADMINISTRATOR, CAPABILITY_EDITOR,
                        CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_SUBSCRIBER))) {
                    $sqlWhere .= " AND m." . TB_COUNTRYMETA_COL_META_VALUE . "= '" . $para->role . "' ";
                    $view->count[NUMBER_SEARCH_COUNTRY] = $view->count[$para->role];
                    $view->role = $para->role;
                }

                if ($view->count[NUMBER_SEARCH_COUNTRY] > 0) {
                    $view->pageNumber = floor($view->count[NUMBER_SEARCH_COUNTRY] / $countrys_per_page);
                    if ($view->count[NUMBER_SEARCH_COUNTRY] % $countrys_per_page != 0) {
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
                    $startCountry = ($page - 1) * $countrys_per_page;
                    $sqlLimit = " LIMIT " . $countrys_per_page . " OFFSET " . $startCountry;

                    $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                    $sth = $this->db->prepare($sqlAll);
                    $sth->execute($paraSQL);
                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $countryList = $sth->fetchAll();
                        for ($i = 0; $i < sizeof($countryList); $i++) {
                            $countryInfo = $countryList[$i];
                            $this->autoloadBO('country');
                            $countryBO = new CountryBO();
                            $countryBO->setCountryInfo($countryInfo);
                            $countryMetaInfoArray = $this->getMetaInfoCountry($countryInfo->ID);
                            $countryBO->setCountryMetaInfo($countryMetaInfoArray);
                            $countryList[$i] = $countryBO;
                        }
                        $view->countryList = $countryList;
                    } else {
                        $view->countryList = NULL;
                    }
                } else {
                    $view->countryList = NULL;
                    $view->page = 0;
                }
            } else {
                $view->countryList = NULL;
            }
        } catch (Exception $e) {
            $view->countryList = NULL;
        }
    }
}
