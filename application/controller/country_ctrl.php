<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class CountryCtrl extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    public function addNew()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('country');
            $this->model = new CountryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];

                if (isset($_POST['name'])) {
                    $this->para->name = $_POST['name'];
                }
                if (isset($_POST['slug'])) {
                    $this->para->slug = $_POST['slug'];
                }
                if (isset($_POST['description'])) {
                    $this->para->description = $_POST['description'];
                }
                if (isset($_POST['parent'])) {
                    $this->para->parent = $_POST['parent'];
                }
                $result = $this->model->addNewCountry($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(RENDER_VIEW_COUNTRY_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_COUNTRY_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($country_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('country');
            $this->model = new CountryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['country'])) {
                $this->para->country_id = $_POST['country'];
            } elseif (isset($country_id) && !is_null($country_id)) {
                $this->para->country_id = $country_id;
            }

            if (isset($this->para->country_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['name'])) {
                        $this->para->name = $_POST['name'];
                    }
                    if (isset($_POST['slug'])) {
                        $this->para->slug = $_POST['slug'];
                    }
                    if (isset($_POST['description'])) {
                        $this->para->description = $_POST['description'];
                    }
                    if (isset($_POST['parent'])) {
                        $this->para->parent = $_POST['parent'];
                    }
                    $result = $this->model->updateInfoCountry($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->countryBO = $this->model->getCountryByCountryId($this->para->country_id);
                if (isset($country_id) && !is_null($country_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_COUNTRY_EDIT);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_COUNTRY_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_COUNTRY_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($country_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('country');
            $this->model = new CountryModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['country'])) {
                $this->para->country_id = $_POST['country'];
            } elseif (isset($country_id) && !is_null($country_id)) {
                $this->para->country_id = $country_id;
            }

            if (isset($this->para->country_id)) {
                $this->view->countryBO = $this->model->getCountryByCountryId($this->para->country_id);
                if (isset($country_id) && !is_null($country_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_COUNTRY_INFO);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_COUNTRY_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_COUNTRY_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('country');
            $this->model = new CountryModel($this->db);
            $this->para = new stdClass();

            if (isset($_POST['type'])) {
                $this->para->type = $_POST['type'];
            }
            if (isset($_POST['orderby'])) {
                $this->para->orderby = $_POST['orderby'];
            }
            if (isset($_POST['order'])) {
                $this->para->order = $_POST['order'];
            }
            if (isset($_POST['page'])) {
                $this->para->page = $_POST['page'];
            }
            if (isset($_POST['s'])) {
                $this->para->s = $_POST['s'];
            }
            if (isset($_POST['paged'])) {
                $this->para->paged = $_POST['paged'];
            }
            if (isset($_POST['countrys'])) {
                $this->para->countrys = $_POST['countrys'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            if (isset($_POST['description_show'])) {
                $this->para->description_show = $_POST['description_show'];
            }
            if (isset($_POST['slug_show'])) {
                $this->para->slug_show = $_POST['slug_show'];
            }
            if (isset($_POST['tours_show'])) {
                $this->para->tours_show = $_POST['tours_show'];
            }
            if (isset($_POST['edit_country_per_page'])) {
                $this->para->edit_country_per_page = $_POST['edit_country_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $this->model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->countrys)) {
                $this->model->executeAction($this->para);
            }

            $this->model->prepareIndexPage($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = true;
                $this->view->renderAdmin(RENDER_VIEW_COUNTRY_INDEX, TRUE);
            } else {
                $this->view->ajax = false;
                $this->view->renderAdmin(RENDER_VIEW_COUNTRY_INDEX);
            }
        } else {
            $this->login();
        }
    }
}
