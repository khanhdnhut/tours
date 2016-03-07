<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class NightlifeCtrl extends Controller
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
            Model::autoloadModel('nightlife');
            $model = new NightlifeModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];
                if (isset($_POST['post_title'])) {
                    $this->para->post_title = trim($_POST['post_title']);
                }
                if (isset($_POST['country_id']) && isset($_POST['country_id']) != "" && $_POST['country_id'] != "0") {
                    $this->para->country_id = $_POST['country_id'];
                }
                if (isset($_POST['city_id'])) {
                    $this->para->city_id = $_POST['city_id'];
                }

                if (isset($_POST['current_rating'])) {
                    $this->para->current_rating = $_POST['current_rating'];
                }
                if (isset($_POST['vote_times'])) {
                    $this->para->vote_times = $_POST['vote_times'];
                }
                if (isset($_POST['tag_list'])) {
                    $this->para->tag_list = $_POST['tag_list'];
                }
                if (isset($_POST['post_content'])) {
                    $this->para->post_content = trim($_POST['post_content']);
                }

                if (isset($_FILES['image']) && isset($_FILES['image']['name']) &&
                    $_FILES['image']['name'] != '') {
                    $this->para->image = $_FILES['image'];
                }

                $result = $model->addToDatabase($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            Model::autoloadModel('taxonomy');
            $taxonomyModel = new TaxonomyModel($this->db);

            $this->view->countryList = new SplDoublyLinkedList();
            $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);
            $this->view->cityList = new SplDoublyLinkedList();
//            $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getAll("city")), -1);
            if (isset($this->view->para) && isset($this->view->para->country_id)) {
                $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->para->country_id)), -1);
            }

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('nightlife');
            $model = new NightlifeModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['nightlife'])) {
                $this->para->post_id = $_POST['nightlife'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['post_title'])) {
                        $this->para->post_title = trim($_POST['post_title']);
                    }
                    if (isset($_POST['country_id']) && isset($_POST['country_id']) != "" && $_POST['country_id'] != "0") {
                        $this->para->country_id = $_POST['country_id'];
                    }
                    if (isset($_POST['city_id'])) {
                        $this->para->city_id = $_POST['city_id'];
                    }
                    if (isset($_POST['current_rating'])) {
                        $this->para->current_rating = $_POST['current_rating'];
                    }
                    if (isset($_POST['vote_times'])) {
                        $this->para->vote_times = $_POST['vote_times'];
                    }
                    if (isset($_POST['tag_list'])) {
                        $this->para->tag_list = $_POST['tag_list'];
                    }
                    if (isset($_POST['post_content'])) {
                        $this->para->post_content = trim($_POST['post_content']);
                    }

                    if (isset($_FILES['image']) && isset($_FILES['image']['name']) &&
                        $_FILES['image']['name'] != '') {
                        $this->para->image = $_FILES['image'];
                    }

                    $result = $model->updateInfo($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->nightlifeBO = $model->get($this->para->post_id);

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);
                $this->view->countryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->nightlifeBO->country_id) && $this->view->nightlifeBO->country_id != "0") {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->nightlifeBO->country_id)), -1);
                }
                if (isset($this->view->para) && isset($this->view->para->country_id)) {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->para->country_id)), -1);
                }

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_EDIT);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_NIGHTLIFE_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('nightlife');
            $model = new NightlifeModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['nightlife'])) {
                $this->para->post_id = $_POST['nightlife'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                $this->view->nightlifeBO = $model->get($this->para->post_id);


                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                $this->view->countryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->nightlifeBO->country_id) && $this->view->nightlifeBO->country_id != "0") {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->nightlifeBO->country_id)), -1);
                }

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_INFO);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_NIGHTLIFE_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function view($post_id = NULL)
    {
        Model::autoloadModel('nightlife');
        $model = new NightlifeModel($this->db);
        $this->para = new stdClass();
        if (isset($_POST['nightlife'])) {
            $this->para->post_id = $_POST['nightlife'];
        } elseif (isset($post_id) && !is_null($post_id)) {
            $this->para->post_id = $post_id;
        }

        if (isset($this->para->post_id)) {
            $this->view->nightlifeBO = $model->get($this->para->post_id);

            Model::autoloadModel('taxonomy');
            $taxonomyModel = new TaxonomyModel($this->db);
            $this->view->countryList = new SplDoublyLinkedList();
            $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

            $this->view->cityList = new SplDoublyLinkedList();
            if (isset($this->view->nightlifeBO->country_id) && $this->view->nightlifeBO->country_id != "0") {
                $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->nightlifeBO->country_id)), -1);
            }

            if (isset($post_id) && !is_null($post_id)) {
                $this->view->render(RENDER_VIEW_NIGHTLIFE);
            } else {
                $this->view->render(RENDER_VIEW_NIGHTLIFE, TRUE);
            }
        } else {
            header('location: ' . URL);
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('nightlife');
            $model = new NightlifeModel($this->db);
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
            if (isset($_POST['nightlifes'])) {
                $this->para->nightlifes = $_POST['nightlifes'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            if (isset($_POST['city_show'])) {
                $this->para->city_show = $_POST['city_show'];
            }
            if (isset($_POST['current_rating'])) {
                $this->para->current_rating = $_POST['current_rating'];
            }
            if (isset($_POST['vote_times'])) {
                $this->para->vote_times = $_POST['vote_times'];
            }
            if (isset($_POST['nightlifes_per_page'])) {
                $this->para->nightlifes_per_page = $_POST['nightlifes_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->nightlifes)) {
                $model->executeAction($this->para);
            }

            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_NIGHTLIFE_INDEX);
            }
        } else {
            $this->login();
        }
    }
}
