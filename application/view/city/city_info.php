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

            <tr class="post_content_1-wrap">
                <th>
                    <label for="post_content_1"><?php echo POST_CONTENT_1_TITLE; ?></label>
                </th>
                <td>
                    <textarea  disabled="disabled" id="post_content_1" name="post_content_1" autocomplete="off" style="height: 100px;" class="wp-editor-area large-text" aria-hidden="true"><?php
                        if (isset($this->cityBO) && isset($this->cityBO->post_content_1)) {
                            echo htmlspecialchars($this->cityBO->post_content_1);
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
                        if (isset($this->cityBO) && isset($this->cityBO->post_content_2)) {
                            echo htmlspecialchars($this->cityBO->post_content_2);
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
                    if (isset($this->cityBO->images)) {
                        foreach ($this->cityBO->images as $image) {
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

        <tr class="city-tags-wrap">   
            <th colspan="1">
                <label for="tags"><?php echo TAGS_TITLE; ?></label>
            </th>
            <td colspan="3">
                <div id="tagchecklist" class="tagBlock TagContainer">
                    <?php
                    if (isset($this->cityBO->tag_list) && count($this->cityBO->tag_list) > 0) {

                        ?>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->cityBO->tag_list as $tag) {
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
