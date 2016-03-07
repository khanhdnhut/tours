<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class InternationalflightBO extends PostBO
{

    public $post_type = "internationalflight";
    public $image_id;
    public $image_url;
    public $city_id;
    public $city_name;
    public $country_id;
    public $country_name;
    public $current_rating;
    public $vote_times;
    public $tag_list;

    function __construct()
    {
        parent::__construct();
    }

    public function setInternationalflightInfo($internationalflightInfo)
    {
        if (!is_null($internationalflightInfo)) {
            if (isset($internationalflightInfo->image_id) && $internationalflightInfo->image_id != "" && is_numeric($internationalflightInfo->image_id)) {
                $this->image_id = $internationalflightInfo->image_id;                
            }
            if (isset($internationalflightInfo->image_url)) {
                $this->image_url = $internationalflightInfo->image_url;                
            }
            if (isset($internationalflightInfo->city_id)) {
                $this->city_id = $internationalflightInfo->city_id;
            }
            if (isset($internationalflightInfo->city_name)) {
                $this->city_name = $internationalflightInfo->city_name;
            }
            if (isset($internationalflightInfo->current_rating)) {
                $this->current_rating = $internationalflightInfo->current_rating;
            }
            if (isset($internationalflightInfo->current_rating)) {
                $this->vote_times = $internationalflightInfo->vote_times;
            }
        }
    }
}
