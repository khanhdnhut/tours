<?php
$userBO = json_decode(Session::get("userInfo"));

if (isset($userBO->manage_eats_columns_show)) {
    if (isset($userBO->manage_eats_columns_show->description_show)) {
        $description_show = $userBO->manage_eats_columns_show->description_show;
    } else {
        $description_show = true;
    }
    if (isset($userBO->manage_eats_columns_show->slug_show)) {
        $slug_show = $userBO->manage_eats_columns_show->slug_show;
    } else {
        $slug_show = true;
    }
    if (isset($userBO->manage_eats_columns_show->tours_show)) {
        $tours_show = $userBO->manage_eats_columns_show->tours_show;
    } else {
        $tours_show = true;
    }
} else {
    $description_show = true;
    $slug_show = true;
    $tours_show = true;
}

if (isset($userBO->eats_per_page)) {
    $eats_per_page = $userBO->eats_per_page;
} else if (isset($_SESSION['options']) && isset($_SESSION['options']->eats_per_page)) {
    $eats_per_page = $_SESSION['options']->eats_per_page;
} else {
    $eats_per_page = EATS_PER_PAGE_DEFAULT;
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
                    <label><input type="checkbox" <?php if (isset($description_show) && $description_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="description" name="description_show" class="hide-column-tog"><?php echo DESCRIPTION_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($slug_show) && $slug_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="slug" name="slug_show" class="hide-column-tog"><?php echo SLUG_TITLE; ?></label>
                    <label><input type="checkbox" <?php if (isset($tours_show) && $tours_show) { ?> 
                                      checked="checked" <?php } ?>
                                  value="tours" name="tours_show" class="hide-column-tog"><?php echo TOURS_TITLE; ?></label>
                </fieldset>
                <fieldset class="screen-options">
                    <legend><?php echo PAGINATION_LABEL; ?></legend>
                    <label for="eats_per_page"><?php echo NUMBER_OF_ITEMS_PER_PAGE_DESC; ?></label>
                    <input type="number" value="<?php
                    if (isset($eats_per_page)) {
                        echo $eats_per_page;
                    } else {
                        echo EATS_PER_PAGE_DEFAULT;
                    }

                    ?>" maxlength="3" id="eats_per_page" name="eats_per_page" class="screen-per-page" max="999" min="1" step="1">
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
        <?php echo DASHBOARD_EAT_TITLE; ?> 
        <a class="page-title-action" onclick="getAddNewPage(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <?php $this->renderFeedbackMessages(); ?>

    <form id="form-eat-edit" method="post" onsubmit="submitFormEatEdit(event)">
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
            <label for="eat-search-input" class="screen-reader-text">
                <?php echo SEARCH_EATS_TITLE; ?>:</label>
            <input type="search" value="<?php
            if (isset($this->s)) {
                echo htmlspecialchars($this->s);
            }

            ?>" name="s" id="eat-search-input" />
            <input type="submit" value="<?php echo SEARCH_EATS_TITLE; ?>" class="button" id="search-submit" />
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
        <h2 class="screen-reader-text"><?php echo EATS_LIST_TITLE; ?></h2>
        <table class="wp-list-table widefat fixed striped eats">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb">
                        <label for="cb-select-all-1" class="screen-reader-text"><?php echo SELECT_ALL_TITLE; ?></label>
                        <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                    </td>

                    <?php
                    if (isset($this->orderby) && $this->orderby == "name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th title="Name of eat" class="manage-column column-name sorted <?php echo $this->order; ?>" id="name" scope="col">
                            <a href="#" orderby="name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th title="Name of eat" class="manage-column column-name sortable desc" id="name" scope="col">
                            <a href="#" orderby="name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }

                    if (isset($this->orderby) && $this->orderby == "description" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th title="Description of eat" class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="description" scope="col">
                            <a href="#" orderby="description" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo DESCRIPTION_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th title="Description of eat" class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="description" scope="col">
                            <a href="#" orderby="description" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo DESCRIPTION_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "slug" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th title="Slug of eat" class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="slug" scope="col">
                            <a href="#" orderby="slug" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo SLUG_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th title="Slug of eat" class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="slug" scope="col">
                            <a href="#" orderby="slug" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo SLUG_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "tours" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th title="Count tours of eat" class="manage-column column-tours <?php
                        if (!(isset($tours_show) && $tours_show)) {
                            echo " hidden";
                        }

                        ?> sorted <?php echo $this->order; ?>" id="tours" scope="col">
                            <a href="#" orderby="tours" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TOURS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th title="Count tours of eat" class="manage-column column-tours <?php
                        if (!(isset($tours_show) && $tours_show)) {
                            echo " hidden";
                        }

                        ?>  sortable desc" id="tours" scope="col">
                            <a href="#" orderby="tours" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TOURS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }

                    ?>


                </tr>
            </thead>

            <tbody data-wp-lists="list:eat" id="the-list">
                <?php
                if (!is_null($this->taxonomyList)) {
                    foreach ($this->taxonomyList as $taxonomyInfo) {

                        ?>
                        <tr id="eat-<?php echo $taxonomyInfo->term_taxonomy_id; ?>">
                            <th class="check-column" scope="row">
                                <label for="eat_<?php echo $taxonomyInfo->term_taxonomy_id; ?>" class="screen-reader-text"><?php echo SELECT_TITLE; ?> <?php echo htmlspecialchars($taxonomyInfo->name); ?></label>
                                <input type="checkbox" value="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" class="author" id="eat_<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="eats[]" >
                            </th>
                            <td data-colname="name" class="name column-name has-row-actions column-primary">                                
                                <strong>
                                    <a href="#" eat="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" onclick="getEatInfoPage(this)"><?php echo htmlspecialchars($taxonomyInfo->name); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" eat="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->slug); ?>" onclick="viewEat(this)"><?php echo VIEW_TITLE; ?>
                                        </a>
                                    </span> |                                    
                                    <span class="edit">
                                        <a href="#" eat="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->slug); ?>" onclick="getEditEatPage(this)"><?php echo EDIT_TITLE; ?>
                                        </a>
                                    </span>
                                    | <span class="delete">
                                        <a href="#" class="submitdelete" eat="<?php echo $taxonomyInfo->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($taxonomyInfo->name); ?>" onclick="deleteEat(this)"><?php echo DELETE_TITLE; ?>
                                        </a>
                                    </span>
                                </div>
                                <button class="toggle-row" type="button">
                                    <span class="screen-reader-text"><?php echo SHOW_MORE_DETAILS_TITLE; ?></span>
                                </button>
                            </td>

                            <td data-colname="<?php echo EMAIL_TITLE; ?>" class="description column-description <?php
                            if (!(isset($description_show) && $description_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($taxonomyInfo->description); ?></td>

                            <td data-colname="<?php echo ROLE_TITLE; ?>" class="slug column-slug <?php
                            if (!(isset($slug_show) && $slug_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($taxonomyInfo->slug); ?></td>

                            <td data-colname="<?php echo TOURS_TITLE; ?>" class="posts column-tours <?php
                            if (!(isset($tours_show) && $tours_show)) {
                                echo " hidden ";
                            }

                            ?>"><?php echo htmlspecialchars($taxonomyInfo->count); ?></td>

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
                    if (isset($this->orderby) && $this->orderby == "name" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-name sorted <?php echo $this->order; ?>" id="name" scope="col">
                            <a href="#" orderby="name" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-name sortable desc" id="name" scope="col">
                            <a href="#" orderby="name" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo NAME_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "description" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="description" scope="col">
                            <a href="#" orderby="description" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo DESCRIPTION_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-description <?php
                        if (!(isset($description_show) && $description_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="description" scope="col">
                            <a href="#" orderby="description" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo DESCRIPTION_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "slug" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="slug" scope="col">
                            <a href="#" orderby="slug" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo SLUG_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-slug <?php
                        if (!(isset($slug_show) && $slug_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="slug" scope="col">
                            <a href="#" orderby="slug" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo SLUG_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    }


                    if (isset($this->orderby) && $this->orderby == "tours" && in_array($this->order, array('asc', 'desc'))) {

                        ?>
                        <th class="manage-column column-tours <?php
                        if (!(isset($tours_show) && $tours_show)) {
                            echo " hidden";
                        }

                        ?>  sorted <?php echo $this->order; ?>" id="tours" scope="col">
                            <a href="#" orderby="tours" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                                <span><?php echo TOURS_TITLE; ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>   
                        <?php
                    } else {

                        ?>
                        <th class="manage-column column-tours <?php
                        if (!(isset($tours_show) && $tours_show)) {
                            echo " hidden";
                        }

                        ?> sortable desc" id="tours" scope="col">
                            <a href="#" orderby="tours" order="desc" onclick="filterOrderBy(this)">
                                <span><?php echo TOURS_TITLE; ?></span>
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
            var postDataSearch = jQuery("#form-eat-edit").serializeArray();
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
            searchEat(postData);
        });

        //        jQuery("#form-eat-edit").submit(function (e) {
        //            e.preventDefault(); //STOP default action
        //            var postData = jQuery(this).serializeArray();
        //            searchEat(postData);
        //        });

        function submitFormEatEdit(e) {
            e.preventDefault(); //STOP default action
            try {
                var postData = jQuery("#form-eat-edit").serializeArray();
                searchEat(postData);
            } catch (e) {

            }
            return false;
        }

        function searchEat(postData) {
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
            jQuery('#form-eat-edit input[name="orderby"]').val(orderby);
            jQuery('#form-eat-edit input[name="order"]').val(order);
            var postData = jQuery("#form-eat-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchEat(postData);
        }

        function filterPage(element) {
            var page = jQuery(element).attr("page");
            jQuery('#form-eat-edit input[name="page"]').val(page);
            var postData = jQuery("#form-eat-edit").serializeArray();
            //        var formURL = jQuery(this).attr("action");
            searchEat(postData);
        }

        function getEditEatPage(element) {
            var term_taxonomy_id = jQuery(element).attr("eat");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_EAT_EDIT_INFO; ?>" + term_taxonomy_id + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }
        function viewEat(element) {
            var term_taxonomy_id = jQuery(element).attr("eat");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_EAT_VIEW; ?>" + term_taxonomy_id + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getAddNewPage(element) {
            var url = "<?php echo URL . CONTEXT_PATH_EAT_ADD_NEW; ?>";
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function getEatInfoPage(element) {
            var eat = jQuery(element).attr("eat");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_EAT_INFO; ?>" + eat + "/" + name;
            if (url != null && url != "" && url != undefined) {
                var win = window.open(url, '_blank');
                win.focus();
            }
        }

        function deleteEat(element) {
            var eat = jQuery(element).attr("eat");
            var name = jQuery(element).attr("name");
            if (confirm('<?php echo CONFIRM_DELETE_EAT; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='eats[]'][type=checkbox]").prop('checked', false);
                jQuery("input[name='eats[]'][type=checkbox][value='" + eat + "']").prop('checked', true);

                jQuery('#form-eat-edit select[name="action"] option').removeAttr('selected');
                jQuery('#form-eat-edit select[name="action2"] option').removeAttr('selected');
                jQuery('#form-eat-edit select[name="action"] option[value="delete"]').attr('selected', true);
                jQuery('#form-eat-edit input[name="type"]').val('action');
                var postData = jQuery("#form-eat-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchEat(postData);

            }
        }

        function applyAction(type) {
            if (confirm('<?php echo CONFIRM_ACTION_EAT . CONFIRM_ACTION_CANCEL_OK; ?>')) {
                jQuery('#form-eat-edit input[name="type"]').val(type);
                var postData = jQuery("#form-eat-edit").serializeArray();
                //        var formURL = jQuery(this).attr("action");
                searchEat(postData);
            }
        }

        function checkAll(element) {
            if (jQuery(element).prop('checked')) {
                jQuery("#cb-select-all-1").prop('checked', true);
                jQuery("#cb-select-all-2").prop('checked', true);
                jQuery("input[name='eats[]'][type=checkbox]").prop('checked', true);
            } else {
                jQuery("#cb-select-all-1").prop('checked', false);
                jQuery("#cb-select-all-2").prop('checked', false);
                jQuery("input[name='eats[]'][type=checkbox]").prop('checked', false);
            }

        }

        //        jQuery(function () {
        //            jQuery(document).tooltip();
        //        });

    </script>
<?php } ?>
<br class="clear">