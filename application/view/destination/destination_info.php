<?php
if (isset($this->destinationBO) && $this->destinationBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . DESTINATION_TITLE; ?> "<strong><?php
            if (isset($this->destinationBO->name)) {
                echo $this->destinationBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" destination="<?php echo $this->destinationBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->destinationBO->name); ?>" onclick="getEditDestinationPage(this)"><?php echo DASHBOARD_TOURS_EDIT_DESTINATION_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="destination-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->destinationBO->name)) {
                        echo htmlspecialchars($this->destinationBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="destination-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->destinationBO->slug)) {
                        echo htmlspecialchars($this->destinationBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="destination-parent-wrap">
                <th>
                    <label for="parent"><?php echo DESTINATION_COUNTRY_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
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
                </td>
            </tr>

            <tr class="destination-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->destinationBO->description)) {
                        echo htmlspecialchars($this->destinationBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>


        </tbody>
    </table>

    <script>
        function getEditDestinationPage(element) {
            var destination = jQuery(element).attr("destination");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_DESTINATION_EDIT_INFO; ?>" + destination + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_DESTINATION_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    destination: destination
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
