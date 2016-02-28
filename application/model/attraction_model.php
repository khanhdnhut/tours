<?php
Model::autoloadModel('taxonomy');

class AttractionModel extends TaxonomyModel
{

    public function get($taxonomy_id)
    {
        $attractionBO = parent::get($taxonomy_id);
        if ($attractionBO != null) {

            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $postBOList = $postModel->getPostRelationshipByTaxonomyId($taxonomy_id, "attraction");
            if (count($postBOList) != 0) {
                $postBO = $postBOList[0];

                $attractionBO->postBO = $postBO;

                if (isset($postBO->post_content)) {
                    $post_content = json_decode($postBO->post_content);
                    if (isset($post_content->post_content_1)) {
                        $attractionBO->post_content_1 = $post_content->post_content_1;
                    }
                    if (isset($post_content->post_content_2)) {
                        $attractionBO->post_content_2 = $post_content->post_content_2;
                    }
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($postBO->ID, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $attractionBO->tag_list = $tagList;
                }

                if (isset($postBO->image_ids)) {
                    $image_ids = json_decode($postBO->image_ids);
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $attractionBO->images = array();
                    foreach ($image_ids as $image_id) {
                        $image_object = $imageModel->get($image_id);
                        if ($image_object != NULL) {
                            $image_info = new stdClass();
                            $image_info->image_id = $image_id;
                            if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {

                                if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                    $image_info->slider_thumb_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                                }
//                                if (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
//                                    $image_info->thumbnail_url = $image_object->attachment_metadata->sizes->thumbnail->url;
//                                }
//                                if (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
//                                    $image_info->post_thumbnail_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
//                                }
//                                if (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
//                                    $image_info->medium_url = $image_object->attachment_metadata->sizes->medium->url;
//                                }
//                                if (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
//                                    $image_info->medium_large_url = $image_object->attachment_metadata->sizes->medium_large->url;
//                                }
                                if (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                    $image_info->large_url = $image_object->attachment_metadata->sizes->large->url;
                                }
                            }
                            $image_info->image_url = $image_object->guid;
                            if (!isset($image_info->slider_thumb_url)) {
                                $image_info->slider_thumb_url = $image_object->guid;
                            }
                            if (!isset($image_info->large_url)) {
                                $image_info->large_url = $image_object->guid;
                            }
                            $attractionBO->images[] = $image_info;
                        }
                    }
                }
            }
        }
        return $attractionBO;
    }

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ATTRACTION;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "attraction") != FALSE) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "attraction")) {
                $_SESSION["fb_error"][] = ERROR_SLUG_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_SLUG_EMPTY;
            return false;
        }

        if (!isset($para->country) || $para->country == "" || !is_numeric($para->country)) {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_EMPTY;
            return false;
        } else {
            $para->country = (int) $para->country;
            if ($para->country < 0) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->city)) {
            if ($para->city != "" && is_numeric($para->city)) {
                $para->city = (int) $para->city;
                if ($para->city < 0) {
                    $_SESSION["fb_error"][] = ERROR_CITY_NOT_IMPOSSIBLE;
                }
            } else {
                $_SESSION["fb_error"][] = ERROR_CITY_NOT_IMPOSSIBLE;
                return false;
            }
        }
        
        if (isset($para->destination)) {
            if ($para->destination != "" && is_numeric($para->destination)) {
                $para->destination = (int) $para->destination;
                if ($para->destination < 0) {
                    $_SESSION["fb_error"][] = ERROR_DESTINATION_NOT_IMPOSSIBLE;
                }
            } else {
                $_SESSION["fb_error"][] = ERROR_DESTINATION_NOT_IMPOSSIBLE;
                return false;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }
        if (isset($para->current_rating) && $para->current_rating != "" && !is_numeric($para->current_rating)) {
            $_SESSION["fb_error"][] = ERROR_CURRENT_RATING_INVALID;
            return false;
        }

        if (isset($para->vote_times) && $para->vote_times != "" && !is_numeric($para->vote_times)) {
            $_SESSION["fb_error"][] = ERROR_VOTE_TIMES_INVALID;
            return false;
        }
        return true;
    }

    /**
     * addContent
     *
     * Add new attraction
     *
     * @param stdClass $attractionBO para for add new attraction
     */
    public function addContent($attractionBO)
    {
        try {
            BO::autoloadBO("post");
            $postBO = new PostBO();

            if (isset($attractionBO->name)) {
                $postBO->post_title = $attractionBO->name;
            }
            if (isset($attractionBO->post_content_1) || isset($attractionBO->post_content_2)) {
                $post_content = new stdClass();
                if (isset($attractionBO->post_content_1)) {
                    $post_content->post_content_1 = $attractionBO->post_content_1;
                }
                if (isset($attractionBO->post_content_2)) {
                    $post_content->post_content_2 = $attractionBO->post_content_2;
                }
                $postBO->post_content = json_encode($post_content);
            }
            if (isset($attractionBO->name)) {
                $postBO->post_name = Utils::createSlug($attractionBO->name);
            }

            if (isset($para->current_rating)) {
                $attractionBO->current_rating = $para->current_rating;
            }
            if (isset($para->vote_times)) {
                $attractionBO->vote_times = $para->vote_times;
            }
            $postBO->post_author = Session::get("user_id");
            $postBO->post_date = date("Y-m-d H:i:s");
            $postBO->post_date_gmt = gmdate("Y-m-d H:i:s");
            $postBO->post_modified = $postBO->post_date;
            $postBO->post_modified_gmt = $postBO->post_date_gmt;
            $postBO->post_parent = 0;
            $postBO->post_status = "publish";
            $postBO->comment_status = "closed";
            $postBO->ping_status = "open";
            $postBO->guid = "";
            $postBO->post_type = "attraction";


            if (isset($attractionBO->images)) {
                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                $imageModel->is_create_thumb = true;
                $imageModel->is_slider_thumb = true;
                $imageModel->is_large = true;
//                $imageModel->slider_thumb_crop = true;
                $image_array_id = $imageModel->uploadImages("images");

                if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                    $postBO->image_ids = json_encode($image_array_id);
                } else {
                    return FALSE;
                }
            }
            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $post_id = $postModel->addToDatabase($postBO);
            if ($post_id != NULL) {
                if (isset($postBO->image_ids) && $postBO->image_ids != "") {
                    if ($postModel->addMetaInfoToDatabase($post_id, "image_ids", $postBO->image_ids) == NULL) {
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_id) {
                                $imageModel->delete($image_id);
                            }
                        }
                        return FALSE;
                    }
                }

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                if ($taxonomyModel->addRelationshipToDatabase($post_id, $attractionBO->term_taxonomy_id, 0) == NULL) {
                    if (isset($imageModel) && isset($image_array_id)) {
                        foreach ($image_array_id as $image_id) {
                            $imageModel->delete($image_id);
                        }
                    }
                    return FALSE;
                }

                if (isset($attractionBO->tag_array) && count($attractionBO->tag_array) > 0) {
                    Model::autoloadModel('tag');
                    $tagModel = new TagModel($this->db);
                    $tag_id_array = $tagModel->addTagArray($attractionBO->tag_array);
                    for ($i = 0; $i < count($tag_id_array); $i++) {
                        $taxonomyModel->addRelationshipToDatabase($post_id, $tag_id_array[$i]);
                    }
                }
                return TRUE;
            } else {
                if (isset($imageModel) && isset($image_id)) {
                    $imageModel->delete($image_id);
                }
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("attraction");
                $attractionBO = new AttractionBO();

                if (isset($para->name)) {
                    $attractionBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $attractionBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $attractionBO->description = $para->description;
                }
                if (isset($para->country) && $para->country != "0") {
                    $attractionBO->country = $para->country;
                }
                if (isset($para->parent) && $para->parent != "0") {
                    $attractionBO->parent = $para->parent;
                }
                if (isset($para->city) && $para->city != "0") {
                    $attractionBO->city = $para->city;
                }
                if (isset($para->destination) && $para->destination != "0") {
                    $attractionBO->destination = $para->destination;
                }
                if (isset($para->post_content_1)) {
                    $attractionBO->post_content_1 = $para->post_content_1;
                }
                if (isset($para->post_content_2)) {
                    $attractionBO->post_content_2 = $para->post_content_2;
                }
                if (isset($para->current_rating)) {
                    $attractionBO->current_rating = $para->current_rating;
                }
                if (isset($para->vote_times)) {
                    $attractionBO->vote_times = $para->vote_times;
                }
                if (isset($para->tag_list)) {
                    $attractionBO->tag_list = $para->tag_list;
                }
                if (isset($para->tag_array)) {
                    $attractionBO->tag_array = $para->tag_array;
                }
                if (isset($para->images)) {
                    $attractionBO->images = $para->images;
                }
                $attractionBO->count = 0;
                $attractionBO->term_group = 0;

                $this->db->beginTransaction();
                $attractionBO->term_taxonomy_id = parent::addToDatabase($attractionBO);

                if ($attractionBO->term_taxonomy_id != NULL) {
                    if (isset($attractionBO->post_content_1) || isset($attractionBO->post_content_2)) {
                        $this->addContent($attractionBO);
                    }
                    $this->db->commit();

                    $attractionBOAdded = parent::get($attractionBO->term_taxonomy_id);

                    if (isset($attractionBO->country) && $attractionBO->country != "") {
                        $this->addMetaInfoToDatabase($attractionBOAdded->term_id, "country", $attractionBO->country);
                    }
                    if (isset($attractionBO->city) && $attractionBO->city != "") {
                        $this->addMetaInfoToDatabase($attractionBOAdded->term_id, "city", $attractionBO->city);
                    }
                    if (isset($attractionBO->destination) && $attractionBO->destination != "") {
                        $this->addMetaInfoToDatabase($attractionBOAdded->term_id, "destination", $attractionBO->destination);
                    }
                    if (isset($attractionBO->current_rating) && $attractionBO->current_rating != "") {
                        $this->addMetaInfoToDatabase($attractionBOAdded->term_id, "current_rating", $attractionBO->current_rating);
                    }
                    if (isset($attractionBO->vote_times) && $attractionBO->vote_times != "") {
                        $this->addMetaInfoToDatabase($attractionBOAdded->term_id, "vote_times", $attractionBO->vote_times);
                    }
                    $_SESSION["fb_success"][] = ADD_ATTRACTION_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_ATTRACTION_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ATTRACTION;
        }
        return FALSE;
    }

    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ATTRACTION;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ATTRACTION;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ATTRACTION;
                return false;
            }
        }
        if (!(isset($para->name) && $para->name != "")) {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }
        if (!(isset($para->slug) && $para->slug != "")) {
            $_SESSION["fb_error"][] = ERROR_SLUG_EMPTY;
            return false;
        }
        if (!(isset($para->country) && $para->country != "" && is_numeric($para->country))) {
            $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->country = (int) $para->country;
            if ($para->country < 0) {
                $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }
        if (!(isset($para->city) && $para->city != "" && is_numeric($para->city))) {
            $_SESSION["fb_error"][] = ERROR_CITY_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->city = (int) $para->city;
            if ($para->city < 0) {
                $_SESSION["fb_error"][] = ERROR_CITY_NOT_IMPOSSIBLE;
            }
        }
        if (!(isset($para->destination) && $para->destination != "" && is_numeric($para->destination))) {
            $_SESSION["fb_error"][] = ERROR_DESTINATION_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->destination = (int) $para->destination;
            if ($para->destination < 0) {
                $_SESSION["fb_error"][] = ERROR_DESTINATION_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }

        if (isset($para->country) && $para->country != "" && !is_numeric($para->country)) {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_INVALID;
            return false;
        }
        if (isset($para->city) && $para->city != "" && !is_numeric($para->city)) {
            $_SESSION["fb_error"][] = ERROR_CITY_INVALID;
            return false;
        }
        if (isset($para->destination) && $para->destination != "" && !is_numeric($para->destination)) {
            $_SESSION["fb_error"][] = ERROR_DESTINATION_INVALID;
            return false;
        }
        if (isset($para->current_rating) && $para->current_rating != "" && !is_numeric($para->current_rating)) {
            $_SESSION["fb_error"][] = ERROR_CURRENT_RATING_INVALID;
            return false;
        }

        if (isset($para->vote_times) && $para->vote_times != "" && !is_numeric($para->vote_times)) {
            $_SESSION["fb_error"][] = ERROR_VOTE_TIMES_INVALID;
            return false;
        }

        return true;
    }

    public function updateContent($attractionBO)
    {
        if (isset($attractionBO->postBO)) {
            $postBO = $attractionBO->postBO;
            try {
                $sql = "UPDATE " . TABLE_POSTS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_POST_COL_ID . " = :post_id;";

                $para_array = [];
                $para_array[":post_id"] = $postBO->ID;

                if (isset($attractionBO->name)) {
                    $postBO->post_title = $attractionBO->name;
                    $set .= " " . TB_POST_COL_POST_TITLE . " = :post_title,";
                    $para_array[":post_title"] = $postBO->post_title;
                }
                if (isset($attractionBO->post_content_1) || isset($attractionBO->post_content_2)) {
                    $post_content = new stdClass();
                    if (isset($attractionBO->post_content_1)) {
                        $post_content->post_content_1 = $attractionBO->post_content_1;
                    }
                    if (isset($attractionBO->post_content_2)) {
                        $post_content->post_content_2 = $attractionBO->post_content_2;
                    }
                    $postBO->post_content = json_encode($post_content);
                    $set .= " " . TB_POST_COL_POST_CONTENT . " = :post_content,";
                    $para_array[":post_content"] = $postBO->post_content;
                }
                if (isset($attractionBO->name)) {
                    $postBO->post_name = Utils::createSlug($attractionBO->name);
                    $set .= " " . TB_POST_COL_POST_NAME . " = :post_name,";
                    $para_array[":post_name"] = $postBO->post_name;
                }

                if (isset($postBO->image_ids)) {
                    $image_ids = json_decode($postBO->image_ids);
                } else {
                    $image_ids = array();
                }

                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                if (isset($attractionBO->images_upload)) {
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
                    $imageModel->is_large = true;
//                $imageModel->slider_thumb_crop = true;
                    $image_array_id = $imageModel->uploadImages("images");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_ids = array_merge($image_ids, $image_array_id);
                    } else {
                        return FALSE;
                    }
                }

                if (isset($attractionBO->image_delete_list) && $attractionBO->image_delete_list != "" && $attractionBO->image_delete_list != NULL) {
                    $image_delete_array = explode(",", $attractionBO->image_delete_list);
                    if (count($image_delete_array) > 0) {
                        foreach ($image_delete_array as $image_delete_id) {
                            $image_ids = array_diff($image_ids, [$image_delete_id]);
//                            array_slice($image_ids, $image_delete_id, 1);
                        }
                    }
                }

                if (count($para_array) != 0) {
                    $set = substr($set, 0, strlen($set) - 1);
                    $sql .= $set . $where;
                    $sth = $this->db->prepare($sql);
                    $sth->execute($para_array);

                    Model::autoloadModel("post");
                    $postModel = new PostModel($this->db);
                    $image_ids = json_encode($image_ids);
                    if (isset($image_ids) && $image_ids != "") {
                        if (isset($postBO->image_ids)) {
                            if (!$postModel->updateMetaInfoToDatabase($postBO->ID, "image_ids", $image_ids)) {
                                if (isset($imageModel) && isset($image_array_id)) {
                                    foreach ($image_array_id as $image_id) {
                                        $imageModel->delete($image_id);
                                    }
                                }
                                return FALSE;
                            } else { //thanh cong xoa image bi tich bo
                                if (isset($imageModel) && isset($image_delete_array)) {
                                    foreach ($image_delete_array as $image_id) {
                                        $imageModel->delete($image_id);
                                    }
                                }
                            }
                        } else {
                            if (!$postModel->addMetaInfoToDatabase($postBO->ID, "image_ids", $image_ids)) {
                                if (isset($imageModel) && isset($image_array_id)) {
                                    foreach ($image_array_id as $image_id) {
                                        $imageModel->delete($image_id);
                                    }
                                }
                                return FALSE;
                            } else { //thanh cong xoa image bi tich bo
                                if (isset($imageModel) && isset($image_delete_array)) {
                                    foreach ($image_delete_array as $image_id) {
                                        $imageModel->delete($image_id);
                                    }
                                }
                            }
                        }
                    }

                    return TRUE;
                }
            } catch (Exception $e) {
                
            }
        }
    }

    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $attractionBO = $this->get($para->term_taxonomy_id);
                if ($attractionBO != NULL) {
                    if (isset($para->name)) {
                        $attractionBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $attractionBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $attractionBO->description = $para->description;
                    }

                    if (isset($para->image_delete_list)) {
                        $attractionBO->image_delete_list = $para->image_delete_list;
                    }

                    if (isset($para->images)) {
                        $attractionBO->images_upload = $para->images;
                    }
                    if (isset($para->parent)) {
                        $attractionBO->parent = $para->parent;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($attractionBO)) {

                        if (isset($para->country)) {
                            if (!isset($attractionBO->country)) {
                                $attractionBO->country = $para->country;
                                $this->addMetaInfoToDatabase($attractionBO->term_id, "country", $attractionBO->country);
                            } else if ($attractionBO->country != $para->country) {
                                $attractionBO->country = $para->country;
                                $this->updateMetaInfoToDatabase($attractionBO->term_id, "country", $attractionBO->country);
                            }
                        }

                        if (isset($para->city)) {
                            if (!isset($attractionBO->city)) {
                                $attractionBO->city = $para->city;
                                $this->addMetaInfoToDatabase($attractionBO->term_id, "city", $attractionBO->city);
                            } else if ($attractionBO->city != $para->city) {
                                $attractionBO->city = $para->city;
                                $this->updateMetaInfoToDatabase($attractionBO->term_id, "city", $attractionBO->city);
                            }
                        }
                        if (isset($para->destination)) {
                            if (!isset($attractionBO->destination)) {
                                $attractionBO->destination = $para->destination;
                                $this->addMetaInfoToDatabase($attractionBO->term_id, "destination", $attractionBO->destination);
                            } else if ($attractionBO->destination != $para->destination) {
                                $attractionBO->destination = $para->destination;
                                $this->updateMetaInfoToDatabase($attractionBO->term_id, "destination", $attractionBO->destination);
                            }
                        }

                        if (isset($para->current_rating)) {
                            if (!isset($attractionBO->current_rating)) {
                                $attractionBO->current_rating = $para->current_rating;
                                $this->addMetaInfoToDatabase($attractionBO->term_id, "current_rating", $attractionBO->current_rating);
                            } else if ($attractionBO->current_rating != $para->current_rating) {
                                $attractionBO->current_rating = $para->current_rating;
                                $this->updateMetaInfoToDatabase($attractionBO->term_id, "current_rating", $attractionBO->current_rating);
                            }
                        }

                        if (isset($para->vote_times)) {
                            if (!isset($attractionBO->vote_times)) {
                                $attractionBO->vote_times = $para->vote_times;
                                $this->addMetaInfoToDatabase($attractionBO->term_id, "vote_times", $attractionBO->vote_times) == NULL;
                            } else if ($attractionBO->vote_times != $para->vote_times) {
                                $attractionBO->vote_times = $para->vote_times;
                                $this->updateMetaInfoToDatabase($attractionBO->term_id, "vote_times", $attractionBO->vote_times);
                            }
                        }

                        if (isset($attractionBO->post_content_1) || isset($attractionBO->post_content_2) || isset($para->post_content_1) || isset($para->post_content_2)) {
                            if (isset($para->post_content_1)) {
                                $attractionBO->post_content_1 = $para->post_content_1;
                            }

                            if (isset($para->post_content_2)) {
                                $attractionBO->post_content_2 = $para->post_content_2;
                            }
                            $this->updateContent($attractionBO);
                            if (isset($para->tag_array) || isset($attractionBO->tag_list)) {
                                Model::autoloadModel('tag');
                                $tagModel = new TagModel($this->db);
                                Model::autoloadModel('taxonomy');
                                $taxonomyModel = new TaxonomyModel($this->db);
                                if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                    foreach ($attractionBO->tag_list as $tag) {
                                        $tagModel->deleteRelationship($attractionBO->postBO->ID, $tag->term_taxonomy_id);
                                    }
                                } elseif (!isset($attractionBO->tag_list) || count($attractionBO->tag_list) == 0) {
                                    if (count($para->tag_array) > 0) {
                                        $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                        for ($i = 0; $i < count($tag_id_array); $i++) {
                                            $taxonomyModel->addRelationshipToDatabase($attractionBO->postBO->ID, $tag_id_array[$i]);
                                        }
                                    }
                                } elseif (isset($para->tag_array) && isset($attractionBO->tag_list) &&
                                    count($para->tag_array) > 0 && count($attractionBO->tag_list) > 0) {
                                    $tags_old_array = array();
                                    foreach ($attractionBO->tag_list as $tag_old) {
                                        $tags_old_array[] = $tag_old->name;
                                    }

                                    $tags_new_array = array();
                                    for ($i = 0; $i < count($para->tag_array); $i++) {
                                        if (!in_array($para->tag_array[$i], $tags_old_array)) {
                                            $tags_new_array[] = $para->tag_array[$i];
                                        }
                                    }
                                    if (count($tags_new_array) > 0) {
                                        $tag_id_new_array = $tagModel->addTagArray($tags_new_array);
                                        for ($i = 0; $i < count($tag_id_new_array); $i++) {
                                            $taxonomyModel->addRelationshipToDatabase($attractionBO->postBO->ID, $tag_id_new_array[$i]);
                                        }
                                    }

                                    $tags_delete_array = array();
                                    for ($i = 0; $i < count($attractionBO->tag_list); $i++) {
                                        if (!in_array($attractionBO->tag_list[$i]->name, $para->tag_array)) {
                                            $tags_delete_array[] = $attractionBO->tag_list[$i];
                                        }
                                    }
                                    if (count($tags_delete_array) > 0) {
                                        foreach ($tags_delete_array as $tag) {
                                            $tagModel->deleteRelationship($attractionBO->postBO->ID, $tag->term_taxonomy_id);
                                        }
                                    }
                                }
                            }
                        }

                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_ATTRACTION_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ATTRACTION;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ATTRACTION;
        }
        return FALSE;
    }

    public function updateAttractionsPerPages($attractions_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "attractions_per_page";
        $meta_value = $attractions_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_attractions_columns_show";
        $meta_value = new stdClass();
        $meta_value->description_show = $description_show;
        $meta_value->slug_show = $slug_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->attractions_per_page) && is_numeric($para->attractions_per_page)) {
            $this->updateAttractionsPerPages($para->attractions_per_page);
        }
        $description_show = false;
        $slug_show = false;
        $tours_show = false;
        if (isset($para->description_show) && $para->description_show == "description") {
            $description_show = true;
        }
        if (isset($para->slug_show) && $para->slug_show == "slug") {
            $slug_show = true;
        }
        if (isset($para->tours_show) && $para->tours_show == "tours") {
            $tours_show = true;
        }
        $this->updateColumnsShow($description_show, $slug_show, $tours_show);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userBO = $userModel->get(Session::get("user_id"));
        $userModel->setNewSessionUser($userBO);
    }

    public function executeActionDelete($para)
    {
        if (isset($para->attractions) && is_array($para->attractions)) {
            foreach ($para->attractions as $term_taxonomy_id) {
                $this->delete($term_taxonomy_id);
            }
        }
    }

    public function executeAction($para)
    {
        $action = NULL;
        if (isset($para->type)) {
            if ($para->type == "action") {
                if (isset($para->action) && in_array($para->action, array("delete"))) {
                    $action = $para->action;
                }
            } elseif ($para->type == "action2") {
                if (isset($para->action2) && in_array($para->action2, array("delete"))) {
                    $action = $para->action2;
                }
            }
        }
        if (!is_null($action)) {
            switch ($action) {
                case "delete":
                    $this->executeActionDelete($para);
                    break;
            }
        }
    }

    public function search($view, $para)
    {
        $attractions_per_page = ATTRACTIONS_PER_PAGE_DEFAULT;
        $taxonomy = "attraction";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->attractions_per_page) && is_numeric($userLoginBO->attractions_per_page)) {
                $attractions_per_page = (int) $userLoginBO->attractions_per_page;
            }
        }

        if (!isset($attractions_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->attractions_per_page = ATTRACTIONS_PER_PAGE_DEFAULT;
                $attractions_per_page = ATTRACTIONS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->attractions_per_page)) {
                $_SESSION['options']->attractions_per_page = ATTRACTIONS_PER_PAGE_DEFAULT;
                $attractions_per_page = ATTRACTIONS_PER_PAGE_DEFAULT;
            }
        }


        $view->taxonomies_per_page = $attractions_per_page;
        $view->taxonomy = $taxonomy;
        parent::search($view, $para);
    }
}
