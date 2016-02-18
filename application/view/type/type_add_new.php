<style>
    .form-table th {
        font-weight: 100;   
    }
</style>
<h1>
    <?php echo ADD_NEW_TYPE_LABEL; ?>
</h1>
<div id="message_notice">
    <?php $this->renderFeedbackMessages(); ?>
</div>
<form id="form-your-profile" novalidate="novalidate"  method="POST" enctype="multipart/form-data" action="<?php echo URL . CONTEXT_PATH_TYPE_ADD_NEW; ?>">
    <input type="hidden" value="addNew" name="action">
    <input type="hidden" value="ajax" name="ajax">
    <table class="form-table">
        <tbody>
            <tr class="type-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para->name)) {
                        echo htmlspecialchars($this->para->name);
                    }

                    ?>" id="name" name="name">
                    <p class="description"><?php echo TYPE_NAME_DESC; ?></p>
                </td>
            </tr>

            <tr class="type-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para->slug)) {
                        echo htmlspecialchars($this->para->slug);
                    }

                    ?>" id="slug" name="slug">
                    <p class="description"><?php echo TYPE_SLUG_DESC; ?></p>
                </td>
            </tr>

            <tr class="type-parent-wrap"><th><label for="parent"><?php echo TYPE_PERENT_TITLE; ?></label></th>
                <td>
                    <select id="parent" name="parent" >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {

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
                    <span class="description"><?php echo TYPE_PERENT_DESC; ?></span>
                </td>
            </tr>

            <tr class="type-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php
                    if (isset($this->para) && isset($this->para->description)) {
                        echo htmlspecialchars($this->para->description);
                    }

                    ?>" id="description" name="description">
                    <p class="description"><?php echo TYPE_DESCRIPTION_DESC; ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit"><input type="submit" value="<?php echo ADD_NEW_TYPE_LABEL; ?>" class="button button-primary" id="submit" name="submit"></p>
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

    jQuery('#form-your-profile input[name="name"]').change(function () {
        jQuery('#form-your-profile input[name="slug"]').val(createSlug(jQuery('#form-your-profile input[name="name"]').val()));
    })

    function validateFormAddNewType() {
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

        if (!validateFormAddNewType()) {
            return;
        }
        var name = jQuery('#form-your-profile input[name="name"]').val();
        if (confirm('<?php echo CONFIRM_ADD_NEW_TYPE; ?>' + name + '<?php echo CONFIRM_EDIT_INFO_CANCEL_OK; ?>')) {
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
