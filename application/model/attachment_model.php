<?php
Model::autoloadModel('post');

class AttachmentModel extends PostModel
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function addAttachmentToDatabase(AttachmentBO $attachmentInfo)
    {
        try {
            $image_id = $this->addPostToDatabase($attachmentInfo);
            if (!is_null($image_id)) {
                if (isset($attachmentInfo->attachment_metadata)) {
                    $attachment_metadata = json_encode($attachmentInfo->attachment_metadata);
                    $this->addPostMetaInfoToDatabase($image_id, 'attachment_metadata', $attachment_metadata);
                }
                if (isset($attachmentInfo->attached_file)) {
                    $this->addPostMetaInfoToDatabase($image_id, 'attached_file', $attachmentInfo->attached_file);
                }
            }
            return $image_id;
        } catch (Exception $e) {
            
        }
        return NULL;
    }

    public function getAttachment($attachment_id)
    {
        $attachmentBO = $this->getPost($attachment_id);
        if (isset($attachmentBO->attachment_metadata) && !is_object($attachmentBO->attachment_metadata)) {
            $attachmentBO->attachment_metadata = json_decode($attachmentBO->attachment_metadata);
        }
        return $attachmentBO;
    }
}
