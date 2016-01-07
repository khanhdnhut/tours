<?php

class TestModel extends Model {

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db) {
        parent::__construct($db);
    }

    public function getMetaInfoUser($user_id) {
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

}
