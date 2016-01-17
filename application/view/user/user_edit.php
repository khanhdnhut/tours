<?php
if (isset($this->userBO) && $this->userBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_USER; ?> <strong><?php
            if (isset($this->userBO->user_login) && $this->userBO->user_id != Session::get('user_id')) {
                echo $this->userBO->user_login;
            } else {
                echo YOU_TITLE;
            }

            ?></strong>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <form id="your-profile" novalidate="novalidate" method="post" action="">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="6" id="user_id" name="user_id">
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
                        <span class="description"><?php echo USER_NAME_DESC; ?></span>
                    </td>
                </tr>

                <tr class="user-role-wrap"><th><label for="role">Role</label></th>
                    <td>
                        <select id="role" name="role" <?php if (isset($this->userBO->user_login) && $this->userBO->user_id == Session::get('user_id')) {
                            echo "disabled='disabled'";
                        } ?> >
                            <option value="<?php echo CAPABILITY_EDITOR; ?>" <?php if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_EDITOR) {
                            echo "selected='selected'";
                        } ?> ><?php echo EDITOR_TITLE; ?></option>
                            <option value="<?php echo CAPABILITY_SUBSCRIBER; ?>" <?php if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_SUBSCRIBER) {
                            echo "selected='selected'";
                        } ?>><?php echo SUBSCRIBER_TITLE; ?></option>
                            <option value="<?php echo CAPABILITY_CONTRIBUTOR; ?>" <?php if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_CONTRIBUTOR) {
                            echo "selected='selected'";
                        } ?>><?php echo CONTRIBUTOR_TITLE; ?></option>
                            <option value="<?php echo CAPABILITY_AUTHOR; ?>" <?php if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_AUTHOR) {
                            echo "selected='selected'";
                        } ?>><?php echo AUTHOR_TITLE; ?></option>
                            <option value="<?php echo CAPABILITY_ADMINISTRATOR; ?>" <?php if (isset($this->userBO->wp_capabilities) && $this->userBO->wp_capabilities == CAPABILITY_ADMINISTRATOR) {
                        echo "selected='selected'";
                    } ?>><?php echo ADMINISTRATOR_TITLE; ?></option>
                        </select>
                    </td>
                </tr>

                <tr class="user-first-name-wrap">
                    <th>
                        <label for="first_name"><?php echo FIRST_NAME_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
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
                        <input type="text" class="regular-text" value="<?php
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
                        <input type="text" class="regular-text" value="<?php
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
                        <input type="text" class="regular-text" value="<?php
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
                        <input type="email" class="regular-text ltr" value="<?php
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
                        <input type="url" class="regular-text code" value="<?php
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
                        <textarea cols="30" rows="5" id="description" name="description"><?php
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
                         if (isset($this->userBO->avatar)) {
                             echo URL . htmlspecialchars($this->userBO->avatar);
                         } else {
                             echo URL . AVATAR_DEFAULT;
                         }

                         ?>" src="<?php
                         if (isset($this->userBO->avatar)) {
                             echo URL . htmlspecialchars($this->userBO->avatar);
                         } else {
                             echo URL . AVATAR_DEFAULT;
                         }

                         ?>" alt="">
                    </td>
                </tr>

            </tbody>
        </table>
        <h2>Account Management</h2>
        <table class="form-table">
            <tbody>
                <tr class="user-pass1-wrap" id="password">
                    <th>
                        <label for="pass1-text">New Password</label>
                    </th>
                    <td>
                        <input value=" " class="hidden">
                        <!-- #24364 workaround -->
                        <button class="button button-secondary wp-generate-pw hide-if-no-js" type="button" style="display: inline-block;">Generate Password</button>
                        <div class="wp-pwd hide-if-js" style="display: none;">
                            <span class="password-input-wrapper show-password">
                                <input type="password" aria-describedby="pass-strength-result" data-pw="(u^b)NDJ643slgjvD*TB2Qny" autocomplete="off" value="" class="regular-text" id="pass1" name="pass1" disabled=""><input type="text" id="pass1-text" name="pass1-text" autocomplete="off" class="regular-text" disabled="">
                            </span>
                            <button aria-label="Hide password" data-toggle="0" class="button button-secondary wp-hide-pw hide-if-no-js" type="button">
                                <span class="dashicons dashicons-hidden"></span>
                                <span class="text">Hide</span>
                            </button>
                            <button aria-label="Cancel password change" data-toggle="0" class="button button-secondary wp-cancel-pw hide-if-no-js" type="button">
                                <span class="text">Cancel</span>
                            </button>
                            <div aria-live="polite" id="pass-strength-result" style="" class="">&nbsp;</div>
                        </div>
                    </td>
                </tr>
                <tr class="user-pass2-wrap hide-if-js" style="display: none;">
                    <th scope="row">
                        <label for="pass2">Repeat New Password</label>
                    </th>
                    <td>
                        <input type="password" autocomplete="off" value="" class="regular-text" id="pass2" name="pass2" disabled="">
                        <p class="description">Type your new password again.</p>
                    </td>
                </tr>
                <tr class="pw-weak" style="display: none;">
                    <th>Confirm Password</th>
                    <td>
                        <label>
                            <input type="checkbox" class="pw-checkbox" name="pw_weak"> Confirm use of weak password </label>
                    </td>
                </tr>


            </tbody>
        </table>
    </form>    
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>
