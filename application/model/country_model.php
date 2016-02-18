<?php
Model::autoloadModel('taxonomy');

class CountryModel extends TaxonomyModel
{

    public function get($taxonomy_id)
    {
        $countryBO = parent::get($taxonomy_id);
        if ($countryBO != null) {

            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $postBOList = $postModel->getPostRelationshipByTaxonomyId($taxonomy_id, "country");
            if (count($postBOList) != 0) {
                $postBO = $postBOList[0];

                $countryBO->postBO = $postBO;

                if (isset($postBO->post_content)) {
                    $post_content = json_decode($postBO->post_content);

                    if (isset($post_content->overview)) {
                        $countryBO->overview = $post_content->overview;
                    }
                    if (isset($post_content->history)) {
                        $countryBO->history = $post_content->history;
                    }
                    if (isset($post_content->weather)) {
                        $countryBO->weather = $post_content->weather;
                    }
                    if (isset($post_content->passport_visa)) {
                        $countryBO->passport_visa = $post_content->passport_visa;
                    }
                    if (isset($post_content->currency)) {
                        $countryBO->currency = $post_content->currency;
                    }
                    if (isset($post_content->phone_internet_service)) {
                        $countryBO->phone_internet_service = $post_content->phone_internet_service;
                    }
                    if (isset($post_content->transportation)) {
                        $countryBO->transportation = $post_content->transportation;
                    }
                    if (isset($post_content->food_drink)) {
                        $countryBO->food_drink = $post_content->food_drink;
                    }
                    if (isset($post_content->public_holiday)) {
                        $countryBO->public_holiday = $post_content->public_holiday;
                    }
                    if (isset($post_content->predeparture_check_list)) {
                        $countryBO->predeparture_check_list = $post_content->predeparture_check_list;
                    }
                }

                Model::autoloadModel('tag');
                $tagModel = new TagModel($this->db);
                $tagList = $tagModel->getTaxonomyRelationshipByObjectId($postBO->ID, 'tag');
                if ($tagList != NULL && count($tagList) > 0) {
                    $countryBO->tag_list = $tagList;
                }

                if (isset($postBO->image_weather_ids)) {
                    $image_weather_ids = json_decode($postBO->image_weather_ids);
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $countryBO->image_weathers = array();
                    foreach ($image_weather_ids as $image_weather_id) {
                        $image_object = $imageModel->get($image_weather_id);
                        if ($image_object != NULL) {
                            $image_info = new stdClass();
                            $image_info->image_weather_id = $image_weather_id;
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
                                if (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                    $image_info->medium_large_url = $image_object->attachment_metadata->sizes->medium_large->url;
                                }
//                                if (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
//                                    $image_info->large_url = $image_object->attachment_metadata->sizes->large->url;
//                                }
                            }
                            $image_info->image_url = $image_object->guid;
                            if (!isset($image_info->slider_thumb_url)) {
                                $image_info->slider_thumb_url = $image_object->guid;
                            }
                            if (!isset($image_info->medium_large_url)) {
                                $image_info->medium_large_url = $image_object->guid;
                            }
                            $countryBO->image_weathers[] = $image_info;
                        }
                    }
                }
            }
        }
        return $countryBO;
    }

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_COUNTRY;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "country") != FALSE) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "country")) {
                $_SESSION["fb_error"][] = ERROR_SLUG_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_SLUG_EMPTY;
            return false;
        }

        if (!(isset($para->parent) && $para->parent != "" && is_numeric($para->parent))) {
            $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }

        return true;
    }

    /**
     * addToDatabase
     *
     * Add new country
     *
     * @param stdClass $para para for add new country
     */
    public function addContent($countryBO)
    {
        try {
            BO::autoloadBO("post");
            $postBO = new PostBO();

            if (isset($countryBO->name)) {
                $postBO->post_title = $countryBO->name;
            }

            if (isset($countryBO->overview) || isset($countryBO->history) ||
                isset($countryBO->weather) || isset($countryBO->passport_visa) ||
                isset($countryBO->currency) || isset($countryBO->phone_internet_service) ||
                isset($countryBO->transportation) || isset($countryBO->food_drink) ||
                isset($countryBO->public_holiday) || isset($countryBO->predeparture_check_list)) {
                $post_content = new stdClass();

                if (isset($countryBO->overview)) {
                    $post_content->overview = $countryBO->overview;
                }
                if (isset($countryBO->history)) {
                    $post_content->history = $countryBO->history;
                }
                if (isset($countryBO->weather)) {
                    $post_content->weather = $countryBO->weather;
                }
                if (isset($countryBO->passport_visa)) {
                    $post_content->passport_visa = $countryBO->passport_visa;
                }
                if (isset($countryBO->currency)) {
                    $post_content->currency = $countryBO->currency;
                }
                if (isset($countryBO->phone_internet_service)) {
                    $post_content->phone_internet_service = $countryBO->phone_internet_service;
                }
                if (isset($countryBO->transportation)) {
                    $post_content->transportation = $countryBO->transportation;
                }
                if (isset($countryBO->food_drink)) {
                    $post_content->food_drink = $countryBO->food_drink;
                }
                if (isset($countryBO->public_holiday)) {
                    $post_content->public_holiday = $countryBO->public_holiday;
                }
                if (isset($countryBO->predeparture_check_list)) {
                    $post_content->predeparture_check_list = $countryBO->predeparture_check_list;
                }

                $postBO->post_content = json_encode($post_content);
            }
            if (isset($countryBO->name)) {
                $postBO->post_name = Utils::createSlug($countryBO->name);
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
            $postBO->post_type = "country";


            if (isset($countryBO->image_weathers)) {
                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                $imageModel->is_create_thumb = true;
                $imageModel->is_slider_thumb = true;
                $imageModel->is_medium_large = true;
//                $imageModel->slider_thumb_crop = true;
                $image_array_id = $imageModel->uploadImages("image_weathers");

                if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                    $postBO->image_weather_ids = json_encode($image_array_id);
                } else {
                    return FALSE;
                }
            }
            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $post_id = $postModel->addToDatabase($postBO);
            if ($post_id != NULL) {
                if (isset($postBO->image_weather_ids) && $postBO->image_weather_ids != "") {
                    if ($postModel->addMetaInfoToDatabase($post_id, "image_weather_ids", $postBO->image_weather_ids) == NULL) {
                        if (isset($imageModel) && isset($image_array_id)) {
                            foreach ($image_array_id as $image_weather_id) {
                                $imageModel->delete($image_weather_id);
                            }
                        }
                        return FALSE;
                    }
                }

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                if ($taxonomyModel->addRelationshipToDatabase($post_id, $countryBO->term_taxonomy_id, 0) == NULL) {
                    if (isset($imageModel) && isset($image_array_id)) {
                        foreach ($image_array_id as $image_weather_id) {
                            $imageModel->delete($image_weather_id);
                        }
                    }
                    return FALSE;
                }

                if (isset($countryBO->tag_array) && count($countryBO->tag_array) > 0) {
                    Model::autoloadModel('tag');
                    $tagModel = new TagModel($this->db);
                    $tag_id_array = $tagModel->addTagArray($countryBO->tag_array);
                    for ($i = 0; $i < count($tag_id_array); $i++) {
                        $taxonomyModel->addRelationshipToDatabase($post_id, $tag_id_array[$i]);
                    }
                }

                return TRUE;
            } else {
                if (isset($imageModel) && isset($image_weather_id)) {
                    $imageModel->delete($image_weather_id);
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
                BO::autoloadBO("country");
                $countryBO = new CountryBO();

                if (isset($para->name)) {
                    $countryBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $countryBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $countryBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $countryBO->parent = $para->parent;
                }

                if (isset($para->overview)) {
                    $countryBO->overview = $para->overview;
                }
                if (isset($para->history)) {
                    $countryBO->history = $para->history;
                }
                if (isset($para->weather)) {
                    $countryBO->weather = $para->weather;
                }
                if (isset($para->passport_visa)) {
                    $countryBO->passport_visa = $para->passport_visa;
                }
                if (isset($para->currency)) {
                    $countryBO->currency = $para->currency;
                }
                if (isset($para->phone_internet_service)) {
                    $countryBO->phone_internet_service = $para->phone_internet_service;
                }
                if (isset($para->transportation)) {
                    $countryBO->transportation = $para->transportation;
                }
                if (isset($para->food_drink)) {
                    $countryBO->food_drink = $para->food_drink;
                }
                if (isset($para->public_holiday)) {
                    $countryBO->public_holiday = $para->public_holiday;
                }
                if (isset($para->predeparture_check_list)) {
                    $countryBO->predeparture_check_list = $para->predeparture_check_list;
                }
                if (isset($para->tag_list)) {
                    $countryBO->tag_list = $para->tag_list;
                }
                if (isset($para->tag_array)) {
                    $countryBO->tag_array = $para->tag_array;
                }


                if (isset($para->image_weathers)) {
                    $countryBO->image_weathers = $para->image_weathers;
                }
                $countryBO->count = 0;
                $countryBO->term_group = 0;

                $this->db->beginTransaction();
                $countryBO->term_taxonomy_id = parent::addToDatabase($countryBO);

                if ($countryBO->term_taxonomy_id != NULL) {

                    if (isset($countryBO->overview) || isset($countryBO->history) ||
                        isset($countryBO->weather) || isset($countryBO->passport_visa) ||
                        isset($countryBO->currency) || isset($countryBO->phone_internet_service) ||
                        isset($countryBO->transportation) || isset($countryBO->food_drink) ||
                        isset($countryBO->public_holiday) || isset($countryBO->predeparture_check_list)) {


                        $this->addContent($countryBO);
                    }
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_COUNTRY_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_COUNTRY_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_COUNTRY;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of country
     *
     * @param stdClass $para para for update info of country
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
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
        if (!(isset($para->parent) && $para->parent != "" && is_numeric($para->parent))) {
            $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            return false;
        } else {
            $para->parent = (int) $para->parent;
            if ($para->parent < 0) {
                $_SESSION["fb_error"][] = ERROR_PARENT_NOT_IMPOSSIBLE;
            }
        }

        if (isset($para->tag_list) && $para->tag_list != NULL && $para->tag_list != "") {
            $tag_array = explode(",", $para->tag_list);
            $para->tag_array = $tag_array;
        }
        return true;
    }

    public function updateContent($countryBO)
    {
        if (isset($countryBO->postBO)) {
            $postBO = $countryBO->postBO;
            try {
                $sql = "UPDATE " . TABLE_POSTS . " ";
                $set = "SET ";
                $where = " WHERE " . TB_POST_COL_ID . " = :post_id;";

                $para_array = [];
                $para_array[":post_id"] = $postBO->ID;

                if (isset($countryBO->name)) {
                    $postBO->post_title = $countryBO->name;
                    $set .= " " . TB_POST_COL_POST_TITLE . " = :post_title,";
                    $para_array[":post_title"] = $postBO->post_title;
                }

                if (isset($countryBO->overview) || isset($countryBO->history) ||
                    isset($countryBO->weather) || isset($countryBO->passport_visa) ||
                    isset($countryBO->currency) || isset($countryBO->phone_internet_service) ||
                    isset($countryBO->transportation) || isset($countryBO->food_drink) ||
                    isset($countryBO->public_holiday) || isset($countryBO->predeparture_check_list)) {
                    $post_content = new stdClass();

                    if (isset($countryBO->overview)) {
                        $post_content->overview = $countryBO->overview;
                    }
                    if (isset($countryBO->history)) {
                        $post_content->history = $countryBO->history;
                    }
                    if (isset($countryBO->weather)) {
                        $post_content->weather = $countryBO->weather;
                    }
                    if (isset($countryBO->passport_visa)) {
                        $post_content->passport_visa = $countryBO->passport_visa;
                    }
                    if (isset($countryBO->currency)) {
                        $post_content->currency = $countryBO->currency;
                    }
                    if (isset($countryBO->phone_internet_service)) {
                        $post_content->phone_internet_service = $countryBO->phone_internet_service;
                    }
                    if (isset($countryBO->transportation)) {
                        $post_content->transportation = $countryBO->transportation;
                    }
                    if (isset($countryBO->food_drink)) {
                        $post_content->food_drink = $countryBO->food_drink;
                    }
                    if (isset($countryBO->public_holiday)) {
                        $post_content->public_holiday = $countryBO->public_holiday;
                    }
                    if (isset($countryBO->predeparture_check_list)) {
                        $post_content->predeparture_check_list = $countryBO->predeparture_check_list;
                    }

                    $postBO->post_content = json_encode($post_content);
                    $set .= " " . TB_POST_COL_POST_CONTENT . " = :post_content,";
                    $para_array[":post_content"] = $postBO->post_content;
                }
                if (isset($countryBO->name)) {
                    $postBO->post_name = Utils::createSlug($countryBO->name);
                    $set .= " " . TB_POST_COL_POST_NAME . " = :post_name,";
                    $para_array[":post_name"] = $postBO->post_name;
                }

                if (isset($postBO->image_weather_ids)) {
                    $image_weather_ids = json_decode($postBO->image_weather_ids);
                } else {
                    $image_weather_ids = array();
                }

                Model::autoloadModel("image");
                $imageModel = new ImageModel($this->db);
                if (isset($countryBO->image_weathers_upload)) {
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_slider_thumb = true;
                    $imageModel->is_medium_large = true;
//                $imageModel->slider_thumb_crop = true;
                    $image_array_id = $imageModel->uploadImages("image_weathers");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_weather_ids = array_merge($image_weather_ids, $image_array_id);
                    } else {
                        return FALSE;
                    }
                }

                if (isset($countryBO->image_weather_delete_list) && $countryBO->image_weather_delete_list != "" && $countryBO->image_weather_delete_list != NULL) {
                    $image_weather_delete_array = explode(",", $countryBO->image_weather_delete_list);
                    if (count($image_weather_delete_array) > 0) {
                        foreach ($image_weather_delete_array as $image_delete_id) {
                            $image_weather_ids = array_diff($image_weather_ids, [$image_delete_id]);
//                            array_slice($image_weather_ids, $image_delete_id, 1);
//                            array_slice($image_weather_ids, $image_delete_id);
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
                    $image_weather_ids = json_encode($image_weather_ids);
                    if (isset($image_weather_ids) && $image_weather_ids != "") {
                        if (isset($postBO->image_weather_ids)) {
                            if (!$postModel->updateMetaInfoToDatabase($postBO->ID, "image_weather_ids", $image_weather_ids)) {
                                if (isset($imageModel) && isset($image_array_id)) {
                                    foreach ($image_array_id as $image_weather_id) {
                                        $imageModel->delete($image_weather_id);
                                    }
                                }
                                return FALSE;
                            } else { //thanh cong xoa image bi tich bo
                                if (isset($imageModel) && isset($image_weather_delete_array)) {
                                    foreach ($image_weather_delete_array as $image_weather_id) {
                                        $imageModel->delete($image_weather_id);
                                    }
                                }
                            }
                        } else {
                            if (!$postModel->addMetaInfoToDatabase($postBO->ID, "image_weather_ids", $image_weather_ids)) {
                                if (isset($imageModel) && isset($image_array_id)) {
                                    foreach ($image_array_id as $image_weather_id) {
                                        $imageModel->delete($image_weather_id);
                                    }
                                }
                                return FALSE;
                            } else { //thanh cong xoa image bi tich bo
                                if (isset($imageModel) && isset($image_weather_delete_array)) {
                                    foreach ($image_weather_delete_array as $image_weather_id) {
                                        $imageModel->delete($image_weather_id);
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
                $countryBO = $this->get($para->term_taxonomy_id);
                if ($countryBO != NULL) {
                    if (isset($para->name)) {
                        $countryBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $countryBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $countryBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $countryBO->parent = $para->parent;
                    } else {
                        $countryBO->parent = 0;
                    }

                    if (isset($para->image_weather_delete_list)) {
                        $countryBO->image_weather_delete_list = $para->image_weather_delete_list;
                    }

                    if (isset($para->image_weathers)) {
                        $countryBO->image_weathers_upload = $para->image_weathers;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($countryBO)) {
                        if (isset($countryBO->overview) || isset($countryBO->history) || isset($para->overview) || isset($para->history) ||
                            isset($countryBO->weather) || isset($countryBO->passport_visa) || isset($para->weather) || isset($para->passport_visa) ||
                            isset($countryBO->currency) || isset($countryBO->phone_internet_service) || isset($para->currency) || isset($para->phone_internet_service) ||
                            isset($countryBO->transportation) || isset($countryBO->food_drink) || isset($para->transportation) || isset($para->food_drink) ||
                            isset($countryBO->public_holiday) || isset($countryBO->predeparture_check_list) || isset($para->public_holiday) || isset($para->predeparture_check_list)) {

                            if (isset($para->overview)) {
                                $countryBO->overview = $para->overview;
                            }
                            if (isset($para->history)) {
                                $countryBO->history = $para->history;
                            }

                            if (isset($para->weather)) {
                                $countryBO->weather = $para->weather;
                            }
                            if (isset($para->passport_visa)) {
                                $countryBO->passport_visa = $para->passport_visa;
                            }

                            if (isset($para->currency)) {
                                $countryBO->currency = $para->currency;
                            }
                            if (isset($para->phone_internet_service)) {
                                $countryBO->phone_internet_service = $para->phone_internet_service;
                            }

                            if (isset($para->transportation)) {
                                $countryBO->transportation = $para->transportation;
                            }
                            if (isset($para->food_drink)) {
                                $countryBO->food_drink = $para->food_drink;
                            }

                            if (isset($para->public_holiday)) {
                                $countryBO->public_holiday = $para->public_holiday;
                            }
                            if (isset($para->public_holiday)) {
                                $countryBO->public_holiday = $para->public_holiday;
                            }

                            if (isset($para->predeparture_check_list)) {
                                $countryBO->predeparture_check_list = $para->predeparture_check_list;
                            }
                            $this->updateContent($countryBO);


                            if (isset($para->tag_array) || isset($countryBO->tag_list)) {
                                Model::autoloadModel('tag');
                                $tagModel = new TagModel($this->db);
                                Model::autoloadModel('taxonomy');
                                $taxonomyModel = new TaxonomyModel($this->db);
                                if (!isset($para->tag_array) || count($para->tag_array) == 0) {
                                    foreach ($countryBO->tag_list as $tag) {
                                        $tagModel->deleteRelationship($countryBO->postBO->ID, $tag->term_taxonomy_id);
                                    }
                                } elseif (!isset($countryBO->tag_list) || count($countryBO->tag_list) == 0) {
                                    if (count($para->tag_array) > 0) {
                                        $tag_id_array = $tagModel->addTagArray($para->tag_array);
                                        for ($i = 0; $i < count($tag_id_array); $i++) {
                                            $taxonomyModel->addRelationshipToDatabase($countryBO->postBO->ID, $tag_id_array[$i]);
                                        }
                                    }
                                } elseif (isset($para->tag_array) && isset($countryBO->tag_list) &&
                                    count($para->tag_array) > 0 && count($countryBO->tag_list) > 0) {
                                    $tags_old_array = array();
                                    foreach ($countryBO->tag_list as $tag_old) {
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
                                            $taxonomyModel->addRelationshipToDatabase($countryBO->postBO->ID, $tag_id_new_array[$i]);
                                        }
                                    }

                                    $tags_delete_array = array();
                                    for ($i = 0; $i < count($countryBO->tag_list); $i++) {
                                        if (!in_array($countryBO->tag_list[$i]->name, $para->tag_array)) {
                                            $tags_delete_array[] = $countryBO->tag_list[$i];
                                        }
                                    }
                                    if (count($tags_delete_array) > 0) {
                                        foreach ($tags_delete_array as $tag) {
                                            $tagModel->deleteRelationship($countryBO->postBO->ID, $tag->term_taxonomy_id);
                                        }
                                    }
                                }
                            }
                        }

                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_COUNTRY_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
        }
        return FALSE;
    }

    /**
     * updateCountriesPerPages
     *
     * Update number countries per page
     *
     * @param string $countries_per_page number countries per page
     */
    public function updateCountriesPerPages($countries_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "countries_per_page";
        $meta_value = $countries_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_countries_columns_show";
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
        if (isset($para->countries_per_page) && is_numeric($para->countries_per_page)) {
            $this->updateCountriesPerPages($para->countries_per_page);
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
        if (isset($para->countries) && is_array($para->countries)) {
            foreach ($para->countries as $term_taxonomy_id) {
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
        $countries_per_page = COUNTRIES_PER_PAGE_DEFAULT;
        $taxonomy = "country";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->countries_per_page) && is_numeric($userLoginBO->countries_per_page)) {
                $countries_per_page = (int) $userLoginBO->countries_per_page;
            }
        }

        if (!isset($countries_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->countries_per_page = COUNTRIES_PER_PAGE_DEFAULT;
                $countries_per_page = COUNTRIES_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->countries_per_page)) {
                $_SESSION['options']->countries_per_page = COUNTRIES_PER_PAGE_DEFAULT;
                $countries_per_page = COUNTRIES_PER_PAGE_DEFAULT;
            }
        }

        $view->taxonomies_per_page = $countries_per_page;
        $view->taxonomy = $taxonomy;

        parent::search($view, $para);
    }
}
