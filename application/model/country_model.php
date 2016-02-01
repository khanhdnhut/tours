<?php
Model::autoloadModel('taxonomy');

class CountryModel extends TaxonomyModel
{

    /**
     * validateAddNew
     *
     * Validate para for add new country
     *
     * @param stdClass $para para for add new country
     */
    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_COUNTRY;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "country")) {
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

        return true;
    }

    /**
     * addToDatabase
     *
     * Add new country
     *
     * @param stdClass $para para for add new country
     */
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
                $countryBO->count = 0;
                $countryBO->term_group = 0;

                $this->db->beginTransaction();

                if (parent::addToDatabase($countryBO)) {
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
        return true;
    }

    /**
     * updateInfo
     *
     * Update info of country
     *
     * @param stdClass $para para for update info of country
     */
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

                    $this->db->beginTransaction();

                    if ($this->update($countryBO)) {
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
