<?php
Model::autoloadModel('post');

class ActivityModel extends PostModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new activity
     *
     * @param stdClass $para para for add new activity
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        
        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_ACTIVITY_CITY_NOT_EXIST;
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
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_IMAGE_EMPTY;
            return false;
        }

        if (!isset($para->image)) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_CONTENT_EMPTY;
            return false;
        } else {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_ACTIVITY_IMAGE_INVALID;
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
     * Add new activity
     *
     * @param stdClass $para para for add new activity
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("activity");
                $activityBO = new ActivityBO();

                if (isset($para->post_title)) {
                    $activityBO->post_title = $para->post_title;
                }
                if (isset($para->current_rating)) {
                    $activityBO->current_rating = $para->current_rating;
                }
                if (isset($para->vote_times)) {
                    $activityBO->vote_times = $para->vote_times;
                }
                if (isset($para->tag_list)) {
                    $activityBO->tag_list = $para->tag_list;
                }
                if (isset($para->post_content)) {
                    $activityBO->post_content = $para->post_content;
                }
                if (isset($para->post_name)) {
                    $activityBO->post_name = $para->post_name;
                }
                if (isset($para->city_id)) {
                    $activityBO->city_id = $para->city_id;
                }
                if (isset($para->country_id)) {
                    $activityBO->country_id = $para->country_id;
                }

                $activityBO->post_author = Session::get("user_id");
                $activityBO->post_date = date("Y-m-d H:i:s");
                $activityBO->post_date_gmt = gmdate("Y-m-d H:i:s");
                $activityBO->post_modified = $activityBO->post_date;
                $activityBO->post_modified_gmt = $activityBO->post_date_gmt;
                $activityBO->post_parent = 0;
                $activityBO->post_status = "publish";
                $activityBO->comment_status = "closed";
                $activityBO->ping_status = "open";
                $activityBO->guid = "";

                $this->db->beginTransaction();

                if (isset($para->image)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_medium = true;
                    $image_array_id = $imageModel->uploadImages("image");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_id = $image_array_id[0];
                        $activityBO->image_id = $image_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                        $this->db->rollBack();
                        return FALSE;
                    }
                }

                $post_id = parent::addToDatabase($activityBO);
                if ($post_id != NULL) {
                    $guid = CONTEXT_PATH_ACTIVITY_VIEW . $post_id . "/" . $activityBO->post_name . "/";
                    if (!$this->updateGuid($post_id, $guid)) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                        return FALSE;
                    }

                    if (isset($activityBO->image_id) && $activityBO->image_id != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "image_id", $activityBO->image_id) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                            return FALSE;
                        }
                    }
                    if (isset($activityBO->current_rating) && $activityBO->current_rating != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "current_rating", $activityBO->current_rating) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                            return FALSE;
                        }
                    }
                    if (isset($activityBO->vote_times) && $activityBO->vote_times != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "vote_times", $activityBO->vote_times) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                            return FALSE;
                        }
                    }

                    Model::autoloadModel('taxonomy');
                    $taxonomyModel = new TaxonomyModel($this->db);

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $activityBO->city_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                        return FALSE;
                    }

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $activityBO->country_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
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
                    $_SESSION["fb_success"][] = ADD_ACTIVITY_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
                    if (isset($imageModel) && isset($image_id)) {
                        $imageModel->delete($image_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_ACTIVITY;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of activity
     *
     * @param stdClass $para para for update info of activity
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_ACTIVITY_CITY_NOT_EXIST;
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
            $_SESSION["fb_error"][] = ERROR_ACTIVITY_CONTENT_EMPTY;
            return false;
        }

        if (isset($para->image)) {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_ACTIVITY_IMAGE_INVALID;
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
            $activityBO = parent::get($post_id);
            if ($activityBO != NULL) {
                Model::autoloadModel("taxonomy");
                $taxonomyModel = new TaxonomyModel($this->db);

                $cityList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "city");
                if (count($cityList) > 0) {
                    $cityBO = $cityList[0];
                    $activityBO->city_name = $cityBO->name;
                    $activityBO->city_id = $cityBO->term_taxonomy_id;
                }
                
                $countryList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "country");
                if (count($countryList) > 0) {
                    $countryBO = $countryList[0];
                    $activityBO->country_name = $countryBO->name;
                    $activityBO->country_id = $countryBO->term_taxonomy_id;
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($post_id, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $activityBO->tag_list = $tagList;
                }

                if (isset($activityBO->image_id) && $activityBO->image_id != "" && is_numeric($activityBO->image_id)) {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $image_object = $imageModel->get($activityBO->image_id);
                    if ($image_object != NULL) {
                        if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                            if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->medium->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                $activityBO->image_url = $image_object->attachment_metadata->sizes->large->url;
                            } else {
                                $activityBO->image_url = $image_object->guid;
                            }
                        } else {
                            $activityBO->image_url = $image_object->guid;
                        }
                    }
                }

                return $activityBO;
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
     * Update info of activity
     *
     * @param stdClass $para para for update info of activity
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $activityBO = $this->get($para->post_id);
                if ($activityBO != NULL) {
                    if (isset($para->post_title) && $activityBO->post_title != $para->post_title) {
                        $activityBO->post_title = $para->post_title;
                    }

                    if (isset($para->post_content) && $activityBO->post_content != $para->post_content) {
                        $activityBO->post_content = $para->post_content;
                    }
                    if (isset($para->post_name) && $activityBO->post_name != $para->post_name) {
                        $activityBO->post_name = $para->post_name;
                    }

                    $activityBO->post_modified = date("Y-m-d H:i:s");
                    $activityBO->post_modified_gmt = gmdate("Y-m-d H:i:s");

                    $this->db->beginTransaction();


                    if (isset($para->image)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->is_create_thumb = true;
                        $imageModel->is_medium = true;
                        $image_array_id = $imageModel->uploadImages("image");

                        if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                            $image_id = $image_array_id[0];
                            $image_id_old = $activityBO->image_id;
                        } else {
                            $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                            $this->db->rollBack();
                            return FALSE;
                        }
                    }

                    if ($this->update($activityBO)) {

                        $guid = CONTEXT_PATH_ACTIVITY_VIEW . $para->post_id . "/" . $activityBO->post_name . "/";

                        if ((isset($activityBO->guid) && $activityBO->guid != $guid) || !isset($activityBO->guid)) {
                            $activityBO->guid = $guid;
                            if (!$this->updateGuid($para->post_id, $guid)) {
                                $this->db->rollBack();
                                if (isset($imageModel) && isset($image_id)) {
                                    $imageModel->delete($image_id);
                                }
                                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                return FALSE;
                            }
                        }

                        if (isset($image_id)) {
                            if (!isset($activityBO->image_id)) {
                                $activityBO->image_id = $image_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "image_id", $activityBO->image_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            } else if ($activityBO->image_id != $image_id) {
                                $activityBO->image_id = $image_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "image_id", $activityBO->image_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->current_rating)) {
                            if (!isset($activityBO->current_rating)) {
                                $activityBO->current_rating = $para->current_rating;
                                if ($this->addMetaInfoToDatabase($para->post_id, "current_rating", $activityBO->current_rating) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            } else if ($activityBO->current_rating != $para->current_rating) {
                                $activityBO->current_rating = $para->current_rating;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "current_rating", $activityBO->current_rating)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->vote_times)) {
                            if (!isset($activityBO->vote_times)) {
                                $activityBO->vote_times = $para->vote_times;
                                if ($this->addMetaInfoToDatabase($para->post_id, "vote_times", $activityBO->vote_times) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            } else if ($activityBO->vote_times != $para->vote_times) {
                                $activityBO->vote_times = $para->vote_times;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "vote_times", $activityBO->vote_times)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->city_id)) {
                            if (!isset($activityBO->city_id)) {
                                $activityBO->city_id = $para->city_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "city_id", $activityBO->city_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            } else if ($activityBO->city_id != $para->city_id) {
                                $activityBO->city_id = $para->city_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "city_id", $activityBO->city_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            }
                        }
                        
                        if (isset($para->country_id)) {
                            if (!isset($activityBO->country_id)) {
                                $activityBO->country_id = $para->country_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "country_id", $activityBO->country_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            } else if ($activityBO->country_id != $para->country_id) {
                                $activityBO->country_id = $para->country_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "country_id", $activityBO->country_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->tag_array) || isset($activityBO->tag_list)) {
                            Model::autoloadModel('tag');
                            $tagModel = new TagModel($this->db);
                            Model::autoloadModel('taxonomy');
                            $taxonomyModel = new TaxonomyModel($this->db);
                            if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                foreach ($activityBO->tag_list as $tag) {
                                    $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                }
                            } elseif (!isset($activityBO->tag_list) || count($activityBO->tag_list) == 0) {
                                if (count($para->tag_array) > 0) {
                                    $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                    for ($i = 0; $i < count($tag_id_array); $i++) {
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_array[$i]);
                                    }
                                }
                            } elseif (isset($para->tag_array) && isset($activityBO->tag_list) &&
                                count($para->tag_array) > 0 && count($activityBO->tag_list) > 0) {
                                $tags_old_array = array();
                                foreach ($activityBO->tag_list as $tag_old) {
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
                                for ($i = 0; $i < count($activityBO->tag_list); $i++) {
                                    if (!in_array($activityBO->tag_list[$i]->name, $para->tag_array)) {
                                        $tags_delete_array[] = $activityBO->tag_list[$i];
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
                        $_SESSION["fb_success"][] = UPDATE_ACTIVITY_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_ACTIVITY;
        }
        return FALSE;
    }

    /**
     * updateActivitiesPerPages
     *
     * Update number activities per page
     *
     * @param string $activities_per_page number activities per page
     */
    public function updateActivitiesPerPages($activities_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "activities_per_page";
        $meta_value = $activities_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_activities_columns_show";
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
        if (isset($para->activities_per_page) && is_numeric($para->activities_per_page)) {
            $this->updateActivitiesPerPages($para->activities_per_page);
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
        if (isset($para->activities) && is_array($para->activities)) {
            foreach ($para->activities as $post_id) {
                $this->delete($post_id);
            }
        }
    }

    public function delete($post_id)
    {
        try {
            $activityBO = $this->get($post_id);
            if ($activityBO != NULL) {
                if (parent::delete($post_id)) {
                    if (isset($activityBO->city_id)) {
                        $this->deleteRelationship($post_id, $activityBO->city_id);
                    }
                    if (isset($activityBO->country_id)) {
                        $this->deleteRelationship($post_id, $activityBO->country_id);
                    }
                    if (isset($activityBO->image_id)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->delete($activityBO->image_id);
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
        $activities_per_page = ACTIVITIES_PER_PAGE_DEFAULT;
        $activity = "activity";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->activities_per_page) && is_numeric($userLoginBO->activities_per_page)) {
                $activities_per_page = (int) $userLoginBO->activities_per_page;
            }
        }

        if (!isset($activities_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->activities_per_page = ACTIVITIES_PER_PAGE_DEFAULT;
                $activities_per_page = ACTIVITIES_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->activities_per_page)) {
                $_SESSION['options']->activities_per_page = ACTIVITIES_PER_PAGE_DEFAULT;
                $activities_per_page = ACTIVITIES_PER_PAGE_DEFAULT;
            }
        }

        $view->activities_per_page = $activities_per_page;
        $view->activity = $activity;

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
            $sqlSelectCount = "SELECT COUNT(*) as countActivity ";
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
            $sqlWhere = " WHERE po.`post_type` = 'activity' ";


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
            $countActivity = (int) $sth->fetch()->countActivity;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countActivity > 0) {
                $view->count = $countActivity;

                $view->pageNumber = floor($view->count / $view->activities_per_page);
                if ($view->count % $view->activities_per_page != 0) {
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
                $startActivity = ($page - 1) * $view->activities_per_page;
                $sqlLimit = " LIMIT " . $view->activities_per_page . " OFFSET " . $startActivity;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $activityList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($activityList); $i++) {
                        $activityInfo = $activityList[$i];
                        Model::autoloadModel('image');
                        $imageModel = new ImageModel($this->db);
                        if (isset($activityInfo->image_id)) {
                            $image_object = $imageModel->get($activityInfo->image_id);
                            if ($image_object != NULL) {
                                if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                                    if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->medium->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                        $activityInfo->image_url = $image_object->attachment_metadata->sizes->large->url;
                                    } else {
                                        $activityInfo->image_url = $image_object->guid;
                                    }
                                } else {
                                    $activityInfo->image_url = $image_object->guid;
                                }
                            }
                        }
                        $this->autoloadBO('activity');
                        $activityBO = new ActivityBO();
                        $activityBO->setActivityInfo($activityInfo);
                        $activityBO->setPost($activityInfo);
                        $activityList[$i] = $activityBO;
                    }
                    $view->activityList = $activityList;
                } else {
                    $view->activityList = NULL;
                }
            } else {
                $view->activityList = NULL;
            }
        } catch (Exception $e) {
            $view->activityList = NULL;
        }
    }
}
