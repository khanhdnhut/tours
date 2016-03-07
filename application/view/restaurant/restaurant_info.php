<?php
if (isset($this->restaurantBO) && $this->restaurantBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . RESTAURANT_TITLE; ?> "<strong><?php
            if (isset($this->restaurantBO->post_title)) {
                echo $this->restaurantBO->post_title;
            }

            ?></strong>"
        <a class="page-title-action" href="#" restaurant="<?php echo $this->restaurantBO->ID; ?>" name="<?php echo htmlspecialchars($this->restaurantBO->post_title); ?>" onclick="getEditRestaurantPage(this)"><?php echo DASHBOARD_TOURS_EDIT_RESTAURANT_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="restaurant-post-title-wrap">
                <th colspan="1">
                    <label for="post_title"><?php echo RESTAURANT_NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input  disabled="disabled" type="text" class="large-text" value="<?php
                    if (isset($this->restaurantBO->post_title)) {
                        echo htmlspecialchars($this->restaurantBO->post_title);
                    }

                    ?>" id="post_title" name="post_title">
                </td>
            </tr>

            <tr class="restaurant-vote-times-wrap">
                <th>
                    <label for="current_rating"><?php echo CURRENT_RATING_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
                    if (isset($this->restaurantBO->current_rating)) {
                        echo htmlspecialchars($this->restaurantBO->current_rating);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="vote_times"><?php echo VOTE_TIMES_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                    if (isset($this->restaurantBO->vote_times)) {
                        echo htmlspecialchars($this->restaurantBO->vote_times);
                    }

                    ?>" style="min-width: 150px;">
                </td>


            </tr>

            <tr class="restaurant-country-id-wrap">
                <th colspan="1"><label for="country_id"><?php echo DESTINATION_COUNTRY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td colspan="3">
                    <select disabled="disabled" id="country_id" name="country_id"  style="min-width: 150px;">
                        <?php
                        if (isset($this->countryList) && is_a($this->countryList, "SplDoublyLinkedList")) {
                            $this->countryList->rewind();
                            foreach ($this->countryList as $value) {

                                ?>
                                <option value="<?php
                                if (isset($value->term_taxonomy_id)) {
                                    echo $value->term_taxonomy_id;
                                }

                                ?>" <?php
                                        if (isset($this->restaurantBO->country_id) && $this->restaurantBO->country_id == $value->term_taxonomy_id) {
                                            echo "selected='selected'";
                                        }

                                        ?>><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                <?php
                            }
                        }

                        ?>
                        <option value="0" <?php
                        if (isset($this->restaurantBO->country_id) && $this->restaurantBO->country_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->restaurantBO->country_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
            </tr>



            <tr class="restaurant-city-id-wrap">
                <th colspan="1"><label for="city_id"><?php echo RESTAURANT_CITY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td colspan="3">
                    <select disabled="disabled" id="city_id" name="city_id"  style="min-width: 150px;">
                        <?php
                        if (isset($this->cityList) && is_a($this->cityList, "SplDoublyLinkedList")) {
                            $this->cityList->rewind();
                            foreach ($this->cityList as $value) {

                                ?>
                                <option value="<?php
                                if (isset($value->term_taxonomy_id)) {
                                    echo $value->term_taxonomy_id;
                                }

                                ?>" <?php
                                        if (isset($this->restaurantBO->city_id) && $this->restaurantBO->city_id == $value->term_taxonomy_id) {
                                            echo "selected='selected'";
                                        }

                                        ?>><?php
                                            if (isset($value->name)) {
                                                echo $value->name;
                                            }

                                            ?></option>
                                <?php
                            }
                        }

                        ?>
                        <option value="0" <?php
                        if (isset($this->restaurantBO->city_id) && $this->restaurantBO->city_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->restaurantBO->city_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
            </tr>


            <tr class="restaurant-image-wrap">
                <th colspan="1">
                    <label for="image"><?php echo RESTAURANT_IMAGE_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <img width="96" height="96" class="avatar avatar-96 photo" srcset="<?php
                    if (isset($this->restaurantBO->image_url)) {
                        echo URL . htmlspecialchars($this->restaurantBO->image_url);
                    } else {
                        echo URL . AVATAR_DEFAULT;
                    }

                    ?>" src="<?php
                         if (isset($this->restaurantBO->image_url)) {
                             echo URL . htmlspecialchars($this->restaurantBO->image_url);
                         } else {
                             echo URL . AVATAR_DEFAULT;
                         }

                         ?>" alt="">
                </td>
            </tr>
            <tr class="restaurant-post-content-wrap">
                <th colspan="1">
                    <label for="post_content"><?php echo RESTAURANT_CONTENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <textarea disabled="disabled" id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->restaurantBO) && isset($this->restaurantBO->post_content)) {
                            echo htmlspecialchars($this->restaurantBO->post_content);
                        }

                        ?></textarea>
                </td>
            </tr>

        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="restaurant-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->restaurantBO->tag_list) && count($this->restaurantBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->restaurantBO->tag_list as $tag) {
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
        function getEditRestaurantPage(element) {
            var restaurant = jQuery(element).attr("restaurant");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_RESTAURANT_EDIT_INFO; ?>" + restaurant + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_RESTAURANT_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    restaurant: restaurant
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
