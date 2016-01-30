<?php
if (isset($this->userBO) && $this->userBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . USER_TITLE; ?> "<strong><?php
            if (isset($this->userBO->user_login) && $this->userBO->user_id != Session::get('user_id')) {
                echo $this->userBO->user_login;
            } else {
                echo YOU_TITLE;
            }

            ?></strong>"
        <a class="page-title-action" href="#" user="<?php echo $this->userBO->user_id; ?>" name="<?php echo htmlspecialchars($this->userBO->user_login); ?>" onclick="getEditUserPage(this)"><?php echo DASHBOARD_TOURS_EDIT_USER_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <h2><?php echo USER_NAME_TITLE; ?></h2>
    <table class="form-table">
        <tbody>
            <tr class="user-user-login-wrap">
                <th>
                    <label for="user_login"><?php echo USER_NAME; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->userBO->user_login)) {
                        echo htmlspecialchars($this->userBO->user_login);
                    }

                    ?>" id="user_login" name="user_login">
                </td>
            </tr>

            <tr class="user-role-wrap"><th><label for="role">Role</label></th>
                <td>
                    <select id="role" name="role" disabled="disabled">
                        <option value="<?php echo CAPABILITY_EDITOR; ?>" <?php
                        if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_EDITOR) {
                            echo "selected='selected'";
                        }

                        ?> ><?php echo EDITOR_TITLE; ?></option>
                        <option value="<?php echo CAPABILITY_SUBSCRIBER; ?>" <?php
                        if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_SUBSCRIBER) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo SUBSCRIBER_TITLE; ?></option>
                        <option value="<?php echo CAPABILITY_CONTRIBUTOR; ?>" <?php
                        if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_CONTRIBUTOR) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo CONTRIBUTOR_TITLE; ?></option>
                        <option value="<?php echo CAPABILITY_AUTHOR; ?>" <?php
                        if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_AUTHOR) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo AUTHOR_TITLE; ?></option>
                        <option value="<?php echo CAPABILITY_ADMINISTRATOR; ?>" <?php
                    if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_ADMINISTRATOR) {
                        echo "selected='selected'";
                    }

                    ?>><?php echo ADMINISTRATOR_TITLE; ?></option>
                    </select>
                </td>
            </tr>

            <tr class="user-first-name-wrap">
                <th>
                    <label for="first_name"><?php echo FIRST_NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->userBO->first_name)) {
                        echo htmlspecialchars($this->userBO->first_name);
                    }

                    ?>" id="first_name" name="first_name">
                </td>
            </tr>

            <tr class="user-last-name-wrap">
                <th>
                    <label for="last_name"><?php echo LAST_NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->userBO->last_name)) {
                        echo htmlspecialchars($this->userBO->last_name);
                    }

                    ?>" id="last_name" name="last_name">
                </td>
            </tr>

            <tr class="user-nickname-wrap">
                <th>
                    <label for="nickname"><?php echo NICKNAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->userBO->nickname)) {
                        echo htmlspecialchars($this->userBO->nickname);
                    }

                    ?>" id="nickname" name="nickname">
                </td>
            </tr>

            <tr class="user-display-name-wrap">
                <th>
                    <label for="display_name"><?php echo DISPLAY_NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->userBO->display_name)) {
                        echo htmlspecialchars($this->userBO->display_name);
                    }

                    ?>" id="display_name" name="display_name">
                </td>
            </tr>
        </tbody>
    </table>

    <h2><?php echo CONTACT_INFO_TITLE; ?></h2>

    <table class="form-table">
        <tbody>
            <tr class="user-email-wrap">
                <th>
                    <label for="email"><?php echo EMAIL_TITLE; ?></label>
                </th>
                <td>
                    <input type="email" class="regular-text ltr" disabled="disabled" value="<?php
                    if (isset($this->userBO->user_email)) {
                        echo htmlspecialchars($this->userBO->user_email);
                    }

                    ?>" id="email" name="email">
                </td>
            </tr>

            <tr class="user-url-wrap">
                <th>
                    <label for="url"><?php echo USER_WEBSITE_TITLE; ?></label>
                </th>
                <td>
                    <input type="url" class="regular-text code" disabled="disabled" value="<?php
                    if (isset($this->userBO->user_url)) {
                        echo htmlspecialchars($this->userBO->user_url);
                    }

                    ?>" id="url" name="url">
                </td>
            </tr>

        </tbody>
    </table>

    <h2><?php echo USER_ABOUT_TITLE; ?></h2>

    <table class="form-table">
        <tbody>
            <tr class="user-description-wrap">
                <th>
                    <label for="description"><?php echo BIOGRAPHICAL_INFO_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" cols="30" rows="5" id="description" name="description"><?php
                         if (isset($this->userBO->description)) {
                             echo htmlspecialchars($this->userBO->description);
                         }

                         ?></textarea>
                </td>
            </tr>

            <tr class="user-profile-picture">
                <th><?php echo PROFILE_PICTURE_TITLE; ?></th>
                <td>
                    <img width="96" height="96" class="avatar avatar-96 photo" srcset="<?php
                     if (isset($this->userBO->avatar_url)) {
                         echo URL . htmlspecialchars($this->userBO->avatar_url);
                     } else {
                         echo URL . AVATAR_DEFAULT;
                     }

                     ?>" src="<?php
                     if (isset($this->userBO->avatar_url)) {
                         echo URL . htmlspecialchars($this->userBO->avatar_url);
                     } else {
                         echo URL . AVATAR_DEFAULT;
                     }

                     ?>" alt="">
                </td>
            </tr>

        </tbody>
    </table> 

    <script>
        function getEditUserPage(element) {
            var user = jQuery(element).attr("user");
            var name = jQuery(element).attr("name");
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
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>
