<?php

/*
 * 
 * 
 */

/**
 * Class View
 * Provides the methods all views will have
 */
class View {

    /**
     * Simply includes (=shows) the view. This is done from the controller.
     * In the controller, you usually say $this->view->render('help/index'); to show
     * the view index.php in the folder help (in this example).
     * Usually the Class and the method are the same like the view, but sometimes you
     * need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file (.php)
     * @param boolean $render_without_header_and_footer Optional: Set this to true if you don't want to include header and footer 
     */
    public function render($filename,
            $render_without_header_and_footer =
    FALSE) {
        // page without header and footer, for whatever reason
        if ($render_without_header_and_footer ==
                true) {
            require VIEW_PATH . $filename . '.php';
        } else {
            require VIEW_TEMPLATES_PATH . 'home/header.php';
            require VIEW_TEMPLATES_PATH . 'home/ja-header.php';
            require VIEW_TEMPLATES_PATH . 'home/ja-mainnav.php';
            if ($filename == 'home/index') {
                require VIEW_TEMPLATES_PATH . 'home/ja-slideshow.php';
            }            
            require VIEW_TEMPLATES_PATH . 'home/ja-container.php';            
            require VIEW_TEMPLATES_PATH . 'home/ja-navhelper.php';
            require VIEW_TEMPLATES_PATH . 'home/ja-botsl-1.php';
            require VIEW_TEMPLATES_PATH . 'home/footer.php';
        }
    }
    
    
    public function renderAdmin($filename,
            $render_without_header_and_footer =
    FALSE) {
        // page without header and footer, for whatever reason
        if ($render_without_header_and_footer == true) {
            require VIEW_PATH . $filename . '.php';
        } else {
            require VIEW_TEMPLATES_PATH . 'admin/header.php';
            require VIEW_TEMPLATES_PATH . 'admin/menuwrap.php';
            require VIEW_TEMPLATES_PATH . 'admin/adminbar.php';
            require VIEW_TEMPLATES_PATH . 'admin/body.php';            
            require VIEW_TEMPLATES_PATH . 'admin/footer.php';
        }
    }

    /**
     * Renders the feedback messages into the view
     */
    public function renderFeedbackMessages() {
        // Echo out the feedback messages (errors and success messages etc..)
        // They are in $_SESSION['feedback_positive'] and $_SESSION['feedback_negative']
        require VIEW_TEMPLATES_PATH . 'feedback.php';

        // Delete these messages
        Session::set('fb_success',
                null);
        Session::set('fb_error',
                null);
    }

    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller
     * @return bool Shows if the controller is used or not
     */
    private function checkForActiveController($filename,
            $navigation_controller) {
        $split_filename =
                explode('/',
                $filename);
        $active_controller =
                $split_filename[0];

        if ($active_controller ==
                $navigation_controller) {
            return TRUE;
        }
        // Default return false
        return FALSE;
    }

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_action
     * @return bool Shows if the action/method is used or not
     */
    private function checkForActiveAction($filename,
            $navigation_action) {
        $split_filename =
                explode('/',
                $filename);
        $active_action =
                $split_filename[1];

        if ($active_action ==
                $navigation_action) {
            return TRUE;
        }
        // Default return false
        return FALSE;
    }

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigation_controller_and_action
     * @return bool
     */
    private function checkForActiveControllerAndAction($filename,
            $navigation_controller_and_action) {
        $split_filename =
                explode('/',
                $filename);
        $active_controller =
                $split_filename[0];
        $active_action =
                $split_filename[1];

        $split_filename =
                explode('/',
                $navigation_controller_and_action);
        $navigation_controller =
                $split_filename[0];
        $navigation_action =
                $split_filename[1];

        if ($active_controller ==
                $navigation_controller AND $active_action ==
                $navigation_action) {
            return TRUE;
        }
        // Default return false
        return false;
    }

}

?>
