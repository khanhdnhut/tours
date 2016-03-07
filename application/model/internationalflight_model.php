<?php
Model::autoloadModel('post');

class InternationalflightModel extends PostModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new internationalflight
     *
     * @param stdClass $para para for add new internationalflight
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        
        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CITY_NOT_EXIST;
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
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_IMAGE_EMPTY;
            return false;
        }

        if (!isset($para->image)) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CONTENT_EMPTY;
            return false;
        } else {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_IMAGE_INVALID;
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
     * Add new internationalflight
     *
     * @param stdClass $para para for add new internationalflight
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("internationalflight");
                $internationalflightBO = new InternationalflightBO();

                if (isset($para->post_title)) {
                    $internationalflightBO->post_title = $para->post_title;
                }
                if (isset($para->current_rating)) {
                    $internationalflightBO->current_rating = $para->current_rating;
                }
                if (isset($para->vote_times)) {
                    $internationalflightBO->vote_times = $para->vote_times;
                }
                if (isset($para->tag_list)) {
                    $internationalflightBO->tag_list = $para->tag_list;
                }
                if (isset($para->post_content)) {
                    $internationalflightBO->post_content = $para->post_content;
                }
                if (isset($para->post_name)) {
                    $internationalflightBO->post_name = $para->post_name;
                }
                if (isset($para->city_id)) {
                    $internationalflightBO->city_id = $para->city_id;
                }
                if (isset($para->country_id)) {
                    $internationalflightBO->country_id = $para->country_id;
                }

                $internationalflightBO->post_author = Session::get("user_id");
                $internationalflightBO->post_date = date("Y-m-d H:i:s");
                $internationalflightBO->post_date_gmt = gmdate("Y-m-d H:i:s");
                $internationalflightBO->post_modified = $internationalflightBO->post_date;
                $internationalflightBO->post_modified_gmt = $internationalflightBO->post_date_gmt;
                $internationalflightBO->post_parent = 0;
                $internationalflightBO->post_status = "publish";
                $internationalflightBO->comment_status = "closed";
                $internationalflightBO->ping_status = "open";
                $internationalflightBO->guid = "";

                $this->db->beginTransaction();

                if (isset($para->image)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_medium = true;
                    $image_array_id = $imageModel->uploadImages("image");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_id = $image_array_id[0];
                        $internationalflightBO->image_id = $image_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                        $this->db->rollBack();
                        return FALSE;
                    }
                }

                $post_id = parent::addToDatabase($internationalflightBO);
                if ($post_id != NULL) {
                    $guid = CONTEXT_PATH_INTERNATIONALFLIGHT_VIEW . $post_id . "/" . $internationalflightBO->post_name . "/";
                    if (!$this->updateGuid($post_id, $guid)) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                        return FALSE;
                    }

                    if (isset($internationalflightBO->image_id) && $internationalflightBO->image_id != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "image_id", $internationalflightBO->image_id) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                            return FALSE;
                        }
                    }
                    if (isset($internationalflightBO->current_rating) && $internationalflightBO->current_rating != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "current_rating", $internationalflightBO->current_rating) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                            return FALSE;
                        }
                    }
                    if (isset($internationalflightBO->vote_times) && $internationalflightBO->vote_times != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "vote_times", $internationalflightBO->vote_times) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                            return FALSE;
                        }
                    }

                    Model::autoloadModel('taxonomy');
                    $taxonomyModel = new TaxonomyModel($this->db);

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $internationalflightBO->city_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                        return FALSE;
                    }

                    if ($taxonomyModel->addRelationshipToDatabase($post_id, $internationalflightBO->country_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
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
                    $_SESSION["fb_success"][] = ADD_INTERNATIONALFLIGHT_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
                    if (isset($imageModel) && isset($image_id)) {
                        $imageModel->delete($image_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_INTERNATIONALFLIGHT;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of internationalflight
     *
     * @param stdClass $para para for update info of internationalflight
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CITY_NOT_EXIST;
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
            $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_CONTENT_EMPTY;
            return false;
        }

        if (isset($para->image)) {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_INTERNATIONALFLIGHT_IMAGE_INVALID;
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
            $internationalflightBO = parent::get($post_id);
            if ($internationalflightBO != NULL) {
                Model::autoloadModel("taxonomy");
                $taxonomyModel = new TaxonomyModel($this->db);

                $cityList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "city");
                if (count($cityList) > 0) {
                    $cityBO = $cityList[0];
                    $internationalflightBO->city_name = $cityBO->name;
                    $internationalflightBO->city_id = $cityBO->term_taxonomy_id;
                }
                
                $countryList = $taxonomyModel->getTaxonomyRelationshipByObjectId($post_id, "country");
                if (count($countryList) > 0) {
                    $countryBO = $countryList[0];
                    $internationalflightBO->country_name = $countryBO->name;
                    $internationalflightBO->country_id = $countryBO->term_taxonomy_id;
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($post_id, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $internationalflightBO->tag_list = $tagList;
                }

                if (isset($internationalflightBO->image_id) && $internationalflightBO->image_id != "" && is_numeric($internationalflightBO->image_id)) {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $image_object = $imageModel->get($internationalflightBO->image_id);
                    if ($image_object != NULL) {
                        if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                            if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->medium->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                $internationalflightBO->image_url = $image_object->attachment_metadata->sizes->large->url;
                            } else {
                                $internationalflightBO->image_url = $image_object->guid;
                            }
                        } else {
                            $internationalflightBO->image_url = $image_object->guid;
                        }
                    }
                }

                return $internationalflightBO;
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
     * Update info of internationalflight
     *
     * @param stdClass $para para for update info of internationalflight
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $internationalflightBO = $this->get($para->post_id);
                if ($internationalflightBO != NULL) {
                    if (isset($para->post_title) && $internationalflightBO->post_title != $para->post_title) {
                        $internationalflightBO->post_title = $para->post_title;
                    }

                    if (isset($para->post_content) && $internationalflightBO->post_content != $para->post_content) {
                        $internationalflightBO->post_content = $para->post_content;
                    }
                    if (isset($para->post_name) && $internationalflightBO->post_name != $para->post_name) {
                        $internationalflightBO->post_name = $para->post_name;
                    }

                    $internationalflightBO->post_modified = date("Y-m-d H:i:s");
                    $internationalflightBO->post_modified_gmt = gmdate("Y-m-d H:i:s");

                    $this->db->beginTransaction();


                    if (isset($para->image)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->is_create_thumb = true;
                        $imageModel->is_medium = true;
                        $image_array_id = $imageModel->uploadImages("image");

                        if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                            $image_id = $image_array_id[0];
                            $image_id_old = $internationalflightBO->image_id;
                        } else {
                            $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                            $this->db->rollBack();
                            return FALSE;
                        }
                    }

                    if ($this->update($internationalflightBO)) {

                        $guid = CONTEXT_PATH_INTERNATIONALFLIGHT_VIEW . $para->post_id . "/" . $internationalflightBO->post_name . "/";

                        if ((isset($internationalflightBO->guid) && $internationalflightBO->guid != $guid) || !isset($internationalflightBO->guid)) {
                            $internationalflightBO->guid = $guid;
                            if (!$this->updateGuid($para->post_id, $guid)) {
                                $this->db->rollBack();
                                if (isset($imageModel) && isset($image_id)) {
                                    $imageModel->delete($image_id);
                                }
                                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                return FALSE;
                            }
                        }

                        if (isset($image_id)) {
                            if (!isset($internationalflightBO->image_id)) {
                                $internationalflightBO->image_id = $image_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "image_id", $internationalflightBO->image_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            } else if ($internationalflightBO->image_id != $image_id) {
                                $internationalflightBO->image_id = $image_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "image_id", $internationalflightBO->image_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->current_rating)) {
                            if (!isset($internationalflightBO->current_rating)) {
                                $internationalflightBO->current_rating = $para->current_rating;
                                if ($this->addMetaInfoToDatabase($para->post_id, "current_rating", $internationalflightBO->current_rating) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            } else if ($internationalflightBO->current_rating != $para->current_rating) {
                                $internationalflightBO->current_rating = $para->current_rating;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "current_rating", $internationalflightBO->current_rating)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->vote_times)) {
                            if (!isset($internationalflightBO->vote_times)) {
                                $internationalflightBO->vote_times = $para->vote_times;
                                if ($this->addMetaInfoToDatabase($para->post_id, "vote_times", $internationalflightBO->vote_times) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            } else if ($internationalflightBO->vote_times != $para->vote_times) {
                                $internationalflightBO->vote_times = $para->vote_times;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "vote_times", $internationalflightBO->vote_times)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->city_id)) {
                            if (!isset($internationalflightBO->city_id)) {
                                $internationalflightBO->city_id = $para->city_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "city_id", $internationalflightBO->city_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            } else if ($internationalflightBO->city_id != $para->city_id) {
                                $internationalflightBO->city_id = $para->city_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "city_id", $internationalflightBO->city_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            }
                        }
                        
                        if (isset($para->country_id)) {
                            if (!isset($internationalflightBO->country_id)) {
                                $internationalflightBO->country_id = $para->country_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "country_id", $internationalflightBO->country_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            } else if ($internationalflightBO->country_id != $para->country_id) {
                                $internationalflightBO->country_id = $para->country_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "country_id", $internationalflightBO->country_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($country_id)) {
                                        $imageModel->delete($country_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->tag_array) || isset($internationalflightBO->tag_list)) {
                            Model::autoloadModel('tag');
                            $tagModel = new TagModel($this->db);
                            Model::autoloadModel('taxonomy');
                            $taxonomyModel = new TaxonomyModel($this->db);
                            if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                foreach ($internationalflightBO->tag_list as $tag) {
                                    $tagModel->deleteRelationship($para->post_id, $tag->term_taxonomy_id);
                                }
                            } elseif (!isset($internationalflightBO->tag_list) || count($internationalflightBO->tag_list) == 0) {
                                if (count($para->tag_array) > 0) {
                                    $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                    for ($i = 0; $i < count($tag_id_array); $i++) {
                                        $taxonomyModel->addRelationshipToDatabase($para->post_id, $tag_id_array[$i]);
                                    }
                                }
                            } elseif (isset($para->tag_array) && isset($internationalflightBO->tag_list) &&
                                count($para->tag_array) > 0 && count($internationalflightBO->tag_list) > 0) {
                                $tags_old_array = array();
                                foreach ($internationalflightBO->tag_list as $tag_old) {
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
                                for ($i = 0; $i < count($internationalflightBO->tag_list); $i++) {
                                    if (!in_array($internationalflightBO->tag_list[$i]->name, $para->tag_array)) {
                                        $tags_delete_array[] = $internationalflightBO->tag_list[$i];
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
                        $_SESSION["fb_success"][] = UPDATE_INTERNATIONALFLIGHT_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_INTERNATIONALFLIGHT;
        }
        return FALSE;
    }

    /**
     * updateInternationalflightsPerPages
     *
     * Update number internationalflights per page
     *
     * @param string $internationalflights_per_page number internationalflights per page
     */
    public function updateInternationalflightsPerPages($internationalflights_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "internationalflights_per_page";
        $meta_value = $internationalflights_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_internationalflights_columns_show";
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
        if (isset($para->internationalflights_per_page) && is_numeric($para->internationalflights_per_page)) {
            $this->updateInternationalflightsPerPages($para->internationalflights_per_page);
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
        if (isset($para->internationalflights) && is_array($para->internationalflights)) {
            foreach ($para->internationalflights as $post_id) {
                $this->delete($post_id);
            }
        }
    }

    public function delete($post_id)
    {
        try {
            $internationalflightBO = $this->get($post_id);
            if ($internationalflightBO != NULL) {
                if (parent::delete($post_id)) {
                    if (isset($internationalflightBO->city_id)) {
                        $this->deleteRelationship($post_id, $internationalflightBO->city_id);
                    }
                    if (isset($internationalflightBO->country_id)) {
                        $this->deleteRelationship($post_id, $internationalflightBO->country_id);
                    }
                    if (isset($internationalflightBO->image_id)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->delete($internationalflightBO->image_id);
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
        $internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
        $internationalflight = "internationalflight";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->internationalflights_per_page) && is_numeric($userLoginBO->internationalflights_per_page)) {
                $internationalflights_per_page = (int) $userLoginBO->internationalflights_per_page;
            }
        }

        if (!isset($internationalflights_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
                $internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->internationalflights_per_page)) {
                $_SESSION['options']->internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
                $internationalflights_per_page = INTERNATIONALFLIGHTS_PER_PAGE_DEFAULT;
            }
        }

        $view->internationalflights_per_page = $internationalflights_per_page;
        $view->internationalflight = $internationalflight;

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
            $sqlSelectCount = "SELECT COUNT(*) as countInternationalflight ";
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
            $sqlWhere = " WHERE po.`post_type` = 'internationalflight' ";


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
            $countInternationalflight = (int) $sth->fetch()->countInternationalflight;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countInternationalflight > 0) {
                $view->count = $countInternationalflight;

                $view->pageNumber = floor($view->count / $view->internationalflights_per_page);
                if ($view->count % $view->internationalflights_per_page != 0) {
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
                $startInternationalflight = ($page - 1) * $view->internationalflights_per_page;
                $sqlLimit = " LIMIT " . $view->internationalflights_per_page . " OFFSET " . $startInternationalflight;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $internationalflightList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($internationalflightList); $i++) {
                        $internationalflightInfo = $internationalflightList[$i];
                        Model::autoloadModel('image');
                        $imageModel = new ImageModel($this->db);
                        if (isset($internationalflightInfo->image_id)) {
                            $image_object = $imageModel->get($internationalflightInfo->image_id);
                            if ($image_object != NULL) {
                                if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                                    if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->medium->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                        $internationalflightInfo->image_url = $image_object->attachment_metadata->sizes->large->url;
                                    } else {
                                        $internationalflightInfo->image_url = $image_object->guid;
                                    }
                                } else {
                                    $internationalflightInfo->image_url = $image_object->guid;
                                }
                            }
                        }
                        $this->autoloadBO('internationalflight');
                        $internationalflightBO = new InternationalflightBO();
                        $internationalflightBO->setInternationalflightInfo($internationalflightInfo);
                        $internationalflightBO->setPost($internationalflightInfo);
                        $internationalflightList[$i] = $internationalflightBO;
                    }
                    $view->internationalflightList = $internationalflightList;
                } else {
                    $view->internationalflightList = NULL;
                }
            } else {
                $view->internationalflightList = NULL;
            }
        } catch (Exception $e) {
            $view->internationalflightList = NULL;
        }
    }
}
