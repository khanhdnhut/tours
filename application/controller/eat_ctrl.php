<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class EatCtrl extends Controller
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
            Model::autoloadModel('eat');
            $model = new EatModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['action']) && $_POST['action'] == "addNew") {
                $this->para->action = $_POST['action'];
                if (isset($_POST['post_title'])) {
                    $this->para->post_title = trim($_POST['post_title']);
                }
                if (isset($_POST['address'])) {
                    $this->para->address = trim($_POST['address']);
                }
                if (isset($_POST['number_of_rooms'])) {
                    $this->para->number_of_rooms = $_POST['number_of_rooms'];
                }
                if (isset($_POST['star'])) {
                    $this->para->star = $_POST['star'];
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

                if (isset($_FILES['images']) && count($_FILES['images']) > 0 &&
                        isset($_FILES['images']['name']) && isset($_FILES['images']['name'][0]) &&
                        $_FILES['images']['name'][0] != "") {
                        $this->para->images = $_FILES['images'];
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
                $this->view->renderAdmin(RENDER_VIEW_HOTEL_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_HOTEL_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('eat');
            $model = new EatModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['eat'])) {
                $this->para->post_id = $_POST['eat'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                if (isset($_POST['action']) && $_POST['action'] == "update") {
                    $this->para->action = $_POST['action'];

                    if (isset($_POST['post_title'])) {
                        $this->para->post_title = trim($_POST['post_title']);
                    }
                    if (isset($_POST['address'])) {
                        $this->para->address = trim($_POST['address']);
                    }
                    if (isset($_POST['number_of_rooms'])) {
                        $this->para->number_of_rooms = $_POST['number_of_rooms'];
                    }
                    if (isset($_POST['star'])) {
                        $this->para->star = $_POST['star'];
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

                    if (isset($_FILES['images']) && count($_FILES['images']) > 0 &&
                        isset($_FILES['images']['name']) && isset($_FILES['images']['name'][0]) &&
                        $_FILES['images']['name'][0] != "") {
                        $this->para->images = $_FILES['images'];
                    }

                    $result = $model->updateInfo($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->eatBO = $model->get($this->para->post_id);

                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);
                $this->view->countryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->eatBO->country_id) && $this->view->eatBO->country_id != "0") {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->eatBO->country_id)), -1);
                }
                if (isset($this->view->para) && isset($this->view->para->country_id)) {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->para->country_id)), -1);
                }

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_HOTEL_EDIT);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_HOTEL_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_HOTEL_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($post_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('eat');
            $model = new EatModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['eat'])) {
                $this->para->post_id = $_POST['eat'];
            } elseif (isset($post_id) && !is_null($post_id)) {
                $this->para->post_id = $post_id;
            }

            if (isset($this->para->post_id)) {
                $this->view->eatBO = $model->get($this->para->post_id);


                Model::autoloadModel('taxonomy');
                $taxonomyModel = new TaxonomyModel($this->db);

                $this->view->countryList = new SplDoublyLinkedList();
                $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->eatBO->country_id) && $this->view->eatBO->country_id != "0") {
                    $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->eatBO->country_id)), -1);
                }

                if (isset($post_id) && !is_null($post_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_HOTEL_INFO);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_HOTEL_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_HOTEL_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function view($post_id = NULL)
    {
        Model::autoloadModel('eat');
        $model = new EatModel($this->db);
        $this->para = new stdClass();
        if (isset($_POST['eat'])) {
            $this->para->post_id = $_POST['eat'];
        } elseif (isset($post_id) && !is_null($post_id)) {
            $this->para->post_id = $post_id;
        }

        if (isset($this->para->post_id)) {
            $this->view->eatBO = $model->get($this->para->post_id);

            Model::autoloadModel('taxonomy');
            $taxonomyModel = new TaxonomyModel($this->db);
            $this->view->countryList = new SplDoublyLinkedList();
            $taxonomyModel->getAllSorted($this->view->countryList, $taxonomyModel->buildTree($taxonomyModel->getAll("country")), -1);

            $this->view->cityList = new SplDoublyLinkedList();
            if (isset($this->view->eatBO->country_id) && $this->view->eatBO->country_id != "0") {
                $taxonomyModel->getAllSorted($this->view->cityList, $taxonomyModel->buildTree($taxonomyModel->getByMetaData("city", "country", $this->view->eatBO->country_id)), -1);
            }

            if (isset($post_id) && !is_null($post_id)) {
                $this->view->render(RENDER_VIEW_HOTEL);
            } else {
                $this->view->render(RENDER_VIEW_HOTEL, TRUE);
            }
        } else {
            header('location: ' . URL);
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('eat');
            $model = new EatModel($this->db);
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
            if (isset($_POST['eats'])) {
                $this->para->eats = $_POST['eats'];
            }
            if (isset($_POST['action'])) {
                $this->para->action = $_POST['action'];
            }
            if (isset($_POST['action2'])) {
                $this->para->action2 = $_POST['action2'];
            }
            if (isset($_POST['address_show'])) {
                $this->para->address_show = $_POST['address_show'];
            }
            if (isset($_POST['city_show'])) {
                $this->para->city_show = $_POST['city_show'];
            }
            if (isset($_POST['star_show'])) {
                $this->para->tours_show = $_POST['star_show'];
            }
            if (isset($_POST['number_of_rooms_show'])) {
                $this->para->number_of_rooms_show = $_POST['number_of_rooms_show'];
            }
            if (isset($_POST['current_rating'])) {
                $this->para->current_rating = $_POST['current_rating'];
            }
            if (isset($_POST['vote_times'])) {
                $this->para->vote_times = $_POST['vote_times'];
            }
            if (isset($_POST['eats_per_page'])) {
                $this->para->eats_per_page = $_POST['eats_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->eats)) {
                $model->executeAction($this->para);
            }

            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(RENDER_VIEW_HOTEL_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_HOTEL_INDEX);
            }
        } else {
            $this->login();
        }
    }
}
