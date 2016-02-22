<?php
if (isset($this->destinationBO) && $this->destinationBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo EDIT_PROFILE_OF_TITLE . " " . DESTINATION_TITLE; ?> "<strong><?php
            if (isset($this->destinationBO->name)) {
                echo $this->destinationBO->name;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . CONTEXT_PATH_DESTINATION_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_DESTINATION_EDIT_INFO; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->destinationBO->term_taxonomy_id)) {
            echo htmlspecialchars($this->destinationBO->term_taxonomy_id);
        }

        ?>" id="destination" name="destination">
        <table class="form-table">
            <tbody>
                <tr class="destination-name-wrap">
                    <th>
                        <label for="name"><?php echo NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->destinationBO->name)) {
                            echo htmlspecialchars($this->destinationBO->name);
                        }

                        ?>" id="name" name="name">
                        <p class="description"><?php echo DESTINATION_NAME_DESC; ?></p>
                    </td>
                </tr>
                <tr class="destination-slug-wrap">
                    <th>
                        <label for="slug"><?php echo SLUG_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->destinationBO->slug)) {
                            echo htmlspecialchars($this->destinationBO->slug);
                        }

                        ?>" id="slug" name="slug">
                        <p class="description"><?php echo DESTINATION_SLUG_DESC; ?></p>
                    </td>
                </tr>

                <tr class="destination-country-wrap"><th><label for="country"><?php echo DESTINATION_PARENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label></th>
                    <td>
                        <select id="country" name="country" >
                            <?php
                            if (isset($this->countryList) && is_a($this->countryList, "SplDoublyLinkedList")) {
                                $this->countryList->rewind();
                                foreach ($this->countryList as $value) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->destinationBO->country) {

                                        ?>
                                            selected="selected"
                                        <?php }

                                        ?> value="<?php
                                        if (isset($value->term_taxonomy_id)) {
                                            echo $value->term_taxonomy_id;
                                        }

                                        ?>"><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                    <?php
                                }
                            }

                            ?>
                            <option value="0" <?php if ($this->destinationBO->country == 0 || $this->destinationBO->country == "0") { ?>
                                        selected="selected"
                                    <?php } ?> ><?php echo NONE_TITLE; ?></option>    
                        </select>
                        <p class="description"><?php echo DESTINATION_PARENT_DESC; ?></p>
                    </td>
                </tr>
                <tr class="destination-city-wrap"><th><label for="city"><?php echo DESTINATION_CITY_TITLE; ?> </label></th>
                    <td>
                        <select id="city" name="city" >
                            <?php
                            if (isset($this->cityList) && is_a($this->cityList, "SplDoublyLinkedList")) {
                                $this->cityList->rewind();
                                foreach ($this->cityList as $value) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->destinationBO->city) {

                                        ?>
                                            selected="selected"
                                        <?php }

                                        ?> value="<?php
                                        if (isset($value->term_taxonomy_id)) {
                                            echo $value->term_taxonomy_id;
                                        }

                                        ?>"><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                    <?php
                                }
                            }

                            ?>
                            <option value="0" <?php if ($this->destinationBO->city == 0 || $this->destinationBO->city == "0") { ?>
                                        selected="selected"
                                    <?php } ?> ><?php echo NONE_TITLE; ?></option>    
                        </select>
                        <p class="description"><?php echo DESTINATION_PARENT_DESC; ?></p>
                    </td>
                </tr>

                <tr class="destination-parent-wrap"><th><label for="parent"><?php echo PARENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label></th>
                    <td>
                        <select id="parent" name="parent" >
                            <?php
                            if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                                $this->parentList->rewind();
                                foreach ($this->parentList as $value) {

                                    if ($value->term_taxonomy_id != $this->destinationBO->term_taxonomy_id &&
                                        $value->parent != $this->destinationBO->term_taxonomy_id) {

                                        ?> 
                                        <option <?php if ($value->term_taxonomy_id == $this->destinationBO->parent) {

                            ?>
                                                selected="selected"
                                            <?php }

                                            ?> value="<?php
                                            if (isset($value->term_taxonomy_id)) {
                                                echo $value->term_taxonomy_id;
                                            }

                                            ?>"><?php
                                                if (isset($value->name)) {
                                                    echo $value->name;
                                                }

                                                ?></option>
                                        <?php
                                    }
                                }
                            }

                            ?>
                            <option value="0" <?php if ($this->destinationBO->parent == 0 || $this->destinationBO->parent == "0") { ?>
                                        selected="selected"
    <?php } ?> ><?php echo NONE_TITLE; ?></option>    
                        </select>
                        <p class="description"><?php echo DESTINATION_PARENT_DESC; ?></p>
                    </td>
                </tr>
                <tr class="current_rating-wrap">
                    <th>
                        <label for="current_rating"><?php echo CURRENT_RATING_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
                        if (isset($this->destinationBO->current_rating)) {
                            echo htmlspecialchars($this->destinationBO->current_rating);
                        }

                        ?>" style="min-width: 150px;">
                    </td>
                </tr>
                <tr class="vote_times-wrap">
                    <th>
                        <label for="vote_times"><?php echo VOTE_TIMES_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                        if (isset($this->destinationBO->vote_times)) {
                            echo htmlspecialchars($this->destinationBO->vote_times);
                        }

                        ?>" style="min-width: 150px;">
                    </td>

                </tr>
                <tr class="destination-description-wrap">
                    <th>
                        <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->destinationBO->description)) {
                            echo htmlspecialchars($this->destinationBO->description);
                        }

                        ?>" id="description" name="description">
                        <p class="description"><?php echo DESTINATION_DESCRIPTION_DESC; ?></p>
                    </td>
                </tr>


                <tr class="post_content_1-wrap">
                    <th>
                        <label for="post_content_1"><?php echo POST_CONTENT_1_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="post_content_1" name="post_content_1" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->destinationBO) && isset($this->destinationBO->post_content_1)) {
                                echo htmlspecialchars($this->destinationBO->post_content_1);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="post_content_2-wrap">
                    <th>
                        <label for="post_content_2"><?php echo POST_CONTENT_2_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="post_content_2" name="post_content_2" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->destinationBO) && isset($this->destinationBO->post_content_2)) {
                                echo htmlspecialchars($this->destinationBO->post_content_2);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="images-wrap">
                    <th>
                        <label for="images"><?php echo IMAGES_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="file" id="images" name="images[]" accept=".jpg, .png, .jpeg"  multiple>
                        <p class="images"  style="float: left; width: 100%; margin-bottom: 30px;"><?php echo DESTINATION_IMAGES_DESCRIPTION_DESC; ?></p>
                        <input type="hidden" name="image_delete_list">
                        <?php
                        if (isset($this->destinationBO->images)) {
                            foreach ($this->destinationBO->images as $image) {
                                if (isset($image->image_url)) {

                                    ?>
                                    <div data-p="144.50" style="float: left; margin-right: 10px;">
                                        <img data-u="thumb" src="<?php echo URL . $image->slider_thumb_url; ?>" />
                                        <div class="row-actions widefat" style="text-align: center;">
                                            <span class="delete">
                                                <a onclick="deleteImage(this)" image_id="<?php echo $image->image_id; ?>" class="submitdelete" href="#">Delete                                        </a>
                                            </span>
                                        </div>

                                    </div>

                                    <?php
                                }
                            }
                        }

                        ?>                 
                    </td>
                </tr>

                <tr class="destination-tags-wrap">   
                    <th colspan="1">
                        <label for="tags"><?php echo TAGS_TITLE; ?></label>
                    </th>
                    <td colspan="3">
                        <input style="min-width: 200px;" type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="tag_input" id="tags" onkeyup="searchTagAjax(this.value)">
                        <ul id="livesearch" class="ac_results" style="display: block;">
                        </ul>                    
                        <input type="button" value="Add" class="button tagadd" onclick="addInputTag()">
                        <p id="new-tag-post_tag-desc" class="howto">Separate tags with commas</p>
                        <div id="tagchecklist" class="tagchecklist">
                            <?php
                            if (isset($this->destinationBO->tag_list) && count($this->destinationBO->tag_list) > 0) {
                                $tagArray = array();
                                foreach ($this->destinationBO->tag_list as $tag) {
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

        <p class="submit"><input type="submit" value="<?php echo LABEL_UPDATE_DESTINATION; ?>" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <script>

        function deleteImage(element) {

            if (confirm('<?php echo CONFIRM_DELETE_IMAGE . CONFIRM_DELETE_CANCEL_OK; ?>')) {
                var image_id = jQuery(element).attr("image_id");
                if (image_id != undefined) {
                    image_id = image_id.trim();
                    if (image_id != "") {
                        jQuery(element).parent().parent().parent().remove();
                        var image_delete_list = jQuery('#form-your-profile input[name="image_delete_list"]').val();
                        if (image_delete_list == "") {
                            var image_delete_array = [];
                        } else {
                            var image_delete_array = image_delete_list.split(",");
                        }

                        if (image_delete_array.indexOf(image_id) == -1) {
                            image_delete_array.push(image_id);
                            jQuery('#form-your-profile input[name="image_delete_list"]').val(image_delete_array.join(","));
                        }
                    }
                }
            }
        }

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

        jQuery('#form-your-profile input[name="name"]').change(function () {
            jQuery('#form-your-profile input[name="slug"]').val(createSlug(jQuery('#form-your-profile input[name="name"]').val()));
        })

        function validateFormEditDestination() {
            if (jQuery('#form-your-profile input[name="name"]').val() == "") {
                noticeError("<?php echo ERROR_NAME_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile input[name="slug"]').val() == "") {
                noticeError("<?php echo ERROR_SLUG_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile select[name="country"]').val() == "0") {
                noticeError("<?php echo ERROR_COUNTRY_EMPTY; ?>");
                return false;
            }
            return true;
        }
        jQuery("#form-your-profile").submit(function (e) {
            e.preventDefault();
            if (!validateFormEditDestination()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="name"]').val();
            if (confirm('<?php echo CONFIRM_EDIT_INFO_DESTINATION; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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

        jQuery('#form-your-profile select[name="country"]').change(function () {
            var country = jQuery('#form-your-profile select[name="country"]').val();
            if (country == "0") {
                jQuery('#form-your-profile select[name="city"]').html("<option selected='selected' value='0'>None</option>");
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
                    jQuery('#form-your-profile select[name="city"]').html(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", "<?php echo URL . CONTEXT_PATH_CITY_BY_COUNTRY_AJAX; ?>" + country, true);
            xmlhttp.send();
        })

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
