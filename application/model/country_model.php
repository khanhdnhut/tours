<?php
Model::autoloadModel('taxonomy');

class CountryModel extends TaxonomyModel
{

    public function validateAddNewCountry($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_ADD_NEW_COUNTRY;
            return false;
        }

        if (isset($para->name) && $para->name != "") {
            if ($this->hasCountryWithName($para->name)) {
                $_SESSION["fb_error"][] = ERROR_NAME_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_NAME_EMPTY;
            return false;
        }
        if (isset($para->slug) && $para->slug != "") {
            if ($this->hasCountryWithSlug($para->slug)) {
                $_SESSION["fb_error"][] = ERROR_SLUG_EXISTED;
                return false;
            }
        } else {
            $_SESSION["fb_error"][] = ERROR_SLUG_EMPTY;
            return false;
        }

        return true;
    }

    public function addNewCountry($para)
    {
        try {
            if ($this->validateAddNewCountry($para)) {
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

                if ($this->addTaxonomyToDatabase($countryBO)) {
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

    public function validateUpdateInfoCountry($para)
    {
        if ($para == null || !is_object($para)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        }
        if (!isset($para->country_id)) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
            return false;
        } else {
            try {
                $para->country_id = (int) $para->country_id;
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
        return true;
    }

    public function updateInfoCountry($para)
    {
        try {
            if ($this->validateUpdateInfoCountry($para)) {
                BO::autoloadBO("country");
                $countryBO = new CountryBO();

                if (isset($para->country_id)) {
                    $countryBO->country_id = $para->country_id;
                }
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

                $this->db->beginTransaction();

                if ($this->updateTerm($countryBO)) {
                    $this->db->commit();
                    $_SESSION["fb_success"][] = UPDATE_COUNTRY_SUCCESS;
                    return TRUE;
                } else {
                    $this->db->rollBack();
                    $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
                }
            }
        } catch (Exception $e) {
            $_SESSION["fb_error"][] = ERROR_UPDATE_INFO_COUNTRY;
        }
        return FALSE;
    }

    public function updateEditCountryPerPages($edit_country_per_page)
    {
        $user_id = Session::get("user_id");
        $meta_key = "edit_country_per_page";
        $meta_value = $edit_country_per_page;
        $this->setCountryMeta($user_id, $meta_key, $meta_value);
    }

    public function updateShowHideColumn($description_show, $slug_show, $tours_show)
    {
        $user_id = Session::get("user_id");
        $meta_key = "manage_country_columns_show";
        $meta_value = new stdClass();
        $meta_value->description_show = $description_show;
        $meta_value->slug_show = $slug_show;
        $meta_value->tours_show = $tours_show;
        $meta_value = json_encode($meta_value);
        $this->setCountryMeta($country_id, $meta_key, $meta_value);
    }

    public function changeAdvSetting($para)
    {
        $action = NULL;
        if (isset($para->edit_country_per_page) && is_numeric($para->edit_country_per_page)) {
            $this->updateEditCountryPerPages($para->edit_country_per_page);
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
        if (isset($para->countrys) && is_array($para->countrys)) {
            foreach ($para->countrys as $country_id) {
                try {
                    $country_id = (int) $country_id;
                } catch (Exception $ex) {
                    return;
                }
                if (!is_int($country_id)) {
                    return;
                }
            }
            $country_ids = join(',', $para->countrys);

            $countryBO = json_decode(Session::get('countryInfo'));
            $country_id = $countryBO->country_id;

            if (in_array("1", $para->countrys)) {
                $country_delete = $this->getCountryByCountryId("1");
                if (!is_null($country_delete)) {
                    $_SESSION["fb_error"][] = ERROR_DELETE_COUNTRY_NOT_PERMISSION . " <strong>" . $country_delete->country_login . "</strong>";
                } else {
                    $_SESSION["fb_error"][] = ERR_COUNTRY_INFO_NOT_FOUND;
                }
            }
            if (in_array($country_id, $para->countrys) && $country_id != "1") {
                $_SESSION["fb_error"][] = ERROR_DELETE_COUNTRY_NOT_PERMISSION . " <strong>" . $countryBO->country_login . "</strong>";
            }

            $sql = "UPDATE " . TABLE_COUNTRYS . " 
                    SET " . TB_COUNTRYS_COL_COUNTRY_STATUS . " = " . COUNTRY_STATUS_DELETED . "
                    WHERE " . TB_COUNTRYS_COL_ID . " IN (" . $country_ids . ")
                    AND " . TB_COUNTRYS_COL_ID . " != 1
                    AND " . TB_COUNTRYS_COL_ID . " != " . $country_id . " ;";

            $sth = $this->db->prepare($sql);
            $sth->execute();
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

    public function getCountryByCountryId($country_id)
    {
        
    }

    public function prepareIndexPage($view, $para)
    {
        
    }
}
