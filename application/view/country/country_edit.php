<?php
if (isset($this->countryBO) && $this->countryBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo EDIT_PROFILE_OF_TITLE . " " . COUNTRY_TITLE; ?> "<strong><?php
            if (isset($this->countryBO->name)) {
                echo $this->countryBO->name;
            }

            ?></strong>"
        <a class="page-title-action" ajaxlink="<?php echo URL . CONTEXT_PATH_COUNTRY_ADD_NEW; ?>" ajaxtarget=".wrap" href="#" onclick="openAjaxLink(this)" ><?php echo ADD_NEW_TITLE; ?></a>
    </h1>

    <div id="message_notice">
        <?php $this->renderFeedbackMessages(); ?>
    </div>

    <form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_COUNTRY_EDIT_INFO; ?>">
        <input type="hidden" value="update" name="action">
        <input type="hidden" value="<?php
        if (isset($this->countryBO->term_taxonomy_id)) {
            echo htmlspecialchars($this->countryBO->term_taxonomy_id);
        }

        ?>" id="country" name="country">
        <table class="form-table">
            <tbody>
                <tr class="country-name-wrap">
                    <th>
                        <label for="name"><?php echo NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->countryBO->name)) {
                            echo htmlspecialchars($this->countryBO->name);
                        }

                        ?>" id="name" name="name">
                        <p class="description"><?php echo COUNTRY_NAME_DESC; ?></p>
                    </td>
                </tr>
                <tr class="country-slug-wrap">
                    <th>
                        <label for="slug"><?php echo SLUG_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->countryBO->slug)) {
                            echo htmlspecialchars($this->countryBO->slug);
                        }

                        ?>" id="slug" name="slug">
                        <p class="description"><?php echo COUNTRY_SLUG_DESC; ?></p>
                    </td>
                </tr>

                <tr class="country-parent-wrap"><th><label for="parent"><?php echo COUNTRY_PERENT_TITLE; ?> </label></th>
                    <td>
                        <select id="parent" name="parent" >
                            <?php
                            if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                                $this->parentList->rewind();
                                foreach ($this->parentList as $value) {
                                    if ($value->term_taxonomy_id != $this->countryBO->term_taxonomy_id &&
                                        $value->parent != $this->countryBO->term_taxonomy_id) {

                                        ?> 
                                        <option <?php if ($value->term_taxonomy_id == $this->countryBO->parent) {

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
                            <option value="0" <?php if ($this->countryBO->parent == 0 || $this->countryBO->parent == "0") { ?>
                                        selected="selected"
                                    <?php } ?> ><?php echo NONE_TITLE; ?></option>    
                        </select>
                        <p class="description"><?php echo COUNTRY_PERENT_DESC; ?></p>
                    </td>
                </tr>

                <tr class="country-description-wrap">
                    <th>
                        <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php
                        if (isset($this->countryBO->description)) {
                            echo htmlspecialchars($this->countryBO->description);
                        }

                        ?>" id="description" name="description">
                        <p class="description"><?php echo COUNTRY_DESCRIPTION_DESC; ?></p>
                    </td>
                </tr>


                <tr class="overview-wrap">
                    <th>
                        <label for="overview"><?php echo OVERVIEW_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="overview" name="overview" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->overview)) {
                                echo htmlspecialchars($this->countryBO->overview);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="history-wrap">
                    <th>
                        <label for="history"><?php echo HISTORY_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="history" name="history" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->history)) {
                                echo htmlspecialchars($this->countryBO->history);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="weather-wrap">
                    <th>
                        <label for="weather"><?php echo WEATHER_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="weather" name="weather" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->weather)) {
                                echo htmlspecialchars($this->countryBO->weather);
                            }

                            ?></textarea>
                    </td>
                </tr>
                
                

                <tr class="image_weathers-wrap">
                    <th>
                        <label for="image_weathers"><?php echo IMAGE_WEATHERS_TITLE; ?></label>
                    </th>
                    <td>
                        <input type="file" id="image_weathers" name="image_weathers[]" accept=".jpg, .png, .jpeg"  multiple  style="float: left; width: 100%; margin-bottom: 30px;">  
                        <input type="hidden" name="image_weather_delete_list">
                        <?php
                        if (isset($this->countryBO->image_weathers)) {
                            foreach ($this->countryBO->image_weathers as $image) {
                                if (isset($image->image_url)) {

                                    ?>
                                    <div data-p="144.50" style="float: left; margin-right: 10px;">
                                        <img data-u="thumb" src="<?php echo URL . $image->slider_thumb_url; ?>" />
                                        <div class="row-actions widefat" style="text-align: center;">
                                            <span class="delete">
                                                <a onclick="deleteImage(this)" image_weather_id="<?php echo $image->image_weather_id; ?>" class="submitdelete" href="#">Delete                                        </a>
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

                <tr class="passport_visa-wrap">
                    <th>
                        <label for="passport_visa"><?php echo PASSPORT_VISA_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="passport_visa" name="passport_visa" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->passport_visa)) {
                                echo htmlspecialchars($this->countryBO->passport_visa);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="currency-wrap">
                    <th>
                        <label for="currency"><?php echo CURRENCY_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="currency" name="currency" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->currency)) {
                                echo htmlspecialchars($this->countryBO->currency);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="phone_internet_service-wrap">
                    <th>
                        <label for="phone_internet_service"><?php echo PHONE_INTERNET_SERVICE_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="phone_internet_service" name="phone_internet_service" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->phone_internet_service)) {
                                echo htmlspecialchars($this->countryBO->phone_internet_service);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="transportation-wrap">
                    <th>
                        <label for="transportation"><?php echo TRANSPORTATION_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="transportation" name="transportation" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->transportation)) {
                                echo htmlspecialchars($this->countryBO->transportation);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="food_drink-wrap">
                    <th>
                        <label for="food_drink"><?php echo FOOD_DRINK_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="food_drink" name="food_drink" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->food_drink)) {
                                echo htmlspecialchars($this->countryBO->food_drink);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="public_holiday-wrap">
                    <th>
                        <label for="public_holiday"><?php echo PUBLIC_HOLIDAY_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="public_holiday" name="public_holiday" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->public_holiday)) {
                                echo htmlspecialchars($this->countryBO->public_holiday);
                            }

                            ?></textarea>
                    </td>
                </tr>

                <tr class="predeparture_check_list-wrap">
                    <th>
                        <label for="predeparture_check_list"><?php echo PREDEPARTURE_CHECK_LIST_TITLE; ?></label>
                    </th>
                    <td>
                        <textarea id="predeparture_check_list" name="predeparture_check_list" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                            if (isset($this->countryBO) && isset($this->countryBO->predeparture_check_list)) {
                                echo htmlspecialchars($this->countryBO->predeparture_check_list);
                            }

                            ?></textarea>
                    </td>
                </tr>
                <tr class="country-tags-wrap">   
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
                            if (isset($this->countryBO->tag_list) && count($this->countryBO->tag_list) > 0) {
                                $tagArray = array();
                                foreach ($this->countryBO->tag_list as $tag) {
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

        <p class="submit"><input type="submit" value="<?php echo LABEL_UPDATE_COUNTRY; ?>" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <script>

        function deleteImage(element) {

            if (confirm('<?php echo CONFIRM_DELETE_IMAGE . CONFIRM_DELETE_CANCEL_OK; ?>')) {
                var image_weather_id = jQuery(element).attr("image_weather_id");
                if (image_weather_id != undefined) {
                    image_weather_id = image_weather_id.trim();
                    if (image_weather_id != "") {
                        jQuery(element).parent().parent().parent().remove();
                        var image_weather_delete_list = jQuery('#form-your-profile input[name="image_weather_delete_list"]').val();
                        if (image_weather_delete_list == "") {
                            var image_delete_array = [];
                        } else {
                            var image_delete_array = image_weather_delete_list.split(",");
                        }

                        if (image_delete_array.indexOf(image_weather_id) == -1) {
                            image_delete_array.push(image_weather_id);
                            jQuery('#form-your-profile input[name="image_weather_delete_list"]').val(image_delete_array.join(","));
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

        function validateFormEditCountry() {
            if (jQuery('#form-your-profile input[name="name"]').val() == "") {
                noticeError("<?php echo ERROR_NAME_EMPTY; ?>");
                return false;
            }
            if (jQuery('#form-your-profile input[name="slug"]').val() == "") {
                noticeError("<?php echo ERROR_SLUG_EMPTY; ?>");
                return false;
            }
            return true;
        }
        jQuery("#form-your-profile").submit(function (e) {
            e.preventDefault();
            if (!validateFormEditCountry()) {
                return;
            }
            var name = jQuery('#form-your-profile input[name="name"]').val();
            if (confirm('<?php echo CONFIRM_EDIT_INFO_COUNTRY; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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
