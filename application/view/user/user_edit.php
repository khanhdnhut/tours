<h1>
    <?php echo DASHBOARD_USERS_TITLE; ?> <a class="page-title-action" href="<?php echo URL . CONTEXT_PATH_USER_ADD_NEW; ?>"><?php echo DASHBOARD_USERS_ADD_NEW_TITLE; ?></a>
</h1>

<h2 class="screen-reader-text"><?php echo FILTER_USERS_LIST_TITLE; ?></h2>
<ul class="subsubsub">
    <li class="all">
        <a class="current" href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
            <?php echo FILTER_USERS_LIST_ALL_TITLE; ?> <span class="count">(<?php echo $this->count[FILTER_USERS_LIST_ALL_TITLE]; ?>)</span></a> |</li>
    <li class="administrator">
        <a href="<?php echo URL . CONTEXT_PATH_USER_FILTER_ADMINISTRATOR; ?>">
            <?php echo ADMINISTRATOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_ADMINISTRATOR]; ?>)</span></a> |</li>
    <li class="editor">
        <a href="<?php echo URL . CONTEXT_PATH_USER_FILTER_EDITOR; ?>">
            <?php echo EDITOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_EDITOR]; ?>)</span></a> |</li>
    <li class="author">
        <a href="<?php echo URL . CONTEXT_PATH_USER_FILTER_AUTHOR; ?>">
            <?php echo AUTHOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_AUTHOR]; ?>)</span></a> |</li>
    <li class="contributor">
        <a href="<?php echo URL . CONTEXT_PATH_USER_FILTER_CONTRIBUTOR; ?>">
            <?php echo CONTRIBUTOR_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_CONTRIBUTOR]; ?>)</span></a> |</li>
    <li class="subscriber">
        <a href="<?php echo URL . CONTEXT_PATH_USER_FILTER_SUBSCRIBER; ?>">
            <?php echo SUBSCRIBER_TITLE; ?> <span class="count">(<?php echo $this->count[CAPABILITY_SUBSCRIBER]; ?>)</span></a>
    </li>
</ul>
<form id="form-user-edit" method="post">
    <input type="hidden" value="" name="orderby"/>
    <input type="hidden" value="" name="order"/>
    <p class="search-box">
        <label for="user-search-input" class="screen-reader-text">
            <?php echo SEARCH_USERS_TITLE; ?>:</label>
        <input type="search" value="" name="s" id="user-search-input" />
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
            <input type="submit" value="<?php echo APPLY_TITLE; ?>" class="button action" id="doaction">
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
            <input type="submit" value="Change" class="button" id="changeit" name="changeit">
        </div>
        <?php if ($this->pageNumber > 0) { ?>
            <h2 class="screen-reader-text"><?php echo USERS_LIST_NAVIGATION; ?></h2>
            <div class="tablenav-pages"><span class="displaying-num"><?php echo $this->count[FILTER_USERS_LIST_ALL_TITLE]; ?> <?php echo ITEMS_TITLE; ?></span>
                <span class="pagination-links">
                    <?php if ($this->page == 1) { ?>
                        <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                        <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                    <?php } else { ?>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . "1"; ?>" class="first-page">
                            <span class="screen-reader-text"><?php echo FIRST_PAGE_TITLE; ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . ($this->page - 1); ?>" class="prev-page">
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
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . ($this->page + 1); ?>" class="next-page">
                            <span class="screen-reader-text"><?php echo NEXT_PAGE_TITLE; ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . $this->pageNumber; ?>" class="last-page">
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
                    <input type="checkbox" id="cb-select-all-1">
                </td>
                <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo USERNAME_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="manage-column column-name sortable desc" id="name" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo NAME_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="manage-column column-email sortable desc" id="email" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo EMAIL_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
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
                            <input type="checkbox" value="<?php echo $userInfo->user_id; ?>" class="author" id="user_<?php echo $userInfo->user_id; ?>" name="users[]">
                        </th>
                        <td data-colname="Username" class="username column-username has-row-actions column-primary">
                            <img width="32" height="32" class="avatar avatar-32 photo" srcset="<?php echo PUBLIC_IMG ?>icon/no_avatar.jpg" src="<?php echo PUBLIC_IMG ?>icon/no_avatar.jpg" alt=""> 
                            <strong>
                                <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT_INFO; ?>"><?php echo $userInfo->user_login; ?></a>
                            </strong>
                            <br>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT_INFO; ?>"><?php echo EDIT_TITLE; ?>
                                    </a> | </span>
                                <span class="delete">
                                    <a href="<?php echo URL . CONTEXT_PATH_USER_DELETE; ?>" class="submitdelete"><?php echo DELETE_TITLE; ?>
                                    </a>
                                </span>
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
                    <input type="checkbox" id="cb-select-all-2">
                </td>
                <th class="manage-column column-username column-primary sortable desc" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo USERNAME_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="manage-column column-name sortable desc" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo NAME_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="manage-column column-email sortable desc" scope="col">
                    <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT; ?>">
                        <span><?php echo EMAIL_TITLE; ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
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
            <input type="submit" value="<?php echo APPLY_TITLE; ?>" class="button action" id="doaction2">
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
            <input type="submit" value="<?php echo CHANGE_TITLE; ?>" class="button" id="changeit" name="changeit">
        </div>

        <?php if ($this->pageNumber > 0) { ?>
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $this->count[FILTER_USERS_LIST_ALL_TITLE]; ?> <?php echo ITEMS_TITLE; ?></span>
                <span class="pagination-links">
                    <?php if ($this->page == 1) { ?>
                        <span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                        <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                    <?php } else { ?>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . "1"; ?>" class="first-page">
                            <span class="screen-reader-text"><?php echo FIRST_PAGE_TITLE; ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . ($this->page - 1); ?>" class="prev-page">
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
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . ($this->page + 1); ?>" class="next-page">
                            <span class="screen-reader-text"><?php echo NEXT_PAGE_TITLE; ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a href="<?php echo URL . CONTEXT_PATH_USER_EDIT . $this->pageNumber; ?>" class="last-page">
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
        e.preventDefault(); //STOP default action
        e.unbind(); //unbind. to stop multiple form submit.
    });



</script>
<br class="clear">