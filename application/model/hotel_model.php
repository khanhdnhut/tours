<?php
Model::autoloadModel('taxonomy');

class HotelModel extends TaxonomyModel
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
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_CITY;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "hotel")) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "hotel")) {
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

                if (isset($para->name)) {
                    $hotelBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $hotelBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $hotelBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $hotelBO->parent = $para->parent;
                }
                $hotelBO->count = 0;
                $hotelBO->term_group = 0;

                $this->db->beginTransaction();

                if (parent::addToDatabase($hotelBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_CITY_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_CITY_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_CITY;
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
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_CITY;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_CITY;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_CITY;
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
                $hotelBO = $this->get($para->term_taxonomy_id);
                if ($hotelBO != NULL) {
                    if (isset($para->name)) {
                        $hotelBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $hotelBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $hotelBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $hotelBO->parent = $para->parent;
                    } else {
                        $hotelBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($hotelBO)) {
                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_CITY_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_CITY;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_CITY;
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
            foreach ($para->hotels as $term_taxonomy_id) {
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
        $hotels_per_page = HOTELS_PER_PAGE_DEFAULT;
        $taxonomy = "hotel";
        
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

        $view->taxonomies_per_page = $hotels_per_page;
        $view->taxonomy = $taxonomy;

        parent::search($view, $para);
    }
}
