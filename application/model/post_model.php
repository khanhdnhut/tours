<?php

class PostModel extends Model
{

    public function addPostMetaInfoToDatabase($post_id, $meta_key, $meta_value)
    {
        try {
            $sql2 = "insert into " . TABLE_POSTMETA . " 
                            (" . TB_POSTMETA_COL_POST_ID . ",
                             " . TB_POSTMETA_COL_META_KEY . ",
                             " . TB_POSTMETA_COL_META_VALUE . ")
                values (:post_id,
                        :meta_key,
                        :meta_value);";
            $sth2 = $this->db->prepare($sql2);

            $sth2->execute(array(
                ":post_id" => $post_id,
                ":meta_key" => $meta_key,
                ":meta_value" => $meta_value
            ));
            $count2 = $sth2->rowCount();
            if ($count2 > 0) {
                $postMeta_id = $this->db->lastInsertId();
                return $postMeta_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function addPostToDatabase(PostBO $postBO)
    {
        try {
            $sql = "insert into " . TABLE_POSTS . " 
                            (" . TB_POST_COL_POST_AUTHOR . ",
                             " . TB_POST_COL_POST_DATE . ",
                             " . TB_POST_COL_POST_DATE_GMT . ",
                             " . TB_POST_COL_POST_TITLE . ",
                             " . TB_POST_COL_POST_STATUS . ",
                             " . TB_POST_COL_COMMENT_STATUS . ",
                             " . TB_POST_COL_PING_STATUS . ",
                             " . TB_POST_COL_POST_NAME . ",
                             " . TB_POST_COL_POST_MODIFIED . ",
                             " . TB_POST_COL_POST_MODIFIED_GMT . ",
                            " . TB_POST_COL_POST_PARENT . ",
                             " . TB_POST_COL_GUID . ",
                             " . TB_POST_COL_MENU_ORDER . ",
                             " . TB_POST_COL_POST_TYPE . ",
                             " . TB_POST_COL_POST_MIME_TYPE . ",
                             " . TB_POST_COL_COMMENT_COUNT . ")
                values (:post_author,
                        :post_date,
                        :post_date_gmt,
                        :post_title,
                        :post_status,
                        :comment_status,
                        :ping_status,
                        :post_name,
                        :post_modified,
                        :post_modified_gmt,
                        :post_parent,
                        :guid,
                        :menu_order,
                        :post_type,
                        :post_mime_type,
                        :comment_count);";
            $sth = $this->db->prepare($sql);

            $sth->execute(array(
                ":post_author" => $postBO->post_author,
                ":post_date" => $postBO->post_date,
                ":post_date_gmt" => $postBO->post_date_gmt,
                ":post_title" => $postBO->post_title,
                ":post_status" => $postBO->post_status,
                ":comment_status" => $postBO->comment_status,
                ":ping_status" => $postBO->ping_status,
                ":post_name" => $postBO->post_name,
                ":post_modified" => $postBO->post_modified,
                ":post_modified_gmt" => $postBO->post_modified_gmt,
                ":post_parent" => $postBO->post_parent,
                ":guid" => $postBO->guid,
                ":menu_order" => $postBO->menu_order,
                ":post_type" => $postBO->post_type,
                ":post_mime_type" => $postBO->post_mime_type,
                ":comment_count" => $postBO->comment_count
            ));

            $count = $sth->rowCount();
            if ($count > 0) {
                $post_id = $this->db->lastInsertId();
                return $post_id;
            }
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function getPost($post_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_POSTS . "
                                   WHERE  " . TB_POST_COL_ID . " = :post_id");

            $sth->execute(array(':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count != 1) {
                return null;
            }
            // fetch one row (we only have one result)
            $result = $sth->fetch();
            $this->autoloadBO('post');
            $postBO = new PostBO();
            $postBO->setPost($result);
            $postMetaInfoArray = $this->getPostMetaInfo($result->ID);
            $postBO->setPostMetaInfo($postMetaInfoArray);
            return $postBO;
        } catch (Exception $e) {
            
        }
        return null;
    }

    public function getPostMetaInfo($post_id)
    {
        try {
            $sth = $this->db->prepare("SELECT *
                                   FROM   " . TABLE_POSTMETA . "
                                   WHERE  " . TB_POSTMETA_COL_POST_ID . " = :post_id");

            $sth->execute(array(':post_id' => $post_id));
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

    public function deletePost($post_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_POSTS . "
                                   WHERE  " . TB_POST_COL_ID . " = :post_id");
            $sth->execute(array(':post_id' => $post_id));
            $count = $sth->rowCount();
            if ($count > 0) {
                $this->deletePostMeta($post_id);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            
        }
        return FALSE;
    }

    public function deletePostMeta($post_id)
    {
        try {
            $sth = $this->db->prepare("DELETE 
                                   FROM   " . TABLE_POSTMETA . "
                                   WHERE  " . TB_POSTMETA_COL_POST_ID . " = :post_id");
            $sth->execute(array(':post_id' => $post_id));
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
