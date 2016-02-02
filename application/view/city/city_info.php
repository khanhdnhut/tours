<?php
if (isset($this->cityBO) && $this->cityBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . CITY_TITLE; ?> "<strong><?php
            if (isset($this->cityBO->name)) {
                echo $this->cityBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" city="<?php echo $this->cityBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->cityBO->name); ?>" onclick="getEditCityPage(this)"><?php echo DASHBOARD_TOURS_EDIT_CITY_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="city-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->cityBO->name)) {
                        echo htmlspecialchars($this->cityBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="city-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->cityBO->slug)) {
                        echo htmlspecialchars($this->cityBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="city-parent-wrap">
                <th>
                    <label for="parent"><?php echo PARENT_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->cityBO->term_taxonomy_id &&
                                    $value->parent != $this->cityBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->cityBO->parent) {

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
                        <option value="0" <?php if ($this->cityBO->parent == 0 || $this->cityBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                              
                    </select>
                </td>
            </tr>

            <tr class="city-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->cityBO->description)) {
                        echo htmlspecialchars($this->cityBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>


        </tbody>
    </table>

    <script>
        function getEditCityPage(element) {
            var city = jQuery(element).attr("city");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_CITY_EDIT_INFO; ?>" + city + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_CITY_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    city: city
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
