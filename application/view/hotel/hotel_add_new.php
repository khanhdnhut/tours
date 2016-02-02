<style>
    .form-table th {
        font-weight: 100;   
    }
</style>
<h1>
    <?php echo ADD_NEW_HOTEL_LABEL; ?>
</h1>
<div id="message_notice">
    <?php $this->renderFeedbackMessages(); ?>
</div>
<form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_HOTEL_ADD_NEW; ?>">
    <input type="hidden" value="addNew" name="action">
    <input type="hidden" value="ajax" name="ajax">
    <table class="form-table">
        <tbody>
            <tr class="hotel-post-title-wrap">
                <th colspan="1">
                    <label for="post_title"><?php echo HOTEL_NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input type="text" class="large-text" value="<?php
                    if (isset($this->para->post_title)) {
                        echo htmlspecialchars($this->para->post_title);
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
                    if (isset($this->para->address)) {
                        echo htmlspecialchars($this->para->address);
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
                    if (isset($this->para->number_of_rooms)) {
                        echo htmlspecialchars($this->para->number_of_rooms);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="star"><?php echo HOTEL_STAR_TITLE; ?></label>
                </th>
                <td>
                    <input type="number" step="1" min="0" max="5" class="screen-per-page" name="star" id="star" maxlength="3" value="<?php
                    if (isset($this->para->star)) {
                        echo htmlspecialchars($this->para->star);
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
                    if (isset($this->para->current_rating)) {
                        echo htmlspecialchars($this->para->current_rating);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="vote_times"><?php echo HOTEL_VOTE_TIMES_TITLE; ?></label>
                </th>
                <td>
                    <input type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                    if (isset($this->para->vote_times)) {
                        echo htmlspecialchars($this->para->vote_times);
                    }

                    ?>" style="min-width: 150px;">
                </td>


            </tr>

            <tr class="hotel-city-wrap">
                <th colspan="1"><label for="city"><?php echo HOTEL_CITY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td colspan="3">
                    <select id="city" name="city"  style="min-width: 150px;">
                        <?php
                        if (isset($this->cityList) && is_a($this->cityList, "SplDoublyLinkedList")) {
                            $this->cityList->rewind();
                            foreach ($this->cityList as $value) {

                                ?>
                                <option value="<?php
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
                        <option value="0" selected="selected"><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
            </tr>

            <tr class="hotel-post-content-wrap">
                <th colspan="1">
                    <label for="post_content"><?php echo HOTEL_CONTENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <textarea id="post_content" name="post_content" cols="40" autocomplete="off" style="" class="wp-editor-area" aria-hidden="true">
                        <?php
                        if (isset($this->para) && isset($this->para->post_content)) {
                            echo $this->para->post_content;
                        }

                        ?>
                    </textarea>
                </td>
            </tr>

            <tr class="hotel-tags-wrap">   
                <th colspan="1">
                    <label for="tags"><?php echo HOTEL_TAGS_TITLE; ?></label>
                </th>
                <td colspan="3">
                    <input type="text" value="<?php
                    if (isset($this->para->tags)) {
                        echo htmlspecialchars($this->para->tags);
                    }

                    ?>" autocomplete="off" size="16" class="newtag form-input-tip" name="tags" id="tags">                    
                    <input type="button" value="Add" class="button tagadd">
                    <div class="tagchecklist"></div>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="submit" class="button" value="Save Draft" id="save-post" name="save">
    <a id="post-preview" target="wp-preview-15" href="" class="preview button">Preview</a>
    <input type="submit" value="Publish" class="button button-primary button-large" id="publish" name="publish">
    <p class="submit"><input type="submit" value="<?php echo ADD_NEW_HOTEL_LABEL; ?>" class="button button-primary" id="submit" name="submit"></p>
</form>
<script src="<?php echo PUBLIC_JS; ?>includes/tinymce/tinymce.min.js?ver=4.4" type="text/javascript"></script>
<script>
    window.scrollTo(0, 0);
    tinymce.init({
        selector: 'textarea',
        height: 500,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ],
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });


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

    function validateFormAddNewHotel() {
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
        if (!validateFormAddNewHotel()) {
            return;
        }
        var name = jQuery('#form-your-profile input[name="name"]').val();
        if (confirm('<?php echo CONFIRM_ADD_NEW_HOTEL; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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

</script>
