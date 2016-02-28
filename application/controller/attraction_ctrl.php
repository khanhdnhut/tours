<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class AttractionCtrl extends Controller
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
            Model::autoloadModel('attraction');
            $model = new AttractionModel($this->db);
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
                if (isset($_POST['country']) && isset($_POST['country']) != "" && $_POST['country'] != "0") {
                    $this->para->country = $_POST['country'];
                }
                if (isset($_POST['parent']) && isset($_POST['parent']) != "" && $_POST['parent'] != "0") {
                    $this->para->parent = $_POST['parent'];
                }
                if (isset($_POST['city']) && isset($_POST['city']) != "" && $_POST['city'] != "0") {
                    $this->para->city = $_POST['city'];
                }
                if (isset($_POST['destination']) && isset($_POST['destination']) != "" && $_POST['destination'] != "0") {
                    $this->para->destination = $_POST['destination'];
                }
                if (isset($_POST['post_content_1'])) {
                    $this->para->post_content_1 = $_POST['post_content_1'];
                }
                if (isset($_POST['post_content_2'])) {
                    $this->para->post_content_2 = $_POST['post_content_2'];
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
                if (isset($_FILES['images']) && count($_FILES['images']) > 0 &&
                    isset($_FILES['images']['name']) && isset($_FILES['images']['name'][0]) &&
                    $_FILES['images']['name'][0] != '') {
                    $this->para->images = $_FILES['images'];
                }
                $result = $model->addToDatabase($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            $this->view->countryList = new SplDoublyLinkedList();
            $model->getAllSorted($this->view->countryList, $model->buildTree($model->getAll("country")), -1);

            $this->view->cityList = new SplDoublyLinkedList();
            if (isset($this->view->para) && isset($this->view->para->country)) {
                $model->getAllSorted($this->view->cityList, $model->buildTree($model->getByMetaData("city", "country", $this->view->para->country)), -1);
            }

            $this->view->destinationList = new SplDoublyLinkedList();
            if (isset($this->view->para) && isset($this->view->para->country)) {
                if (isset($this->view->para->city)) {
                    $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "city", $this->view->para->city)), -1);
                } else {
                    $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "country", $this->view->para->country)), -1);
                }
            }

            $this->view->parentList = new SplDoublyLinkedList();
            $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("attraction")), -1);
            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('attraction');
            $model = new AttractionModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['attraction'])) {
                $this->para->term_taxonomy_id = $_POST['attraction'];
            } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->para->term_taxonomy_id = $term_taxonomy_id;
            }

            if (isset($this->para->term_taxonomy_id)) {
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
                    if (isset($_POST['country']) && isset($_POST['country']) != "" && $_POST['country'] != "0") {
                        $this->para->country = $_POST['country'];
                    }
                    if (isset($_POST['parent']) && isset($_POST['parent']) != "" && $_POST['parent'] != "0") {
                        $this->para->parent = $_POST['parent'];
                    }
                    if (isset($_POST['city'])) {
                        $this->para->city = $_POST['city'];
                    }
                    if (isset($_POST['destination'])) {
                        $this->para->destination = $_POST['destination'];
                    }
                    if (isset($_POST['post_content_1'])) {
                        $this->para->post_content_1 = $_POST['post_content_1'];
                    }
                    if (isset($_POST['post_content_2'])) {
                        $this->para->post_content_2 = $_POST['post_content_2'];
                    }
                    if (isset($_POST['current_rating'])) {
                        $this->para->current_rating = $_POST['current_rating'];
                    }
                    if (isset($_POST['vote_times'])) {
                        $this->para->vote_times = $_POST['vote_times'];
                    }
                    if (isset($_POST['image_delete_list'])) {
                        $this->para->image_delete_list = $_POST['image_delete_list'];
                    }
                    if (isset($_POST['tag_list'])) {
                        $this->para->tag_list = $_POST['tag_list'];
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
                $this->view->attractionBO = $model->get($this->para->term_taxonomy_id);

                $this->view->countryList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->countryList, $model->buildTree($model->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->attractionBO->country) && $this->view->attractionBO->country != "0") {
                    $model->getAllSorted($this->view->cityList, $model->buildTree($model->getByMetaData("city", "country", $this->view->attractionBO->country)), -1);
                }
                if (isset($this->view->para) && isset($this->view->para->country)) {
                    $model->getAllSorted($this->view->cityList, $model->buildTree($model->getByMetaData("city", "country", $this->view->para->country)), -1);
                }

                $this->view->destinationList = new SplDoublyLinkedList();
                if (isset($this->view->attractionBO->country) && $this->view->attractionBO->country != "0") {
                    if (isset($this->view->attractionBO->city) && $this->view->attractionBO->city != "0") {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "city", $this->view->attractionBO->city)), -1);
                    } else {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "country", $this->view->attractionBO->country)), -1);
                    }
                }
                if (isset($this->view->para) && isset($this->view->para->country)) {
                    if (isset($this->view->para->city)) {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "city", $this->view->para->city)), -1);
                    } else {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "country", $this->view->para->country)), -1);
                    }
                }


                $this->view->parentList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("attraction")), -1);

                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_EDIT);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_ATTRACTION_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('attraction');
            $model = new AttractionModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['attraction'])) {
                $this->para->term_taxonomy_id = $_POST['attraction'];
            } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->para->term_taxonomy_id = $term_taxonomy_id;
            }

            if (isset($this->para->term_taxonomy_id)) {
                $this->view->attractionBO = $model->get($this->para->term_taxonomy_id);

                $this->view->countryList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->countryList, $model->buildTree($model->getAll("country")), -1);

                $this->view->cityList = new SplDoublyLinkedList();
                if (isset($this->view->attractionBO->country) && $this->view->attractionBO->country != "0") {
                    $model->getAllSorted($this->view->cityList, $model->buildTree($model->getByMetaData("city", "country", $this->view->attractionBO->country)), -1);
                }

                $this->view->destinationList = new SplDoublyLinkedList();
                if (isset($this->view->para) && isset($this->view->para->country)) {
                    if (isset($this->view->para->city)) {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "city", $this->view->para->city)), -1);
                    } else {
                        $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "country", $this->view->para->country)), -1);
                    }
                }

                $this->view->parentList = new SplDoublyLinkedList();
                $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("attraction")), -1);

                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_INFO);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_ATTRACTION_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function view($term_taxonomy_id = NULL)
    {
        Model::autoloadModel('attraction');
        $model = new AttractionModel($this->db);
        $this->para = new stdClass();
        if (isset($_POST['attraction'])) {
            $this->para->term_taxonomy_id = $_POST['attraction'];
        } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
            $this->para->term_taxonomy_id = $term_taxonomy_id;
        }

        if (isset($this->para->term_taxonomy_id)) {
            $this->view->attractionBO = $model->get($this->para->term_taxonomy_id);

            $this->view->countryList = new SplDoublyLinkedList();
            $model->getAllSorted($this->view->countryList, $model->buildTree($model->getAll("country")), -1);

            $this->view->cityList = new SplDoublyLinkedList();
            if (isset($this->view->attractionBO->country) && $this->view->attractionBO->country != "0") {
                $model->getAllSorted($this->view->cityList, $model->buildTree($model->getByMetaData("city", "country", $this->view->attractionBO->country)), -1);
            }

            $this->view->destinationList = new SplDoublyLinkedList();
            if (isset($this->view->para) && isset($this->view->para->country)) {
                if (isset($this->view->para->city)) {
                    $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "city", $this->view->para->city)), -1);
                } else {
                    $model->getAllSorted($this->view->destinationList, $model->buildTree($model->getByMetaData("destination", "country", $this->view->para->country)), -1);
                }
            }

            $this->view->parentList = new SplDoublyLinkedList();
            $model->getAllSorted($this->view->parentList, $model->buildTree($model->getAll("attraction")), -1);

            if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->view->render(RENDER_VIEW_ATTRACTION);
            } else {
                $this->view->render(RENDER_VIEW_ATTRACTION, TRUE);
            }
        } else {
            header('location: ' . URL);
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR))) {
            Model::autoloadModel('attraction');
            $model = new AttractionModel($this->db);
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
            if (isset($_POST['attractions'])) {
                $this->para->attractions = $_POST['attractions'];
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
            if (isset($_POST['attractions_per_page'])) {
                $this->para->attractions_per_page = $_POST['attractions_per_page'];
            }
            if (isset($_POST['current_rating'])) {
                $this->para->current_rating = $_POST['current_rating'];
            }
            if (isset($_POST['vote_times'])) {
                $this->para->vote_times = $_POST['vote_times'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->attractions)) {
                $model->executeAction($this->para);
            }

            $model->search($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_ATTRACTION_INDEX);
            }
        } else {
            $this->login();
        }
    }
}
