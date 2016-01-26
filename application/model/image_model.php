<?php
Model::autoloadModel('attachment');

class ImageModel extends AttachmentModel
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function uploadImage($name, $tmp_name, $is_thumb = true)
    {
        $this->autoloadBO('image');
        $imageInfo = new ImageBO();
        $imageSizes = getimagesize($tmp_name);
        $attachment_metadata = new Image_MetadataBO();
        $attachment_metadata->width = $imageSizes[0];
        $attachment_metadata->height = $imageSizes[1];
        $image_meta = new Image_MetaBO();
        $image_meta->aperture = 0;
        $image_meta->credit = "";
        $image_meta->camera = "";
        $image_meta->caption = "";
        $image_meta->created_timestamp = 0;
        $image_meta->copyright = "";
        $image_meta->focal_length = 0;
        $image_meta->iso = 0;
        $image_meta->shutter_speed = 0;
        $image_meta->title = "";
        $image_meta->orientation = 1;
        $image_meta->keywords = "";
        $attachment_metadata->image_meta = $image_meta;

        $imageInfo->comment_count = 0;
        $imageInfo->comment_status = "open";

        $imageInfo->menu_order = 0;
        $imageInfo->ping_status = "open";
        $imageInfo->pinged = "";
        $imageInfo->post_author = Session::get("user_id");
        $imageInfo->post_content = $name;
        $imageInfo->post_content_filtered = "";
        $imageInfo->post_date = date("Y-m-d H:i:s");
        $imageInfo->post_date_gmt = gmdate("Y-m-d H:i:s");
        $imageInfo->post_excerpt = "";
        $imageInfo->post_mime_type = Utils::_mime_content_type($name);
        $imageInfo->post_modified = $imageInfo->post_date;
        $imageInfo->post_modified_gmt = $imageInfo->post_date_gmt;
        $imageInfo->post_name = $name;
        $imageInfo->post_parent = 0;
        $imageInfo->post_password = "";
        $imageInfo->post_status = "inherit";
        $imageInfo->post_title = $name;
        $imageInfo->post_type = "attachment";

        $year = date("Y");
        $month = date("n");
        if (!Utils::checkCreateFolder(PUBLIC_UPLOAD_PATH . $year)) {
            return NULL;
        }

        if (!Utils::checkCreateFolder(PUBLIC_UPLOAD_PATH . $year . '/' . $month)) {
            return NULL;
        }

        $folder = PUBLIC_UPLOAD_PATH . $year . '/' . $month . '/';
        $path_parts = pathinfo($name);
        $date = new DateTime();
        $name = $date->getTimestamp() . mt_rand();
        $file_name = $name . '.' . $path_parts['extension'];

        if (!copy($tmp_name, $folder . $file_name)) {
            return NULL;
        }
        $imageInfo->guid = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $file_name;
        $imageInfo->attachment_post = $imageInfo;
        if ($is_thumb) {
            $attachment_metadata->sizes = new Image_SizesBO();
            if ($attachment_metadata->width >= SIZE_WITH_SLIDER_THUMB) {
                $with = SIZE_WITH_SLIDER_THUMB;
                $height = SIZE_WITH_SLIDER_THUMB * $imageSizes[1] / $imageSizes[0];
                $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                    $attachment_metadata->sizes->slider_thumb = new ThumbnailBO($file_name_thumb, $file_path);
                }
                if ($attachment_metadata->width >= SIZE_WITH_THUMBNAIL) {
                    $with = SIZE_WITH_THUMBNAIL;
                    $height = SIZE_WITH_THUMBNAIL * $imageSizes[1] / $imageSizes[0];
                    $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                    $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                    if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                        $attachment_metadata->sizes->thumbnail = new ThumbnailBO($file_name_thumb, $file_path);
                    }
                    if ($attachment_metadata->width >= SIZE_WITH_POST_THUMBNAIL) {
                        $with = SIZE_WITH_POST_THUMBNAIL;
                        $height = SIZE_WITH_POST_THUMBNAIL * $imageSizes[1] / $imageSizes[0];
                        $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                        $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                        if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                            $attachment_metadata->sizes->post_thumbnail = new ThumbnailBO($file_name_thumb, $file_path);
                        }
                        if ($attachment_metadata->width >= SIZE_WITH_MEDIUM) {
                            $with = SIZE_WITH_MEDIUM;
                            $height = SIZE_WITH_MEDIUM * $imageSizes[1] / $imageSizes[0];
                            $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                            $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                            if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                                $attachment_metadata->sizes->medium = new ThumbnailBO($file_name_thumb, $file_path);
                            }
                            if ($attachment_metadata->width >= SIZE_WITH_MEDIUM_LARGE) {
                                $with = SIZE_WITH_MEDIUM_LARGE;
                                $height = SIZE_WITH_MEDIUM_LARGE * $imageSizes[1] / $imageSizes[0];
                                $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                                $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                                if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                                    $attachment_metadata->sizes->medium_large = new ThumbnailBO($file_name_thumb, $file_path);
                                }
                                if ($attachment_metadata->width >= SIZE_WITH_LARGE) {
                                    $with = SIZE_WITH_LARGE;
                                    $height = SIZE_WITH_LARGE * $imageSizes[1] / $imageSizes[0];
                                    $file_name_thumb = $folder . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                                    $file_path = PUBLIC_UPLOAD_REL . $year . "/" . $month . "/" . $name . "-" . $with . "x" . round($height) . "." . $path_parts['extension'];
                                    if ($this->resize_image($tmp_name, $file_name_thumb, $with, $height)) {
                                        $attachment_metadata->sizes->large = new ThumbnailBO($file_name_thumb, $file_path);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $imageInfo->attachment_metadata = $attachment_metadata;

        return $this->addAttachmentToDatabase($imageInfo);
    }

    /**
     * 
     * @param type $name_image
     * @return boolean images_id
     */
    public function uploadImages($name_image, $is_thumb = true)
    {
        if (isset($_FILES[$name_image])) {
            $images_id_array = array();
            //You need to handle both cases
            //If Any browser does not support serializing of multiple images using FormData() 
            if (!is_array($_FILES[$name_image]['name'])) { //single image
                $image_id = $this->uploadImage($_FILES[$name_image]['name'], $_FILES[$name_image]['tmp_name']);
                $images_id_array[] = $image_id;
            } else {  //Multiple images, image[]
                $image_count = count($_FILES[$name_image]['name']);
                for ($i = 0; $i < $image_count; $i++) {
                    $image_id = $this->uploadImage($_FILES[$name_image]['name'][$i], $_FILES[$name_image]['tmp_name'][$i]);
                    if ($image_id == FALSE) {
                        return FALSE;
                    } else {
                        $images_id_array[] = $image_id;
                    }
                }
            }
            return $images_id_array;
        }
    }

    /**
     * 
     * @param type $source_image
     * @param type $destination_filename
     * @param type $width
     * @param type $height
     * @param type $quality
     * @param type $crop
     * @return boolean
     */
    public function resize_image($source_image, $destination_filename, $width = 1024, $height = 1024, $quality = 100, $crop = true)
    {
        $image_data = getimagesize($source_image);
        if (!$image_data) {
            return false;
        }

        // set to-be-used function according to filetype
        switch ($image_data['mime']) {
            case 'image/gif':
                $get_func = 'imagecreatefromgif';
                $suffix = ".gif";
                break;
            case 'image/jpeg';
                $get_func = 'imagecreatefromjpeg';
                $suffix = ".jpg";
                break;
            case 'image/png':
                $get_func = 'imagecreatefrompng';
                $suffix = ".png";
                break;
        }

        $img_original = call_user_func($get_func, $source_image);
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round($old_width / $old_height, 2);
        $desired_ratio_after = round($width / $height, 2);
        $desired_ratio_before = round($height / $width, 2);

        if ($old_width < $width OR $old_height < $height) {
            // the desired image size is bigger than the original image. Best not to do anything at all really.
            // save original file to folder avatar        
            if (!copy($source_image, $destination_filename)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }

        // if crop is on: it will take an image and best fit it so it will always come out the exact specified size.
        if ($crop) {
            // create empty image of the specified size
            $new_image = imagecreatetruecolor($width, $height);

            // landscape image
            if ($current_ratio > $desired_ratio_after) {
                $new_width = $old_width * $height / $old_height;
            }

            // nearly square ratio image
            if ($current_ratio > $desired_ratio_before AND $current_ratio < $desired_ratio_after) {

                if ($old_width > $old_height) {
                    $new_height = max($width, $height);
                    $new_width = $old_width * $new_height / $old_height;
                } else {
                    $new_height = $old_height * $width / $old_width;
                }
            }

            // portrait sized image
            if ($current_ratio < $desired_ratio_before) {
                $new_height = $old_height * $width / $old_width;
            }

            // find ratio of original image to find where to crop
            $width_ratio = $old_width / $new_width;
            $height_ratio = $old_height / $new_height;

            // calculate where to crop based on the center of the image
            $src_x = floor((($new_width - $width) / 2) * $width_ratio);
            $src_y = round((($new_height - $height) / 2) * $height_ratio);
        }
        // don't crop the image, just resize it proportionally
        else {
            if ($old_width > $old_height) {
                $ratio = max($old_width, $old_height) / max($width, $height);
            } else {
                $ratio = max($old_width, $old_height) / min($width, $height);
            }

            $new_width = $old_width / $ratio;
            $new_height = $old_height / $ratio;
            $new_image = imagecreatetruecolor($new_width, $new_height);
        }

        // create avatar thumbnail
        imagecopyresampled($new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height);

        // save it as a .jpg file with our $destination_filename parameter
        imagejpeg($new_image, $destination_filename, $quality);

        // delete "working copy" and original file, keep the thumbnail
        imagedestroy($new_image);
        imagedestroy($img_original);

        if (file_exists($destination_filename)) {
            return true;
        }
        // default return
        return false;
    }
}
