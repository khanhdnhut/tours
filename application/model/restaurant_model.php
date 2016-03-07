<?php
Model::autoloadModel('post');

class RestaurantModel extends PostModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new restaurant
     *
     * @param stdClass $para para for add new restaurant
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        
        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_RESTAURANT_CITY_NOT_EXIST;
                return false;
            }
        }
        if (!(isset($para->country_id) && $para->country_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("country");
            $countryModel = new CityModel($this->db);
            $countryBO = $countryModel->get($para->country_id);
            if ($countryBO == NULL || !(isset($countryBO->taxonomy) && $countryBO->taxonomy == "country")) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_NOT_IMPOSSIBLE;
                return false;
            }
        }
        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_IMAGE_EMPTY;
            return false;
        }

        if (!isset($para->image)) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_CONTENT_EMPTY;
            return false;
        } else {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_RESTAURANT_IMAGE_INVALID;
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
     * addToDatabase
     *
     * Add new restaurant
     *
     * @param stdClass $para para for add new restaurant
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("restaurant");
                $restaurantBO = new RestaurantBO();

                if (isset($para->post_title)) {
                    $restaurantBO->post_title = $para->post_title;
                }
                if (isset($para->current_rating)) {
                    $restaurantBO->current_rating = $para->current_rating;
                }
                if (isset($para->vote_times)) {
                    $restaurantBO->vote_times = $para->vote_times;
                }
                if (isset($para->tag_list)) {
                    $restaurantBO->tag_list = $para->tag_list;
                }
                if (isset($para->post_content)) {
                    $restaurantBO->post_content = $para->post_content;
                }
                if (isset($para->post_name)) {
                    $restaurantBO->post_name = $para->post_name;
                }
                if (isset($para->city_id)) {
                    $restaurantBO->city_id = $para->city_id;
                }
                if (isset($para->country_id)) {
                    $restaurantBO->country_id = $para->country_id;
                }

                $restaurantBO->post_author = Session::get("user_id");
                $restaurantBO->post_date = date("Y-m-d H:i:s");
                $restaurantBO->post_date_gmt = gmdate("Y-m-d H:i:s");
                $restaurantBO->post_modified = $restaurantBO->post_date;
                $restaurantBO->post_modified_gmt = $restaurantBO->post_date_gmt;
                $restaurantBO->post_parent = 0;
                $restaurantBO->post_status = "publish";
                $restaurantBO->comment_status = "closed";
                $restaurantBO->ping_status = "open";
                $restaurantBO->guid = "";

                $this->db->beginTransaction();

                if (isset($para->image)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_medium = true;
                    $image_array_id = $imageModel->uploadImages("image");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_id = $image_array_id[0];
                        $restaurantBO->image_id = $image_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                        $this->db->rollBack();
                        return FALSE;
                    }
                }

                $post_id = parent::addToDatabase($restaurantBO);
                if ($post_id != NULL) {
                    $guid = CONTEXT_PATH_RESTAURANT_VIEW . $post_id . "/" . $restaurantBO->post_name . "/";
                    if (!$this->updateGuid($post_id, $guid)) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                        return FALSE;
                    }

                    if (isset($restaurantBO->image_id) && $restaurantBO->image_id != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "image_id", $restaurantBO->image_id) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                            return FALSE;
                        }
                    }
                    if (isset($restaurantBO->current_rating) && $restaurantBO->current_rating != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "current_rating", $restaurantBO->current_rating) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                            return FALSE;
                        }
                    }
                    if (isset($restaurantBO->vote_times) && $restaurantBO->vote_times != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "vote_times", $restaurantBO->vote_times) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                            return FALSE;
                        }
                    }

                    Model::autoloadModel('taxonomy');
                    $taxonomyModel = new TaxonomyModel($this->db);

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $restaurantBO->city_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                        return FALSE;
                    }

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $restaurantBO->country_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                        return FALSE;
                    }

                    if (isset($para->tag_array) && count($para->tag_array) > 0) {
                        Model::autoloadModel('tag');
                        $tagModel = new TagModel($this->db);
                        $tag_id_array = $tagModel->addTagArray($para->tag_array);
                        for ($i = 0; $i < count($tag_id_array); $i++) {
                            $taxonomyModel->addRelationshipToDatabase($post_id, $tag_id_array[$i]);
                        }
                    }


                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_RESTAURANT_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
                    if (isset($imageModel) && isset($image_id)) {
                        $imageModel->delete($image_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_RESTAURANT;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of restaurant
     *
     * @param stdClass $para para for update info of restaurant
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_RESTAURANT_CITY_NOT_EXIST;
                return false;
            }
        }
        
        if (!(isset($para->country_id) && $para->country_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_COUNTRY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("country");
            $countryModel = new CityModel($this->db);
            $countryBO = $countryModel->get($para->country_id);
            if ($countryBO == NULL || !(isset($countryBO->taxonomy) && $countryBO->taxonomy == "country")) {
                $_SESSION["fb_error"][] = ERROR_COUNTRY_NOT_IMPOSSIBLE;
                return false;
            }
        }
        
        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = ERROR_RESTAURANT_CONTENT_EMPTY;
            return false;
        }

        if (isset($para->image)) {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_RESTAURANT_IMAGE_INVALID;
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

    public function get($post_id)
    {
        try {
            $restaurantBO = parent::get($post_id);
            if ($restaurantBO != NULL) {
                Model::autoloadModel("taxonomy");
                $taxonomyModel = new TaxonomyModel($this->db);

                $cityList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "city");
                if (count($cityList) > 0) {
                    $cityBO = $cityList[0];
                    $restaurantBO->city_name = $cityBO->name;
                    $restaurantBO->city_id = $cityBO->term_taxonomy_id;
                }
                
                $countryList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "country");
                if (count($countryList) > 0) {
                    $countryBO = $countryList[0];
                    $restaurantBO->country_name = $countryBO->name;
                    $restaurantBO->country_id = $countryBO->term_taxonomy_id;
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($post_id, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $restaurantBO->tag_list = $tagList;
                }

                if (isset($restaurantBO->image_id) && $restaurantBO->image_id != "" && is_numeric($restaurantBO->image_id)) {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $image_object = $imageModel->get($restaurantBO->image_id);
                    if ($image_object != NULL) {
                        if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                            if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->medium->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                $restaurantBO->image_url = $image_object->attachment_metadata->sizes->large->url;
                            } else {
                                $restaurantBO->image_url = $image_object->guid;
                            }
                        } else {
                            $restaurantBO->image_url = $image_object->guid;
                        }
                    }
                }

                return $restaurantBO;
            } else {
                return NULL;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    /**
     * updateInfo
     *
     * Update info of restaurant
     *
     * @param stdClass $para para for update info of restaurant
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $restaurantBO = $this->get($para->post_id);
                if ($restaurantBO != NULL) {
                    if (isset($para->post_title) && $restaurantBO->post_title != $para->post_title) {
                        $restaurantBO->post_title = $para->post_title;
                    }

                    if (isset($para->post_content) && $restaurantBO->post_content != $para->post_content) {
                        $restaurantBO->post_content = $para->post_content;
                    }
                    if (isset($para->post_name) && $restaurantBO->post_name != $para->post_name) {
                        $restaurantBO->post_name = $para->post_name;
                    }

                    $restaurantBO->post_modified = date("Y-m-d H:i:s");
                    $restaurantBO->post_modified_gmt = gmdate("Y-m-d H:i:s");

                    $this->db->beginTransaction();


                    if (isset($para->image)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->is_create_thumb = true;
                        $imageModel->is_medium = true;
                        $image_array_id = $imageModel->uploadImages("image");

                        if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                            $image_id = $image_array_id[0];
                            $image_id_old = $restaurantBO->image_id;
                        } else {
                            $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                            $this->db->rollBack();
                            return FALSE;
                        }
                    }

                    if ($this->update($restaurantBO)) {

                        $guid = CONTEXT_PATH_RESTAURANT_VIEW . $para->post_id . "/" . $restaurantBO->post_name . "/";

                        if ((isset($restaurantBO->guid) && $restaurantBO->guid != $guid) || !isset($restaurantBO->guid)) {
                            $restaurantBO->guid = $guid;
                            if (!$this->updateGuid($para->post_id, $guid)) {
                                $this->db->rollBack();
                                if (isset($imageModel) && isset($image_id)) {
                                    $imageModel->delete($image_id);
                                }
                                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                return FALSE;
                            }
                        }

                        if (isset($image_id)) {
                            if (!isset($restaurantBO->image_id)) {
                                $restaurantBO->image_id = $image_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "image_id", $restaurantBO->image_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            } else if ($restaurantBO->image_id != $image_id) {
                                $restaurantBO->image_id = $image_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "image_id", $restaurantBO->image_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->current_rating)) {
                            if (!isset($restaurantBO->current_rating)) {
                                $restaurantBO->current_rating = $para->current_rating;
                                if ($this->addMetaInfoToDatabase($para->post_id, "current_rating", $restaurantBO->current_rating) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            } else if ($restaurantBO->current_rating != $para->current_rating) {
                                $restaurantBO->current_rating = $para->current_rating;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "current_rating", $restaurantBO->current_rating)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->vote_times)) {
                            if (!isset($restaurantBO->vote_times)) {
                                $restaurantBO->vote_times = $para->vote_times;
                                if ($this->addMetaInfoToDatabase($para->post_id, "vote_times", $restaurantBO->vote_times) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            } else if ($restaurantBO->vote_times != $para->vote_times) {
                                $restaurantBO->vote_times = $para->vote_times;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "vote_times", $restaurantBO->vote_times)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->city_id)) {
                            if (!isset($restaurantBO->city_id)) {
                                $restaurantBO->city_id = $para->city_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "city_id", $restaurantBO->city_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            } else if ($restaurantBO->city_id != $para->city_id) {
                                $restaurantBO->city_id = $para->city_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "city_id", $restaurantBO->city_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            }
                        }
                        
                        if (isset($para->country_id)) {
                            if (!isset($restaurantBO->country_id)) {
                                $restaurantBO->country_id = $para->country_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "country_id", $restaurantBO->country_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            } else if ($restaurantBO->country_id != $para->country_id) {
                                $restaurantBO->country_id = $para->country_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "country_id", $restaurantBO->country_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->tag_array) || isset($restaurantBO->tag_list)) {
                            Model::autoloadModel('tag');
                            $tagModel = new TagModel($this->db);
                            Model::autoloadModel('taxonomy');
                            $taxonomyModel = new TaxonomyModel($this->db);
                            if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                foreach ($restaurantBO->tag_list as $tag) {
                                    $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                }
                            } elseif (!isset($restaurantBO->tag_list) || count($restaurantBO->tag_list) == 0) {
                                if (count($para->tag_array) > 0) {
                                    $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                    for ($i = 0; $i < count($tag_id_array); $i++) {
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_array[$i]);
                                    }
                                }
                            } elseif (isset($para->tag_array) && isset($restaurantBO->tag_list) &&
                                count($para->tag_array) > 0 && count($restaurantBO->tag_list) > 0) {
                                $tags_old_array = array();
                                foreach ($restaurantBO->tag_list as $tag_old) {
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
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_new_array[$i]);
                                    }
                                }

                                $tags_delete_array = array();
                                for ($i = 0; $i < count($restaurantBO->tag_list); $i++) {
                                    if (!in_array($restaurantBO->tag_list[$i]->name, $para->tag_array)) {
                                        $tags_delete_array[] = $restaurantBO->tag_list[$i];
                                    }
                                }
                                if (count($tags_delete_array) > 0) {
                                    foreach ($tags_delete_array as $tag) {
                                        $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                    }
                                }
                            }
                        }

                        $this->db->commit();
                        if (isset($imageModel) && isset($image_id) && isset($image_id_old)) {
                            $imageModel->delete($image_id_old);
                        }
                        $_SESSION["fb_success"][] = UPDATE_RESTAURANT_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_RESTAURANT;
        }
        return FALSE;
    }

    /**
     * updateRestaurantsPerPages
     *
     * Update number restaurants per page
     *
     * @param string $restaurants_per_page number restaurants per page
     */
    public function updateRestaurantsPerPages($restaurants_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "restaurants_per_page";
        $meta_value = $restaurants_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_restaurants_columns_show";
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
        if (isset($para->restaurants_per_page) && is_numeric($para->restaurants_per_page)) {
            $this->updateRestaurantsPerPages($para->restaurants_per_page);
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
        if (isset($para->restaurants) && is_array($para->restaurants)) {
            foreach ($para->restaurants as $post_id) {
                $this->delete($post_id);
            }
        }
    }

    public function delete($post_id)
    {
        try {
            $restaurantBO = $this->get($post_id);
            if ($restaurantBO != NULL) {
                if (parent::delete($post_id)) {
                    if (isset($restaurantBO->city_id)) {
                        $this->deleteRelationship($post_id, $restaurantBO->city_id);
                    }
                    if (isset($restaurantBO->country_id)) {
                        $this->deleteRelationship($post_id, $restaurantBO->country_id);
                    }
                    if (isset($restaurantBO->image_id)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->delete($restaurantBO->image_id);
                    }
                    return TRUE;
                }
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
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
        $restaurants_per_page = RESTAURANTS_PER_PAGE_DEFAULT;
        $restaurant = "restaurant";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->restaurants_per_page) && is_numeric($userLoginBO->restaurants_per_page)) {
                $restaurants_per_page = (int) $userLoginBO->restaurants_per_page;
            }
        }

        if (!isset($restaurants_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->restaurants_per_page = RESTAURANTS_PER_PAGE_DEFAULT;
                $restaurants_per_page = RESTAURANTS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->restaurants_per_page)) {
                $_SESSION['options']->restaurants_per_page = RESTAURANTS_PER_PAGE_DEFAULT;
                $restaurants_per_page = RESTAURANTS_PER_PAGE_DEFAULT;
            }
        }

        $view->restaurants_per_page = $restaurants_per_page;
        $view->restaurant = $restaurant;

        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT DISTINCT 
                                cu.`meta_value` AS current_rating,
                                vo.`meta_value` AS vote_times,
                                im.`meta_value` AS image_id,
                                te.`name` AS city_name,
                                ta.`term_taxonomy_id` AS city_id,
                                ts.`name` AS country_name,
                                tc.`term_taxonomy_id` AS country_id,
                                po.*  ";
            $sqlSelectCount = "SELECT COUNT(*) as countRestaurant ";
            //para: orderby, order, page, s, paged, countries, new_role, new_role2, action, action2
            $sqlFrom = " FROM
                            posts AS po
                            LEFT JOIN `postmeta` AS cu ON po.`ID` = cu.`post_id` AND cu.`meta_key` = 'current_rating' 
                            LEFT JOIN `postmeta` AS vo ON po.`ID` = vo.`post_id` AND vo.`meta_key` = 'vote_times' 
                            LEFT JOIN `postmeta` AS im ON po.`ID` = im.`post_id` AND im.`meta_key` = 'image_id'   
                            LEFT JOIN `term_relationships` AS re ON po.`ID` = re.`object_id` 
                            JOIN `term_taxonomy` AS ta ON re.`term_taxonomy_id` = ta.`term_taxonomy_id` AND ta.`taxonomy` = 'city'                             
                            LEFT JOIN `terms` AS te ON ta.`term_id` = te.`term_id` 
                            LEFT JOIN `term_relationships` AS rd ON po.`ID` = rd.`object_id` 
                            JOIN `term_taxonomy` AS tc ON rd.`term_taxonomy_id` = tc.`term_taxonomy_id` AND tc.`taxonomy` = 'country' 
                            LEFT JOIN `terms` AS ts ON tc.`term_id` = ts.`term_id` ";
            $sqlWhere = " WHERE po.`post_type` = 'restaurant' ";


            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= " AND (
                                po.`post_content` LIKE :s 
                                OR po.`post_name` LIKE :s 
                                OR po.`post_title` LIKE :s 
                                OR te.`name` LIKE :s
                                OR ts.`name` LIKE :s
                              ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "post_title";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("post_title", "city_name", "country_name", "current_rating", "vote_times"))) {
                switch ($para->orderby) {
                    case "post_title":
                        $para->orderby = "post_title";
                        $view->orderby = "post_title";
                        break;
                    case "current_rating":
                        $para->orderby = "current_rating";
                        $view->orderby = "current_rating";
                        break;
                    case "vote_times":
                        $para->orderby = "vote_times";
                        $view->orderby = "vote_times";
                        break;
                    case "city_name":
                        $para->orderby = "city_name";
                        $view->orderby = "city_name";
                        break;
                    case "country_name":
                        $para->orderby = "country_name";
                        $view->orderby = "country_name";
                        break;
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $view->order = $para->order;
                } else {
                    $para->order = "asc";
                    $view->order = "asc";
                }
                $sqlOrderby = " ORDER BY " . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY " . TB_POST_COL_POST_TITLE . " ASC";
            }

            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countRestaurant = (int) $sth->fetch()->countRestaurant;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countRestaurant > 0) {
                $view->count = $countRestaurant;

                $view->pageNumber = floor($view->count / $view->restaurants_per_page);
                if ($view->count % $view->restaurants_per_page != 0) {
                    $view->pageNumber++;
                }

                if (isset($para->page)) {
                    try {
                        $page = (int) $para->page;
                        if ($para->page <= 0) {
                            $page = 1;
                        }
                    } catch (Exception $e) {
                        $page = 1;
                    }
                } else {
                    $page = 1;
                }
                if ($page > $view->pageNumber) {
                    $page = $view->pageNumber;
                }

                $view->page = $page;
                $startRestaurant = ($page - 1) * $view->restaurants_per_page;
                $sqlLimit = " LIMIT " . $view->restaurants_per_page . " OFFSET " . $startRestaurant;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $restaurantList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($restaurantList); $i++) {
                        $restaurantInfo = $restaurantList[$i];
                        Model::autoloadModel('image');
                        $imageModel = new ImageModel($this->db);
                        if (isset($restaurantInfo->image_id)) {
                            $image_object = $imageModel->get($restaurantInfo->image_id);
                            if ($image_object != NULL) {
                                if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                                    if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->medium->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                        $restaurantInfo->image_url = $image_object->attachment_metadata->sizes->large->url;
                                    } else {
                                        $restaurantInfo->image_url = $image_object->guid;
                                    }
                                } else {
                                    $restaurantInfo->image_url = $image_object->guid;
                                }
                            }
                        }
                        $this->autoloadBO('restaurant');
                        $restaurantBO = new RestaurantBO();
                        $restaurantBO->setRestaurantInfo($restaurantInfo);
                        $restaurantBO->setPost($restaurantInfo);
                        $restaurantList[$i] = $restaurantBO;
                    }
                    $view->restaurantList = $restaurantList;
                } else {
                    $view->restaurantList = NULL;
                }
            } else {
                $view->restaurantList = NULL;
            }
        } catch (Exception $e) {
            $view->restaurantList = NULL;
        }
    }
}
