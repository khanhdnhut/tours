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
class Auth
{

    public static function handleLogin()
    {
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

    public static function isLogged()
    {
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

    public static function getRole($capability_encode)
    {
        $capability = json_decode($capability_encode);
        $capability_administrator = CAPABILITY_ADMINISTRATOR;
        $capability_editor = CAPABILITY_EDITOR;
        $capability_author = CAPABILITY_AUTHOR;
        $capability_contributor = CAPABILITY_CONTRIBUTOR;
        $capability_subscriber = CAPABILITY_SUBSCRIBER;

        if (property_exists($capability, $capability_administrator) && $capability->$capability_administrator) {
            
            return CAPABILITY_ADMINISTRATOR;
        } else if (property_exists($capability, $capability_editor) && $capability->$capability_editor) {
            return CAPABILITY_EDITOR;
        } else if (property_exists($capability, $capability_author) && $capability->$capability_author) {
            return CAPABILITY_AUTHOR;
        } else if (property_exists($capability, $capability_contributor) && $capability->$capability_contributor) {
            return CAPABILITY_CONTRIBUTOR;
        } else if (property_exists($capability, $capability_subscriber) && $capability->$capability_subscriber) {
            return CAPABILITY_SUBSCRIBER;
        }
    }

    public static function getCapability()
    {
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
                    $role = Auth::getRole($userBO->wp_capabilities);
                    Session::set("capability", $role);
                    return $role;
                }
            }
        }
        return NULL;
    }
}

?>
