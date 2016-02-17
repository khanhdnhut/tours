<?php
Model::autoloadModel('taxonomy');

class DestinationModel extends TaxonomyModel
{

    public function get($taxonomy_id)
    {
        $destinationBO = parent::get($taxonomy_id);
        if ($destinationBO != null) {

            Model::autoloadModel("post");
            $postModel = new PostModel($this->db);
            $postBOList = $postModel->getPostRelationshipByTaxonomyId($taxonomy_id, "destination");
            if (count($postBOList) != 0) {
                $postBO = $postBOList[0];
                if (isset($postBO->post_content)) {
                    $post_content = json_decode($postBO->post_content);
                    if (isset($post_content->post_content_1)) {
                        $destinationBO->post_content_1 = $post_content->post_content_1;
                    }
                    if (isset($post_content->post_content_2)) {
                        $destinationBO->post_content_2 = $post_content->post_content_2;
                    }
                }

                if (isset($postBO->image_ids)) {
                    $image_ids = json_decode($postBO->image_ids);
                    Model::autoloadModel('image');
                    $imageModel = new ImageModel($this->db);
                    $destinationBO->images = array();
                    foreach ($image_ids as $image_id) {
                        $image_object = $imageModel->get($image_id);
                        if ($image_object != NULL) {
                            $image_info = new stdClass();

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
                            $destinationBO->images[] = $image_info;
                        }
                    }
                }
            }
        }
        return $destinationBO;
    }

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_DESTINATION;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "destination") != FALSE) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "destination")) {
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

        return true;
    }

    /**
     * addContent
     *
     * Add new destination
     *
     * @param stdClass $destinationBO para for add new destination
     */
    public function addContent($destinationBO)
    {
        try {
            BO::autoloadBO("post");
            $postBO = new PostBO();

            if (isset($destinationBO->name)) {
                $postBO->post_title = $destinationBO->name;
            }
            if (isset($destinationBO->post_content_1) || isset($destinationBO->post_content_2)) {
                $post_content = new stdClass();
                if (isset($destinationBO->post_content_1)) {
                    $post_content->post_content_1 = $destinationBO->post_content_1;
                }
                if (isset($destinationBO->post_content_2)) {
                    $post_content->post_content_2 = $destinationBO->post_content_2;
                }
                $postBO->post_content = json_encode($post_content);
            }
            if (isset($destinationBO->post_name)) {
                $postBO->post_name = $destinationBO->name;
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
            $postBO->post_type = "destination";


            if (isset($destinationBO->images)) {
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

                if ($taxonomyModel->addRelationshipToDatabase($post_id, $destinationBO->term_taxonomy_id, 0) == NULL) {
                    if (isset($imageModel) && isset($image_array_id)) {
                        foreach ($image_array_id as $image_id) {
                            $imageModel->delete($image_id);
                        }
                    }
                    return FALSE;
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
                BO::autoloadBO("destination");
                $destinationBO = new DestinationBO();

                if (isset($para->name)) {
                    $destinationBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $destinationBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $destinationBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $destinationBO->parent = $para->parent;
                }
                if (isset($para->post_content_1)) {
                    $destinationBO->post_content_1 = $para->post_content_1;
                }
                if (isset($para->post_content_2)) {
                    $destinationBO->post_content_2 = $para->post_content_2;
                }
                if (isset($para->images)) {
                    $destinationBO->images = $para->images;
                }
                $destinationBO->count = 0;
                $destinationBO->term_group = 0;

                $this->db->beginTransaction();
                $destinationBO->term_taxonomy_id = parent::addToDatabase($destinationBO);

                if ($destinationBO->term_taxonomy_id != NULL) {
                    if (isset($destinationBO->post_content_1) || isset($destinationBO->post_content_2)) {
                        $this->addContent($destinationBO);
                    }
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_DESTINATION_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_DESTINATION_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_DESTINATION;
        }
        return FALSE;
    }

    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_DESTINATION;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_DESTINATION;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_DESTINATION;
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
        return true;
    }

    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $destinationBO = $this->get($para->term_taxonomy_id);
                if ($destinationBO != NULL) {
                    if (isset($para->name)) {
                        $destinationBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $destinationBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $destinationBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $destinationBO->parent = $para->parent;
                    } else {
                        $destinationBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($destinationBO)) {
                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_DESTINATION_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_DESTINATION;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_DESTINATION;
        }
        return FALSE;
    }

    public function updateDestinationsPerPages($destinations_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "destinations_per_page";
        $meta_value = $destinations_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_destinations_columns_show";
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
        if (isset($para->destinations_per_page) && is_numeric($para->destinations_per_page)) {
            $this->updateDestinationsPerPages($para->destinations_per_page);
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
        if (isset($para->destinations) && is_array($para->destinations)) {
            foreach ($para->destinations as $term_taxonomy_id) {
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
        $destinations_per_page = DESTINATIONS_PER_PAGE_DEFAULT;
        $taxonomy = "destination";

        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->destinations_per_page) && is_numeric($userLoginBO->destinations_per_page)) {
                $destinations_per_page = (int) $userLoginBO->destinations_per_page;
            }
        }

        if (!isset($destinations_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->destinations_per_page = DESTINATIONS_PER_PAGE_DEFAULT;
                $destinations_per_page = DESTINATIONS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->destinations_per_page)) {
                $_SESSION['options']->destinations_per_page = DESTINATIONS_PER_PAGE_DEFAULT;
                $destinations_per_page = DESTINATIONS_PER_PAGE_DEFAULT;
            }
        }


        $view->taxonomies_per_page = $destinations_per_page;
        $view->taxonomy = $taxonomy;
        parent::search($view, $para);
    }
}
