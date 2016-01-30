<?php
class TagCtrl extends Controller
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
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_EDITOR))) {
            Model::autoloadModel('tag');
            $model = new TagModel($this->db);
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
                $result = $model->addNewTag($this->para);
                if (!$result) {
                    $this->view->para = $this->para;
                }
            }

            $this->view->parentList = new SplDoublyLinkedList();
            $model->getAllTaxonomiesSorted($this->view->parentList, $model->buildTree($model->getAllTaxonomies("tag")), -1);

            if (isset($_POST['ajax']) && !is_null($_POST['ajax'])) {
                $this->view->renderAdmin(RENDER_VIEW_TAG_ADD_NEW, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_TAG_ADD_NEW);
            }
        } else {
            $this->login();
        }
    }

    public function editInfo($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_EDITOR))) {
            Model::autoloadModel('tag');
            $model = new TagModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['tag'])) {
                $this->para->term_taxonomy_id = $_POST['tag'];
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
                    if (isset($_POST['parent'])) {
                        $this->para->parent = $_POST['parent'];
                    }

                    $result = $model->updateInfoTag($this->para);
                    if (!$result) {
                        $this->view->para = $this->para;
                    } else {
                        $update_success = TRUE;
                    }
                }
                $this->view->tagBO = $model->getTerm($this->para->term_taxonomy_id);

                $this->view->parentList = new SplDoublyLinkedList();
                $model->getAllTaxonomiesSorted($this->view->parentList, $model->buildTree($model->getAllTaxonomies("tag")), -1);

                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_TAG_EDIT);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_TAG_EDIT, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_TAG_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function info($term_taxonomy_id = NULL)
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_EDITOR))) {
            Model::autoloadModel('tag');
            $model = new TagModel($this->db);
            $this->para = new stdClass();
            if (isset($_POST['tag'])) {
                $this->para->term_taxonomy_id = $_POST['tag'];
            } elseif (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                $this->para->term_taxonomy_id = $term_taxonomy_id;
            }

            if (isset($this->para->term_taxonomy_id)) {
                $this->view->tagBO = $model->getTerm($this->para->term_taxonomy_id);
                if (isset($term_taxonomy_id) && !is_null($term_taxonomy_id)) {
                    $this->view->renderAdmin(RENDER_VIEW_TAG_INFO);
                } else {
                    $this->view->renderAdmin(RENDER_VIEW_TAG_INFO, TRUE);
                }
            } else {
                header('location: ' . URL . CONTEXT_PATH_TAG_INDEX);
            }
        } else {
            $this->login();
        }
    }

    public function index()
    {
        if (in_array(Auth::getCapability(), array(CAPABILITY_ADMINISTRATOR, CAPABILITY_AUTHOR, CAPABILITY_CONTRIBUTOR, CAPABILITY_EDITOR))) {
            Model::autoloadModel('tag');
            $model = new TagModel($this->db);
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
            if (isset($_POST['tags'])) {
                $this->para->tags = $_POST['tags'];
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
            if (isset($_POST['tags_per_page'])) {
                $this->para->tags_per_page = $_POST['tags_per_page'];
            }
            if (isset($_POST['adv_setting'])) {
                $this->para->adv_setting = $_POST['adv_setting'];
            }

            if (isset($this->para->adv_setting) && $this->para->adv_setting == "adv_setting") {
                $model->changeAdvSetting($this->para);
            }

            if (isset($this->para->type) && in_array($this->para->type, array("action", "action2")) && isset($this->para->tags)) {
                $model->executeAction($this->para);
            }
            
            $model->searchTag($this->view, $this->para);

            if (count((array) $this->para) > 0) {
                $this->view->ajax = TRUE;
                $this->view->renderAdmin(RENDER_VIEW_TAG_INDEX, TRUE);
            } else {
                $this->view->renderAdmin(RENDER_VIEW_TAG_INDEX);
            }
        } else {
            $this->login();
        }
    }
}
