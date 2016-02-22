<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_hotels_columns_show)) {
    if (isset($userBO->manage_hotels_columns_show->address_show)) {
        $address_show = $userBO->manage_hotels_columns_show->address_show;
    } else {
        $address_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->city_name_show)) {
        $city_name_show = $userBO->manage_hotels_columns_show->city_name_show;
    } else {
        $city_name_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->country_name_show)) {
        $country_name_show = $userBO->manage_hotels_columns_show->country_name_show;
    } else {
        $country_name_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->star_show)) {
        $star_show = $userBO->manage_hotels_columns_show->star_show;
    } else {
        $star_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->number_of_rooms_show)) {
        $number_of_rooms_show = $userBO->manage_hotels_columns_show->number_of_rooms_show;
    } else {
        $number_of_rooms_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->current_rating_show)) {
        $current_rating_show = $userBO->manage_hotels_columns_show->current_rating_show;
    } else {
        $current_rating_show = true;
    }
    if (isset($userBO->manage_hotels_columns_show->vote_times_show)) {
        $vote_times_show = $userBO->manage_hotels_columns_show->vote_times_show;
    } else {
        $vote_times_show = true;
    }
} else {
    $address_show = true;
    $city_name_show = true;
    $country_name_show = true;
    $star_show = true;
    $number_of_rooms_show = true;
    $current_rating_show = true;
    $vote_times_show = true;
}

if (isset($userBO->hotels_per_page)) {
    $hotels_per_page = $userBO->hotels_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->hotels_per_page)) {
    $hotels_per_page = $_SESSION['options']->hotels_per_page;
} else {
    $hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
}

if (!(isset($this->ajax) && $this->ajax)) {

    ?>

    <div class="metabox-prefs" id="screen-meta">
        <div aria-label="Contextual Help Tab" tabindex="-1" class="hidden" id="contextual-help-wrap">
            Help
        </div>
        <div aria-label="Screen Options Tab" tabindex="-1" class="hidden" id="screen-options-wrap">
            <form method="post" id="adv-settings" >
                <fieldset class="metabox-prefs">
                    <legend><?php echo COLUMNS_LABEL; ?></legend>
                    <label><input type="checkbox" <?php if (isset($address_show) && $address_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="address" name="address_show" class="hide-column-tog"><?php echo HOTEL_ADDRESS_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($city_name_show) && $city_name_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="city" name="city_name_show" class="hide-column-tog"><?php echo HOTEL_CITY_TITLE; ?></label>
                                  <label><input type="checkbox" <?php if (isset($country_name_show) && $country_name_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="country" name="country_name_show" class="hide-column-tog"><?php echo HOTEL_CITY_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($star_show) && $star_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="star" name="star_show" class="hide-column-tog"><?php echo STAR_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($number_of_rooms_show) && $number_of_rooms_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="number_of_rooms" name="number_of_rooms_show" class="hide-column-tog"><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($current_rating_show) && $current_rating_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="current_rating" name="current_rating_show" class="hide-column-tog"><?php echo CURRENT_RATING_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($vote_times_show) && $vote_times_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="vote_times" name="vote_times_show" class="hide-column-tog"><?php echo VOTE_TIMES_TITLE; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo PAGINATION_LABEL; ?></legend>
                    <label for="hotels_per_page"><?php echo NUMBER_OF_ITEMS_PER_PAGE_DESC; ?></label>
                    <input type="number" value="<?php
                    if (isset($hotels_per_page)) {
                        echo $hotels_per_page;
                    } else {
                        echo HOTELS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="hotels_per_page" name="hotels_per_page" class="screen-per-page" max="999" min="1" step="1">
                </fieldset>
                <input type="hidden" value="adv_setting" name="adv_setting">
                <p class="submit"><input type="submit" value="<?php echo APPLY_TITLE; ?>" class="button button-primary"></p>            
            </form>
        </div>
    </div>
    <div id="screen-meta-links">
        <div class="hide-if-no-js screen-meta-toggle" id="contextual-help-link-wrap">
            <button aria-expanded="false" aria-controls="contextual-help-wrap" class="button show-settings" id="contextual-help-link" type="button">Help</button>
        </div>
        <div class="hide-if-no-js screen-meta-toggle" id="screen-options-link-wrap">
            <button aria-expanded="false" aria-controls="screen-options-wrap" class="button show-settings" id="show-settings-link" type="button">Screen Options</button>
        </div>
    </div>
    <div class="wrap2">
    <?php }

    ?>


    <h1>
        <?php echo DASHBOARD_HOTEL_TITLE; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <form id="form-hotel-edit" method="post" onsubmit="submitFormHotelEdit(event)">
        <input type="hidden" value="<?php
        if (isset($this->orderby)) {
            echo htmlspecialchars($this->orderby);
        }

        ?>" name="orderby"/>
        <input type="hidden" value="<?php
        if (isset($this->order)) {
            echo htmlspecialchars($this->order);
        }

        ?>" name="order"/>

        <input type="hidden" value="" name="type"/>

        <p class="search-box">
            <label for="hotel-search-input" class="screen-reader-text">
                <?php echo SEARCH_HOTELS_TITLE; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="hotel-search-input" />
            <input type="submit" value="<?php echo SEARCH_HOTELS_TITLE; ?>" class="button" id="search-submit" />
        </p>

        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-top"><?php echo SELECT_BULK_ACTION_TITLE; ?></label>
                <select id="bulk-action-selector-top" name="action">
                    <option value="-1">
                        <?php echo BULK_ACTIONS; ?>
                    </option>
                    <option value="delete">
                        <?php echo DELETE_TITLE; ?>
                    </option>
                </select>
                <div class="button" onclick="applyAction('action')"><?php echo APPLY_TITLE; ?></div>
            </div>

            <?php if ($this->pageNumber > 0) { ?>
                <h2 class="screen-reader-text"><?php echo USERS_LIST_NAVIGATION; ?></h2>
                <div class="tablenav-pages"><span class="displaying-num"><?php echo $this->count; ?> <?php echo ITEMS_TITLE; ?></span>
                    <span class="pagination-links">
                        <?php if ($this->page == 1) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                        <?php } else { ?>
                            <a  href="#" page="1" onclick="filterPage(this)" class="first-page">
                                <span class="screen-reader-text"><?php echo FIRST_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">«</span>
                            </a>
                            <a href="#" page="<?php echo ($this->page - 1); ?>" onclick="filterPage(this)" class="prev-page">
                                <span class="screen-reader-text"><?php echo PREVIOUS_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">‹</span>
                            </a>
                        <?php } ?>
                        <span class="paging-input">
                            <label class="screen-reader-text" for="current-page-selector"><?php echo CURRENT_PAGE_TITLE; ?></label>
                            <input type="text" aria-describedby="table-paging" size="1" value="<?php echo $this->page; ?>" name="page" id="current-page-selector" class="current-page"/>
                            <?php echo OF_TITLE; ?> <span class="total-pages"><?php echo $this->pageNumber; ?></span>
                        </span>

                        <?php if ($this->page == $this->pageNumber) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">›</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">»</span>
                        <?php } else { ?>
                            <a href="#" page="<?php echo ($this->page + 1); ?>" onclick="filterPage(this)" class="next-page">
                                <span class="screen-reader-text"><?php echo NEXT_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">›</span>
                            </a>
                            <a href="#" page="<?php echo $this->pageNumber; ?>" onclick="filterPage(this)" class="last-page">
                                <span class="screen-reader-text"><?php echo LAST_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">»</span>
                            </a>
                        <?php } ?> 
                    </span>
                </div>
                <br class="clear">
            <?php } ?>        
        </div>
        <h2 class="screen-reader-text"><?php echo HOTELS_LIST_TITLE; ?></h2>
        <table class="wp-list-table widefat fixed striped hotels">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb">
                        <label for="cb-select-all-1" class="screen-reader-text"><?php echo SELECT_ALL_TITLE; ?></label>
                        <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                    </td>

                    <?php
                    if (isset($this->orderby) && $this->orderby == "post_title" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-post_title sorted <?php echo $this->order; ?>" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "address" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-address <?php
                        if (!(isset($address_show) && $address_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="address" scope="col">
                            <a href="#" orderby="address" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_ADDRESS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-address <?php
                        if (!(isset($address_show) && $address_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="address" scope="col">
                            <a href="#" orderby="address" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_ADDRESS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "city_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-city_name <?php
                        if (!(isset($city_name_show) && $city_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="city_name" scope="col">
                            <a href="#" orderby="city_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_CITY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-city_name <?php
                        if (!(isset($city_name_show) && $city_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="city_name" scope="col">
                            <a href="#" orderby="city_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_CITY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }
                    
                    
                    if (isset($this->orderby) && $this->orderby == "country_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-country_name <?php
                        if (!(isset($country_name_show) && $country_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="country_name" scope="col">
                            <a href="#" orderby="country_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo COUNTRY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-country_name <?php
                        if (!(isset($country_name_show) && $country_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="country_name" scope="col">
                            <a href="#" orderby="country_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo COUNTRY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "star" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-star <?php
                        if (!(isset($star_show) && $star_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="star" scope="col">
                            <a href="#" orderby="star" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo STAR_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-star <?php
                        if (!(isset($star_show) && $star_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="star" scope="col">
                            <a href="#" orderby="star" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo STAR_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "number_of_rooms" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-number_of_rooms <?php
                        if (!(isset($number_of_rooms_show) && $number_of_rooms_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="number_of_rooms" scope="col">
                            <a href="#" orderby="number_of_rooms" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-number_of_rooms <?php
                        if (!(isset($number_of_rooms_show) && $number_of_rooms_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="number_of_rooms" scope="col">
                            <a href="#" orderby="number_of_rooms" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "current_rating" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-current_rating <?php
                        if (!(isset($current_rating_show) && $current_rating_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="current_rating" scope="col">
                            <a href="#" orderby="current_rating" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo CURRENT_RATING_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-current_rating <?php
                        if (!(isset($current_rating_show) && $current_rating_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="current_rating" scope="col">
                            <a href="#" orderby="current_rating" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo CURRENT_RATING_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "vote_times" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-vote_times <?php
                        if (!(isset($vote_times_show) && $vote_times_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="vote_times" scope="col">
                            <a href="#" orderby="vote_times" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo VOTE_TIMES_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-vote_times <?php
                        if (!(isset($vote_times_show) && $vote_times_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="vote_times" scope="col">
                            <a href="#" orderby="vote_times" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo VOTE_TIMES_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>
                </tr>
            </thead>

            <tbody data-wp-lists="list:hotel" id="the-list">
                <?php
                if (!is_null($this->hotelList)) {
                    foreach ($this->hotelList as $hotelInfo) {

                        ?>
                        <tr id="hotel-<?php echo $hotelInfo->ID; ?>">
                            <th class="check-column" scope="row">
                                <label for="hotel_<?php echo $hotelInfo->ID; ?>" class="screen-reader-text"><?php echo SELECT_TITLE; ?> <?php echo htmlspecialchars($hotelInfo->post_title); ?></label>
                                <input type="checkbox" value="<?php echo $hotelInfo->ID; ?>" class="author" id="hotel_<?php echo $hotelInfo->ID; ?>" name="hotels[]" >
                            </th>

                            <td data-colname="<?php echo HOTEL_NAME_TITLE; ?>" class="username column-username has-row-actions column-primary">
                                <img width="32" height="32" class="image image-32 photo" srcset="<?php
                                if (isset($hotelInfo->image_url)) {
                                    echo URL . htmlspecialchars($hotelInfo->image_url);
                                } else {
                                    echo URL . AVATAR_DEFAULT;
                                }

                                ?>" src="<?php
                                     if (isset($hotelInfo->image_url)) {
                                         echo URL . htmlspecialchars($hotelInfo->image_url);
                                     } else {
                                         echo URL . AVATAR_DEFAULT;
                                     }

                                     ?>" alt=""> 
                                <strong>
                                    <a href="#" hotel="<?php echo $hotelInfo->ID; ?>" name="<?php echo htmlspecialchars($hotelInfo->post_name); ?>" onclick="getHotelInfoPage(this)"><?php echo htmlspecialchars($hotelInfo->post_title); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" hotel="<?php echo $hotelInfo->ID; ?>" name="<?php echo htmlspecialchars($hotelInfo->post_name); ?>" onclick="viewHotel(this)"><?php echo VIEW_TITLE; ?>
                                        </a>
                                    </span> |
                                    <span class="edit">
                                        <a href="#" hotel="<?php echo $hotelInfo->ID; ?>" name="<?php echo htmlspecialchars($hotelInfo->post_name); ?>" onclick="getEditHotelPage(this)"><?php echo EDIT_TITLE; ?>
                                        </a>
                                    </span> |
                                    <span class="delete">
                                        <a href="#" class="submitdelete" hotel="<?php echo $hotelInfo->ID; ?>" name="<?php echo htmlspecialchars($hotelInfo->post_title); ?>" onclick="deleteHotel(this)"><?php echo DELETE_TITLE; ?>
                                        </a>
                                    </span>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo SHOW_MORE_DETAILS_TITLE; ?></span>
                                </button>
                            </td>


                            <td data-colname="<?php echo HOTEL_ADDRESS_TITLE; ?>" class="description column-address <?php
                            if (!(isset($address_show) && $address_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->address); ?></td>

                            <td data-colname="<?php echo HOTEL_CITY_TITLE; ?>" class="slug column-city <?php
                            if (!(isset($city_name_show) && $city_name_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->city_name); ?></td>
                            
                            <td data-colname="<?php echo COUNTRY_TITLE; ?>" class="slug column-country <?php
                            if (!(isset($country_name_show) && $country_name_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->country_name); ?></td>

                            <td data-colname="<?php echo STAR_TITLE; ?>" class="posts column-star <?php
                            if (!(isset($star_show) && $star_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->star); ?></td>

                            <td data-colname="<?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?>" class="posts column-number_of_rooms <?php
                            if (!(isset($number_of_rooms_show) && $number_of_rooms_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->number_of_rooms); ?></td>

                            <td data-colname="<?php echo CURRENT_RATING_TITLE; ?>" class="posts column-current_rating <?php
                            if (!(isset($current_rating_show) && $current_rating_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->current_rating); ?></td>

                            <td data-colname="<?php echo VOTE_TIMES_TITLE; ?>" class="posts column-vote_times <?php
                            if (!(isset($vote_times_show) && $vote_times_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($hotelInfo->vote_times); ?></td>
                        </tr>      
                        <?php
                    }
                }

                ?>
            </tbody>

            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <label for="cb-select-all-2" class="screen-reader-text"><?php echo SELECT_ALL_TITLE; ?></label>
                        <input type="checkbox" id="cb-select-all-2" onclick="checkAll(this)">
                    </td>
                    <?php
                    if (isset($this->orderby) && $this->orderby == "post_title" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-post_title sorted <?php echo $this->order; ?>" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "address" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-address <?php
                        if (!(isset($address_show) && $address_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="address" scope="col">
                            <a href="#" orderby="address" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_ADDRESS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-address <?php
                        if (!(isset($address_show) && $address_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="address" scope="col">
                            <a href="#" orderby="address" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_ADDRESS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "city_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-city_name <?php
                        if (!(isset($city_name_show) && $city_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="city_name" scope="col">
                            <a href="#" orderby="city_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_CITY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-city_name <?php
                        if (!(isset($city_name_show) && $city_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="city_name" scope="col">
                            <a href="#" orderby="city_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_CITY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    
                    if (isset($this->orderby) && $this->orderby == "country_name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-country_name <?php
                        if (!(isset($country_name_show) && $country_name_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="country_name" scope="col">
                            <a href="#" orderby="country_name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo COUNTRY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-country_name <?php
                        if (!(isset($country_name_show) && $country_name_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="country_name" scope="col">
                            <a href="#" orderby="country_name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo COUNTRY_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }
                    
                    if (isset($this->orderby) && $this->orderby == "star" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-star <?php
                        if (!(isset($star_show) && $star_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="star" scope="col">
                            <a href="#" orderby="star" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo STAR_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-star <?php
                        if (!(isset($star_show) && $star_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="star" scope="col">
                            <a href="#" orderby="star" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo STAR_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "number_of_rooms" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-number_of_rooms <?php
                        if (!(isset($number_of_rooms_show) && $number_of_rooms_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="number_of_rooms" scope="col">
                            <a href="#" orderby="number_of_rooms" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-number_of_rooms <?php
                        if (!(isset($number_of_rooms_show) && $number_of_rooms_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="number_of_rooms" scope="col">
                            <a href="#" orderby="number_of_rooms" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "current_rating" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-current_rating <?php
                        if (!(isset($current_rating_show) && $current_rating_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="current_rating" scope="col">
                            <a href="#" orderby="current_rating" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo CURRENT_RATING_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-current_rating <?php
                        if (!(isset($current_rating_show) && $current_rating_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="current_rating" scope="col">
                            <a href="#" orderby="current_rating" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo CURRENT_RATING_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "vote_times" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-vote_times <?php
                        if (!(isset($vote_times_show) && $vote_times_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="vote_times" scope="col">
                            <a href="#" orderby="vote_times" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo VOTE_TIMES_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-vote_times <?php
                        if (!(isset($vote_times_show) && $vote_times_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="vote_times" scope="col">
                            <a href="#" orderby="vote_times" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo VOTE_TIMES_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>

                </tr>
            </tfoot>

        </table>
        <div class="tablenav bottom">

            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-bottom"><?php echo SELECT_BULK_ACTION_TITLE; ?></label>
                <select id="bulk-action-selector-bottom" name="action2">
                    <option value="-1"><?php echo BULK_ACTIONS; ?></option>
                    <option value="delete"><?php echo DELETE_TITLE; ?></option>
                </select>
                <div class="button" onclick="applyAction('action2')"><?php echo APPLY_TITLE; ?></div>
            </div>


            <?php if ($this->pageNumber > 0) { ?>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $this->count; ?> <?php echo ITEMS_TITLE; ?></span>
                    <span class="pagination-links">
                        <?php if ($this->page == 1) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                        <?php } else { ?>
                            <a href="#" page="1" onclick="filterPage(this)" class="first-page">
                                <span class="screen-reader-text"><?php echo FIRST_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">«</span>
                            </a>
                            <a href="#" page="<?php echo ($this->page - 1); ?>" onclick="filterPage(this)" class="prev-page">
                                <span class="screen-reader-text"><?php echo PREVIOUS_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">‹</span>
                            </a>                        
                        <?php } ?>
                        <span class="screen-reader-text"><?php echo CURRENT_PAGE_TITLE; ?></span>
                        <span class="paging-input" id="table-paging"><?php echo $this->page; ?> <?php echo OF_TITLE; ?> <span class="total-pages"><?php echo $this->pageNumber; ?></span></span>
                        <?php if ($this->page == $this->pageNumber) { ?>
                            <span aria-hidden="true" class="tablenav-pages-navspan">›</span>
                            <span aria-hidden="true" class="tablenav-pages-navspan">»</span>
                        <?php } else { ?>
                            <a href="#" page="<?php echo ($this->page + 1); ?>" onclick="filterPage(this)" class="next-page">
                                <span class="screen-reader-text"><?php echo NEXT_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">›</span>
                            </a>
                            <a href="#" page="<?php echo $this->pageNumber; ?>" onclick="filterPage(this)" class="last-page">
                                <span class="screen-reader-text"><?php echo LAST_PAGE_TITLE; ?></span>
                                <span aria-hidden="true">»</span>
                            </a>                      
                        <?php } ?>
                </div>
                <br class="clear">
                <br class="clear">
            <?php } ?> 
        </div>
    </form>
    <?php if (!(isset($this->ajax) && $this->ajax)) {

        ?>
    </div>
    <?php
}
if (!(isset($this->ajax) && $this->ajax)) {

    ?>
    <script>
        jQuery("#adv-settings").submit(function (e) {
            e.preventDefault(); //STOP default action
            var postData = jQuery(this).serializeArray();
            var postDataSearch = jQuery("#form-hotel-edit").serializeArray();
            for (var i = 0; i < postDataSearch.length; i++) {
                if (postDataSearch[i].name == "type") {
                    postDataSearch[i].value == "";
                } else if (postDataSearch[i].name == "action") {
                    postDataSearch[i].value == "-1";
                } else if (postDataSearch[i].name == "action2") {
                    postDataSearch[i].value == "-1";
                }
                postData[postData.length] = postDataSearch[i];
            }
            searchHotel(postData);
        });

        //        jQuery("#form-hotel-edit").submit(function (e) {
        //            e.preventDefault(); //STOP default action
        //            var postData = jQuery(this).serializeArray();
        //            searchHotel(postData);
        //        });

        function submitFormHotelEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-hotel-edit").serializeArray();
                searchHotel(postData);
            } catch (e) {

            }
            return false;
        }

        function searchHotel(postData) {
            jQuery.ajax({
                url: "",
                type: "POST",
                data: postData,
                success: function (data, textStatus, jqXHR)
                {
                    jQuery(".wrap2").html(data);
                    //data: return data from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //if fails      
                }
            });
        }

        function filterOrderBy(element) {
            var orderby = jQuery(element).attr("orderby");
            var order = jQuery(element).attr("order");
            if (order == "asc") {
                order = "desc";
            } else {
                order = "asc";
            }
            jQuery('#form-hotel-edit input[name="orderby"]').val(orderby);
            jQuery('#form-hotel-edit input[name="order"]').val(order);
            var postData = jQuery("#form-hotel-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchHotel(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-hotel-edit input[name="page"]').val(page);
            var postData = jQuery("#form-hotel-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchHotel(postData);
        }

        function getEditHotelPage(element) {
            var ID = jQuery(element).attr("hotel");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_HOTEL_EDIT_INFO; ?>" + ID + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . CONTEXT_PATH_HOTEL_ADD_NEW; ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getHotelInfoPage(element) {
            var hotel = jQuery(element).attr("hotel");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_HOTEL_INFO; ?>" + hotel + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }
        function viewHotel(element) {
            var hotel = jQuery(element).attr("hotel");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_HOTEL_VIEW; ?>" + hotel + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteHotel(element) {
            var hotel = jQuery(element).attr("hotel");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo CONFIRM_DELETE_HOTEL; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='hotels[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='hotels[]'][type=checkbox][value='" + hotel + "']").prop('checked', true);

                jQuery('#form-hotel-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-hotel-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-hotel-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-hotel-edit input[name="type"]').val('action');
                var postData = jQuery("#form-hotel-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchHotel(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo CONFIRM_ACTION_DESTINATION . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-hotel-edit input[name="type"]').val(type);
                var postData = jQuery("#form-hotel-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchHotel(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='hotels[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='hotels[]'][type=checkbox]").prop('checked', false);
            }

        }

    </script>
<?php } ?>
<br class="clear">