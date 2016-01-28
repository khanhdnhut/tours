<?php
Model::autoloadModel('term');

class TaxonomyModel extends TermModel
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function updateTerm(TaxonomyBO $taxonomyBO)
    {
        try {
            $sql = "UPDATE " . TABLE_TERM_TAXONOMY . " ";
            $set = "SET ";
            $where = " WHERE " . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id;";

            $para_array = [];
            $para_array[":term_taxonomy_id"] = $taxonomyBO->term_taxonomy_id;

            if (isset($taxonomyBO->count)) {
                $set .= " " . TB_TERM_TAXONOMY_COL_COUNT . " = :count,";
                $para_array[":count"] = $taxonomyBO->count;
            }
            if (isset($taxonomyBO->description)) {
                $set .= " " . TB_TERM_TAXONOMY_COL_DESCRIPTION . " = :description,";
                $para_array[":description"] = $taxonomyBO->description;
            }
            if (isset($taxonomyBO->parent)) {
                $set .= " " . TB_TERM_TAXONOMY_COL_PARENT . " = :parent,";
                $para_array[":parent"] = $taxonomyBO->parent;
            }

            if (count($para_array) != 0) {
                $set = substr($set, 0, strlen($set) - 1);
                $sql .= $set . $where;
                $sth = $this->db->prepare($sql);
                $sth->execute($para_array);
                parent::updateTerm($taxonomyBO);
                return TRUE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function addTaxonomyToDatabase(TaxonomyBO $taxonomyBO)
    {
        try {
            $term_id = $this->addTermToDatabase($taxonomyBO);
            if (!is_null($term_id)) {
                try {
                    $sql = "insert into " . TABLE_TERM_TAXONOMY . " 
                            (" . TB_TERM_TAXONOMY_COL_COUNT . ",
                             " . TB_TERM_TAXONOMY_COL_DESCRIPTION . ",
                             " . TB_TERM_TAXONOMY_COL_PARENT . ",
                             " . TB_TERM_TAXONOMY_COL_TAXONOMY . ",
                             " . TB_TERM_TAXONOMY_COL_TERM_ID . ")
                values (:count,
                        :description,
                        :parent,
                        :taxonomy,
                        :term_id);";
                    $sth = $this->db->prepare($sql);

                    $sth->execute(array(
                        ":count" => $taxonomyBO->count,
                        ":description" => $taxonomyBO->description,
                        ":parent" => $taxonomyBO->parent,
                        ":taxonomy" => $taxonomyBO->taxonomy,
                        ":term_id" => $term_id
                    ));

                    $count = $sth->rowCount();
                    if ($count > 0) {
                        $taxonomy_id = $this->db->lastInsertId();
                        return $taxonomy_id;
                    }
                } catch (Exception $e) {
                    
                }
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function getTerm($taxonomy_id)
    {

        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_TERM_TAXONOMY . "
                                   WHERE  " . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id");

            $sth->execute(array(':term_taxonomy_id' => $taxonomy_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return null;
            }
            $result = $sth->fetch();
            $this->autoloadBO('taxonomy');
            $taxonomyBO = new TaxonomyBO();
            $taxonomyBO->setTaxonomyInfo($result);

            $termBO = parent::getTerm($result->term_id);
            $taxonomyBO->setTermInfo($termBO);
            $termMetaInfoArray = $this->getTermMetaInfo($termBO->term_id);
            $taxonomyBO->setTermMetaInfo($termMetaInfoArray);
            return $taxonomyBO;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function deleteTerm($taxonomy_id)
    {
        $termBO = $this->getTerm($taxonomy_id);

        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_TERM_TAXONOMY . "
                                   WHERE  " . TB_TERM_TAXONOMY_COL_TERM_TAXONOMY_ID . " = :term_taxonomy_id");
            $sth->execute(array(':term_taxonomy_id' => $taxonomy_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                parent::deleteTerm($termBO->term_id);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }
}
