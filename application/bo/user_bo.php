<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserBO {

    public $user_id = null;
    public $user_login = null;
    public $user_pass = null;
    public $user_nicename = null;
    public $user_email = null;
    public $user_url = null;
    public $user_registered = null;
    public $user_activation_key = null;
    public $user_status = null;
    public $display_name = null;
    public $nickname = null;
    public $first_name = null;
    public $last_name = null;
    public $description = null;
    public $rich_editing = null;
    public $comment_shortcuts = null;
    public $admin_color = null;
    public $show_admin_bar_front = null;
    public $wp_capabilities = null;
    public $wp_user_level = null;
    public $dismissed_wp_pointers = null;
    public $show_welcome_panel = null;
    public $session_tokens = null;
    public $wp_dashboard_quick_press_last_post_id = null;
    public $wporg_favorites = null;

    public function setUserInfo($userInfo) {
        if ($userInfo != NULL) {
            if (isset($userInfo->ID)) {
                $this->user_id = $userInfo->ID;
            }
            if (isset($userInfo->user_login)) {
                $this->user_login = $userInfo->user_login;
            }
            if (isset($userInfo->user_pass)) {
                $this->user_pass = $userInfo->user_pass;
            }
            if (isset($userInfo->user_nicename)) {
                $this->user_nicename = $userInfo->user_nicename;
            }
            if (isset($userInfo->user_email)) {
                $this->user_email = $userInfo->user_email;
            }
            if (isset($userInfo->user_url)) {
                $this->user_url = $userInfo->user_url;
            }
            if (isset($userInfo->user_registered)) {
                $this->user_registered = $userInfo->user_registered;
            }
            if (isset($userInfo->user_activation_key)) {
                $this->user_activation_key = $userInfo->user_activation_key;
            }
            if (isset($userInfo->user_status)) {
                $this->user_status = $userInfo->user_status;
            }
            if (isset($userInfo->display_name)) {
                $this->display_name = $userInfo->display_name;
            }
        }
    }

    public function setUserMetaInfo($userMetaInfoArray) {
        //Kết quả tìm kiếm từ bảng userMeta
        if ($userMetaInfoArray != NULL && is_array($userMetaInfoArray) && count($userMetaInfoArray) > 0) {
            foreach ($userMetaInfoArray as $userMeta) {
                if (isset($userMeta->user_id) && $userMeta->user_id == $this->user_id) {
                    if (isset($userMeta->meta_key) && isset($userMeta->meta_value) && property_exists("UserBO" ,$userMeta->meta_key)) {
                        $meta_key = $userMeta->meta_key;
                        $this->$meta_key = $userMeta->meta_value;
                    }
                }
            }
        }
    }

}
