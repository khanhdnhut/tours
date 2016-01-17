<h1>
    <?php echo DASHBOARD_USERS_TITLE; ?> <a class="page-title-action" href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>"><?php echo DASHBOARD_USERS_ADD_NEW_TITLE; ?></a>
</h1>

<?php $this->renderFeedbackMessages(); ?>

<h2 class="screen-reader-text"><?php echo FILTER_USERS_LIST_TITLE; ?></h2>
<ul class="subsubsub">
    <li class="all">
        <a class="<?php
        if (isset($this->role) && $this->role == -1) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="-1">
            <?php echo FILTER_USERS_LIST_ALL_TITLE; ?> <span class="count">(<?php echo $this->count[FILTER_USERS_LIST_ALL_TITLE]; ?>)</span></a> |</li>
    <li class="administrator">
        <a class="<?php
        if (isset($this->role) && $this->role == CAPABILITY_ADMINISTRATOR) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="<?php echo CAPABILITY_ADMINISTRATOR; ?>">
            <?php echo ADMINISTRATOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_ADMINISTRATOR]; ?>)</span></a> |</li>
    <li class="editor">
        <a class="<?php
        if (isset($this->role) && $this->role == CAPABILITY_EDITOR) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="<?php echo CAPABILITY_EDITOR; ?>">
            <?php echo EDITOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_EDITOR]; ?>)</span></a> |</li>
    <li class="author">
        <a class="<?php
        if (isset($this->role) && $this->role == CAPABILITY_AUTHOR) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="<?php echo CAPABILITY_AUTHOR; ?>">
            <?php echo AUTHOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_AUTHOR]; ?>)</span></a> |</li>
    <li class="contributor">
        <a class="<?php
        if (isset($this->role) && $this->role == CAPABILITY_CONTRIBUTOR) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="<?php echo CAPABILITY_CONTRIBUTOR; ?>">
            <?php echo CONTRIBUTOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_CONTRIBUTOR]; ?>)</span></a> |</li>
    <li class="subscriber">
        <a class="<?php
        if (isset($this->role) && $this->role == CAPABILITY_SUBSCRIBER) {
            echo "current";
        }

        ?>" href="#" onclick="filterRole(this)" role="<?php echo CAPABILITY_SUBSCRIBER; ?>">
            <?php echo SUBSCRIBER_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_SUBSCRIBER]; ?>)</span></a>
    </li>
</ul>
<form id="form-user-edit" method="post">
    <input type="hidden" value="<?php
    if (isset($this->role)) {
        echo htmlspecialchars($this->role);
    }

    ?>" name="role"/>
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
        <label for="user-search-input" class="screen-reader-text">
            <?php echo SEARCH_USERS_TITLE; ?>:</label>
        <input type="search" value="<?php
        if (isset($this->s)) {
            echo htmlspecialchars($this->s);
        }

        ?>" name="s" id="user-search-input" />
        <input type="submit" value="<?php echo SEARCH_USERS_TITLE; ?>" class="button" id="search-submit" />
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
        <div class="alignleft actions">
            <label for="new_role" class="screen-reader-text">
                <?php echo CHANGE_ROLE_TO; ?>…</label>
            <select id="new_role" name="new_role">
                <option value="">
                    <?php echo CHANGE_ROLE_TO; ?>…</option>

                <option value="subscriber">
                    <?php echo SUBSCRIBER_TITLE; ?>
                </option>
                <option value="contributor">
                    <?php echo CONTRIBUTOR_TITLE; ?>
                </option>
                <option value="author">
                    <?php echo AUTHOR_TITLE; ?>
                </option>
                <option value="editor">
                    <?php echo EDITOR_TITLE; ?>
                </option>
                <option value="administrator">
                    <?php echo ADMINISTRATOR_TITLE; ?>
                </option>
            </select>
            <div class="button" onclick="applyAction('new_role')"><?php echo APPLY_TITLE; ?></div>
        </div>
        <?php if ($this->pageNumber > 0) { ?>
            <h2 class="screen-reader-text"><?php echo USERS_LIST_NAVIGATION; ?></h2>
            <div class="tablenav-pages"><span class="displaying-num"><?php echo $this->count[NUMBER_SEARCH_USER]; ?> <?php echo ITEMS_TITLE; ?></span>
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
    <h2 class="screen-reader-text"><?php echo USERS_LIST_TITLE; ?></h2>
    <table class="wp-list-table widefat fixed striped users">
        <thead>
            <tr>
                <td class="manage-column column-cb check-column" id="cb">
                    <label for="cb-select-all-1" class="screen-reader-text"><?php echo SELECT_ALL_TITLE; ?></label>
                    <input type="checkbox" id="cb-select-all-1" onclick="checkAll(this)">
                </td>

                <?php
                if (isset($this->orderby) && $this->orderby == "login" && in_array($this->order, array('asc', 'desc'))) {

                    ?>
                    <th class="manage-column column-username column-primary sorted <?php echo $this->order; ?>" id="username" scope="col">
                        <a href="#" orderby="login" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                            <span><?php echo USERNAME_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php
                } else {

                    ?>
                    <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                        <a href="#" orderby="login" order="desc" onclick="filterOrderBy(this)">
                            <span><?php echo USERNAME_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php
                }

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

                if (isset($this->orderby) && $this->orderby == "email" && in_array($this->order, array('asc', 'desc'))) {

                    ?>
                    <th class="manage-column column-email sorted <?php echo $this->order; ?>" id="email" scope="col">
                        <a href="#" orderby="email" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                            <span><?php echo EMAIL_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>   
                    <?php
                } else {

                    ?>
                    <th class="manage-column column-email sortable desc" id="email" scope="col">
                        <a href="#" orderby="email" order="desc" onclick="filterOrderBy(this)">
                            <span><?php echo EMAIL_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>   
                    <?php
                }

                ?>

                <th class="manage-column column-role" id="role" scope="col"><?php echo ROLE_TITLE; ?></th>
                <th class="manage-column column-posts num" id="posts" scope="col"><?php echo POSTS_TITLE; ?></th>
            </tr>
        </thead>

        <tbody data-wp-lists="list:user" id="the-list">
            <?php
            if ($this->userList != NULL) {
                foreach ($this->userList as $userInfo) {

                    ?>
                    <tr id="user-<?php echo $userInfo->user_id; ?>">
                        <th class="check-column" scope="row">
                            <label for="user_<?php echo $userInfo->user_id; ?>" class="screen-reader-text"><?php echo SELECT_TITLE; ?> <?php echo $userInfo->user_login; ?></label>
                            <input type="checkbox" value="<?php echo $userInfo->user_id; ?>" class="author" id="user_<?php echo $userInfo->user_id; ?>" name="users[]" <?php
                            if ($userInfo->user_id == Session::get('user_id') || $userInfo->user_id != 1) {
                                echo "";
                            }

                            ?>>
                        </th>
                        <td data-colname="Username" class="username column-username has-row-actions column-primary">
                            <img width="32" height="32" class="avatar avatar-32 photo" srcset="<?php
                            if (isset($userInfo->avatar)) {
                                echo URL . htmlspecialchars($userInfo->avatar);
                            } else {
                                echo URL . AVATAR_DEFAULT;
                            }

                            ?>" src="<?php
                                 if (isset($userInfo->avatar)) {
                                     echo URL . htmlspecialchars($userInfo->avatar);
                                 } else {
                                     echo URL . AVATAR_DEFAULT;
                                 }

                                 ?>" alt=""> 
                            <strong>
                                <a href="#" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="getUserInfoPage(this)"><?php echo htmlspecialchars($userInfo->user_login); ?></a>
                            </strong>
                            <br>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="#" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="getEditUserPage(this)"><?php echo EDIT_TITLE; ?>
                                    </a>
                                </span>
                                <?php
                                if ($userInfo->user_id != Session::get('user_id') && $userInfo->user_id != 1) {

                                    ?>
                                    | <span class="delete">
                                        <a href="#" class="submitdelete" user="<?php echo $userInfo->user_id; ?>" name="<?php echo htmlspecialchars($userInfo->user_login); ?>" onclick="deleteUser(this)"><?php echo DELETE_TITLE; ?>
                                        </a>
                                    </span>
                                    <?php
                                }

                                ?>
                            </div>
                            <button class="toggle-row" type="button">
                                <span class="screen-reader-text"><?php echo SHOW_MORE_DETAILS_TITLE; ?></span>
                            </button>
                        </td>
                        <td data-colname="<?php echo NAME_TITLE; ?>" class="name column-name"><?php echo $userInfo->display_name; ?></td>
                        <td data-colname="<?php echo EMAIL_TITLE; ?>" class="email column-email"><a href="mailto:<?php echo $userInfo->user_email; ?>"><?php echo $userInfo->user_email; ?></a></td>
                        <td data-colname="<?php echo ROLE_TITLE; ?>" class="role column-role">
                            <?php echo ucfirst($userInfo->wp_capabilities); ?>
                        </td>
                        <td data-colname="<?php echo POSTS_TITLE; ?>" class="posts column-posts num">0</td>
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
                if (isset($this->orderby) && $this->orderby == "login" && in_array($this->order, array('asc', 'desc'))) {

                    ?>
                    <th class="manage-column column-username column-primary sorted <?php echo $this->order; ?>" id="username" scope="col">
                        <a href="#" orderby="login" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                            <span><?php echo USERNAME_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php
                } else {

                    ?>
                    <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                        <a href="#" orderby="login" order="desc" onclick="filterOrderBy(this)">
                            <span><?php echo USERNAME_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php
                }

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

                if (isset($this->orderby) && $this->orderby == "email" && in_array($this->order, array('asc', 'desc'))) {

                    ?>
                    <th class="manage-column column-email sorted <?php echo $this->order; ?>" id="email" scope="col">
                        <a href="#" orderby="email" order="<?php echo $this->order; ?>" onclick="filterOrderBy(this)">
                            <span><?php echo EMAIL_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>   
                    <?php
                } else {

                    ?>
                    <th class="manage-column column-email sortable desc" id="email" scope="col">
                        <a href="#" orderby="email" order="desc" onclick="filterOrderBy(this)">
                            <span><?php echo EMAIL_TITLE; ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>   
                    <?php
                }

                ?>
                <th class="manage-column column-role" scope="col"><?php echo ROLE_TITLE; ?></th>
                <th class="manage-column column-posts num" scope="col"><?php echo POSTS_TITLE; ?></th>
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
        <div class="alignleft actions">
            <label for="new_role2" class="screen-reader-text"><?php echo CHANGE_ROLE_TO; ?>…</label>
            <select id="new_role2" name="new_role2">
                <option value=""><?php echo CHANGE_ROLE_TO; ?>…</option>

                <option value="subscriber"><?php echo SUBSCRIBER_TITLE; ?></option>
                <option value="contributor"><?php echo CONTRIBUTOR_TITLE; ?></option>
                <option value="author"><?php echo AUTHOR_TITLE; ?></option>
                <option value="editor"><?php echo EDITOR_TITLE; ?></option>
                <option value="administrator"><?php echo ADMINISTRATOR_TITLE; ?></option>
            </select>            
            <div class="button" onclick="applyAction('new_role2')"><?php echo APPLY_TITLE; ?></div>
        </div>

        <?php if ($this->pageNumber > 0) { ?>
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $this->count[NUMBER_SEARCH_USER]; ?> <?php echo ITEMS_TITLE; ?></span>
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
<script>
    jQuery("#form-user-edit").submit(function (e) {
        var postData = jQuery(this).serializeArray();
//        var formURL = jQuery(this).attr("action");
        searchUser(postData);
        e.preventDefault(); //STOP default action
    });

    function searchUser(postData) {
        jQuery.ajax({
            url: "",
            type: "POST",
            data: postData,
            success: function (data, textStatus, jqXHR)
            {
                jQuery(".wrap").html(data);
                //data: return data from server
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //if fails      
            }
        });
    }

    function filterRole(element) {
        var role = jQuery(element).attr("role");
        jQuery('#form-user-edit input[name="role"]').val(role);
        var postData = jQuery("#form-user-edit").serializeArray();
//        var formURL = jQuery(this).attr("action");
        searchUser(postData);
    }

    function filterOrderBy(element) {
        var orderby = jQuery(element).attr("orderby");
        var order = jQuery(element).attr("order");
        if (order == "asc") {
            order = "desc";
        } else {
            order = "asc";
        }
        jQuery('#form-user-edit input[name="orderby"]').val(orderby);
        jQuery('#form-user-edit input[name="order"]').val(order);
        var postData = jQuery("#form-user-edit").serializeArray();
//        var formURL = jQuery(this).attr("action");
        searchUser(postData);
    }

    function filterPage(element) {
        var page = jQuery(element).attr("page");
        jQuery('#form-user-edit input[name="page"]').val(page);
        var postData = jQuery("#form-user-edit").serializeArray();
//        var formURL = jQuery(this).attr("action");
        searchUser(postData);
    }

    function getEditUserPage(element) {
        var user = jQuery(element).attr("user");
        if (confirm('<?php echo CONFIRM_EDIT_INFO_USER; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
            var url = "<?php echo URL . CONTEXT_PATH_USER_EDIT_INFO; ?>" + user + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_USER_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    user: user
                },
                success: function (data, textStatus, jqXHR)
                {
                    jQuery(".wrap").html(data);
                    //data: return data from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //if fails      
                }
            });
        }
    }
    function getUserInfoPage(element) {
        var user = jQuery(element).attr("user");
        var name = jQuery(element).attr("name");
        var url = "<?php echo URL . CONTEXT_PATH_USER_INFO; ?>" + user + "/" + name;
        if (window.history.replaceState) {
            window.history.replaceState(null, null, url);
        } else if (window.history && window.history.pushState) {
            window.history.pushState({}, null, url);
        } else {
            location = url;
        }

        jQuery.ajax({
            url: "<?php echo URL . CONTEXT_PATH_USER_INFO; ?>",
            type: "POST",
            data: {
                user: user
            },
            success: function (data, textStatus, jqXHR)
            {
                jQuery(".wrap").html(data);
                //data: return data from server
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //if fails      
            }
        });
    }

    function deleteUser(element) {
        var user = jQuery(element).attr("user");
        var name = jQuery(element).attr("name");
        if (confirm('<?php echo CONFIRM_DELETE_USER; ?>' + name + '<?php echo CONFIRM_DELETE_CANCEL_OK; ?>')) {
            jQuery("#cb-select-all-1").prop('checked', false);
            jQuery("#cb-select-all-2").prop('checked', false);
            jQuery("input[name='users[]'][type=checkbox]").prop('checked', false);
            jQuery("input[name='users[]'][type=checkbox][value='" + user + "']").prop('checked', true);

            jQuery('#form-user-edit select[name="action"] option').removeAttr('selected');
            jQuery('#form-user-edit select[name="action2"] option').removeAttr('selected');
            jQuery('#form-user-edit select[name="new_role"] option').removeAttr('selected');
            jQuery('#form-user-edit select[name="new_role2"] option').removeAttr('selected');
            jQuery('#form-user-edit select[name="action"] option[value="delete"]').attr('selected', true);
            jQuery('#form-user-edit input[name="type"]').val('action');
            var postData = jQuery("#form-user-edit").serializeArray();
//        var formURL = jQuery(this).attr("action");
            searchUser(postData);

        }
    }

    function applyAction(type) {
        jQuery('#form-user-edit input[name="type"]').val(type);
        var postData = jQuery("#form-user-edit").serializeArray();
//        var formURL = jQuery(this).attr("action");
        searchUser(postData);
    }

    function checkAll(element) {
        if (jQuery(element).prop('checked')) {
            jQuery("#cb-select-all-1").prop('checked', true);
            jQuery("#cb-select-all-2").prop('checked', true);
            jQuery("input[name='users[]'][type=checkbox]").prop('checked', true);
        } else {
            jQuery("#cb-select-all-1").prop('checked', false);
            jQuery("#cb-select-all-2").prop('checked', false);
            jQuery("input[name='users[]'][type=checkbox]").prop('checked', false);
        }

    }

</script>
<br class="clear">