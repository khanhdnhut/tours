<?php
if (isset($this->hotelBO) && $this->hotelBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . HOTEL_TITLE; ?> "<strong><?php
            if (isset($this->hotelBO->post_title)) {
                echo $this->hotelBO->post_title;
            }

            ?></strong>"
        <a class="page-title-action" href="#" hotel="<?php echo $this->hotelBO->ID; ?>" name="<?php echo htmlspecialchars($this->hotelBO->post_title); ?>" onclick="getEditHotelPage(this)"><?php echo DASHBOARD_TOURS_EDIT_HOTEL_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="hotel-post-title-wrap">
                <th colspan="1">
                    <label for="post_title"><?php echo HOTEL_NAME_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input  disabled="disabled" type="text" class="large-text" value="<?php
                    if (isset($this->hotelBO->post_title)) {
                        echo htmlspecialchars($this->hotelBO->post_title);
                    }

                    ?>" id="post_title" name="post_title">
                </td>
            </tr>

            <tr class="hotel-address-wrap">
                <th colspan="1">
                    <label for="address"><?php echo HOTEL_ADDRESS_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></span></label>
                </th>
                <td colspan="3">
                    <input  disabled="disabled" type="text" class="large-text" value="<?php
                    if (isset($this->hotelBO->address)) {
                        echo htmlspecialchars($this->hotelBO->address);
                    }

                    ?>" id="address" name="address">
                </td>
            </tr>

            <tr class="hotel-number-of-rooms-wrap">
                <th>
                    <label for="number_of_rooms"><?php echo HOTEL_NUMBER_OF_ROOMS_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="1" max="999" class="screen-per-page" name="number_of_rooms" id="number_of_rooms" maxlength="3" value="<?php
                    if (isset($this->hotelBO->number_of_rooms)) {
                        echo htmlspecialchars($this->hotelBO->number_of_rooms);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="star"><?php echo HOTEL_STAR_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="5" class="screen-per-page" name="star" id="star" maxlength="3" value="<?php
                    if (isset($this->hotelBO->star)) {
                        echo htmlspecialchars($this->hotelBO->star);
                    }

                    ?>" style="min-width: 150px;">
                </td>


            </tr>

            <tr class="hotel-vote-times-wrap">
                <th>
                    <label for="current_rating"><?php echo HOTEL_CURRENT_RATING_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="5" class="screen-per-page" name="current_rating" id="current_rating" maxlength="3" value="<?php
                    if (isset($this->hotelBO->current_rating)) {
                        echo htmlspecialchars($this->hotelBO->current_rating);
                    }

                    ?>" style="min-width: 150px;">
                </td>

                <th>
                    <label for="vote_times"><?php echo HOTEL_VOTE_TIMES_TITLE; ?></label>
                </th>
                <td>
                    <input disabled="disabled" type="number" step="1" min="0" max="999" class="screen-per-page" name="vote_times" id="vote_times" maxlength="3" value="<?php
                    if (isset($this->hotelBO->vote_times)) {
                        echo htmlspecialchars($this->hotelBO->vote_times);
                    }

                    ?>" style="min-width: 150px;">
                </td>


            </tr>

            <tr class="hotel-city-id-wrap">
                <th colspan="1"><label for="city_id"><?php echo HOTEL_CITY_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label></th>
                <td colspan="3">
                    <select disabled="disabled" id="city_id" name="city_id"  style="min-width: 150px;">
                        <?php
                        if (isset($this->cityList) && is_array($this->cityList)) {
                            foreach ($this->cityList as $value) {

                                ?>
                                <option value="<?php
                                if (isset($value->term_taxonomy_id)) {
                                    echo $value->term_taxonomy_id;
                                }

                                ?>" <?php
                                        if (isset($this->hotelBO->city_id) && $this->hotelBO->city_id == $value->term_taxonomy_id) {
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
                        if (isset($this->hotelBO->city_id) && $this->hotelBO->city_id == 0) {
                            echo "selected='selected'";
                        } elseif (!isset($this->hotelBO->city_id)) {
                            echo "selected='selected'";
                        }

                        ?>><?php echo NONE_TITLE; ?></option>                                                                        
                    </select>
                </td>
            </tr>


            <tr class="hotel-image-wrap">
                <th colspan="1">
                    <label for="image"><?php echo HOTEL_IMAGE_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <img width="96" height="96" class="avatar avatar-96 photo" srcset="<?php
                    if (isset($this->hotelBO->image_url)) {
                        echo URL . htmlspecialchars($this->hotelBO->image_url);
                    } else {
                        echo URL . AVATAR_DEFAULT;
                    }

                    ?>" src="<?php
                         if (isset($this->hotelBO->image_url)) {
                             echo URL . htmlspecialchars($this->hotelBO->image_url);
                         } else {
                             echo URL . AVATAR_DEFAULT;
                         }

                         ?>" alt="">
                </td>
            </tr>
            <tr class="hotel-post-content-wrap">
                <th colspan="1">
                    <label for="post_content"><?php echo HOTEL_CONTENT_TITLE; ?> <span style="color: red;" class="description"><?php echo LABEL_REQUIRED; ?></label>
                </th>
                <td colspan="3">
                    <textarea disabled="disabled" id="post_content" name="post_content" autocomplete="off" style="height: 200px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->hotelBO) && isset($this->hotelBO->post_content)) {
                            echo htmlspecialchars($this->hotelBO->post_content);
                        }

                        ?></textarea>
                </td>
            </tr>

        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="hotel-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->hotelBO->tag_list) && count($this->hotelBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->hotelBO->tag_list as $tag) {
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
        function getEditHotelPage(element) {
            var hotel = jQuery(element).attr("hotel");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_HOTEL_EDIT_INFO; ?>" + hotel + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_HOTEL_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    hotel: hotel
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
