<?php

/*
 * 
 * 
 */

/**
 * Class Auth
 * Simply checks if user is logged in. In the app, several controllers use Auth::handleLogin() to
 * check if user is logged in, useful to show controllers/methods only to logged-in users.
 * @author khanh_000
 */
class Auth {

    public static function handleLogin() {
        // Initialize the session
        Session::init();

        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            header('location: ' . URL . 'user/login');
            return FALSE;
        }
        return TRUE;
    }

    public static function isLogged() {
        // Initialize the session
        Session::init();

        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            return FALSE;
        }
        return TRUE;
    }

    public static function getCapability() {
        // Initialize the session
        Session::init();
        // If user is still not logged in, then destroy session, handle user as "Not logged in"
        // and redirect user to login page
        if (isset($_SESSION['user_logged_in'])) {
            if (Session::get('userInfo') !=
                    NULL) {
                if (Session::get('capability') !=
                        NULL) {
                    return Session::get('capability');
                } else {
                    $userBO = json_decode(Session::get('userInfo'));
                    $capability = json_decode($userBO->wp_capabilities);
                    $capability_administrator = CAPABILITY_ADMINISTRATOR;
                    $capability_editor = CAPABILITY_EDITOR;
                    $capability_author = CAPABILITY_AUTHOR;
                    $capability_contributor = CAPABILITY_CONTRIBUTOR;
                    $capability_subscriber = CAPABILITY_SUBSCRIBER;
                    if ($capability->$capability_administrator) {
                        Session::set("capability", CAPABILITY_ADMINISTRATOR);
                        return CAPABILITY_ADMINISTRATOR;
                    } else if ($capability->$capability_editor) {
                        Session::set("capability", CAPABILITY_EDITOR);
                        return CAPABILITY_EDITOR;
                    } else if ($capability->$capability_author) {
                        Session::set("capability", CAPABILITY_AUTHOR);
                        return CAPABILITY_AUTHOR;
                    } else if ($capability->$capability_contributor) {
                        Session::set("capability", CAPABILITY_CONTRIBUTOR);
                        return CAPABILITY_CONTRIBUTOR;
                    } else if ($capability->$capability_subscriber) {
                        Session::set("capability", CAPABILITY_SUBSCRIBER);
                        return CAPABILITY_SUBSCRIBER;
                    }
                }
            }
        }
        return NULL;
    }

}

?>
