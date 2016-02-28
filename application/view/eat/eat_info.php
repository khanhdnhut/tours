<?php
if (isset($this->eatBO) && $this->eatBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . EAT_TITLE; ?> "<strong><?php
            if (isset($this->eatBO->name)) {
                echo $this->eatBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" eat="<?php echo $this->eatBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->eatBO->name); ?>" onclick="getEditEatPage(this)"><?php echo DASHBOARD_TOURS_EDIT_EAT_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="eat-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->eatBO->name)) {
                        echo htmlspecialchars($this->eatBO->name);
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
                    if (isset($this->eatBO->current_rating)) {
                        echo htmlspecialchars($this->eatBO->current_rating);
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
                    if (isset($this->eatBO->vote_times)) {
                        echo htmlspecialchars($this->eatBO->vote_times);
                    }

                    ?>" style="min-width: 150px;">
                </td>
            </tr>
            <tr class="eat-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->eatBO->slug)) {
                        echo htmlspecialchars($this->eatBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="eat-country-wrap">
                <th>
                    <label for="country"><?php echo EAT_COUNTRY_TITLE; ?></label>
                </th>
                <td>
                    <select id="country" name="country" disabled="disabled"  >
                        <?php
                        if (isset($this->countryList) && is_a($this->countryList, "SplDoublyLinkedList")) {
                            $this->countryList->rewind();
                            foreach ($this->countryList as $value) {

                                ?> 
                                <option <?php if ($value->term_taxonomy_id == $this->eatBO->country) {

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
                        <option value="0" <?php if ($this->eatBO->country == 0 || $this->eatBO->country == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            <tr class="eat-city-wrap">
                <th>
                    <label for="city"><?php echo EAT_CITY_TITLE; ?></label>
                </th>
                <td>
                    <select id="city" name="city" disabled="disabled"  >
                        <?php
                        if (isset($this->cityList) && is_a($this->cityList, "SplDoublyLinkedList")) {
                            $this->cityList->rewind();
                            foreach ($this->cityList as $value) {

                                ?> 
                                <option <?php if ($value->term_taxonomy_id == $this->eatBO->city) {

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
                        <option value="0" <?php if ($this->eatBO->city == 0 || $this->eatBO->city == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            
            <tr class="eat-destination-wrap">
                <th>
                    <label for="destination"><?php echo EAT_DESTINATION_TITLE; ?></label>
                </th>
                <td>
                    <select id="destination" name="destination" disabled="disabled"  >
                        <?php
                        if (isset($this->destinationList) && is_a($this->destinationList, "SplDoublyLinkedList")) {
                            $this->destinationList->rewind();
                            foreach ($this->destinationList as $value) {

                                ?> 
                                <option <?php if ($value->term_taxonomy_id == $this->eatBO->destination) {

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
                        <option value="0" <?php if ($this->eatBO->destination == 0 || $this->eatBO->destination == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            
            <tr class="eat-parent-wrap">
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
                                <option <?php if ($value->term_taxonomy_id == $this->eatBO->parent) {

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
                        <option value="0" <?php if ($this->eatBO->parent == 0 || $this->eatBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            <tr class="eat-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->eatBO->description)) {
                        echo htmlspecialchars($this->eatBO->description);
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
                        if (isset($this->eatBO) && isset($this->eatBO->post_content_1)) {
                            echo htmlspecialchars($this->eatBO->post_content_1);
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
                        if (isset($this->eatBO) && isset($this->eatBO->post_content_2)) {
                            echo htmlspecialchars($this->eatBO->post_content_2);
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
                    if (isset($this->eatBO->images)) {
                        foreach ($this->eatBO->images as $image) {
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

        <tr class="eat-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->eatBO->tag_list) && count($this->eatBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->eatBO->tag_list as $tag) {
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
        function getEditEatPage(element) {
            var eat = jQuery(element).attr("eat");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_EAT_EDIT_INFO; ?>" + eat + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_EAT_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    eat: eat
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
