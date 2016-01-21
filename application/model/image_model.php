<?php

class ImageModel extends Model
{

    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db)
    {
        parent::__construct($db);
    }

    public function checkCreateFolder($folder)
    {
        try {
            if (!file_exists($folder)) {
//                if(mkdir($folder, 0777)){
                if (mkdir($folder)) {
                    return true;
                }
            } else {
                return TRUE;
            }
        } catch (Exception $ex) {
            
        }
        return false;
    }

    /**
     * 
     * @param type $name_image
     * @return boolean images_id
     */
    public function uploadImages($name_image, $is_thumb = true)
    {
        if (isset($_FILES[$name_image])) {
            $images_id = array();


            //You need to handle both cases
            //If Any browser does not support serializing of multiple images using FormData() 
            if (!is_array($_FILES[$name_image]["name"])) { //single image
                $this->autoloadBO('image');
                $imageInfo = new ImageBO();
                $imageSizes = getimagesize($_FILES[$name_image]['tmp_name']);
                $imageInfo->attachment_metadata = new Image_MetadataBO();
                $imageInfo->attachment_metadata->width = $imageSizes[0];
                $imageInfo->attachment_metadata->height = $imageSizes[1];
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
                $imageInfo->attachment_metadata->image_meta = $image_meta;

                $image_post = new PostBO();

                $image_post->comment_count = 0;
                $image_post->comment_status = "open";

                $image_post->menu_order = 0;
                $image_post->ping_status = "open";
                $image_post->pinged = "";
                $image_post->post_author = Session::get("user_id");
                $image_post->post_content = $_FILES[$name_image]["name"];
                $image_post->post_content_filtered = "";
                $image_post->post_date = date("Y-m-d H:i:s");
                $image_post->post_date_gmt = gmdate("Y-m-d H:i:s");
                $image_post->post_excerpt = "";
                $image_post->post_mime_type = $this->_mime_content_type($_FILES[$name_image]['name']);
                $image_post->post_modified = $image_post->post_date;
                $image_post->post_modified_gmt = $image_post->post_date_gmt;
                $image_post->post_name = $_FILES[$name_image]["name"];
                $image_post->post_parent = 0;
                $image_post->post_password = "";
                $image_post->post_status = "inherit";
                $image_post->post_title = $_FILES[$name_image]["name"];
                $image_post->post_type = "attachment";

                $year = date("Y");
                $month = date("n");
                if (!$this->checkCreateFolder(PUBLIC_UPLOAD_PATH . $year)) {
                    return NULL;
                }

                if (!$this->checkCreateFolder(PUBLIC_UPLOAD_PATH . $year . DIRECTORY_SEPARATOR . $month)) {
                    return NULL;
                }
                
                $folder = PUBLIC_UPLOAD_PATH . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
                $path_parts = pathinfo($_FILES[$name_image]['name']);
                $date = new DateTime();
                $name = $date->getTimestamp() . mt_rand();
                $file_name = $name . '.' . $path_parts['extension'];

                if (!$this->copyImage($_FILES[$name_image]['tmp_name'], $folder . $file_name)) {
                    return NULL;
                }
                $image_post->guid = PUBLIC_UPLOAD . $year . "/" . $month . "/" . $file_name;
                $imageInfo->attachment_post = $image_post;
                if ($is_thumb) {
                    $imageInfo->attachment_metadata->sizes = new Image_SizesBO();
                    if ($imageInfo->attachment_metadata->width >= SIZE_WITH_SLIDER_THUMB) {
                        $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_SLIDER_THUMB . "." . $path_parts['extension'];
                        if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_SLIDER_THUMB, SIZE_WITH_SLIDER_THUMB * $imageSizes[1] / $imageSizes[0])) {
                            $slider_thumb = new ThumbnailBO();
                            $slider_thumb->file = $file_name_thumb;
                            $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                            $imageThumbSizes = getimagesize($file_name_thumb);
                            $slider_thumb->width = $imageThumbSizes[0];
                            $slider_thumb->height = $imageThumbSizes[1];
                            $imageInfo->attachment_metadata->sizes->slider_thumb = $slider_thumb;
                        }
                        if ($imageInfo->attachment_metadata->width >= SIZE_WITH_THUMBNAIL) {
                            $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_THUMBNAIL . "." . $path_parts['extension'];
                            if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_THUMBNAIL, SIZE_WITH_THUMBNAIL * $imageSizes[1] / $imageSizes[0])) {
                                $slider_thumb = new ThumbnailBO();
                                $slider_thumb->file = $file_name_thumb;
                                $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                                $imageThumbSizes = getimagesize($file_name_thumb);
                                $slider_thumb->width = $imageThumbSizes[0];
                                $slider_thumb->height = $imageThumbSizes[1];
                                $imageInfo->attachment_metadata->sizes->thumbnail = $slider_thumb;
                            }
                            if ($imageInfo->attachment_metadata->width >= SIZE_WITH_POST_THUMBNAIL) {
                                $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_POST_THUMBNAIL . "." . $path_parts['extension'];
                                if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_POST_THUMBNAIL, SIZE_WITH_POST_THUMBNAIL * $imageSizes[1] / $imageSizes[0])) {
                                    $slider_thumb = new ThumbnailBO();
                                    $slider_thumb->file = $file_name_thumb;
                                    $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                                    $imageThumbSizes = getimagesize($file_name_thumb);
                                    $slider_thumb->width = $imageThumbSizes[0];
                                    $slider_thumb->height = $imageThumbSizes[1];
                                    $imageInfo->attachment_metadata->sizes->post_thumbnail = $slider_thumb;
                                }
                                if ($imageInfo->attachment_metadata->width >= SIZE_WITH_MEDIUM) {
                                    $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_MEDIUM . "." . $path_parts['extension'];
                                    if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_MEDIUM, SIZE_WITH_MEDIUM * $imageSizes[1] / $imageSizes[0])) {
                                        $slider_thumb = new ThumbnailBO();
                                        $slider_thumb->file = $file_name_thumb;
                                        $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                                        $imageThumbSizes = getimagesize($file_name_thumb);
                                        $slider_thumb->width = $imageThumbSizes[0];
                                        $slider_thumb->height = $imageThumbSizes[1];
                                        $imageInfo->attachment_metadata->sizes->medium = $slider_thumb;
                                    }
                                    if ($imageInfo->attachment_metadata->width >= SIZE_WITH_MEDIUM_LARGE) {
                                        $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_MEDIUM_LARGE . "." . $path_parts['extension'];
                                        if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_MEDIUM_LARGE, SIZE_WITH_MEDIUM_LARGE * $imageSizes[1] / $imageSizes[0])) {
                                            $slider_thumb = new ThumbnailBO();
                                            $slider_thumb->file = $file_name_thumb;
                                            $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                                            $imageThumbSizes = getimagesize($file_name_thumb);
                                            $slider_thumb->width = $imageThumbSizes[0];
                                            $slider_thumb->height = $imageThumbSizes[1];
                                            $imageInfo->attachment_metadata->sizes->medium_large = $slider_thumb;
                                        }
                                        if ($imageInfo->attachment_metadata->width >= SIZE_WITH_LARGE) {
                                            $file_name_thumb = $folder .  $name . "-" . SIZE_WITH_LARGE . "." . $path_parts['extension'];
                                            if ($this->resize_image($_FILES[$name_image]['tmp_name'], $file_name_thumb, SIZE_WITH_LARGE, SIZE_WITH_LARGE * $imageSizes[1] / $imageSizes[0])) {
                                                $slider_thumb = new ThumbnailBO();
                                                $slider_thumb->file = $file_name_thumb;
                                                $slider_thumb->mine_type = $this->_mime_content_type($file_name_thumb);
                                                $imageThumbSizes = getimagesize($file_name_thumb);
                                                $slider_thumb->width = $imageThumbSizes[0];
                                                $slider_thumb->height = $imageThumbSizes[1];
                                                $imageInfo->attachment_metadata->sizes->large = $slider_thumb;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $image_id = $this->addImageToDatabase($imageInfo);
                if ($image_id == FALSE) {
                    return FALSE;
                } else {
                    $images_id[] = $image_id;
                }
            } else {  //Multiple images, image[]
//                $image_count = count($_FILES[$name_image]["name"]);
//                for ($i = 0; $i < $image_count; $i++) {
//                    $image_id = $this->insert_image_upload($_FILES[$name_image]['name'][$i], $_FILES[$name_image]['tmp_name'][$i]);
//                    if ($image_id == FALSE) {
//                        return FALSE;
//                    } else {
//                        $images_id[] = $image_id;
//                    }
//                }
            }
            return $images_id;
        }
    }

    public function addImageToDatabase($imageInfo)
    {
        try {
            $image_post = $imageInfo->attachment_post;
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
                ":post_author" => $image_post->post_author,
                ":post_date" => $image_post->post_date,
                ":post_date_gmt" => $image_post->post_date_gmt,
                ":post_title" => $image_post->post_title,
                ":post_status" => $image_post->post_status,
                ":comment_status" => $image_post->comment_status,
                ":ping_status" => $image_post->ping_status,
                ":post_name" => $image_post->post_name,
                ":post_modified" => $image_post->post_modified,
                ":post_modified_gmt" => $image_post->post_modified_gmt,
                ":post_parent" => $image_post->post_parent,
                ":guid" => $image_post->guid,
                ":menu_order" => $image_post->menu_order,
                ":post_type" => $image_post->post_type,
                ":post_mime_type" => $image_post->post_mime_type,
                ":comment_count" => $image_post->comment_count
            ));

            $count = $sth->rowCount();
            if ($count > 0) {
                $post_id = $this->db->lastInsertId();
                $attachment_metadata = json_encode($imageInfo->attachment_metadata);

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
                    ":meta_key" => "attachment_metadata",
                    ":meta_value" => $attachment_metadata
                ));
                $count2 = $sth2->rowCount();
                return $post_id;
            }
        } catch (Exception $e) {
            
        }
        return -1;
    }

    public function copyImage($source_image, $destination_original_filename)
    {
        try {
            if (copy($source_image, $destination_original_filename)) {
                return TRUE;
            }
        } catch (Exception $ex) {
            
        }
        return FALSE;
    }

    public function deleteImage($source_image)
    {
        try {
            if (unlink($source_image)) {
                return TRUE;
            }
        } catch (Exception $ex) {
            
        }
        return FALSE;
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

    function _mime_content_type($filename)
    {
        $realpath = realpath($filename);
        if ($realpath && function_exists('finfo_file') && function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')
        ) {
            // Use the Fileinfo PECL extension (PHP 5.3+)
            return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
        }
        if (function_exists('mime_content_type')) {
            // Deprecated in PHP 5.3
            return mime_content_type($realpath);
        }
        $GetFileFormatArray = $this->GetFileFormatArray();
        $path_parts = pathinfo($filename);
        $extension = $path_parts['extension'];
        $info = $GetFileFormatArray[$extension];
        return $info['mime_type'];
    }

    public function GetFileFormatArray()
    {
        static $format_info = array();
        if (empty($format_info)) {
            $format_info = array(
                // Audio formats
                // AC-3   - audio      - Dolby AC-3 / Dolby Digital
                'ac3' => array(
                    'pattern' => '^\x0B\x77',
                    'group' => 'audio',
                    'module' => 'ac3',
                    'mime_type' => 'audio/ac3',
                ),
                // AAC  - audio       - Advanced Audio Coding (AAC) - ADIF format
                'adif' => array(
                    'pattern' => '^ADIF',
                    'group' => 'audio',
                    'module' => 'aac',
                    'mime_type' => 'application/octet-stream',
                    'fail_ape' => 'WARNING',
                ),
                /*
                  // AA   - audio       - Audible Audiobook
                  'aa'   => array(
                  'pattern'   => '^.{4}\x57\x90\x75\x36',
                  'group'     => 'audio',
                  'module'    => 'aa',
                  'mime_type' => 'audio/audible',
                  ),
                 */
                // AAC  - audio       - Advanced Audio Coding (AAC) - ADTS format (very similar to MP3)
                'adts' => array(
                    'pattern' => '^\xFF[\xF0-\xF1\xF8-\xF9]',
                    'group' => 'audio',
                    'module' => 'aac',
                    'mime_type' => 'application/octet-stream',
                    'fail_ape' => 'WARNING',
                ),
                // AU   - audio       - NeXT/Sun AUdio (AU)
                'au' => array(
                    'pattern' => '^\.snd',
                    'group' => 'audio',
                    'module' => 'au',
                    'mime_type' => 'audio/basic',
                ),
                // AMR  - audio       - Adaptive Multi Rate
                'amr' => array(
                    'pattern' => '^\x23\x21AMR\x0A', // #!AMR[0A]
                    'group' => 'audio',
                    'module' => 'amr',
                    'mime_type' => 'audio/amr',
                ),
                // AVR  - audio       - Audio Visual Research
                'avr' => array(
                    'pattern' => '^2BIT',
                    'group' => 'audio',
                    'module' => 'avr',
                    'mime_type' => 'application/octet-stream',
                ),
                // BONK - audio       - Bonk v0.9+
                'bonk' => array(
                    'pattern' => '^\x00(BONK|INFO|META| ID3)',
                    'group' => 'audio',
                    'module' => 'bonk',
                    'mime_type' => 'audio/xmms-bonk',
                ),
                // DSS  - audio       - Digital Speech Standard
                'dss' => array(
                    'pattern' => '^[\x02-\x03]ds[s2]',
                    'group' => 'audio',
                    'module' => 'dss',
                    'mime_type' => 'application/octet-stream',
                ),
                // DTS  - audio       - Dolby Theatre System
                'dts' => array(
                    'pattern' => '^\x7F\xFE\x80\x01',
                    'group' => 'audio',
                    'module' => 'dts',
                    'mime_type' => 'audio/dts',
                ),
                // FLAC - audio       - Free Lossless Audio Codec
                'flac' => array(
                    'pattern' => '^fLaC',
                    'group' => 'audio',
                    'module' => 'flac',
                    'mime_type' => 'audio/x-flac',
                ),
                // LA   - audio       - Lossless Audio (LA)
                'la' => array(
                    'pattern' => '^LA0[2-4]',
                    'group' => 'audio',
                    'module' => 'la',
                    'mime_type' => 'application/octet-stream',
                ),
                // LPAC - audio       - Lossless Predictive Audio Compression (LPAC)
                'lpac' => array(
                    'pattern' => '^LPAC',
                    'group' => 'audio',
                    'module' => 'lpac',
                    'mime_type' => 'application/octet-stream',
                ),
                // MIDI - audio       - MIDI (Musical Instrument Digital Interface)
                'midi' => array(
                    'pattern' => '^MThd',
                    'group' => 'audio',
                    'module' => 'midi',
                    'mime_type' => 'audio/midi',
                ),
                // MAC  - audio       - Monkey's Audio Compressor
                'mac' => array(
                    'pattern' => '^MAC ',
                    'group' => 'audio',
                    'module' => 'monkey',
                    'mime_type' => 'application/octet-stream',
                ),
// has been known to produce false matches in random files (e.g. JPEGs), leave out until more precise matching available
//				// MOD  - audio       - MODule (assorted sub-formats)
//				'mod'  => array(
//							'pattern'   => '^.{1080}(M\\.K\\.|M!K!|FLT4|FLT8|[5-9]CHN|[1-3][0-9]CH)',
//							'group'     => 'audio',
//							'module'    => 'mod',
//							'option'    => 'mod',
//							'mime_type' => 'audio/mod',
//						),
                // MOD  - audio       - MODule (Impulse Tracker)
                'it' => array(
                    'pattern' => '^IMPM',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 'it',
                    'mime_type' => 'audio/it',
                ),
                // MOD  - audio       - MODule (eXtended Module, various sub-formats)
                'xm' => array(
                    'pattern' => '^Extended Module',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 'xm',
                    'mime_type' => 'audio/xm',
                ),
                // MOD  - audio       - MODule (ScreamTracker)
                's3m' => array(
                    'pattern' => '^.{44}SCRM',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 's3m',
                    'mime_type' => 'audio/s3m',
                ),
                // MPC  - audio       - Musepack / MPEGplus
                'mpc' => array(
                    'pattern' => '^(MPCK|MP\+|[\x00\x01\x10\x11\x40\x41\x50\x51\x80\x81\x90\x91\xC0\xC1\xD0\xD1][\x20-37][\x00\x20\x40\x60\x80\xA0\xC0\xE0])',
                    'group' => 'audio',
                    'module' => 'mpc',
                    'mime_type' => 'audio/x-musepack',
                ),
                // MP3  - audio       - MPEG-audio Layer 3 (very similar to AAC-ADTS)
                'mp3' => array(
                    'pattern' => '^\xFF[\xE2-\xE7\xF2-\xF7\xFA-\xFF][\x00-\x0B\x10-\x1B\x20-\x2B\x30-\x3B\x40-\x4B\x50-\x5B\x60-\x6B\x70-\x7B\x80-\x8B\x90-\x9B\xA0-\xAB\xB0-\xBB\xC0-\xCB\xD0-\xDB\xE0-\xEB\xF0-\xFB]',
                    'group' => 'audio',
                    'module' => 'mp3',
                    'mime_type' => 'audio/mpeg',
                ),
                // OFR  - audio       - OptimFROG
                'ofr' => array(
                    'pattern' => '^(\*RIFF|OFR)',
                    'group' => 'audio',
                    'module' => 'optimfrog',
                    'mime_type' => 'application/octet-stream',
                ),
                // RKAU - audio       - RKive AUdio compressor
                'rkau' => array(
                    'pattern' => '^RKA',
                    'group' => 'audio',
                    'module' => 'rkau',
                    'mime_type' => 'application/octet-stream',
                ),
                // SHN  - audio       - Shorten
                'shn' => array(
                    'pattern' => '^ajkg',
                    'group' => 'audio',
                    'module' => 'shorten',
                    'mime_type' => 'audio/xmms-shn',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TTA  - audio       - TTA Lossless Audio Compressor (http://tta.corecodec.org)
                'tta' => array(
                    'pattern' => '^TTA', // could also be '^TTA(\x01|\x02|\x03|2|1)'
                    'group' => 'audio',
                    'module' => 'tta',
                    'mime_type' => 'application/octet-stream',
                ),
                // VOC  - audio       - Creative Voice (VOC)
                'voc' => array(
                    'pattern' => '^Creative Voice File',
                    'group' => 'audio',
                    'module' => 'voc',
                    'mime_type' => 'audio/voc',
                ),
                // VQF  - audio       - transform-domain weighted interleave Vector Quantization Format (VQF)
                'vqf' => array(
                    'pattern' => '^TWIN',
                    'group' => 'audio',
                    'module' => 'vqf',
                    'mime_type' => 'application/octet-stream',
                ),
                // WV  - audio        - WavPack (v4.0+)
                'wv' => array(
                    'pattern' => '^wvpk',
                    'group' => 'audio',
                    'module' => 'wavpack',
                    'mime_type' => 'application/octet-stream',
                ),
                // Audio-Video formats
                // ASF  - audio/video - Advanced Streaming Format, Windows Media Video, Windows Media Audio
                'asf' => array(
                    'pattern' => '^\x30\x26\xB2\x75\x8E\x66\xCF\x11\xA6\xD9\x00\xAA\x00\x62\xCE\x6C',
                    'group' => 'audio-video',
                    'module' => 'asf',
                    'mime_type' => 'video/x-ms-asf',
                    'iconv_req' => false,
                ),
                // BINK - audio/video - Bink / Smacker
                'bink' => array(
                    'pattern' => '^(BIK|SMK)',
                    'group' => 'audio-video',
                    'module' => 'bink',
                    'mime_type' => 'application/octet-stream',
                ),
                // FLV  - audio/video - FLash Video
                'flv' => array(
                    'pattern' => '^FLV\x01',
                    'group' => 'audio-video',
                    'module' => 'flv',
                    'mime_type' => 'video/x-flv',
                ),
                // MKAV - audio/video - Mastroka
                'matroska' => array(
                    'pattern' => '^\x1A\x45\xDF\xA3',
                    'group' => 'audio-video',
                    'module' => 'matroska',
                    'mime_type' => 'video/x-matroska', // may also be audio/x-matroska
                ),
                // MPEG - audio/video - MPEG (Moving Pictures Experts Group)
                'mpeg' => array(
                    'pattern' => '^\x00\x00\x01(\xBA|\xB3)',
                    'group' => 'audio-video',
                    'module' => 'mpeg',
                    'mime_type' => 'video/mpeg',
                ),
                // NSV  - audio/video - Nullsoft Streaming Video (NSV)
                'nsv' => array(
                    'pattern' => '^NSV[sf]',
                    'group' => 'audio-video',
                    'module' => 'nsv',
                    'mime_type' => 'application/octet-stream',
                ),
                // Ogg  - audio/video - Ogg (Ogg-Vorbis, Ogg-FLAC, Speex, Ogg-Theora(*), Ogg-Tarkin(*))
                'ogg' => array(
                    'pattern' => '^OggS',
                    'group' => 'audio',
                    'module' => 'ogg',
                    'mime_type' => 'application/ogg',
                    'fail_id3' => 'WARNING',
                    'fail_ape' => 'WARNING',
                ),
                // QT   - audio/video - Quicktime
                'quicktime' => array(
                    'pattern' => '^.{4}(cmov|free|ftyp|mdat|moov|pnot|skip|wide)',
                    'group' => 'audio-video',
                    'module' => 'quicktime',
                    'mime_type' => 'video/quicktime',
                ),
                // RIFF - audio/video - Resource Interchange File Format (RIFF) / WAV / AVI / CD-audio / SDSS = renamed variant used by SmartSound QuickTracks (www.smartsound.com) / FORM = Audio Interchange File Format (AIFF)
                'riff' => array(
                    'pattern' => '^(RIFF|SDSS|FORM)',
                    'group' => 'audio-video',
                    'module' => 'riff',
                    'mime_type' => 'audio/x-wave',
                    'fail_ape' => 'WARNING',
                ),
                // Real - audio/video - RealAudio, RealVideo
                'real' => array(
                    'pattern' => '^(\\.RMF|\\.ra)',
                    'group' => 'audio-video',
                    'module' => 'real',
                    'mime_type' => 'audio/x-realaudio',
                ),
                // SWF - audio/video - ShockWave Flash
                'swf' => array(
                    'pattern' => '^(F|C)WS',
                    'group' => 'audio-video',
                    'module' => 'swf',
                    'mime_type' => 'application/x-shockwave-flash',
                ),
                // TS - audio/video - MPEG-2 Transport Stream
                'ts' => array(
                    'pattern' => '^(\x47.{187}){10,}', // packets are 188 bytes long and start with 0x47 "G".  Check for at least 10 packets matching this pattern
                    'group' => 'audio-video',
                    'module' => 'ts',
                    'mime_type' => 'video/MP2T',
                ),
                // Still-Image formats
                // BMP  - still image - Bitmap (Windows, OS/2; uncompressed, RLE8, RLE4)
                'bmp' => array(
                    'pattern' => '^BM',
                    'group' => 'graphic',
                    'module' => 'bmp',
                    'mime_type' => 'image/bmp',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // GIF  - still image - Graphics Interchange Format
                'gif' => array(
                    'pattern' => '^GIF',
                    'group' => 'graphic',
                    'module' => 'gif',
                    'mime_type' => 'image/gif',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // JPEG - still image - Joint Photographic Experts Group (JPEG)
                'jpg' => array(
                    'pattern' => '^\xFF\xD8\xFF',
                    'group' => 'graphic',
                    'module' => 'jpg',
                    'mime_type' => 'image/jpeg',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PCD  - still image - Kodak Photo CD
                'pcd' => array(
                    'pattern' => '^.{2048}PCD_IPI\x00',
                    'group' => 'graphic',
                    'module' => 'pcd',
                    'mime_type' => 'image/x-photo-cd',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PNG  - still image - Portable Network Graphics (PNG)
                'png' => array(
                    'pattern' => '^\x89\x50\x4E\x47\x0D\x0A\x1A\x0A',
                    'group' => 'graphic',
                    'module' => 'png',
                    'mime_type' => 'image/png',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // SVG  - still image - Scalable Vector Graphics (SVG)
                'svg' => array(
                    'pattern' => '(<!DOCTYPE svg PUBLIC |xmlns="http:\/\/www\.w3\.org\/2000\/svg")',
                    'group' => 'graphic',
                    'module' => 'svg',
                    'mime_type' => 'image/svg+xml',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TIFF - still image - Tagged Information File Format (TIFF)
                'tiff' => array(
                    'pattern' => '^(II\x2A\x00|MM\x00\x2A)',
                    'group' => 'graphic',
                    'module' => 'tiff',
                    'mime_type' => 'image/tiff',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // EFAX - still image - eFax (TIFF derivative)
                'efax' => array(
                    'pattern' => '^\xDC\xFE',
                    'group' => 'graphic',
                    'module' => 'efax',
                    'mime_type' => 'image/efax',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // Data formats
                // ISO  - data        - International Standards Organization (ISO) CD-ROM Image
                'iso' => array(
                    'pattern' => '^.{32769}CD001',
                    'group' => 'misc',
                    'module' => 'iso',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                    'iconv_req' => false,
                ),
                // RAR  - data        - RAR compressed data
                'rar' => array(
                    'pattern' => '^Rar\!',
                    'group' => 'archive',
                    'module' => 'rar',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // SZIP - audio/data  - SZIP compressed data
                'szip' => array(
                    'pattern' => '^SZ\x0A\x04',
                    'group' => 'archive',
                    'module' => 'szip',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TAR  - data        - TAR compressed data
                'tar' => array(
                    'pattern' => '^.{100}[0-9\x20]{7}\x00[0-9\x20]{7}\x00[0-9\x20]{7}\x00[0-9\x20\x00]{12}[0-9\x20\x00]{12}',
                    'group' => 'archive',
                    'module' => 'tar',
                    'mime_type' => 'application/x-tar',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // GZIP  - data        - GZIP compressed data
                'gz' => array(
                    'pattern' => '^\x1F\x8B\x08',
                    'group' => 'archive',
                    'module' => 'gzip',
                    'mime_type' => 'application/x-gzip',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // ZIP  - data         - ZIP compressed data
                'zip' => array(
                    'pattern' => '^PK\x03\x04',
                    'group' => 'archive',
                    'module' => 'zip',
                    'mime_type' => 'application/zip',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // Misc other formats
                // PAR2 - data        - Parity Volume Set Specification 2.0
                'par2' => array(
                    'pattern' => '^PAR2\x00PKT',
                    'group' => 'misc',
                    'module' => 'par2',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PDF  - data        - Portable Document Format
                'pdf' => array(
                    'pattern' => '^\x25PDF',
                    'group' => 'misc',
                    'module' => 'pdf',
                    'mime_type' => 'application/pdf',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // MSOFFICE  - data   - ZIP compressed data
                'msoffice' => array(
                    'pattern' => '^\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1', // D0CF11E == DOCFILE == Microsoft Office Document
                    'group' => 'misc',
                    'module' => 'msoffice',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // CUE  - data       - CUEsheet (index to single-file disc images)
                'cue' => array(
                    'pattern' => '', // empty pattern means cannot be automatically detected, will fall through all other formats and match based on filename and very basic file contents
                    'group' => 'misc',
                    'module' => 'cue',
                    'mime_type' => 'application/octet-stream',
                ),
            );
        }

        return $format_info;
    }
}
