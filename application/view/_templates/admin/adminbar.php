<?php
if (Session::get('userInfo') !=
        NULL) {
    $userBO =
            json_decode(Session::get('userInfo'));
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
                    <li id="wp-admin-bar-comments"><a title="0 comments awaiting moderation" href="<?php echo URL; ?>requests/edit" class="ab-item"><span class="ab-icon"></span><span class="ab-label awaiting-mod pending-count count-0" id="ab-awaiting-mod">0</span></a> </li>
                    <li class="menupop" id="wp-admin-bar-new-content"><a href="http://localhost:8082/wordpress1/wp-admin/post-new.php" aria-haspopup="true" class="ab-item"><span class="ab-icon"></span><span class="ab-label"><?php echo ADMIN_BAR_ADD_NEW_TITLE; ?></span></a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-new-content-default">
                                <li id="wp-admin-bar-new-post"><a href="<?php echo URL; ?>tours/addNew" class="ab-item"><?php echo DASHBOARD_TOURS_TITLE; ?></a> </li>
                                <li id="wp-admin-bar-new-media"><a href="<?php echo URL; ?>media/addNew" class="ab-item"><?php echo DASHBOARD_MEDIA_TITLE; ?></a> </li>
                                <li id="wp-admin-bar-new-page"><a href="<?php echo URL; ?>news/addNew" class="ab-item"><?php echo DASHBOARD_NEWS_TITLE; ?></a> </li>
                                <li id="wp-admin-bar-new-user"><a href="<?php echo URL; ?>user/addNew" class="ab-item"><?php echo DASHBOARD_USERS_TITLE; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <ul class="ab-top-secondary ab-top-menu" id="wp-admin-bar-top-secondary">
                    <li class="menupop with-avatar" id="wp-admin-bar-my-account">
                        <a href="#" aria-haspopup="true" class="ab-item">
                            Hello <?php echo $userBO->user_login; ?><img width="26" height="26" class="avatar avatar-26 photo" srcset="<?php echo PUBLIC_IMG ?>icon/no_avatar.jpg" src="<?php echo PUBLIC_IMG ?>icon/no_avatar.jpg" alt="">                       
                        </a>
                        <div class="ab-sub-wrapper">
                            <ul class="ab-submenu" id="wp-admin-bar-user-actions">
                                <li id="wp-admin-bar-user-info">
                                    <a href="<?php echo URL; ?>user/profile" tabindex="-1" class="ab-item"><img width="64" height="64" class="avatar avatar-64 photo" srcset="<?php echo PUBLIC_IMG; ?>icon/no_avatar.jpg" src="<?php echo PUBLIC_IMG ?>icon/no_avatar.jpg" alt=""><span class="display-name"><?php echo $userBO->user_login; ?></span></a>
                                </li>
                                <li id="wp-admin-bar-edit-profile"><a href="<?php echo URL; ?>user/profile" class="ab-item"><?php echo ADMIN_BAR_EDIT_MY_PROFILE_TITLE; ?></a> </li>
                                <li id="wp-admin-bar-logout"><a href="<?php echo URL; ?>user/logout" class="ab-item"><?php echo ADMIN_BAR_LOG_OUT_TITLE; ?></a> </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <a href="<?php echo URL; ?>user/logout" class="screen-reader-shortcut"><?php echo ADMIN_BAR_LOG_OUT_TITLE; ?></a>
        </div>
        <?php
    } else {
        Auth::handleLogin();
    }
    ?>