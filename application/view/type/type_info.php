<?php
if (isset($this->typeBO) && $this->typeBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . TYPE_TITLE; ?> "<strong><?php
            if (isset($this->typeBO->name)) {
                echo $this->typeBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" type="<?php echo $this->typeBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->typeBO->name); ?>" onclick="getEditTypePage(this)"><?php echo DASHBOARD_TOURS_EDIT_TYPE_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="type-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->typeBO->name)) {
                        echo htmlspecialchars($this->typeBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="type-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->typeBO->slug)) {
                        echo htmlspecialchars($this->typeBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="type-parent-wrap">
                <th>
                    <label for="parent"><?php echo PARENT_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->typeBO->term_taxonomy_id &&
                                    $value->parent != $this->typeBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->typeBO->parent) {

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
                        <option value="0" <?php if ($this->typeBO->parent == 0 || $this->typeBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                           
                    </select>
                </td>
            </tr>

            <tr class="type-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->typeBO->description)) {
                        echo htmlspecialchars($this->typeBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>


        </tbody>
    </table>

    <script>
        function getEditTypePage(element) {
            var type = jQuery(element).attr("type");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_TYPE_EDIT_INFO; ?>" + type + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_TYPE_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    type: type
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
