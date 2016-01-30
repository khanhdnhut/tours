<?php
Model::autoloadModel('taxonomy');

class TagModel extends TaxonomyModel
{

    public function validateAddNewTag($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_TAG;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->hasTaxonomyWithName($para->name, "tag")) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }
        
        if (isset($para->slug) && $para->slug != "") {
            if ($this->hasTaxonomyWithSlug($para->slug, "tag")) {
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

    public function addNewTag($para)
    {
        try {
            if ($this->validateAddNewTag($para)) {
                BO::autoloadBO("tag");
                $tagBO = new TagBO();

                if (isset($para->name)) {
                    $tagBO->name = $para->name;
                }
                if (isset($para->slug)) {
                    $tagBO->slug = $para->slug;
                }
                if (isset($para->description)) {
                    $tagBO->description = $para->description;
                }
                if (isset($para->parent)) {
                    $tagBO->parent = $para->parent;
                }
                $tagBO->count = 0;
                $tagBO->term_group = 0;

                $this->db->beginTransaction();

                if ($this->addTaxonomyToDatabase($tagBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = ADD_TAG_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ADD_TAG_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_TAG;
        }
        return FALSE;
    }

    public function validateUpdateInfoTag($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TAG;
            return false;
        }
        if (!isset($para->term_taxonomy_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TAG;
            return false;
        } else {
            try {
                $para->term_taxonomy_id = (int) $para->term_taxonomy_id;
            } catch (Exception $e) {
                $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TAG;
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

    public function updateInfoTag($para)
    {
        try {
            if ($this->validateUpdateInfoTag($para)) {
                $tagBO = $this->getTerm($para->term_taxonomy_id);
                if ($tagBO != NULL) {
                    if (isset($para->name)) {
                        $tagBO->name = $para->name;
                    }
                    if (isset($para->slug)) {
                        $tagBO->slug = $para->slug;
                    }
                    if (isset($para->description)) {
                        $tagBO->description = $para->description;
                    }
                    if (isset($para->parent)) {
                        $tagBO->parent = $para->parent;
                    } else {
                        $tagBO->parent = 0;
                    }

                    $this->db->beginTransaction();

                    if ($this->updateTerm($tagBO)) {
                        $this->db->commit();
                        $_SESSION["fb_success"][] = UPDATE_TAG_SUCCESS;
                        return TRUE;
                    } else {
                        $this->db->rollBack();
                        $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TAG;
                    }
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_TAG;
        }
        return FALSE;
    }

    public function updateEditTagPerPages($tags_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "tags_per_page";
        $meta_value = $tags_per_page;
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setUserMeta($user_id, $meta_key, $meta_value);
    }

    public function updateShowHideColumn($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_tags_columns_show";
        $meta_value = new stdClass();
        $meta_value->description_show = $description_show;
        $meta_value->slug_show = $slug_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userModel->setUserMeta($user_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->tags_per_page) && is_numeric($para->tags_per_page)) {
            $this->updateEditTagPerPages($para->tags_per_page);
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
        $this->updateShowHideColumn($description_show, $slug_show, $tours_show);
        Model::autoloadModel('user');
        $userModel = new UserModel($this->db);
        $userBO = $userModel->getUserByUserId(Session::get("user_id"));
        $userModel->setNewSessionUser($userBO);
    }

    public function executeActionDelete($para)
    {
        if (isset($para->tags) && is_array($para->tags)) {
            foreach ($para->tags as $term_taxonomy_id) {
                $this->deleteTerm($term_taxonomy_id);
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

    public function searchTag($view, $para)
    {
        $tags_per_page = TAGS_PER_PAGE_DEFAULT;
        $userLoginBO = json_decode(Session::get("userInfo"));
        if ($userLoginBO != NULL) {
            if (isset($userLoginBO->tags_per_page) && is_numeric($userLoginBO->tags_per_page)) {
                $tags_per_page = (int) $userLoginBO->tags_per_page;
            }
        }

        if (!isset($tags_per_page)) {
            if (!isset($_SESSION['options'])) {
                $_SESSION['options'] = new stdClass();
                $_SESSION['options']->tags_per_page = TAGS_PER_PAGE_DEFAULT;
                $tags_per_page = TAGS_PER_PAGE_DEFAULT;
            } elseif (!isset($_SESSION['options']->tags_per_page)) {
                $_SESSION['options']->tags_per_page = TAGS_PER_PAGE_DEFAULT;
                $tags_per_page = TAGS_PER_PAGE_DEFAULT;
            }
        }
        
        $taxonomy = "tag";
        parent::searchTaxonomy($view, $para, $tags_per_page, $taxonomy);
    }
}
