<?php
if (!is_null(Session::get('userInfo'))) {
    $userLoginBO = json_decode(Session::get('userInfo'));

    ?>
    <div id="wpcontent">
        <div class="" id="wpadminbar">
            <div tabindex="0" aria-label="Toolbar" role="navigation" id="wp-toolbar" class="quicklinks">
                <ul class="ab-top-menu" id="wp-admin-bar-root-default">
                    <li id="wp-admin-bar-menu-toggle"><a href="#" class="ab-item" aria-expanded="false"><span class="ab-icon"></span><span class="screen-reader-text">Menu</span></a> </li>                
                    <li class="menupop" id="wp-admin-bar-site-name"><a href="<?php echo URL; ?>" aria-haspopup="true" class="ab-item"><?php echo WEBSITE_NAME; ?></a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-site-name-default">
                                <li id="wp-admin-bar-view-site"><a href="<?php echo URL; ?>" class="ab-item"><?php echo WEBSITE_NAME; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_CONTRIBUTOR,
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR,
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>                                     
                        <li id="wp-admin-bar-comments"><a title="0 comments awaiting moderation" href="<?php echo URL . CONTEXT_PATH_REQUEST_EDIT; ?>" class="ab-item"><span class="ab-icon"></span><span class="ab-label awaiting-mod pending-count count-0" id="ab-awaiting-mod">0</span></a> </li>
                        <?php
                    }

                    ?>

                    <?php
                    if (!in_array(Auth::getCapability(), array(
                            CAPABILITY_SUBSCRIBER))) {

                        ?>                                     

                        <li class="menupop" id="wp-admin-bar-new-content"><a href="http://localhost:8082/wordpress1/wp-admin/post-new.php" aria-haspopup="true" class="ab-item"><span class="ab-icon"></span><span class="ab-label"><?php echo ADMIN_BAR_ADD_NEW_TITLE; ?></span></a>
                            <div class="ab-sub-wrapper">
                                <ul class="ab-submenu" id="wp-admin-bar-new-content-default">
                                    <?php
                                    if (in_array(Auth::getCapability(), array(
                                            CAPABILITY_CONTRIBUTOR,
                                            CAPABILITY_AUTHOR,
                                            CAPABILITY_EDITOR,
                                            CAPABILITY_ADMINISTRATOR))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-post"><a href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>" class="ab-item"><?php echo DASHBOARD_TOURS_TITLE; ?></a> </li>
                                        <?php
                                    }
                                    if (in_array(Auth::getCapability(), array(
                                            CAPABILITY_AUTHOR,
                                            CAPABILITY_EDITOR,
                                            CAPABILITY_ADMINISTRATOR))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-media"><a href="<?php echo URL . CONTEXT_PATH_MEDIA_ADD_NEW; ?>" class="ab-item"><?php echo DASHBOARD_MEDIA_TITLE; ?></a> </li>
                                        <?php
                                    }
                                    if (in_array(Auth::getCapability(), array(
                                            CAPABILITY_CONTRIBUTOR,
                                            CAPABILITY_AUTHOR,
                                            CAPABILITY_EDITOR,
                                            CAPABILITY_ADMINISTRATOR))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-page"><a href="<?php echo URL . CONTEXT_PATH_NEWS_ADD_NEW; ?>" class="ab-item"><?php echo DASHBOARD_NEWS_TITLE; ?></a> </li>
                                        <?php
                                    }
                                    if (in_array(Auth::getCapability(), array(
                                            CAPABILITY_ADMINISTRATOR))) {

                                        ?>                                     
                                        <li id="wp-admin-bar-new-user"><a href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>" class="ab-item"><?php echo DASHBOARD_USERS_TITLE; ?></a> </li>
                                        <?php
                                    }

                                    ?>

                                </ul>
                            </div>
                        </li>
                        <?php
                    }

                    ?>

                </ul>
                <ul class="ab-top-secondary ab-top-menu" id="wp-admin-bar-top-secondary">
                    <li class="menupop with-avatar" id="wp-admin-bar-my-account">
                        <a href="#" aria-haspopup="true" class="ab-item">
                            Hello <?php echo htmlspecialchars($userLoginBO->user_login); ?><img width="26" height="26" class="avatar avatar-26 photo" srcset="<?php
                            if (isset($userLoginBO->avatar_url)) {
                                echo URL . htmlspecialchars($userLoginBO->avatar_url);
                            } else {
                                echo URL . AVATAR_DEFAULT;
                            }

                            ?>" src="<?php
                                                                                                if (isset($userLoginBO->avatar_url)) {
                                                                                                    echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                                                                                } else {
                                                                                                    echo URL . AVATAR_DEFAULT;
                                                                                                }

                                                                                                ?>" alt="">                       
                        </a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-user-actions">
                                <li id="wp-admin-bar-user-info">
                                    <a href="<?php echo URL . CONTEXT_PATH_USER_PROFILE; ?>" tabindex="-1" class="ab-item"><img width="64" height="64" class="avatar avatar-64 photo" srcset="<?php
                                        if (isset($userLoginBO->avatar_url)) {
                                            echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                        } else {
                                            echo URL . AVATAR_DEFAULT;
                                        }

                                        ?>" src="<?php
                                                                                                                                if (isset($userLoginBO->avatar_url)) {
                                                                                                                                    echo URL . htmlspecialchars($userLoginBO->avatar_url);
                                                                                                                                } else {
                                                                                                                                    echo URL . AVATAR_DEFAULT;
                                                                                                                                }

                                                                                                                                ?>" alt=""><span class="display-name"><?php echo htmlspecialchars($userLoginBO->user_login); ?></span></a>
                                </li>
                                <li id="wp-admin-bar-edit-profile"><a href="<?php echo URL . CONTEXT_PATH_USER_EDIT_INFO . $userLoginBO->user_id . "/" . htmlspecialchars($userLoginBO->user_login); ?>" class="ab-item"><?php echo ADMIN_BAR_EDIT_MY_PROFILE_TITLE; ?></a> </li>
                                <li id="wp-admin-bar-logout"><a href="<?php echo URL . CONTEXT_PATH_USER_LOGOUT; ?>" class="ab-item"><?php echo ADMIN_BAR_LOG_OUT_TITLE; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <a href="<?php echo URL . CONTEXT_PATH_USER_LOGOUT; ?>" class="screen-reader-shortcut"><?php echo ADMIN_BAR_LOG_OUT_TITLE; ?></a>
        </div>
        <div role="main" id="wpbody">
            <div tabindex="0" aria-label="Main content" id="wpbody-content" style="overflow: hidden;">               
                <div class="wrap">
                    <?php
                } else {
                    Auth::handleLogin();
                }

                ?>
