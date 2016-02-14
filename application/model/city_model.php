<?php
Model::autoloadModel('taxonomy');

class CityModel extends TaxonomyModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new city
     *
     * @param stdClass $para para for add new city
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_CITY;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "city") != FALSE) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }

        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "city")) {
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
     * Add new city
     *
     * @param stdClass $para para for add new city
     */
    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("city");
                $cityBO = new CityBO();

                if (isset($para->name)) {
                    $cityBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $cityBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $cityBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $cityBO->parent = $para->parent;
                }
                $cityBO->count = 0;
                $cityBO->term_group = 0;

                $this->db->beginTransaction();

                if (parent::addToDatabase($cityBO)) {
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
     * Validate para for update info of city
     *
     * @param stdClass $para para for update info of city
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
     * Update info of city
     *
     * @param stdClass $para para for update info of city
     */
    public function updateInfo($para)
    {
        try {
            if ($this->validateUpdateInfo($para)) {
                $cityBO = $this->get($para->term_taxonomy_id);
                if ($cityBO != NULL) {
                    if (isset($para->name)) {
                        $cityBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $cityBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $cityBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $cityBO->parent = $para->parent;
                    } else {
                        $cityBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($cityBO)) {
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
     * updateCitiesPerPages
     *
     * Update number cities per page
     *
     * @param string $cities_per_page number cities per page
     */
    public function updateCitiesPerPages($cities_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "cities_per_page";
        $meta_value = $cities_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_cities_columns_show";
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
        if (isset($para->cities_per_page) && is_numeric($para->cities_per_page)) {
            $this->updateCitiesPerPages($para->cities_per_page);
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
        if (isset($para->cities) && is_array($para->cities)) {
            foreach ($para->cities as $term_taxonomy_id) {
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
        $cities_per_page = CITIES_PER_PAGE_DEFAULT;
        $taxonomy = "city";
        
        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->cities_per_page) && is_numeric($userLoginBO->cities_per_page)) {
                $cities_per_page = (int) $userLoginBO->cities_per_page;
            }
        }

        if (!isset($cities_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->cities_per_page = CITIES_PER_PAGE_DEFAULT;
                $cities_per_page = CITIES_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->cities_per_page)) {
                $_SESSION['options']->cities_per_page = CITIES_PER_PAGE_DEFAULT;
                $cities_per_page = CITIES_PER_PAGE_DEFAULT;
            }
        }

        $view->taxonomies_per_page = $cities_per_page;
        $view->taxonomy = $taxonomy;

        parent::search($view, $para);
    }
}
