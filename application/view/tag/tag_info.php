<?php
if (isset($this->tagBO) && $this->tagBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . TAG_TITLE; ?> "<strong><?php
            if (isset($this->tagBO->name)) {
                echo $this->tagBO->name;
            }
            ?></strong>"
        <a class="page-title-action" href="#" tag="<?php echo $this->tagBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->tagBO->name); ?>" onclick="getEditCountryPage(this)"><?php echo DASHBOARD_TOURS_EDIT_TAG_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="tag-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->name)) {
                        echo htmlspecialchars($this->tagBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="tag-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->slug)) {
                        echo htmlspecialchars($this->tagBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>
            
            <tr class="tag-parent-wrap">
                <th>
                    <label for="parent"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <option value="-1" selected="selected"><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            
            <tr class="tag-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->tagBO->description)) {
                        echo htmlspecialchars($this->tagBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>

            
        </tbody>
    </table>

    <script>
        function getEditCountryPage(element) {
            var tag = jQuery(element).attr("tag");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_TAG_EDIT_INFO; ?>" + tag + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_TAG_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    tag: tag
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
