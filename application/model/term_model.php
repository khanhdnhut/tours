<?php

class TermModel extends Model
{

    public function addTermMetaInfoToDatabase($term_id, $meta_key, $meta_value)
    {
        try {
            $sql2 = "insert into " . TABLE_TERMMETA . " 
                            (" . TB_TERMMETA_COL_TERM_ID . ",
                             " . TB_TERMMETA_COL_META_KEY . ",
                             " . TB_TERMMETA_COL_META_VALUE . ")
                values (:term_id,
                        :meta_key,
                        :meta_value);";
            $sth2 = $this->db->prepare($sql2);

            $sth2->execute(array(
                ":term_id" => $term_id,
                ":meta_key" => $meta_key,
                ":meta_value" => $meta_value
            ));
            $count2 = $sth2->rowCount();
            if ($count2 > 0) {
                $termMeta_id = $this->db->lastInsertId();
                return $termMeta_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function addTermToDatabase(TermBO $termBO)
    {
        try {
            $sql = "insert into " . TABLE_TERMS . " 
                            (" . TB_TERMS_COL_NAME . ",
                             " . TB_TERMS_COL_SLUG . ",
                             " . TB_TERMS_COL_TERM_GROUP . ")
                values (:name,
                        :slug,
                        :term_group);";
            $sth = $this->db->prepare($sql);

            $sth->execute(array(
                ":name" => $termBO->name,
                ":slug" => $termBO->slug,
                ":term_group" => $termBO->term_group
            ));

            $count = $sth->rowCount();
            if ($count > 0) {
                $term_id = $this->db->lastInsertId();
                return $term_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function updateTerm(TermBO $termBO)
    {
        try {
            $sql = "UPDATE " . TABLE_TERMS . " ";
            $set = "SET ";
            $where = " WHERE " . TB_TERMS_COL_TERM_ID . " = :term_id;";

            $para_array = [];
            $para_array[":term_id"] = $termBO->term_id;

            if (isset($termBO->name)) {
                $set .= " " . TB_TERMS_COL_NAME . " = :name,";
                $para_array[":name"] = $termBO->name;
            }
            if (isset($termBO->slug) && $termBO->slug != "") {
                $set .= " " . TB_TERMS_COL_SLUG . " = :slug,";
                $para_array[":slug"] = $termBO->slug;
            }
            if (isset($termBO->term_group)) {
                $set .= " " . TB_TERMS_COL_TERM_GROUP . " = :term_group,";
                $para_array[":term_group"] = $termBO->term_group;
            }

            if (count($para_array) != 0) {
                $set = substr($set, 0, strlen($set) - 1);
                $sql .= $set . $where;
                $sth = $this->db->prepare($sql);
                $sth->execute($para_array);
                return TRUE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function getTermMeta($term_id, $meta_key)
    {
        $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_TERMMETA . "
                                   WHERE  " . TB_TERMMETA_COL_TERM_ID . " = :term_id AND " . TB_TERMMETA_COL_META_KEY . " = :meta_key; ");

        $sth->execute(array(':term_id' => $term_id, ':meta_key' => $meta_key));
        $count = $sth->rowCount();
        if ($count != 1) {
            return NULL;
        }
        // fetch one row (we only have one result)
        $result = $sth->fetch();
        return $result->meta_value;
    }

    public function setTermMeta($term_id, $meta_key, $meta_value)
    {
        try {
            if (!is_null($this->getTermMeta($term_id, $meta_key))) {
                //update
                $sql = "UPDATE " . TABLE_TERMMETA . " 
                    SET " . TB_TERMMETA_COL_META_VALUE . " = :meta_value
                    WHERE " . TB_TERMMETA_COL_TERM_ID . " = :term_id AND " . TB_TERMMETA_COL_META_KEY . " = :meta_key;";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':term_id' => $term_id, ':meta_key' => $meta_key));
                $count = $sth->rowCount();
                if ($count != 1) {
                    return false;
                }
            } else {
                //insert
                $sql = "INSERT INTO " . TABLE_TERMMETA . " 
                                (" . TB_TERMMETA_COL_TERM_ID . ",
                                 " . TB_TERMMETA_COL_META_KEY . ",
                                 " . TB_TERMMETA_COL_META_VALUE . ")
                    VALUES (:term_id, :meta_key, :meta_value);";
                $sth = $this->db->prepare($sql);
                $sth->execute(array(':meta_value' => $meta_value, ':term_id' => $term_id, ':meta_key' => $meta_key));
                $count = $sth->rowCount();
                if ($count != 1) {
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function getTerm($term_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_TERMS . "
                                   WHERE  " . TB_TERMS_COL_TERM_ID . " = :term_id");

            $sth->execute(array(':term_id' => $term_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return null;
            }
            // fetch one row (we only have one result)
            $result = $sth->fetch();
            $this->autoloadBO('term');
            $termBO = new TermBO();
            $termBO->setTermInfo($result);
            $termMetaInfoArray = $this->getTermMetaInfo($result->ID);
            $termBO->setTermMetaInfo($termMetaInfoArray);
            return $termBO;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getTermMetaInfo($term_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_TERMMETA . "
                                   WHERE  " . TB_TERMMETA_COL_TERM_ID . " = :term_id");

            $sth->execute(array(':term_id' => $term_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                return $sth->fetchAll();
            } else {
                return null;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function deleteTerm($term_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_TERMS . "
                                   WHERE  " . TB_TERMS_COL_TERM_ID . " = :term_id");
            $sth->execute(array(':term_id' => $term_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                $this->deleteTermMeta($term_id);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function deleteTermMeta($term_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_TERMMETA . "
                                   WHERE  " . TB_TERMMETA_COL_TERM_ID . " = :term_id");
            $sth->execute(array(':term_id' => $term_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }
}
