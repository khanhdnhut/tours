<?php
if (isset($this->styleBO) && $this->styleBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . STYLE_TITLE; ?> "<strong><?php
            if (isset($this->styleBO->name)) {
                echo $this->styleBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" style="<?php echo $this->styleBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->styleBO->name); ?>" onclick="getEditStylePage(this)"><?php echo DASHBOARD_TOURS_EDIT_STYLE_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="style-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->styleBO->name)) {
                        echo htmlspecialchars($this->styleBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="style-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->styleBO->slug)) {
                        echo htmlspecialchars($this->styleBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="style-parent-wrap">
                <th>
                    <label for="parent"><?php echo PARENT_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->styleBO->term_taxonomy_id &&
                                    $value->parent != $this->styleBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->styleBO->parent) {

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
                        <option value="0" <?php if ($this->styleBO->parent == 0 || $this->styleBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                          
                    </select>
                </td>
            </tr>

            <tr class="style-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->styleBO->description)) {
                        echo htmlspecialchars($this->styleBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>


        </tbody>
    </table>

    <script>
        function getEditStylePage(element) {
            var style = jQuery(element).attr("style");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_STYLE_EDIT_INFO; ?>" + style + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_STYLE_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    style: style
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
