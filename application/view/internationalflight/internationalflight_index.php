<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_internationalflights_columns_show)) {
    if (isset($userBO->manage_internationalflights_columns_show->city_name_show)) {
        $city_name_show = $userBO->manage_internationalflights_columns_show->city_name_show;
    } else {
        $city_name_show = true;
    }
    if (isset($userBO->manage_internationalflights_columns_show->country_name_show)) {
        $country_name_show = $userBO->manage_internationalflights_columns_show->country_name_show;
    } else {
        $country_name_show = true;
    }
    if (isset($userBO->manage_internationalflights_columns_show->current_rating_show)) {
        $current_rating_show = $userBO->manage_internationalflights_columns_show->current_rating_show;
    } else {
        $current_rating_show = true;
    }
    if (isset($userBO->manage_internationalflights_columns_show->vote_times_show)) {
        $vote_times_show = $userBO->manage_internationalflights_columns_show->vote_times_show;
    } else {
        $vote_times_show = true;
    }
} else {
    $city_name_show = true;
    $country_name_show = true;
    $current_rating_show = true;
    $vote_times_show = true;
}

if (isset($userBO->internationalflights_per_page)) {
    $internationalflights_per_page = $userBO->internationalflights_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->internationalflights_per_page)) {
    $internationalflights_per_page = $_SESSION['options']->internationalflights_per_page;
} else {
    $internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
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
                    
                    <label><input type="checkbox" <?php if (isset($city_name_show) && $city_name_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="city" name="city_name_show" class="hide-column-tog"><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></label>
                                  <label><input type="checkbox" <?php if (isset($country_name_show) && $country_name_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="country" name="country_name_show" class="hide-column-tog"><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></label>
                    
                    <label><input type="checkbox" <?php if (isset($current_rating_show) && $current_rating_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="current_rating" name="current_rating_show" class="hide-column-tog"><?php echo CURRENT_RATING_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($vote_times_show) && $vote_times_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="vote_times" name="vote_times_show" class="hide-column-tog"><?php echo VOTE_TIMES_TITLE; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo PAGINATION_LABEL; ?></legend>
                    <label for="internationalflights_per_page"><?php echo NUMBER_OF_ITEMS_PER_PAGE_DESC; ?></label>
                    <input type="number" value="<?php
                    if (isset($internationalflights_per_page)) {
                        echo $internationalflights_per_page;
                    } else {
                        echo INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="internationalflights_per_page" name="internationalflights_per_page" class="screen-per-page" max="999" min="1" step="1">
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
        <?php echo DASHBOARD_INTERNATIONALFLIGHT_TITLE; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <form id="form-internationalflight-edit" method="post" onsubmit="submitFormInternationalflightEdit(event)">
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
            <label for="internationalflight-search-input" class="screen-reader-text">
                <?php echo SEARCH_INTERNATIONALFLIGHTS_TITLE; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="internationalflight-search-input" />
            <input type="submit" value="<?php echo SEARCH_INTERNATIONALFLIGHTS_TITLE; ?>" class="button" id="search-submit" />
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
        <h2 class="screen-reader-text"><?php echo INTERNATIONALFLIGHTS_LIST_TITLE; ?></h2>
        <table class="wp-list-table widefat fixed striped internationalflights">
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
                                <span><?php echo INTERNATIONALFLIGHT_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo INTERNATIONALFLIGHT_NAME_TITLE; ?></span>
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
                                <span><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></span>
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
                                <span><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></span>
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

            <tbody data-wp-lists="list:internationalflight" id="the-list">
                <?php
                if (!is_null($this->internationalflightList)) {
                    foreach ($this->internationalflightList as $internationalflightInfo) {

                        ?>
                        <tr id="internationalflight-<?php echo $internationalflightInfo->ID; ?>">
                            <th class="check-column" scope="row">
                                <label for="internationalflight_<?php echo $internationalflightInfo->ID; ?>" class="screen-reader-text"><?php echo SELECT_TITLE; ?> <?php echo htmlspecialchars($internationalflightInfo->post_title); ?></label>
                                <input type="checkbox" value="<?php echo $internationalflightInfo->ID; ?>" class="author" id="internationalflight_<?php echo $internationalflightInfo->ID; ?>" name="internationalflights[]" >
                            </th>

                            <td data-colname="<?php echo INTERNATIONALFLIGHT_NAME_TITLE; ?>" class="username column-username has-row-actions column-primary">
                                <img width="32" height="32" class="image image-32 photo" srcset="<?php
                                if (isset($internationalflightInfo->image_url)) {
                                    echo URL . htmlspecialchars($internationalflightInfo->image_url);
                                } else {
                                    echo URL . AVATAR_DEFAULT;
                                }

                                ?>" src="<?php
                                     if (isset($internationalflightInfo->image_url)) {
                                         echo URL . htmlspecialchars($internationalflightInfo->image_url);
                                     } else {
                                         echo URL . AVATAR_DEFAULT;
                                     }

                                     ?>" alt=""> 
                                <strong>
                                    <a href="#" internationalflight="<?php echo $internationalflightInfo->ID; ?>" name="<?php echo htmlspecialchars($internationalflightInfo->post_name); ?>" onclick="getInternationalflightInfoPage(this)"><?php echo htmlspecialchars($internationalflightInfo->post_title); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" internationalflight="<?php echo $internationalflightInfo->ID; ?>" name="<?php echo htmlspecialchars($internationalflightInfo->post_name); ?>" onclick="viewInternationalflight(this)"><?php echo VIEW_TITLE; ?>
                                        </a>
                                    </span> |
                                    <span class="edit">
                                        <a href="#" internationalflight="<?php echo $internationalflightInfo->ID; ?>" name="<?php echo htmlspecialchars($internationalflightInfo->post_name); ?>" onclick="getEditInternationalflightPage(this)"><?php echo EDIT_TITLE; ?>
                                        </a>
                                    </span> |
                                    <span class="delete">
                                        <a href="#" class="submitdelete" internationalflight="<?php echo $internationalflightInfo->ID; ?>" name="<?php echo htmlspecialchars($internationalflightInfo->post_title); ?>" onclick="deleteInternationalflight(this)"><?php echo DELETE_TITLE; ?>
                                        </a>
                                    </span>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo SHOW_MORE_DETAILS_TITLE; ?></span>
                                </button>
                            </td>

                            <td data-colname="<?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?>" class="slug column-city <?php
                            if (!(isset($city_name_show) && $city_name_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($internationalflightInfo->city_name); ?></td>
                            
                            <td data-colname="<?php echo COUNTRY_TITLE; ?>" class="slug column-country <?php
                            if (!(isset($country_name_show) && $country_name_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($internationalflightInfo->country_name); ?></td>

                            <td data-colname="<?php echo CURRENT_RATING_TITLE; ?>" class="posts column-current_rating <?php
                            if (!(isset($current_rating_show) && $current_rating_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($internationalflightInfo->current_rating); ?></td>

                            <td data-colname="<?php echo VOTE_TIMES_TITLE; ?>" class="posts column-vote_times <?php
                            if (!(isset($vote_times_show) && $vote_times_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($internationalflightInfo->vote_times); ?></td>
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
                                <span><?php echo INTERNATIONALFLIGHT_NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-post_title sortable desc" id="post_title" scope="col">
                            <a href="#" orderby="post_title" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo INTERNATIONALFLIGHT_NAME_TITLE; ?></span>
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
                                <span><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></span>
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
                                <span><?php echo INTERNATIONALFLIGHT_CITY_TITLE; ?></span>
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
            var postDataSearch = jQuery("#form-internationalflight-edit").serializeArray();
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
            searchInternationalflight(postData);
        });

        //        jQuery("#form-internationalflight-edit").submit(function (e) {
        //            e.preventDefault(); //STOP default action
        //            var postData = jQuery(this).serializeArray();
        //            searchInternationalflight(postData);
        //        });

        function submitFormInternationalflightEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-internationalflight-edit").serializeArray();
                searchInternationalflight(postData);
            } catch (e) {

            }
            return false;
        }

        function searchInternationalflight(postData) {
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
            jQuery('#form-internationalflight-edit input[name="orderby"]').val(orderby);
            jQuery('#form-internationalflight-edit input[name="order"]').val(order);
            var postData = jQuery("#form-internationalflight-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchInternationalflight(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-internationalflight-edit input[name="page"]').val(page);
            var postData = jQuery("#form-internationalflight-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchInternationalflight(postData);
        }

        function getEditInternationalflightPage(element) {
            var ID = jQuery(element).attr("internationalflight");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_EDIT_INFO; ?>" + ID + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_ADD_NEW; ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getInternationalflightInfoPage(element) {
            var internationalflight = jQuery(element).attr("internationalflight");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_INFO; ?>" + internationalflight + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }
        function viewInternationalflight(element) {
            var internationalflight = jQuery(element).attr("internationalflight");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_INTERNATIONALFLIGHT_VIEW; ?>" + internationalflight + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteInternationalflight(element) {
            var internationalflight = jQuery(element).attr("internationalflight");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo CONFIRM_DELETE_INTERNATIONALFLIGHT; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='internationalflights[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='internationalflights[]'][type=checkbox][value='" + internationalflight + "']").prop('checked', true);

                jQuery('#form-internationalflight-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-internationalflight-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-internationalflight-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-internationalflight-edit input[name="type"]').val('action');
                var postData = jQuery("#form-internationalflight-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchInternationalflight(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo CONFIRM_ACTION_DESTINATION . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-internationalflight-edit input[name="type"]').val(type);
                var postData = jQuery("#form-internationalflight-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchInternationalflight(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='internationalflights[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='internationalflights[]'][type=checkbox]").prop('checked', false);
            }

        }

    </script>
<?php } ?>
<br class="clear">