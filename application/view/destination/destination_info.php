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
            <tr class="current_rating-wrap">
                <th>
                    <label for="current_rating"><?php echo CURRENT_RATING_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
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
                    <input disabled="disabled" type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                    if (isset($this->destinationBO->vote_times)) {
                        echo htmlspecialchars($this->destinationBO->vote_times);
                    }

                    ?>" style="min-width: 150px;">
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

            <tr class="destination-country-wrap">
                <th>
                    <label for="country"><?php echo DESTINATION_COUNTRY_TITLE; ?></label>
                </th>
                <td>
                    <select id="country" name="country" disabled="disabled"  >
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
                </td>
            </tr>
            <tr class="destination-city-wrap">
                <th>
                    <label for="city"><?php echo DESTINATION_CITY_TITLE; ?></label>
                </th>
                <td>
                    <select id="city" name="city" disabled="disabled"  >
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
                </td>
            </tr>
            
            <tr class="destination-parent-wrap">
                <th>
                    <label for="parent"><?php echo PARENT_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {

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

            <tr class="post_content_1-wrap">
                <th>
                    <label for="post_content_1"><?php echo POST_CONTENT_1_TITLE; ?></label>
                </th>
                <td>
                    <textarea  disabled="disabled" id="post_content_1" name="post_content_1" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
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
                    <textarea disabled="disabled" id="post_content_2" name="post_content_2" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
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
                    <?php
                    if (isset($this->destinationBO->images)) {
                        foreach ($this->destinationBO->images as $image) {
                            if (isset($image->image_url)) {

                                ?>
                                <div data-p="144.50" style="float: left; margin-right: 10px;">
                                    <img data-u="thumb" src="<?php echo URL . $image->slider_thumb_url; ?>" />
                                </div>

                                <?php
                            }
                        }
                    }

                    ?>                 
                </td>
            </tr>
        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="destination-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->destinationBO->tag_list) && count($this->destinationBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->destinationBO->tag_list as $tag) {
                                $tagArray[] = $tag->name;

                                ?>
                                <li><a class="tag" href="<?php echo URL . CONTEXT_PATH_TAG_INFO . $tag->term_taxonomy_id . "/" . $tag->slug; ?>/" title=""><span class="arrow2"></span><?php echo $tag->name; ?></a></li>
                                        <?php
                                    }

                                    ?>
                        </ul>
                        <?php
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
