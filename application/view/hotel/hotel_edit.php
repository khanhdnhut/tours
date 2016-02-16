<?php
if (isset($this->hotelBO) && $this->hotelBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo EDIT_PROFILE_OF_TITLE . " " . HOTEL_TITLE; ?> "<strong><?php
            if (isset($this->hotelBO->name)) {
                echo $this->hotelBO->name;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . CONTEXT_PATH_HOTEL_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_HOTEL_EDIT_INFO; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->hotelBO->ID)) {
            echo htmlspecialchars($this->hotelBO->ID);
        }

        ?>" id="hotel" name="hotel">
        <table class="form-table">
            <tbody>
                <tr class="hotel-post-title-wrap">
                    <th colspan="1">
                        <label for="post_title"><?php echo HOTEL_NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td colspan="3">
                        <input type="text" class="large-text" value="<?php
                        if (isset($this->hotelBO->post_title)) {
                            echo htmlspecialchars($this->hotelBO->post_title);
                        }

                        ?>" id="post_title" name="post_title">
                    </td>
                </tr>

                <tr class="hotel-address-wrap">
                    <th colspan="1">
                        <label for="address"><?php echo HOTEL_ADDRESS_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td colspan="3">
                        <input type="text" class="large-text" value="<?php
                        if (isset($this->hotelBO->address)) {
                            echo htmlspecialchars($this->hotelBO->address);
                        }

                        ?>" id="address" name="address">
                    </td>
                </tr>

                <tr class="hotel-number-of-rooms-wrap">
                    <th>
                        <label for="number_of_rooms"><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="1" max="999" class="screen-per-page" name="number_of_rooms" id="number_of_rooms" maxlength="3" value="<?php
                        if (isset($this->hotelBO->number_of_rooms)) {
                            echo htmlspecialchars($this->hotelBO->number_of_rooms);
                        }

                        ?>" style="min-width: 150px;">
                    </td>

                    <th>
                        <label for="star"><?php echo HOTEL_STAR_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="0" max="5" class="screen-per-page" name="star" id="star" maxlength="3" value="<?php
                        if (isset($this->hotelBO->star)) {
                            echo htmlspecialchars($this->hotelBO->star);
                        }

                        ?>" style="min-width: 150px;">
                    </td>


                </tr>

                <tr class="hotel-vote-times-wrap">
                    <th>
                        <label for="current_rating"><?php echo HOTEL_CURRENT_RATING_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
                        if (isset($this->hotelBO->current_rating)) {
                            echo htmlspecialchars($this->hotelBO->current_rating);
                        }

                        ?>" style="min-width: 150px;">
                    </td>

                    <th>
                        <label for="vote_times"><?php echo HOTEL_VOTE_TIMES_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                        if (isset($this->hotelBO->vote_times)) {
                            echo htmlspecialchars($this->hotelBO->vote_times);
                        }

                        ?>" style="min-width: 150px;">
                    </td>


                </tr>

                <tr class="hotel-city-id-wrap">
                    <th colspan="1"><label for="city_id"><?php echo HOTEL_CITY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                    <td colspan="3">
                        <select id="city_id" name="city_id"  style="min-width: 150px;">
                            <?php
                            if (isset($this->cityList) && is_a($this->cityList, "SplDoublyLinkedList")) {
                                $this->cityList->rewind();
                                foreach ($this->cityList as $value) {

                                    ?>
                                    <option value="<?php
                                    if (isset($value->term_taxonomy_id)) {
                                        echo $value->term_taxonomy_id;
                                    }

                                    ?>" <?php
                                            if (isset($this->hotelBO->city_id) && $this->hotelBO->city_id == $value->term_taxonomy_id) {
                                                echo "selected='selected'";
                                            }

                                            ?>><?php
                                                if (isset($value->name)) {
                                                    echo $value->name;
                                                }

                                                ?></option>
                                    <?php
                                }
                            }

                            ?>
                            <option value="0" <?php
                            if (isset($this->hotelBO->city_id) && $this->hotelBO->city_id == 0) {
                                echo "selected='selected'";
                            } elseif (!isset($this->hotelBO->city_id)) {
                                echo "selected='selected'";
                            }

                            ?>><?php echo NONE_TITLE; ?></option>                                                                        
                        </select>
                    </td>
                </tr>


                <tr class="hotel-image-wrap">
                    <th colspan="1">
                        <label for="image"><?php echo HOTEL_IMAGE_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                    </th>
                    <td colspan="3">
                        <img width="96" height="96" class="avatar avatar-96 photo" srcset="<?php
                        if (isset($this->hotelBO->image_url)) {
                            echo URL . htmlspecialchars($this->hotelBO->image_url);
                        } else {
                            echo URL . AVATAR_DEFAULT;
                        }

                        ?>" src="<?php
                             if (isset($this->hotelBO->image_url)) {
                                 echo URL . htmlspecialchars($this->hotelBO->image_url);
                             } else {
                                 echo URL . AVATAR_DEFAULT;
                             }

                             ?>" alt="">
                        <br>
                        <input type="file" id="image" name="image" accept=".jpg, .png, .jpeg" required>
                    </td>
                </tr>
                <tr class="hotel-post-content-wrap">
                    <th colspan="1">
                        <label for="post_content"><?php echo HOTEL_CONTENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                    </th>
                    <td colspan="3">
                        <textarea id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->hotelBO) && isset($this->hotelBO->post_content)) {
                                echo htmlspecialchars($this->hotelBO->post_content);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="hotel-tags-wrap">   
                    <th colspan="1">
                        <label for="tags"><?php echo HOTEL_TAGS_TITLE; ?></label>
                    </th>
                    <td colspan="3">
                        <input style="min-width: 200px;" type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="tag_input" id="tags" onkeyup="searchTagAjax(this.value)">
                        <ul id="livesearch" class="ac_results" style="display: block;">
                        </ul>                    
                        <input type="button" value="Add" class="button tagadd" onclick="addInputTag()">
                        <p id="new-tag-post_tag-desc" class="howto">Separate tags with commas</p>
                        <div id="tagchecklist" class="tagchecklist">
                            <?php
                            if (isset($this->hotelBO->tag_list) && count($this->hotelBO->tag_list) > 0) {
                                $tagArray = array();
                                foreach ($this->hotelBO->tag_list as $tag) {
                                    $tagArray[] = $tag->name;

                                    ?>
                                    <span><a onclick="removeTag(this)" tag_name="<?php echo $tag->name; ?>" class="ntdelbutton" tabindex="0">X</a>&nbsp;<?php echo $tag->name; ?></span>
                                    <?php
                                }
                            }

                            ?>

                        </div>
                        <input type="hidden" name="tag_list" value="<?php
                        if (isset($tagArray) && count($tagArray) > 0) {
                            echo join(",", $tagArray);
                        }

                        ?>">
                    </td>
                </tr>

            </tbody>
        </table>

        <p class="submit"><input type="submit" value="<?php echo LABEL_UPDATE_HOTEL; ?>" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <script>
        window.scrollTo(0, 0);

        function getDoc(frame) {
            var doc = null;
            // IE8 cascading access check
            try {
                if (frame.contentWindow) {
                    doc = frame.contentWindow.document;
                }
            } catch (err) {
            }
            if (doc) { // successful getting content
                return doc;
            }
            try { // simply checking may throw in ie8 under ssl or mismatched protocol
                doc = frame.contentDocument ? frame.contentDocument : frame.document;
            } catch (err) {
                // last attempt
                doc = frame.document;
            }
            return doc;
        }

        function hideMessageSuccess() {
            jQuery("#message-success").hide();
        }
        function hideMessageError() {
            jQuery("#message-error").hide();
        }

        function noticeError(message) {
            document.getElementById('message_notice').innerHTML =
                    "<div class='error notice is-dismissible' id='message-error'><p>" + message + "</p>"
            "   <button class='notice-dismiss' type='button' onclick='hideMessageError()'>" + "       <span class='screen-reader-text'>Dismiss this notice.</span>" +
                    "   </button>" +
                    "</div>";
            window.scrollTo(0, 0);
        }

        function validateFormEditHotel() {
            if (jQuery('#form-your-profile input[name="post_title"]').val() == "") {
                noticeError("<?php echo ERROR_TITLE_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile input[name="address"]').val() == "") {
                noticeError("<?php echo ERROR_ADDRESS_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile select[name="city_id"]').val() == "0") {
                noticeError("<?php echo ERROR_CITY_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile textarea[name="post_content"]').val() == "") {
                noticeError("<?php echo ERROR_CONTENT_EMPTY; ?>");
                return false;
            }
            if (jQuery("#image").val() != "") {
                var validExts = new Array(".jpg", ".png", ".jpeg");
                var fileExt = jQuery('#image').val();
                fileExt = fileExt.substring(fileExt.lastIndexOf('.')).toLowerCase();
                if (validExts.indexOf(fileExt) < 0) {
                    noticeError("<?php echo ERROR_HOTEL_IMAGE_INVALID; ?>");
                    return false;
                }
            }
            return true;
        }
        jQuery("#form-your-profile").submit(function (e) {
            e.preventDefault();
            if (!validateFormEditHotel()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="post_title"]').val();
            if (confirm('<?php echo CONFIRM_EDIT_INFO_HOTEL; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
                var formObj = jQuery(this);
                var formURL = formObj.attr("action");
                if (window.FormData !== undefined)  // for HTML5 browsers
                {
                    var formData = new FormData(this);
                    jQuery.ajax({
                        url: formURL,
                        type: "POST",
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data, textStatus, jqXHR)
                        {
                            jQuery(".wrap").html(data);
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                        }
                    });
                    e.preventDefault();
                }
                else  //for olden browsers
                {
                    //generate a random id
                    var iframeId = "unique" + (new Date().getTime());
                    //create an empty iframe
                    var iframe = jQuery('<iframe src="javascript:false;" name="' + iframeId + '" />');
                    //hide it
                    iframe.hide();
                    //set form target to iframe
                    formObj.attr("target", iframeId);
                    //Add iframe to body
                    iframe.appendTo("body");
                    iframe.load(function (e)
                    {
                        var doc = getDoc(iframe[0]);
                        var docRoot = doc.body ? doc.body : doc.documentElement;
                        var data = docRoot.innerHTML;
                        jQuery(".wrap").html(data);
                        //data return from server.

                    });
                }
            }
        });
        function searchTagAjax(str) {
            if (str.length == 0) {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("livesearch").innerHTML = xmlhttp.responseText;
                    document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "<?php echo URL . CONTEXT_PATH_TAG_SEARCH_AJAX; ?>" + str, true);
            xmlhttp.send();

        }

        function addInputTag() {
            var tag_name = jQuery('#form-your-profile input[name="tag_input"]').val().trim();
            if (tag_name != "") {
                var tag_add_array = tag_name.split(",");
                for (var i = 0; i < tag_add_array.length; i++) {
                    var name = tag_add_array[i];
                    if (name != undefined) {
                        name = name.trim();
                        if (name != "") {
                            addTag(name);
                        }
                    }
                }
            }
        }

        function selectTag(element) {
            var tag_name = jQuery(element).attr("tag_name");
            if (tag_name != undefined) {
                tag_name = tag_name.trim();
                if (tag_name != "") {
                    addTag(tag_name);
                }
            }
        }

        function addTag(tag_name) {
            var tag_list = jQuery('#form-your-profile input[name="tag_list"]').val();
            if (tag_list == "") {
                var tag_array = [];
            } else {
                var tag_array = tag_list.split(",");
            }

            if (tag_array.indexOf(tag_name) == -1) {
                tag_array.push(tag_name);
                document.getElementById("tagchecklist").innerHTML = document.getElementById("tagchecklist").innerHTML + "<span><a tabindex='0' class='ntdelbutton' tag_name='" + tag_name + "' onclick='removeTag(this)'>X</a>&nbsp;" + tag_name + "</span>";
                jQuery('#form-your-profile input[name="tag_list"]').val(tag_array.join(","));
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                jQuery('#form-your-profile input[name="tag_input"]').val("");
            } else {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                jQuery('#form-your-profile input[name="tag_input"]').val("");
            }
        }

        function removeTag(element) {
            var tag_name = jQuery(element).attr("tag_name");
            if (tag_name != undefined) {
                tag_name = tag_name.trim();
                if (tag_name != "") {
                    jQuery(element).parent().remove();
                    var tag_list = jQuery('#form-your-profile input[name="tag_list"]').val();
                    if (tag_list == "") {
                        var tag_array = [];
                    } else {
                        var tag_array = tag_list.split(",");
                    }

                    if (tag_array.indexOf(tag_name) != -1) {
                        tag_array.splice(tag_array.indexOf(tag_name), 1);
                        jQuery('#form-your-profile input[name="tag_list"]').val(tag_array.join(","));
                        document.getElementById("livesearch").innerHTML = "";
                        document.getElementById("livesearch").style.border = "0px solid #A5ACB2";
                    }
                }
            }
        }
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>
