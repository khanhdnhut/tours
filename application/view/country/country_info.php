<?php
if (isset($this->countryBO) && $this->countryBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE . " " . COUNTRY_TITLE; ?> "<strong><?php
            if (isset($this->countryBO->name)) {
                echo $this->countryBO->name;
            }

            ?></strong>"
        <a class="page-title-action" href="#" country="<?php echo $this->countryBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->countryBO->name); ?>" onclick="getEditCountryPage(this)"><?php echo DASHBOARD_TOURS_EDIT_COUNTRY_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="country-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->name)) {
                        echo htmlspecialchars($this->countryBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="country-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->slug)) {
                        echo htmlspecialchars($this->countryBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>

            <tr class="country-parent-wrap">
                <th>
                    <label for="parent"><?php echo PARENT_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <?php
                        if (isset($this->parentList) && is_a($this->parentList, "SplDoublyLinkedList")) {
                            $this->parentList->rewind();
                            foreach ($this->parentList as $value) {
                                if ($value->term_taxonomy_id != $this->countryBO->term_taxonomy_id &&
                                    $value->parent != $this->countryBO->term_taxonomy_id) {

                                    ?> 
                                    <option <?php if ($value->term_taxonomy_id == $this->countryBO->parent) {

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
                        <option value="0" <?php if ($this->countryBO->parent == 0 || $this->countryBO->parent == "0") { ?>
                                    selected="selected"
                                <?php } ?> ><?php echo NONE_TITLE; ?></option>                                              
                    </select>
                </td>
            </tr>

            <tr class="country-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->description)) {
                        echo htmlspecialchars($this->countryBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>



            <tr class="overview-wrap">
                <th>
                    <label for="overview"><?php echo OVERVIEW_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="overview" name="overview" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->overview)) {
                            echo htmlspecialchars($this->countryBO->overview);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="history-wrap">
                <th>
                    <label for="history"><?php echo HISTORY_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="history" name="history" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->history)) {
                            echo htmlspecialchars($this->countryBO->history);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="weather-wrap">
                <th>
                    <label for="weather"><?php echo WEATHER_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="weather" name="weather" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->weather)) {
                            echo htmlspecialchars($this->countryBO->weather);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="image_weathers-wrap">
                <th>
                    <label for="image_weathers"><?php echo IMAGE_WEATHERS_TITLE; ?></label>
                </th>
                <td>
                    <?php
                    if (isset($this->countryBO->image_weathers)) {
                        foreach ($this->countryBO->image_weathers as $image) {
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

            <tr class="passport_visa-wrap">
                <th>
                    <label for="passport_visa"><?php echo PASSPORT_VISA_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="passport_visa" name="passport_visa" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->passport_visa)) {
                            echo htmlspecialchars($this->countryBO->passport_visa);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="currency-wrap">
                <th>
                    <label for="currency"><?php echo CURRENCY_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="currency" name="currency" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->currency)) {
                            echo htmlspecialchars($this->countryBO->currency);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="phone_internet_service-wrap">
                <th>
                    <label for="phone_internet_service"><?php echo PHONE_INTERNET_SERVICE_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="phone_internet_service" name="phone_internet_service" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->phone_internet_service)) {
                            echo htmlspecialchars($this->countryBO->phone_internet_service);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="transportation-wrap">
                <th>
                    <label for="transportation"><?php echo TRANSPORTATION_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="transportation" name="transportation" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->transportation)) {
                            echo htmlspecialchars($this->countryBO->transportation);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="food_drink-wrap">
                <th>
                    <label for="food_drink"><?php echo FOOD_DRINK_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="food_drink" name="food_drink" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->food_drink)) {
                            echo htmlspecialchars($this->countryBO->food_drink);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="public_holiday-wrap">
                <th>
                    <label for="public_holiday"><?php echo PUBLIC_HOLIDAY_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="public_holiday" name="public_holiday" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->public_holiday)) {
                            echo htmlspecialchars($this->countryBO->public_holiday);
                        }

                        ?></textarea>
                </td>
            </tr>

            <tr class="predeparture_check_list-wrap">
                <th>
                    <label for="predeparture_check_list"><?php echo PREDEPARTURE_CHECK_LIST_TITLE; ?></label>
                </th>
                <td>
                    <textarea disabled="disabled" id="predeparture_check_list" name="predeparture_check_list" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->countryBO) && isset($this->countryBO->predeparture_check_list)) {
                            echo htmlspecialchars($this->countryBO->predeparture_check_list);
                        }

                        ?></textarea>
                </td>
            </tr>

        <link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />

        <tr class="country-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->countryBO->tag_list) && count($this->countryBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->countryBO->tag_list as $tag) {
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
        function getEditCountryPage(element) {
            var country = jQuery(element).attr("country");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_COUNTRY_EDIT_INFO; ?>" + country + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_COUNTRY_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    country: country
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
