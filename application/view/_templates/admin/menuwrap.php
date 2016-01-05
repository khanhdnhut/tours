<body class="wp-admin wp-core-ui js  index-php auto-fold admin-bar branch-4-4 version-4-4 admin-color-fresh locale-en-ca customize-support svg">
    <script type="text/javascript">
        document.body.className = document.body.className.replace('no-js', 'js');
    </script>

    <script type="text/javascript">
        (function () {
            var request, b = document.body,
                    c = 'className',
                    cs = 'customize-support',
                    rcs = new RegExp('(^|\\s+)(no-)?' + cs + '(\\s+|$)');
            request = true;
            b[c] = b[c].replace(rcs, ' ');
            b[c] += (window.postMessage & amp; & amp; request ? ' ' : ' no-') + cs;
        }());
    </script>

    <div id="wpwrap">

        <div aria-label="Main menu" role="navigation" id="adminmenumain">
            <a class="screen-reader-shortcut" href="#wpbody-content">Skip to main content</a>
            <a class="screen-reader-shortcut" href="#wp-toolbar">Skip to toolbar</a>
            <div id="adminmenuback"></div>
            <div id="adminmenuwrap" style="position: fixed;">
                <ul id="adminmenu">


                    <li id="menu-dashboard" class="wp-first-item wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-top-first menu-icon-dashboard menu-top-last">
                        <a class="wp-first-item wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-top-first menu-icon-dashboard menu-top-last" href="<?php echo URL . CONTEXT_PATH_ADMIN; ?>" aria-haspopup="false">
                            <div class="wp-menu-arrow">
                                <div></div>
                            </div>
                            <div class="wp-menu-image dashicons-before dashicons-dashboard">
                                <br>
                            </div>
                            <div class="wp-menu-name"><?php echo DASHBOARD_TITLE; ?></div>
                        </a>
                    </li>

                    <?php
                    if (!in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_SUBSCRIBER))) {
                        ?>                                     
                        <li aria-hidden="true" class="wp-not-current-submenu wp-menu-separator">
                            <div class="separator"></div>
                        </li>
                        <?php
                    }
                    ?>


                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_CONTRIBUTOR,
                                CAPABILITY_AUTHOR,
                                CAPABILITY_EDITOR,
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>
                        <li id="menu-posts" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post open-if-no-js menu-top-first">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post open-if-no-js menu-top-first" href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-post">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_TOURS_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_TOURS_TITLE; ?></li>
                                <li class="wp-first-item"><a class="wp-first-item" href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT; ?>"><?php echo DASHBOARD_ALL_TOURS_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_TOUR_ADD_NEW; ?>"><?php echo DASHBOARD_TOURS_ADD_NEW_TITLE; ?></a></li>
                                <?php
                                if (in_array(Auth::getCapability(),
                                                array(
                                            CAPABILITY_EDITOR,
                                            CAPABILITY_ADMINISTRATOR))) {
                                    ?>                                     
                                    <li><a href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT_CATEGORY; ?>"><?php echo DASHBOARD_TOURS_CATEGORIES_TITLE; ?></a></li>
                                    <li><a href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT_TAG; ?>"><?php echo DASHBOARD_TOURS_TAGS_TITLE; ?></a></li>   
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>


                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_AUTHOR,
                                CAPABILITY_EDITOR,
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>                                     
                        <li id="menu-media" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media" href="<?php echo URL . CONTEXT_PATH_MEDIA_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-media">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_MEDIA_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_MEDIA_TITLE; ?></li>
                                <li class="wp-first-item"><a class="wp-first-item" href="<?php echo URL . CONTEXT_PATH_MEDIA_UPLOAD; ?>">Library</a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_MEDIA_ADD_NEW; ?>"><?php echo DASHBOARD_MEDIA_ADD_NEW_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>


                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_CONTRIBUTOR,
                                CAPABILITY_AUTHOR,
                                CAPABILITY_EDITOR,
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>                                     
                        <li id="menu-pages" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page" href="<?php echo URL . CONTEXT_PATH_NEWS_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-page">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_NEWS_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_NEWS_TITLE; ?></li>
                                <li class="wp-first-item"><a class="wp-first-item" href="<?php echo URL . CONTEXT_PATH_NEWS_EDIT; ?>"><?php echo DASHBOARD_ALL_NEWS_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_NEWS_ADD_NEW; ?>"><?php echo DASHBOARD_NEWS_ADD_NEW_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>

                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_CONTRIBUTOR,
                                CAPABILITY_AUTHOR,
                                CAPABILITY_EDITOR,
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>                                     
                        <li id="menu-comments" class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last">
                            <a class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last" href="<?php echo URL . CONTEXT_PATH_REQUEST_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-comments">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_REQUESTS_TITLE; ?> <span class="awaiting-mod count-0"><span class="pending-count">0</span></span>
                                </div>
                            </a>
                        </li>
                        <?php
                    }
                    ?>

                    <?php
                    if (!in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_SUBSCRIBER))) {
                        ?>                                     
                        <li aria-hidden="true" class="wp-not-current-submenu wp-menu-separator">
                            <div class="separator"></div>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>                                     
                        <li id="menu-users" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-users">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_USERS_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_USERS_TITLE; ?></li>
                                <li class="wp-first-item"><a class="wp-first-item" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>"><?php echo DASHBOARD_ALL_USERS_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>"><?php echo DASHBOARD_USERS_ADD_NEW_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_USER_PROFILE; ?>"><?php echo DASHBOARD_USERS_YOUR_PROFILE_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    } else if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_SUBSCRIBER,
                                CAPABILITY_CONTRIBUTOR,
                                CAPABILITY_AUTHOR,
                                CAPABILITY_EDITOR))) {
                        ?>
                        <li id="menu-users" class="wp-not-current-submenu menu-top menu-icon-users menu-top-first">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-users">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_USERS_YOUR_PROFILE_TITLE; ?></div>
                            </a>
                        </li>
                        <?php
                    }
                    ?>

                    <?php
                    if (in_array(Auth::getCapability(),
                                    array(
                                CAPABILITY_ADMINISTRATOR))) {
                        ?>                                     
                        <li id="menu-settings" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings menu-top-last">
                            <a aria-haspopup="true" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings menu-top-last" href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-settings">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_SETTINGS_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap" style="">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_SETTINGS_TITLE; ?></li>
                                <li class="wp-first-item"><a class="wp-first-item" href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_GENERAL; ?>">General</a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_WRITING; ?>"><?php echo DASHBOARD_SETTINGS_WRITING_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_READING; ?>"><?php echo DASHBOARD_SETTINGS_READING_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_DISCUSSION; ?>"><?php echo DASHBOARD_SETTINGS_DISCUSSION_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_MEDIA; ?>"><?php echo DASHBOARD_SETTINGS_MEDIA_TITLE; ?></a></li>
                                <li><a href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_PERMALINK; ?>"><?php echo DASHBOARD_SETTINGS_PERMALINKS_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?> 
                    <li class="hide-if-no-js" id="collapse-menu">
                        <div id="collapse-button">
                            <div></div>
                        </div><span><?php echo DASHBOARD_COLLAPSE_MENU_TITLE; ?></span></li>
                </ul>
            </div>
        </div>