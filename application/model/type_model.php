<?php
Model::autoloadModel('taxonomy');

class TypeModel extends TaxonomyModel
{

    public function validateAddNew($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_TYPE;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->isExistName($para->name, "type") != FALSE) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }
        
        if (isset($para->slug) && $para->slug != "") {
            if ($this->isExistSlug($para->slug, "type")) {
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

    public function addToDatabase($para)
    {
        try {
            if ($this->validateAddNew($para)) {
                BO::autoloadBO("type");
                $typeBO = new TypeBO();

                if (isset($para->name)) {
                    $typeBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $typeBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $typeBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $typeBO->parent = $para->parent;
                }
                $typeBO->count = 0;
                $typeBO->term_group = 0;

                $this->db->beginTransaction();

                if (parent::addToDatabase($typeBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_TYPE_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_TYPE_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_TYPE;
        }
        return FALSE;
    }

    public function validateUpdateInfo($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TYPE;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TYPE;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TYPE;
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
                $typeBO = $this->get($para->term_taxonomy_id);
                if ($typeBO != NULL) {
                    if (isset($para->name)) {
                        $typeBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $typeBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $typeBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $typeBO->parent = $para->parent;
                    } else {
                        $typeBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->update($typeBO)) {
                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_TYPE_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TYPE;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TYPE;
        }
        return FALSE;
    }

    public function updateTypesPerPage($types_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "types_per_page";
        $meta_value = $types_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setMeta($user_id, $meta_key, $meta_value);
    }

    public function updateColumnsShow($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_types_columns_show";
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
        if (isset($para->types_per_page) && is_numeric($para->types_per_page)) {
            $this->updateTypesPerPage($para->types_per_page);
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
        if (isset($para->types) && is_array($para->types)) {
            foreach ($para->types as $term_taxonomy_id) {
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
        $types_per_page = TYPES_PER_PAGE_DEFAULT;
        $taxonomy = "type";
        
        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->types_per_page) && is_numeric($userLoginBO->types_per_page)) {
                $types_per_page = (int) $userLoginBO->types_per_page;
            }
        }

        if (!isset($types_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->types_per_page = TYPES_PER_PAGE_DEFAULT;
                $types_per_page = TYPES_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->types_per_page)) {
                $_SESSION['options']->types_per_page = TYPES_PER_PAGE_DEFAULT;
                $types_per_page = TYPES_PER_PAGE_DEFAULT;
            }
        }

        
        $view->taxonomies_per_page = $types_per_page;
        $view->taxonomy = $taxonomy;
        parent::search($view, $para);
    }
}
