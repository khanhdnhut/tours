<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class ActivityBO extends PostBO
{

    public $post_type = "activity";
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

    public function setActivityInfo($activityInfo)
    {
        if (!is_null($activityInfo)) {
            if (isset($activityInfo->image_id) && $activityInfo->image_id != "" && is_numeric($activityInfo->image_id)) {
                $this->image_id = $activityInfo->image_id;                
            }
            if (isset($activityInfo->image_url)) {
                $this->image_url = $activityInfo->image_url;                
            }
            if (isset($activityInfo->city_id)) {
                $this->city_id = $activityInfo->city_id;
            }
            if (isset($activityInfo->city_name)) {
                $this->city_name = $activityInfo->city_name;
            }
            if (isset($activityInfo->current_rating)) {
                $this->current_rating = $activityInfo->current_rating;
            }
            if (isset($activityInfo->current_rating)) {
                $this->vote_times = $activityInfo->vote_times;
            }
        }
    }
}
