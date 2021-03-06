<body class="wp-admin wp-core-ui js  index-php auto-fold admin-bar branch-4-4 version-4-4 admin-color-fresh locale-en-ca customize-support svg">
    <script type="text/javascript">
        document.body.className = document.body.className.replace('no-js', 'js');
    </script>

    <div id="wpwrap">

        <div aria-label="Main menu" role="navigation" id="adminmenumain">
            <a class="screen-reader-shortcut" href="#wpbody-content">Skip to main content</a>
            <a class="screen-reader-shortcut" href="#wp-toolbar">Skip to toolbar</a>
            <div id="adminmenuback"></div>
            <div id="adminmenuwrap" style="position: fixed;">
                <ul id="adminmenu">


                    <li id="menu-dashboard" class="wp-first-item wp-has-submenu <?php
                    if ($this->checkForActiveController($_GET['url'], 'admin')) {
                        echo "wp-has-current-submenu wp-menu-open";
                    } else {
                        echo "wp-not-current-submenu";
                    }

                    ?> menu-top menu-top-first menu-icon-dashboard menu-top-last">
                        <a class="wp-first-item wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'admin')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-top-first menu-icon-dashboard menu-top-last" href="<?php echo URL . CONTEXT_PATH_ADMIN; ?>" aria-haspopup="false">
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
                    if (!in_array(Auth::getCapability(), array(
                            CAPABILITY_SUBSCRIBER))) {

                        ?>                                     
                        <li aria-hidden="true" class="wp-not-current-submenu wp-menu-separator">
                            <div class="separator"></div>
                        </li>
                        <?php
                    }

                    ?>


                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_CONTRIBUTOR,
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR,
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>

                        <li id="menu-posts" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'tour') ||
                            $this->checkForActiveController($_GET['url'], 'style') ||
                            $this->checkForActiveController($_GET['url'], 'type') ||
                            $this->checkForActiveController($_GET['url'], 'tag')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-post open-if-no-js menu-top-first">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'tour') ||
                                $this->checkForActiveController($_GET['url'], 'style') ||
                                $this->checkForActiveController($_GET['url'], 'type') ||
                                $this->checkForActiveController($_GET['url'], 'tag')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-post open-if-no-js menu-top-first" href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT; ?>">
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
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TOUR_EDIT)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TOUR_EDIT)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_TOUR_EDIT; ?>"><?php echo DASHBOARD_ALL_TOURS_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TOUR_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TOUR_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_TOUR_ADD_NEW; ?>"><?php echo DASHBOARD_TOUR_ADD_NEW_TITLE; ?></a></li>
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TAG_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TAG_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_TAG_INDEX; ?>"><?php echo DASHBOARD_ALL_TAG_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TAG_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TAG_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_TAG_ADD_NEW; ?>"><?php echo DASHBOARD_TAG_ADD_NEW_TITLE; ?></a></li>
                                    <?php
                                    if (in_array(Auth::getCapability(), array(
                                            CAPABILITY_EDITOR,
                                            CAPABILITY_ADMINISTRATOR))) {

                                        ?>                                                                            

                                    <li class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_STYLE_INDEX)) {
                                        echo "current";
                                    }

                                    ?>"><a class="wp-first-item <?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_STYLE_INDEX)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_STYLE_INDEX; ?>"><?php echo DASHBOARD_ALL_STYLE_TITLE; ?></a></li>                                
                                    <li class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_STYLE_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>"><a class="wp-first-item <?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_STYLE_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_STYLE_ADD_NEW; ?>"><?php echo DASHBOARD_STYLE_ADD_NEW_TITLE; ?></a></li>   

                                    <li class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TYPE_INDEX)) {
                                        echo "current";
                                    }

                                    ?>"><a class="wp-first-item <?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TYPE_INDEX)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_TYPE_INDEX; ?>"><?php echo DASHBOARD_ALL_TYPE_TITLE; ?></a></li>                                
                                    <li class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TYPE_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>"><a class="wp-first-item <?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_TYPE_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_TYPE_ADD_NEW; ?>"><?php echo DASHBOARD_TYPE_ADD_NEW_TITLE; ?></a></li>
                                        <?php
                                    }

                                    ?>
                            </ul>
                        </li>



                        <li id="menu-settings" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'country') ||
                            $this->checkForActiveController($_GET['url'], 'destination') ||
                            $this->checkForActiveController($_GET['url'], 'attraction') ||
                            $this->checkForActiveController($_GET['url'], 'eat')
                        ) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-site menu-top-last">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'country') ||
                                $this->checkForActiveController($_GET['url'], 'destination') ||
                                $this->checkForActiveController($_GET['url'], 'attraction') ||
                                $this->checkForActiveController($_GET['url'], 'eat')
                            ) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-site menu-top-last" href="<?php echo URL . CONTEXT_PATH_COUNTRY_INDEX; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-site">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_COUNTRY_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap" style="">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_COUNTRY_TITLE; ?></li>
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_COUNTRY_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_COUNTRY_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_COUNTRY_INDEX; ?>"><?php echo DASHBOARD_ALL_COUNTRY_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_COUNTRY_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_COUNTRY_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_COUNTRY_ADD_NEW; ?>"><?php echo DASHBOARD_COUNTRY_ADD_NEW_TITLE; ?></a></li>                                

                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_DESTINATION_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_DESTINATION_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_DESTINATION_INDEX; ?>"><?php echo DASHBOARD_ALL_DESTINATION_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_DESTINATION_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_DESTINATION_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_DESTINATION_ADD_NEW; ?>"><?php echo DASHBOARD_DESTINATION_ADD_NEW_TITLE; ?></a></li>
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_EAT_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_EAT_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_EAT_INDEX; ?>"><?php echo DASHBOARD_ALL_EAT_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_EAT_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_EAT_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_EAT_ADD_NEW; ?>"><?php echo DASHBOARD_EAT_ADD_NEW_TITLE; ?></a></li>
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ATTRACTION_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ATTRACTION_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_ATTRACTION_INDEX; ?>"><?php echo DASHBOARD_ALL_ATTRACTION_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ATTRACTION_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ATTRACTION_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_ATTRACTION_ADD_NEW; ?>"><?php echo DASHBOARD_ATTRACTION_ADD_NEW_TITLE; ?></a></li>
                            </ul>
                        </li>

                        <li id="menu-settings" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'city') ||
                            $this->checkForActiveController($_GET['url'], 'restaurant') ||
                            $this->checkForActiveController($_GET['url'], 'shopping') ||
                            $this->checkForActiveController($_GET['url'], 'internalflight') ||
                            $this->checkForActiveController($_GET['url'], 'internationalflight') ||
                            $this->checkForActiveController($_GET['url'], 'activity') ||
                            $this->checkForActiveController($_GET['url'], 'nightlife') ||
                            $this->checkForActiveController($_GET['url'], 'hotel')
                        ) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-site menu-top-last">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'city') ||
                                $this->checkForActiveController($_GET['url'], 'restaurant') ||
                                $this->checkForActiveController($_GET['url'], 'shopping') ||
                                $this->checkForActiveController($_GET['url'], 'internalflight') ||
                                $this->checkForActiveController($_GET['url'], 'internationalflight') ||
                                $this->checkForActiveController($_GET['url'], 'activity') ||
                                $this->checkForActiveController($_GET['url'], 'nightlife') ||
                                $this->checkForActiveController($_GET['url'], 'hotel')
                            ) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-site menu-top-last" href="<?php echo URL . CONTEXT_PATH_CITY_INDEX; ?>">
                                <div class="wp-menu-arrow">
                                    <div></div>
                                </div>
                                <div class="wp-menu-image dashicons-before dashicons-admin-site">
                                    <br>
                                </div>
                                <div class="wp-menu-name"><?php echo DASHBOARD_CITY_TITLE; ?></div>
                            </a>
                            <ul class="wp-submenu wp-submenu-wrap" style="">
                                <li aria-hidden="true" class="wp-submenu-head"><?php echo DASHBOARD_CITY_TITLE; ?></li>

                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_CITY_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_CITY_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_CITY_INDEX; ?>"><?php echo DASHBOARD_ALL_CITY_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_CITY_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_CITY_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_CITY_ADD_NEW; ?>"><?php echo DASHBOARD_CITY_ADD_NEW_TITLE; ?></a></li>   
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_HOTEL_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_HOTEL_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_HOTEL_INDEX; ?>"><?php echo DASHBOARD_ALL_HOTEL_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_HOTEL_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_HOTEL_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_HOTEL_ADD_NEW; ?>"><?php echo DASHBOARD_HOTEL_ADD_NEW_TITLE; ?></a></li>   

                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_RESTAURANT_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_RESTAURANT_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_RESTAURANT_INDEX; ?>"><?php echo DASHBOARD_ALL_RESTAURANT_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_RESTAURANT_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_RESTAURANT_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_RESTAURANT_ADD_NEW; ?>"><?php echo DASHBOARD_RESTAURANT_ADD_NEW_TITLE; ?></a></li>

                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_SHOPPING_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_SHOPPING_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_SHOPPING_INDEX; ?>"><?php echo DASHBOARD_ALL_SHOPPING_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_SHOPPING_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_SHOPPING_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_SHOPPING_ADD_NEW; ?>"><?php echo DASHBOARD_SHOPPING_ADD_NEW_TITLE; ?></a></li>  
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNALFLIGHT_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNALFLIGHT_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_INTERNALFLIGHT_INDEX; ?>"><?php echo DASHBOARD_ALL_INTERNALFLIGHT_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNALFLIGHT_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNALFLIGHT_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_INTERNALFLIGHT_ADD_NEW; ?>"><?php echo DASHBOARD_INTERNALFLIGHT_ADD_NEW_TITLE; ?></a></li>  
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNATIONALFLIGHT_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNATIONALFLIGHT_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_INDEX; ?>"><?php echo DASHBOARD_ALL_INTERNATIONALFLIGHT_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNATIONALFLIGHT_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_INTERNATIONALFLIGHT_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_ADD_NEW; ?>"><?php echo DASHBOARD_INTERNATIONALFLIGHT_ADD_NEW_TITLE; ?></a></li>
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ACTIVITY_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ACTIVITY_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_ACTIVITY_INDEX; ?>"><?php echo DASHBOARD_ALL_ACTIVITY_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ACTIVITY_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_ACTIVITY_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_ACTIVITY_ADD_NEW; ?>"><?php echo DASHBOARD_ACTIVITY_ADD_NEW_TITLE; ?></a></li>  
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NIGHTLIFE_INDEX)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NIGHTLIFE_INDEX)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_NIGHTLIFE_INDEX; ?>"><?php echo DASHBOARD_ALL_NIGHTLIFE_TITLE; ?></a></li>                                
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NIGHTLIFE_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NIGHTLIFE_ADD_NEW)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_NIGHTLIFE_ADD_NEW; ?>"><?php echo DASHBOARD_NIGHTLIFE_ADD_NEW_TITLE; ?></a></li>  

                            </ul>
                        </li>

                        <?php
                    }

                    ?>


                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR,
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>                                     
                        <li id="menu-media" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'media')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-media">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'media')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-media" href="<?php echo URL . CONTEXT_PATH_MEDIA_EDIT; ?>">
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
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_MEDIA_UPLOAD)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_MEDIA_UPLOAD)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_MEDIA_UPLOAD; ?>">Library</a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_MEDIA_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_MEDIA_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_MEDIA_ADD_NEW; ?>"><?php echo DASHBOARD_MEDIA_ADD_NEW_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }

                    ?>


                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_CONTRIBUTOR,
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR,
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>                                     
                        <li id="menu-pages" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'news')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-page">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'news')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-page" href="<?php echo URL . CONTEXT_PATH_NEWS_EDIT; ?>">
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
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NEWS_EDIT)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NEWS_EDIT)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_NEWS_EDIT; ?>"><?php echo DASHBOARD_ALL_NEWS_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NEWS_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_NEWS_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_NEWS_ADD_NEW; ?>"><?php echo DASHBOARD_NEWS_ADD_NEW_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }

                    ?>

                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_CONTRIBUTOR,
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR,
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>                                     
                        <li id="menu-comments" class="<?php
                        if ($this->checkForActiveController($_GET['url'], 'request')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-comments menu-top-last">
                            <a class="<?php
                            if ($this->checkForActiveController($_GET['url'], 'request')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-comments menu-top-last" href="<?php echo URL . CONTEXT_PATH_REQUEST_EDIT; ?>">
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
                    if (!in_array(Auth::getCapability(), array(
                            CAPABILITY_SUBSCRIBER))) {

                        ?>                                     
                        <li aria-hidden="true" class="wp-not-current-submenu wp-menu-separator">
                            <div class="separator"></div>
                        </li>
                        <?php
                    }

                    ?>
                    <?php
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>                                     
                        <li id="menu-users" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'user')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-users">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'user')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-users" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
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
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_EDIT)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_EDIT)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>"><?php echo DASHBOARD_ALL_USERS_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_ADD_NEW)) {
                                    echo "current";
                                }

                                ?>"><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_ADD_NEW)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>"><?php echo DASHBOARD_USERS_ADD_NEW_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_PROFILE)) {
                                    echo "current";
                                }

                                ?>"><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_USER_PROFILE)) {
                                            echo "current";
                                        }

                                        ?>" href="<?php echo URL . CONTEXT_PATH_USER_PROFILE; ?>"><?php echo DASHBOARD_USERS_YOUR_PROFILE_TITLE; ?></a></li>
                            </ul>
                        </li>
                        <?php
                    } else if (in_array(Auth::getCapability(), array(
                            CAPABILITY_SUBSCRIBER,
                            CAPABILITY_CONTRIBUTOR,
                            CAPABILITY_AUTHOR,
                            CAPABILITY_EDITOR))) {

                        ?>
                        <li id="menu-users" class="<?php
                        if ($this->checkForActiveController($_GET['url'], 'user')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-users menu-top-first">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'user')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-users" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
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
                    if (in_array(Auth::getCapability(), array(
                            CAPABILITY_ADMINISTRATOR))) {

                        ?>     
                        <li id="menu-settings" class="wp-has-submenu <?php
                        if ($this->checkForActiveController($_GET['url'], 'options')) {
                            echo "wp-has-current-submenu wp-menu-open";
                        } else {
                            echo "wp-not-current-submenu";
                        }

                        ?> menu-top menu-icon-settings menu-top-last">
                            <a aria-haspopup="true" class="wp-has-submenu <?php
                            if ($this->checkForActiveController($_GET['url'], 'options')) {
                                echo "wp-has-current-submenu wp-menu-open";
                            } else {
                                echo "wp-not-current-submenu";
                            }

                            ?> menu-top menu-icon-settings menu-top-last" href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT; ?>">
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
                                <li class="wp-first-item <?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_GENERAL)) {
                                    echo "current";
                                }

                                ?>"><a class="wp-first-item <?php
                                    if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_GENERAL)) {
                                        echo "current";
                                    }

                                    ?>" href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_GENERAL; ?>">General</a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_WRITING)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_WRITING)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_WRITING; ?>"><?php echo DASHBOARD_SETTINGS_WRITING_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_READING)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_READING)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_READING; ?>"><?php echo DASHBOARD_SETTINGS_READING_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_DISCUSSION)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_DISCUSSION)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_DISCUSSION; ?>"><?php echo DASHBOARD_SETTINGS_DISCUSSION_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_MEDIA)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_MEDIA)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_MEDIA; ?>"><?php echo DASHBOARD_SETTINGS_MEDIA_TITLE; ?></a></li>
                                <li class="<?php
                                if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_PERMALINK)) {
                                    echo "current";
                                }

                                ?>" ><a class="<?php
                                        if ($this->checkForActiveControllerAndAction($_GET['url'], CONTEXT_PATH_OPTIONS_EDIT_PERMALINK)) {
                                            echo "current";
                                        }

                                        ?>"  href="<?php echo URL . CONTEXT_PATH_OPTIONS_EDIT_PERMALINK; ?>"><?php echo DASHBOARD_SETTINGS_PERMALINKS_TITLE; ?></a></li>
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