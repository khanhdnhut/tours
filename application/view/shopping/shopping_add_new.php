<script src="<?php echo PUBLIC_JS; ?>includes/ckeditor/ckeditor.js"></script>
<style>
    .form-table th {
        font-weight: 100;   
    }
</style>
<h1>
    <?php echo ADD_NEW_SHOPPING_LABEL; ?>
</h1>
<div id="message_notice">
    <?php $this->renderFeedbackMessages(); ?>
</div>
<form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_SHOPPING_ADD_NEW; ?>">
    <input type="hidden" value="addNew" name="action">
    <input type="hidden" value="ajax" name="ajax">
    <table class="form-table">
        <tbody>
            <tr class="shopping-post-title-wrap">
                <th colspan="1">
                    <label for="post_title"><?php echo SHOPPING_NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input type="text" class="large-text" value="<?php
                    if (isset($this->para->post_title)) {
                        echo htmlspecialchars($this->para->post_title);
                    }

                    ?>" id="post_title" name="post_title">
                </td>
            </tr>

            <tr class="shopping-vote-times-wrap">
                <th>
                    <label for="current_rating"><?php echo CURRENT_RATING_TITLE; ?></label>
                </th>
                <td>
                    <input type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
                    if (isset($this->para->current_rating)) {
                        echo htmlspecialchars($this->para->current_rating);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="vote_times"><?php echo VOTE_TIMES_TITLE; ?></label>
                </th>
                <td>
                    <input type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                    if (isset($this->para->vote_times)) {
                        echo htmlspecialchars($this->para->vote_times);
                    }

                    ?>" style="min-width: 150px;">
                </td>


            </tr>

            <tr class="shopping-country-id-wrap">
                <th><label for="country_id"><?php echo DESTINATION_COUNTRY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td>
                    <select id="country_id" name="country_id"  style="min-width: 150px;">
                        <?php
                        if (isset($this->countryList) && is_a($this->countryList, "SplDoublyLinkedList")) {
                            $this->countryList->rewind();
                            foreach ($this->countryList as $value) {

                                ?>
                                <option value="<?php
                                if (isset($value->term_taxonomy_id)) {
                                    echo $value->term_taxonomy_id;
                                }

                                ?>" <?php
                                        if (isset($this->para->country_id) && $this->para->country_id == $value->term_taxonomy_id) {
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
                        if (isset($this->para->country_id) && $this->para->country_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->para->country_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
                <th><label for="city_id"><?php echo SHOPPING_CITY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td>
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
                                        if (isset($this->para->city_id) && $this->para->city_id == $value->term_taxonomy_id) {
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
                        if (isset($this->para->city_id) && $this->para->city_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->para->city_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
            </tr>

            <tr class="shopping-city-id-wrap">

                <th colspan="1"><label for="image"><?php echo SHOPPING_IMAGE_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_TYPE_IMAGE_REQUIRED; ?></label></th>
                <td colspan="3">                    
                    <input type="file" id="image" name="image" accept=".jpg, .png, .jpeg" required>
                    <p class="images"><?php echo SHOPPING_IMAGES_DESCRIPTION_DESC; ?></p>
                </td>
            </tr>


            <tr class="shopping-post-content-wrap">
                <th colspan="1">
                    <label for="post_content"><?php echo SHOPPING_CONTENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>                
            </tr>

            <tr class="shopping-post-content-wrap">
                <td colspan="4">
                    <textarea id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->para) && isset($this->para->post_content)) {
                            echo htmlspecialchars($this->para->post_content);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="shopping-tags-wrap">   
                <th colspan="1">
                    <label for="tags"><?php echo TAGS_TITLE; ?></label>
                </th>
                <td colspan="3">
                    <input style="min-width: 200px;" type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="tag_input" id="tags" onkeyup="searchTagAjax(this.value)">
                    <ul id="livesearch" class="ac_results" style="display: block;">
                    </ul>                    
                    <input type="button" value="Add" class="button tagadd" onclick="addInputTag()">
                    <p id="new-tag-post_tag-desc" class="howto">Separate tags with commas</p>
                    <div id="tagchecklist" class="tagchecklist"></div>
                    <input type="hidden" name="tag_list">
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit"><input type="submit" value="<?php echo ADD_NEW_SHOPPING_LABEL; ?>" class="button button-primary" id="submit" name="submit"></p>
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
        "   <button class='notice-dismiss' type='button' onclick='hideMessageError()'>" +
                "       <span class='screen-reader-text'>Dismiss this notice.</span>" +
                "   </button>" +
                "</div>";
        window.scrollTo(0, 0);
    }
    function validateFormAddNewShopping() {
        if (jQuery('#form-your-profile input[name="post_title"]').val() == "") {
            noticeError("<?php echo ERROR_TITLE_EMPTY; ?>");
            return false;
        }
        if (jQuery('#form-your-profile select[name="country_id"]').val() == "0") {
            noticeError("<?php echo ERROR_COUNTRY_EMPTY; ?>");
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
        if (jQuery("#image").val() == "") {
            noticeError("<?php echo ERROR_SHOPPING_IMAGE_EMPTY; ?>");
            return false;
        } else {
            var validExts = new Array(".jpg", ".png", ".jpeg");
            var fileExt = jQuery('#image').val();
            fileExt = fileExt.substring(fileExt.lastIndexOf('.')).toLowerCase();
            if (validExts.indexOf(fileExt) < 0) {
                noticeError("<?php echo ERROR_SHOPPING_IMAGE_INVALID; ?>");
                return false;
            }
        }

        return true;
    }

    jQuery('#form-your-profile select[name="country_id"]').change(function () {
        var country = jQuery('#form-your-profile select[name="country_id"]').val();
        if (country == "0") {
            jQuery('#form-your-profile select[name="city_id"]').html("<option selected='selected' value='0'>None</option>");
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
                jQuery('#form-your-profile select[name="city_id"]').html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "<?php echo URL . CONTEXT_PATH_CITY_BY_COUNTRY_AJAX; ?>" + country, true);
        xmlhttp.send();
    })

    jQuery("#form-your-profile").submit(function (e) {
        e.preventDefault();
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        if (!validateFormAddNewShopping()) {
            return;
        }
        var name = jQuery('#form-your-profile input[name="post_title"]').val();
        if (confirm('<?php echo CONFIRM_ADD_NEW_SHOPPING; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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
    
    CKEDITOR.replace( 'post_content' );

</script>