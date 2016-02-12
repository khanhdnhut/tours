<?php
Model::autoloadModel('post');

class HotelModel extends PostModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new hotel
     *
     * @param stdClass $para para for add new hotel
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->address) && $para->address != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_ADDRESS_EMPTY;
            return false;
        }
        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_HOTEL_CITY_NOT_EXIST;
                return false;
            }
        }
        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_IMAGE_EMPTY;
            return false;
        }

        if (!isset($para->image)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CONTENT_EMPTY;
            return false;
        } else {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_HOTEL_IMAGE_INVALID;
                return false;
            }
        }



        if (isset($para->number_of_rooms) && $para->number_of_rooms != "" && !is_numeric($para->number_of_rooms)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_NUMBER_OF_ROOMS_INVALID;
            return false;
        }

        if (isset($para->star) && $para->star != "" && !is_numeric($para->star)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_STAR_INVALID;
            return false;
        }
        if (isset($para->current_rating) && $para->current_rating != "" && !is_numeric($para->current_rating)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CURRENT_RATING_INVALID;
            return false;
        }

        if (isset($para->vote_times) && $para->vote_times != "" && !is_numeric($para->vote_times)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_VOTE_TIMES_INVALID;
            return false;
        }

        return true;
    }

    /**
     * addToDatabase
     *
     * Add new hotel
     *
     * @param stdClass $para para for add new hotel
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("hotel");
                $hotelBO = new HotelBO();

                if (isset($para->post_title)) {
                    $hotelBO->post_title = $para->post_title;
                }
                if (isset($para->address)) {
                    $hotelBO->address = $para->address;
                }
                if (isset($para->number_of_rooms)) {
                    $hotelBO->number_of_rooms = $para->number_of_rooms;
                }
                if (isset($para->star)) {
                    $hotelBO->star = $para->star;
                }
                if (isset($para->current_rating)) {
                    $hotelBO->current_rating = $para->current_rating;
                }
                if (isset($para->vote_times)) {
                    $hotelBO->vote_times = $para->vote_times;
                }
                if (isset($para->tags)) {
                    $hotelBO->tags = $para->tags;
                }
                if (isset($para->post_content)) {
                    $hotelBO->post_content = $para->post_content;
                }
                if (isset($para->post_name)) {
                    $hotelBO->post_name = $para->post_name;
                }
                if (isset($para->city_id)) {
                    $hotelBO->city_id = $para->city_id;
                }

                $hotelBO->post_author = Session::get("user_id");
                $hotelBO->post_date = date("Y-m-d H:i:s");
                $hotelBO->post_date_gmt = gmdate("Y-m-d H:i:s");
                $hotelBO->post_modified = $hotelBO->post_date;
                $hotelBO->post_modified_gmt = $hotelBO->post_date_gmt;
                $hotelBO->post_parent = 0;
                $hotelBO->post_status = "publish";
                $hotelBO->comment_status = "closed";
                $hotelBO->ping_status = "open";
                $hotelBO->guid = "";

                $this->db->beginTransaction();

                if (isset($para->image)) {
                    Model::autoloadModel("image");
                    $imageModel = new ImageModel($this->db);
                    $imageModel->is_create_thumb = true;
                    $imageModel->is_medium = true;
                    $image_array_id = $imageModel->uploadImages("image");

                    if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                        $image_id = $image_array_id[0];
                        $hotelBO->image_id = $image_id;
                    } else {
                        $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                        $this->db->rollBack();
                        return FALSE;
                    }
                }


                $post_id = parent::addToDatabase($hotelBO);
                if ($post_id != NULL) {
                    $guid = CONTEXT_PATH_HOTEL_VIEW . $post_id . "/" . $hotelBO->post_name . "/";
                    if (!$this->updateGuid($post_id, $guid)) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                        return FALSE;
                    }

                    if (isset($hotelBO->address) && $hotelBO->address != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "address", $hotelBO->address) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }
                    if (isset($hotelBO->number_of_rooms) && $hotelBO->number_of_rooms != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "number_of_rooms", $hotelBO->number_of_rooms) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }
                    if (isset($hotelBO->star) && $hotelBO->star != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "star", $hotelBO->star) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }
                    if (isset($hotelBO->image_id) && $hotelBO->image_id != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "image_id", $hotelBO->image_id) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }
                    if (isset($hotelBO->current_rating) && $hotelBO->current_rating != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "current_rating", $hotelBO->current_rating) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }
                    if (isset($hotelBO->vote_times) && $hotelBO->vote_times != "") {
                        if ($this->addMetaInfoToDatabase($post_id, "vote_times", $hotelBO->vote_times) == NULL) {
                            if (isset($imageModel) && isset($image_id)) {
                                $imageModel->delete($image_id);
                            }
                            $this->db->rollBack();
                            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                            return FALSE;
                        }
                    }

                    if ($this->addRelationshipToDatabase($post_id, $hotelBO->city_id, 0) == NULL) {
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                        return FALSE;
                    }

                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_HOTEL_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
                    if (isset($imageModel) && isset($image_id)) {
                        $imageModel->delete($image_id);
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_HOTEL;
        }
        return FALSE;
    }

    /**
     * validateUpdateInfo
     *
     * Validate para for update info of hotel
     *
     * @param stdClass $para para for update info of hotel
     */
    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
            return false;
        }

        if (!(isset($para->post_title) && $para->post_title != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_TITLE_EMPTY;
            return false;
        }

        $post_name = strtolower(preg_replace('/\s+/', '-', $para->post_title));

        if (!(isset($post_name) && $post_name != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_TITLE_EMPTY;
            return false;
        }

        $para->post_name = $post_name;

        if (!(isset($para->address) && $para->address != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_ADDRESS_EMPTY;
            return false;
        }
        if (!(isset($para->city_id) && $para->city_id != "0")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CITY_EMPTY;
            return false;
        } else {
            Model::autoloadModel("city");
            $cityModel = new CityModel($this->db);
            $cityBO = $cityModel->get($para->city_id);
            if ($cityBO == NULL || !(isset($cityBO->taxonomy) && $cityBO->taxonomy == "city")) {
                $_SESSION["fb_error"][] = ERROR_HOTEL_CITY_NOT_EXIST;
                return false;
            }
        }
        if (!(isset($para->post_content) && $para->post_content != "")) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CONTENT_EMPTY;
            return false;
        }

        if (isset($para->image)) {
            $validExts = array(".jpg", ".png", ".jpeg");
            $fileExt = $para->image['name'];
            $fileExt = strtolower(substr($fileExt, strrpos($fileExt, ".")));
            if (!in_array($fileExt, $validExts)) {
                $_SESSION["fb_error"][] = ERROR_HOTEL_IMAGE_INVALID;
                return false;
            }
        }

        if (isset($para->number_of_rooms) && $para->number_of_rooms != "" && !is_numeric($para->number_of_rooms)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_NUMBER_OF_ROOMS_INVALID;
            return false;
        }

        if (isset($para->star) && $para->star != "" && !is_numeric($para->star)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_STAR_INVALID;
            return false;
        }
        if (isset($para->current_rating) && $para->current_rating != "" && !is_numeric($para->current_rating)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_CURRENT_RATING_INVALID;
            return false;
        }

        if (isset($para->vote_times) && $para->vote_times != "" && !is_numeric($para->vote_times)) {
            $_SESSION["fb_error"][] = ERROR_HOTEL_VOTE_TIMES_INVALID;
            return false;
        }

        return true;
    }

    public function get($post_id)
    {
        try {
            $hotelBO = parent::get($post_id);
            if ($hotelBO != NULL) {
                Model::autoloadModel("taxonomy");
                $taxonomyModel = new TaxonomyModel($this->db);
                $cityList = $taxonomyModel->getRelationship($post_id, "city");
                if (count($cityList) > 0) {
                    $cityBO = $cityList[0];
                    $hotelBO->city_name = $cityBO->name;
                    $hotelBO->city_id = $cityBO->term_taxonomy_id;
                }

                if (isset($hotelBO->image_id) && $hotelBO->image_id != "" && is_numeric($hotelBO->image_id)) {
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $image_object = $imageModel->get($hotelBO->image_id);
                    if ($image_object != NULL) {
                        if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                            if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->medium->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                            } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                $hotelBO->image_url = $image_object->attachment_metadata->sizes->large->url;
                            } else {
                                $hotelBO->image_url = $image_object->guid;
                            }
                        } else {
                            $hotelBO->image_url = $image_object->guid;
                        }
                    }
                }

                return $hotelBO;
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
     * Update info of hotel
     *
     * @param stdClass $para para for update info of hotel
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $hotelBO = $this->get($para->post_id);
                if ($hotelBO != NULL) {
                    if (isset($para->post_title) && $hotelBO->post_title != $para->post_title) {
                        $hotelBO->post_title = $para->post_title;
                    }


//                    if (isset($para->tags) && $hotelBO->tags != $para->tags) {
//                        $hotelBO->tags = $para->tags;
//                    }
                    if (isset($para->post_content) && $hotelBO->post_content != $para->post_content) {
                        $hotelBO->post_content = $para->post_content;
                    }
                    if (isset($para->post_name) && $hotelBO->post_name != $para->post_name) {
                        $hotelBO->post_name = $para->post_name;
                    }

                    $hotelBO->post_modified = date("Y-m-d H:i:s");
                    $hotelBO->post_modified_gmt = gmdate("Y-m-d H:i:s");

                    $this->db->beginTransaction();


                    if (isset($para->image)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->is_create_thumb = true;
                        $imageModel->is_medium = true;
                        $image_array_id = $imageModel->uploadImages("image");

                        if (!is_null($image_array_id) && is_array($image_array_id) && sizeof($image_array_id) != 0) {
                            $image_id = $image_array_id[0];
                            $image_id_old = $hotelBO->image_id;
                        } else {
                            $_SESSION["fb_error"][] = ERROR_UPLOAD_IMAGE_FAILED;
                            $this->db->rollBack();
                            return FALSE;
                        }
                    }

                    if ($this->update($hotelBO)) {

                        $guid = CONTEXT_PATH_HOTEL_VIEW . $para->post_id . "/" . $hotelBO->post_name . "/";

                        if ((isset($hotelBO->guid) && $hotelBO->guid != $guid) || !isset($hotelBO->guid)) {
                            $hotelBO->guid = $guid;
                            if (!$this->updateGuid($para->post_id, $guid)) {
                                $this->db->rollBack();
                                if (isset($imageModel) && isset($image_id)) {
                                    $imageModel->delete($image_id);
                                }
                                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                return FALSE;
                            }
                        }

                        if (isset($para->address)) {
                            if (!isset($hotelBO->address)) {
                                $hotelBO->address = $para->address;
                                if ($this->addMetaInfoToDatabase($para->post_id, "address", $hotelBO->address) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->address != $para->address) {
                                $hotelBO->address = $para->address;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "address", $hotelBO->address)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->number_of_rooms)) {
                            if (!isset($hotelBO->number_of_rooms)) {
                                $hotelBO->number_of_rooms = $para->number_of_rooms;
                                if ($this->addMetaInfoToDatabase($para->post_id, "number_of_rooms", $hotelBO->number_of_rooms) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->number_of_rooms != $para->number_of_rooms) {
                                $hotelBO->number_of_rooms = $para->number_of_rooms;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "number_of_rooms", $hotelBO->number_of_rooms)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($image_id)) {
                            if (!isset($hotelBO->image_id)) {
                                $hotelBO->image_id = $image_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "image_id", $hotelBO->image_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->image_id != $image_id) {
                                $hotelBO->image_id = $image_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "image_id", $hotelBO->image_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($image_id)) {
                                        $imageModel->delete($image_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->current_rating)) {
                            if (!isset($hotelBO->current_rating)) {
                                $hotelBO->current_rating = $para->current_rating;
                                if ($this->addMetaInfoToDatabase($para->post_id, "current_rating", $hotelBO->current_rating) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->current_rating != $para->current_rating) {
                                $hotelBO->current_rating = $para->current_rating;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "current_rating", $hotelBO->current_rating)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($current_rating)) {
                                        $imageModel->delete($current_rating);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->vote_times)) {
                            if (!isset($hotelBO->vote_times)) {
                                $hotelBO->vote_times = $para->vote_times;
                                if ($this->addMetaInfoToDatabase($para->post_id, "vote_times", $hotelBO->vote_times) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->vote_times != $para->vote_times) {
                                $hotelBO->vote_times = $para->vote_times;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "vote_times", $hotelBO->vote_times)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($vote_times)) {
                                        $imageModel->delete($vote_times);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }


                        if (isset($para->star)) {
                            if (!isset($hotelBO->star)) {
                                $hotelBO->star = $para->star;
                                if ($this->addMetaInfoToDatabase($para->post_id, "star", $hotelBO->star) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($star)) {
                                        $imageModel->delete($star);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->star != $para->star) {
                                $hotelBO->star = $para->star;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "star", $hotelBO->star)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($star)) {
                                        $imageModel->delete($star);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        if (isset($para->city_id)) {
                            if (!isset($hotelBO->city_id)) {
                                $hotelBO->city_id = $para->city_id;
                                if ($this->addMetaInfoToDatabase($para->post_id, "city_id", $hotelBO->city_id) == NULL) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            } else if ($hotelBO->city_id != $para->city_id) {
                                $hotelBO->city_id = $para->city_id;
                                if (!$this->updateMetaInfoToDatabase($para->post_id, "city_id", $hotelBO->city_id)) {
                                    $this->db->rollBack();
                                    if (isset($imageModel) && isset($city_id)) {
                                        $imageModel->delete($city_id);
                                    }
                                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                                    return FALSE;
                                }
                            }
                        }

                        $this->db->commit();
                        if (isset($imageModel) && isset($image_id) && isset($image_id_old)) {
                            $imageModel->delete($image_id_old);
                        }
                        $_SESSION["fb_success"][] = UPDATE_HOTEL_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        if (isset($imageModel) && isset($image_id)) {
                            $imageModel->delete($image_id);
                        }
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_HOTEL;
        }
        return FALSE;
    }

    /**
     * updateHotelsPerPages
     *
     * Update number hotels per page
     *
     * @param string $hotels_per_page number hotels per page
     */
    public function updateHotelsPerPages($hotels_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "hotels_per_page";
        $meta_value = $hotels_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_hotels_columns_show";
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
        if (isset($para->hotels_per_page) && is_numeric($para->hotels_per_page)) {
            $this->updateHotelsPerPages($para->hotels_per_page);
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
        if (isset($para->hotels) && is_array($para->hotels)) {
            foreach ($para->hotels as $post_id) {
                $this->delete($post_id);
            }
        }
    }

    public function delete($post_id)
    {
        try {
            $hotelBO = $this->get($post_id);
            if ($hotelBO != NULL) {
                if (parent::delete($post_id)) {
                    if (isset($hotelBO->city_id)) {
                        $this->deleteRelationship($post_id, $hotelBO->city_id);
                    }
                    if (isset($hotelBO->image_id)) {
                        Model::autoloadModel("image");
                        $imageModel = new ImageModel($this->db);
                        $imageModel->delete($hotelBO->image_id);
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
        $hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
        $hotel = "hotel";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->hotels_per_page) && is_numeric($userLoginBO->hotels_per_page)) {
                $hotels_per_page = (int) $userLoginBO->hotels_per_page;
            }
        }

        if (!isset($hotels_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
                $hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->hotels_per_page)) {
                $_SESSION['options']->hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
                $hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
            }
        }

        $view->hotels_per_page = $hotels_per_page;
        $view->hotel = $hotel;

        try {
            $paraSQL = [];
            $sqlSelectAll = "SELECT DISTINCT 
                                ad.`meta_value` AS address,
                                nu.`meta_value` AS number_of_rooms,
                                st.`meta_value` AS star,
                                cu.`meta_value` AS current_rating,
                                vo.`meta_value` AS vote_times,
                                im.`meta_value` AS image_id,
                                te.`name` AS city_name,
                                ta.`term_taxonomy_id` AS city_id,
                                po.*  ";
            $sqlSelectCount = "SELECT COUNT(*) as countHotel ";
            //para: orderby, order, page, s, paged, countries, new_role, new_role2, action, action2
            $sqlFrom = " FROM
                            posts AS po
                            LEFT JOIN `postmeta` AS ad ON po.`ID` = ad.`post_id` AND ad.`meta_key` = 'address'   
                            LEFT JOIN `postmeta` AS nu ON po.`ID` = nu.`post_id` AND nu.`meta_key` = 'number_of_rooms' 
                            LEFT JOIN `postmeta` AS st ON po.`ID` = st.`post_id` AND st.`meta_key` = 'star' 
                            LEFT JOIN `postmeta` AS cu ON po.`ID` = cu.`post_id` AND cu.`meta_key` = 'current_rating' 
                            LEFT JOIN `postmeta` AS vo ON po.`ID` = vo.`post_id` AND vo.`meta_key` = 'vote_times' 
                            LEFT JOIN `postmeta` AS im ON po.`ID` = im.`post_id` AND im.`meta_key` = 'image_id'   
                            LEFT JOIN `term_relationships` AS re ON po.`ID` = re.`object_id` 
                            LEFT JOIN `term_taxonomy` AS ta ON re.`term_taxonomy_id` = ta.`term_taxonomy_id` AND ta.`taxonomy` = 'city' 
                            LEFT JOIN `terms` AS te ON ta.`term_id` = te.`term_id`  ";
            $sqlWhere = " WHERE po.`post_type` = 'hotel' ";


            if (isset($para->s) && strlen(trim($para->s)) > 0) {
                $sqlWhere .= " AND (
                                po.`post_content` LIKE :s 
                                OR po.`post_name` LIKE :s 
                                OR po.`post_title` LIKE :s 
                                OR te.`name` LIKE :s
                              ) ";
                $paraSQL[':s'] = "%" . $para->s . "%";
                $view->s = $para->s;
            }

            $view->orderby = "name";
            $view->order = "asc";

            if (isset($para->orderby) && in_array($para->orderby, array("post_title", "address", "city_name",
                    "star", "number_of_rooms", "current_rating", "vote_times"))) {
                switch ($para->orderby) {
                    case "post_title":
                        $para->orderby = "post_title";
                        $view->orderby = "post_title";
                        break;
                    case "address":
                        $para->orderby = "address";
                        $view->orderby = "address";
                        break;
                    case "number_of_rooms":
                        $para->orderby = "number_of_rooms";
                        $view->orderby = "number_of_rooms";
                        break;
                    case "star":
                        $para->orderby = "star";
                        $view->orderby = "star";
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
                }

                if (isset($para->order) && in_array($para->order, array("desc", "asc"))) {
                    $view->order = $para->order;
                } else {
                    $para->order = "asc";
                    $view->order = "asc";
                }
                $sqlOrderby = " ORDER BY " . $para->orderby . " " . $para->order;
            } else {
                $sqlOrderby = " ORDER BY " . TB_TERMS_COL_NAME . " ASC";
            }

            $sqlCount = $sqlSelectCount . $sqlFrom . $sqlWhere;
            $sth = $this->db->prepare($sqlCount);
            $sth->execute($paraSQL);
            $countHotel = (int) $sth->fetch()->countHotel;
            $view->pageNumber = 0;
            $view->page = 1;

            $sqlLimit = "";
            if ($countHotel > 0) {
                $view->count = $countHotel;

                $view->pageNumber = floor($view->count / $view->hotels_per_page);
                if ($view->count % $view->hotels_per_page != 0) {
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
                $startHotel = ($page - 1) * $view->hotels_per_page;
                $sqlLimit = " LIMIT " . $view->hotels_per_page . " OFFSET " . $startHotel;

                $sqlAll = $sqlSelectAll . $sqlFrom . $sqlWhere . $sqlOrderby . $sqlLimit;
                $sth = $this->db->prepare($sqlAll);
                $sth->execute($paraSQL);
                $count = $sth->rowCount();
                if ($count > 0) {
                    $hotelList = $sth->fetchAll();
                    for ($i = 0; $i < sizeof($hotelList); $i++) {
                        $hotelInfo = $hotelList[$i];
                        Model::autoloadModel('image');
                        $imageModel = new ImageModel($this->db);
                        if (isset($hotelInfo->image_id)) {
                            $image_object = $imageModel->get($hotelInfo->image_id);
                            if ($image_object != NULL) {
                                if (isset($image_object->attachment_metadata) && isset($image_object->attachment_metadata->sizes)) {
                                    if (isset($image_object->attachment_metadata->sizes->slider_thumb) && isset($image_object->attachment_metadata->sizes->slider_thumb->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->slider_thumb->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->thumbnail) && isset($image_object->attachment_metadata->sizes->thumbnail->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->post_thumbnail) && isset($image_object->attachment_metadata->sizes->post_thumbnail->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->post_thumbnail->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium) && isset($image_object->attachment_metadata->sizes->medium->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->medium->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->medium_large) && isset($image_object->attachment_metadata->sizes->medium_large->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->medium_large->url;
                                    } elseif (isset($image_object->attachment_metadata->sizes->large) && isset($image_object->attachment_metadata->sizes->large->url)) {
                                        $hotelInfo->image_url = $image_object->attachment_metadata->sizes->large->url;
                                    } else {
                                        $hotelInfo->image_url = $image_object->guid;
                                    }
                                } else {
                                    $hotelInfo->image_url = $image_object->guid;
                                }
                            }
                        }
                        $this->autoloadBO('hotel');
                        $hotelBO = new HotelBO();
                        $hotelBO->setHotelInfo($hotelInfo);
                        $hotelBO->setPost($hotelInfo);
                        $hotelList[$i] = $hotelBO;
                    }
                    $view->hotelList = $hotelList;
                } else {
                    $view->hotelList = NULL;
                }
            } else {
                $view->hotelList = NULL;
            }
        } catch (Exception $e) {
            $view->hotelList = NULL;
        }
    }
}
